<?php

namespace App\Controllers;

use App\Models\PendingOrganizerModel;
use App\Models\UserModel;
use App\Models\PendingProposalModel;
use App\Models\EventModel;
use App\Models\RegistrationModel;
use setasign\Fpdi\Tcpdf\Fpdi;

class Coordinator extends BaseController
{
    public function dashboard()
    {
        $pendingModel = new PendingOrganizerModel();
        $data['pendingOrganizers'] = $pendingModel->findAll();

        $data['stats'] = [
            'ongoing'   => 3,
            'upcoming'  => 2,
            'organizer' => 5,
            'attendance'=> 120
        ];

        return view('coordinator/dashboard', $data);
    }

    // ✅ Added this method
    public function approvals()
    {
        $pendingModel = new PendingOrganizerModel();
        $data['pendingOrganizers'] = $pendingModel->findAll();

        return view('coordinator/approvals', $data);
    }

    public function approve($id)
    {
        $pendingModel = new PendingOrganizerModel();
        $userModel = new UserModel();

        $pending = $pendingModel->find($id);
        if (!$pending) {
            return redirect()->back()->with('error', 'Organizer not found.');
        }

        $userModel->insert([
            'name'     => $pending['name'],
            'email'    => $pending['email'],
            'password' => $pending['password'], // already hashed
            'role'     => 'organizer',
            'staff_id' => $pending['staff_id'],
        ]);

        $pendingModel->delete($id);

        return redirect()->back()->with('success', 'Organizer approved successfully.');
    }

    public function reject($id)
    {
        $pendingModel = new PendingOrganizerModel();

        $pending = $pendingModel->find($id);
        if (!$pending) {
            return redirect()->back()->with('error', 'Organizer not found.');
        }

        $pendingModel->delete($id);

        return redirect()->back()->with('success', 'Organizer registration rejected and removed.');
    }

    public function proposals()
    {
        $pendingModel = new PendingProposalModel();
        $data['pendingProposals'] = $pendingModel->findAll();
        return view('coordinator/proposals', $data);
    }

