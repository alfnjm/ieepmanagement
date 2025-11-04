<?php

namespace App\Controllers;

use App\Models\PendingProposalModel;
use App\Models\EventModel;
use App\Models\PendingOrganizerModel;
use App\Models\UserModel;
use App\Models\EventRegistrationModel;
use App\Models\CertificateTemplateModel;
// --- ADDED: We need these for the preview and PDF generation ---
use setasign\Fpdi\Fpdi;

class Coordinator extends BaseController
{
    // ... dashboard() function is unchanged ...
    public function dashboard()
    {
        $eventModel = new EventModel();
        $proposalModel = new PendingProposalModel();
        $organizerModel = new PendingOrganizerModel();
        $userModel = new UserModel();
        $registrationModel = new EventRegistrationModel(); 

        $stats = [
            'ongoing'            => $eventModel->countAllResults(),
            // --- FIX 1 (minor): Changed to 'Pending' for consistency ---
            'upcoming'           => $proposalModel->where('status', 'Pending')->countAllResults(),
            'organizer'          => $organizerModel->countAllResults(),
            'total_users'        => $userModel->where('role', 'user')->countAllResults(),
        ];

        $data['title'] = 'Coordinator Dashboard';
        $data['stats'] = $stats; 

        return view('coordinator/dashboard', $data);
    }


    /**
     * --- THIS FUNCTION HAS BEEN FIXED ---
     */
    public function proposals()
    {
        $proposalModel = new PendingProposalModel();
        // --- FIX 2: Renamed variable to match your view file ---
        $data['pendingProposals'] = $proposalModel
            ->join('users', 'users.id = pending_proposals.organizer_id')
            ->select('pending_proposals.*, users.email as organizer_name')
            // --- FIX 3: Changed to 'Pending' (case-sensitive) ---
            ->where('status', 'Pending')
            ->findAll();
        
        $data['title'] = 'Pending Proposals';
        return view('coordinator/proposals', $data);
    }

    // ... registrationControl() and upcomingEvents() are unchanged ...

    public function registrationControl()
    {
        $data['title'] = 'Registration Control';
        // Logic to toggle registration on/off
        return view('coordinator/registration_control', $data);
    }

    public function upcomingEvents()
    {
        $eventModel = new EventModel();
        $data['events'] = $eventModel
            ->join('users', 'users.id = events.organizer_id')
            ->select('events.*, users.email as organizer_name')
            ->findAll();
            
        $data['title'] = 'Upcoming Events';
        return view('coordinator/upcoming_events', $data);
    }

    // ... approvals(), approve(), and reject() are unchanged ...

    public function approvals()
    {
        $model = new PendingOrganizerModel();
        $data['pending_organizers'] = $model->findAll();
        $data['title'] = 'Pending Approvals';
        return view('coordinator/approvals', $data);
    }

    public function approve($id)
    {
        $pendingModel = new PendingOrganizerModel();
        $userModel = new UserModel();

        $pendingUser = $pendingModel->find($id);

        if ($pendingUser) {
            $userModel->insert([
                'username' => $pendingUser['email'], // Using email as username
                'email' => $pendingUser['email'],
                'password' => $pendingUser['password'], // Already hashed
                'role' => 'organizer',
            ]);
            $pendingModel->delete($id);
            return redirect()->to('coordinator/approvals')->with('success', 'Organizer approved.');
        }
        return redirect()->to('coordinator/approvals')->with('error', 'User not found.');
    }

    public function reject($id)
    {
        $pendingModel = new PendingOrganizerModel();
        $pendingModel->delete($id);
        return redirect()->to('coordinator/approvals')->with('success', 'Organizer rejected.');
    }


    /**
     * --- THIS FUNCTION HAS BEEN FIXED ---
     */
    public function approveProposal($id)
    {
        $pendingModel = new PendingProposalModel();
        $eventModel = new EventModel();

        $proposal = $pendingModel->find($id);

        if ($proposal) {
            
            // --- THIS IS THE FIX ---
            // We are now mapping all the fields from the pending_proposals
            // table to the correct columns in the events table.
            $dataToInsert = [
                'title'              => $proposal['event_name'],
                'description'        => $proposal['event_description'],
                'date'               => $proposal['event_date'],
                'time'               => $proposal['event_time'], // <-- Added this
                'location'           => $proposal['event_location'], // <-- Added this
                'program_start'      => $proposal['program_start'], // <-- Added this
                'program_end'        => $proposal['program_end'], // <-- Added this
                'eligible_semesters' => $proposal['eligible_semesters'], // <-- Added this
                'thumbnail'          => $proposal['poster_image'], // <-- Corrected this name
                'organizer_id'       => $proposal['organizer_id'],
                'status'             => 'approved' // Set status for the new event
            ];
            
            $eventModel->insert($dataToInsert);
            // --- END OF FIX ---

            // Update the proposal status to 'Approved' (with a capital A)
            $pendingModel->update($id, ['status' => 'Approved']);
            
            return redirect()->to('coordinator/proposals')->with('success', 'Proposal approved and event created.');
        }
        
        return redirect()->to('coordinator/proposals')->with('error', 'Proposal not found.');
    }

    /**
     * --- THIS FUNCTION HAS BEEN FIXED ---
     */
    public function rejectProposal($id)
    {
        $pendingModel = new PendingProposalModel();
        // --- FIX 6: Changed to 'Rejected' (case-sensitive) ---
        $pendingModel->update($id, ['status' => 'Rejected']);
        return redirect()->to('coordinator/proposals')->with('success', 'Proposal rejected.');
    }

    public function certificates()
    {
        $eventModel = new EventModel();
        $templateModel = new CertificateTemplateModel(); // <-- ADDED
        $coordinatorId = session()->get('id');

        // Fetch all approved events
        $data['events'] = $eventModel->where('status', 'approved')->findAll(); 
        
        // --- THIS IS THE FIX ---
        // Fetch all templates uploaded by this coordinator
        $data['templates'] = $templateModel->where('coordinator_id', $coordinatorId)->findAll();
        // --- END OF FIX ---

        $data['title'] = 'Publish Certificates';
        return view('coordinator/certificates', $data);
    }
    
    // ... templates() and previewTemplate() functions remain the same ...

    /**
     * publish_certificates()
     * THIS FUNCTION IS UPDATED
     * It now reads event_id and template_id from the POST form.
     */
    public function publish_certificates()
    {
        // --- THIS IS THE FIX ---
        // 1. Get data from the POST form
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $eventId = $this->request->getPost('event_id');
        $templateId = $this->request->getPost('template_id');

        if (empty($eventId) || empty($templateId)) {
            return redirect()->back()->with('error', 'You must select an event AND a template.');
        }
        // --- END OF FIX ---

        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        
        $templateModel = new CertificateTemplateModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();
        $coordinatorId = session()->get('id');

        // --- THIS IS THE FIX ---
        // 2. Find the SPECIFIC template the coordinator selected
        $template = $templateModel->where('id', $templateId)
                                  ->where('coordinator_id', $coordinatorId) // Security check
                                  ->first();
        
        if (!$template) {
            return redirect()->back()->with('error', 'The selected template was not found or you do not own it.');
        }
        // --- END OF FIX ---

        $templatePath = ROOTPATH . $template['template_path'];
        if (!file_exists($templatePath)) {
            log_message('error', 'Certificate template file not found: ' . $templatePath);
            return redirect()->back()->with('error', 'Certificate template file is missing. Please re-upload it.');
        }

        $participants = $registrationModel
            ->where('event_id', $eventId)
            ->where('is_attended', 1)
            ->findAll();

        if (empty($participants)) {
            return redirect()->back()->with('info', 'No participants have been marked as attended for this event.');
        }

        $generatedCount = 0;
        foreach ($participants as $participant) {
            $user = $userModel->find($participant['user_id']);
            if (!$user) continue;

            $pdf = new Fpdi(); // This works now because of the 'use' statement

            $pdf->AddPage();
            
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0);

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(0, 0, 0);

            // Add participant name
            $pdf->SetXY($template['name_x'], $template['name_y']);
            $pdf->Write(0, $user['name'] ?? $user['email']);

            // Add Student ID
            $pdf->SetXY($template['student_id_x'], $template['student_id_y']);
            $pdf->Write(0, $user['student_id'] ?? 'N/A');

            // Add event title
            $pdf->SetXY($template['event_x'], $template['event_y']);
            $pdf->Write(0, $event['title']);

            // Define the output path
            $certDir = FCPATH . 'certificates';
            if (!is_dir($certDir)) {
                mkdir($certDir, 0777, true);
            }
            $certPath = $certDir . '/event_' . $eventId . '_user_' . $user['id'] . '.pdf';
            $certSavePath = 'certificates/event_' . $eventId . '_user_' . $user['id'] . '.pdf';

            $pdf->Output('F', $certPath);

            $registrationModel->update($participant['id'], [
                'certificate_published' => 1,
                'certificate_path' => $certSavePath
            ]);
            $generatedCount++;
        }