    // ⭐ FIX: This method now MOVES the event to the live EventModel table.
    public function approveProposal($id)
    {
        $proposalModel = new PendingProposalModel();
        $eventModel = new EventModel(); // Instantiate the live event model

        $proposal = $proposalModel->find($id);

        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        // 1. Prepare data for the live events table (MAPPING FIX)
        $eventData = [
            // Mapped Fields
            'title'              => $proposal['event_name'], 
            'description'        => $proposal['event_description'], 
            'thumbnail'          => $proposal['poster_image'], 
            'date'               => $proposal['event_date'],
            'location'           => $proposal['event_location'],
            
            // Directly Transferred Fields
            'organizer_id'       => $proposal['organizer_id'],
            'status'             => 'approved',
            'time'               => $proposal['event_time'],
            'program_start'      => $proposal['program_start'],
            'program_end'        => $proposal['program_end'],
            'eligible_semesters' => $proposal['eligible_semesters'],
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        try {
            // 2. Insert the mapped data into the main events table (EventModel)
            $eventModel->insert($eventData);
            
            // 3. Delete the record from the pending proposals table
            $proposalModel->delete($id);

            return redirect()->back()->with('success', 'Event approved and published to the main page successfully!');
        } catch (\Exception $e) {
            log_message('error', 'Event Approval Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve event due to a database error. Check logs and model fields.');
        }
    }

    public function rejectProposal($id)
    {
        $model = new PendingProposalModel();
        $proposal = $model->find($id);

        if (!$proposal) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        // Simply deleting the pending record
        $model->delete($id); 
        return redirect()->back()->with('success', 'Proposal rejected and removed.');
    }

    public function registrationControl()
    {
        // Now fetches from the live events table for approved events
        $eventModel = new EventModel();
        $data['approvedEvents'] = $eventModel->getApprovedEvents();

        return view('coordinator/registration_control', $data);
    }

    public function upcomingEvents()
    {
        // 1. Fetch upcoming events (approved)
        $eventModel = new \App\Models\EventModel();

        // --- FIX ---
        // Replaced getApprovedEvents() with a direct query for 'approved' status
        $data['events'] = $eventModel
            ->where('status', 'approved')
            ->orderBy('date', 'ASC') // Show soonest events first
            ->findAll();
        // --- End Fix ---

        // 2. Pass to the view
        return view('coordinator/upcoming_events', $data);
    }

    public function certificates()
{
    $eventModel = new EventModel();

    // Get all approved events
    $data['events'] = $eventModel
        ->where('status', 'approved')
        ->orderBy('date', 'DESC')
        ->findAll();

    return view('coordinator/certificates', $data);
}

    public function publish_certificates($event_id)
    {
        $registrationModel = new RegistrationModel();
        $userModel = new UserModel();
        $eventModel = new EventModel();

        // Ensure the certificates directory exists
        $certPath = WRITEPATH . 'uploads/certificates/';
        if (!is_dir($certPath)) {
            mkdir($certPath, 0777, true);
        }

        // Get the event details
        $event = $eventModel->find($event_id);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }

        // Find participants who were marked as 'attended' (certificate_ready = 1)
        // but have not had their certificate 'published' yet.
        $participantsToPublish = $registrationModel
            ->where('event_id', $event_id)
            ->where('certificate_ready', 1)
            ->where('certificate_published', 0)
            ->findAll();

        if (empty($participantsToPublish)) {
            return redirect()->to('coordinator/certificates')->with('message', 'No new certificates to publish for this event.');
        }

        $publishedCount = 0;
        foreach ($participantsToPublish as $participant) {
            $user = $userModel->find($participant['user_id']);

            if ($user) {
                // 1. Generate the certificate
                $generatedFilePath = $this->_generateCertificate($user, $event);

                if ($generatedFilePath) {
                    // 2. Update the registration record
                    $data = [
                        'certificate_path' => $generatedFilePath,
                        'certificate_published' => 1
                    ];
                    $registrationModel->update($participant['id'], $data);
                    $publishedCount++;
                }
            }
        }

        return redirect()->to('coordinator/certificates')->with('message', $publishedCount . ' certificates have been successfully generated and published.');
    }
    private function _generateCertificate($user, $event)
    {
        // Path to your template PDF
        $templatePath = WRITEPATH . 'templates/example_cert.pdf'; // <-- Make sure this path is correct
        if (!file_exists($templatePath)) {
            log_message('error', 'Certificate template not found: ' . $templatePath);
            return false; // Stop if template is missing
        }

        // --- Use FPDI to load the template ---
        $pdf = new \setasign\Fpdi\Tcpdf\Fpdi('L', 'mm', 'A4'); // 'L' for Landscape, mm units, A4 size

        // Add a page (FPDI automatically uses the template dimensions)
        $pdf->AddPage();
        
        // Import the first page of the template
        $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        
        // Use the imported page as the background for the current page
        // The 'useTemplate' parameters might need adjustment if your PDF isn't exactly A4 landscape
        // useTemplate($templateId, x-position, y-position, width, height) - null width/height uses original size
        $pdf->useTemplate($tplId, 0, 0, null, null, true); // Adjust x, y, width, height if needed

        // --- Add Dynamic Text using TCPDF methods ---

        // Set Font
        // You might need different fonts/sizes/colors for different text elements
        $pdf->SetFont('helvetica', 'B', 16); // Example: Helvetica Bold, Size 16
        $pdf->SetTextColor(0, 0, 0); // Black text

        /* * IMPORTANT: Adjust X and Y Coordinates!
         * You MUST measure the positions (in millimeters) on your PDF template 
         * where you want the text to appear and update the SetXY(X, Y) values below.
         * (0,0) is the top-left corner. X increases to the right, Y increases downwards.
         * A standard A4 Landscape page is 297mm wide x 210mm high.
         */

        // Add User Name (Example: Positioned at 100mm from left, 90mm from top)
        $pdf->SetXY(100, 90); 
        $pdf->Write(0, strtoupper($user['name'])); // Write text: 0 = line height (auto)

        // Add Event Title (Example: Positioned at 100mm from left, 110mm from top)
        $pdf->SetFont('helvetica', '', 12); // Change font style if needed
        $pdf->SetXY(100, 110);
        $pdf->Write(0, $event['title']);

        // Add Event Date (Example: Positioned at 100mm from left, 120mm from top)
        $pdf->SetXY(100, 120);
        $pdf->Write(0, 'on ' . date('F j, Y', strtotime($event['date'])));
        
        // --- Define Output Path ---
        $fileName = $event['id'] . '_' . $user['id'] . '_cert.pdf';
        $filePath = WRITEPATH . 'uploads/certificates/' . $fileName;

        // Ensure the output directory exists
        $certDir = dirname($filePath);
        if (!is_dir($certDir)) {
            mkdir($certDir, 0777, true);
        }

        // --- Save the PDF ---
        // 'F' saves the file to the path specified in $filePath
        try {
            $pdf->Output($filePath, 'F');
            return $filePath; // Return the path if successful
        } catch (\Exception $e) {
            log_message('error', 'Failed to save certificate PDF: ' . $e->getMessage());
            return false; // Return false on failure
        }
    }
}