        return redirect()->to('coordinator/certificates')->with('success', "Published $generatedCount certificates successfully.");
    }
    
    public function templates()
    {
        helper('form');
        $templateModel = new CertificateTemplateModel();
        $coordinatorId = session()->get('id'); // This is the Coordinator's ID

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'template_name' => 'required|max_length[255]',
                'template_file' => 'uploaded[template_file]|max_size[template_file,10240]|ext_in[template_file,pdf]',
                'name_x' => 'required|integer',
                'name_y' => 'required|integer',
                'event_x' => 'required|integer',
                'event_y' => 'required|integer',
                'student_id_x' => 'required|integer',
                'student_id_y' => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                // --- FIX 1 (for the where clause) ---
                $data['templates'] = $templateModel->where('coordinator_id', $coordinatorId)->findAll();
                $data['title'] = 'Manage IEEP Templates';
                $data['validation'] = $this->validator;
                return view('coordinator/templates', $data);
            }

            $file = $this->request->getFile('template_file');
            $fileName = $file->getRandomName();

            if ( ! $file->move(ROOTPATH . 'writable/templates', $fileName)) {
                return redirect()->to('coordinator/templates')->withInput()
                                 ->with('error', 'Failed to move uploaded file. Please check folder permissions for "writable/templates".');
            }

            // --- FIX 2 (for saving to the database) ---
            $data = [
                'coordinator_id' => $coordinatorId, // <-- RENAMED
                'template_name' => $this->request->getPost('template_name'),
                'template_path' => 'writable/templates/' . $fileName,
                'name_x' => $this->request->getPost('name_x'),
                'name_y' => $this->request->getPost('name_y'),
                'event_x' => $this->request->getPost('event_x'),
                'event_y' => $this->request->getPost('event_y'),
                'student_id_x' => $this->request->getPost('student_id_x'),
                'student_id_y' => $this->request->getPost('student_id_y'),
            ];

            if ($templateModel->save($data)) {
                return redirect()->to('coordinator/templates')->with('success', 'Template uploaded successfully.');
            } else {
                return redirect()->to('coordinator/templates')->withInput()
                                 ->with('error', 'Failed to save template to database. Please check model and database fields.');
            }
        }

        // --- FIX 3 (for listing the templates) ---
        // This query runs when the page first loads
        $data['templates'] = $templateModel->where('coordinator_id', $coordinatorId)->findAll();
        $data['title'] = 'Manage IEEP Templates';
        return view('coordinator/templates', $data);
    }

    public function previewTemplate($id)
    {
        $templateModel = new CertificateTemplateModel();
        $template = $templateModel->find($id);

        if (!$template) {
            return redirect()->to('coordinator/templates')->with('error', 'Template not found.');
        }

        $templatePath = ROOTPATH . $template['template_path'];
        if (!file_exists($templatePath)) {
            log_message('error', 'Certificate template file not found for preview: ' . $templatePath);
            return redirect()->to('coordinator/templates')->with('error', 'Template file is missing. Please re-upload it.');
        }

        $pdf = new Fpdi(); // This works now because of the 'use' statement
        $pdf->AddPage();

        // Set source file and import
        $pageCount = $pdf->setSourceFile($templatePath);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, 0);

        // Set font and color for dummy text
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 0, 0); // Black

        // 1. Add Participant Name
        $pdf->SetXY($template['name_x'], $template['name_y']);
        $pdf->Write(0, 'Participant Name');

        // 2. Add Student ID
        $pdf->SetXY($template['student_id_x'], $template['student_id_y']);
        $pdf->Write(0, '10DDT23F1000');

        // 3. Add Event Title
        $pdf->SetXY($template['event_x'], $template['event_y']);
        $pdf->Write(0, 'Sample Event Title');

        // Output PDF to browser
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('I', 'template_preview.pdf');
    }
}