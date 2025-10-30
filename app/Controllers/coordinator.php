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
    public function dashboard()
    {
        $eventModel = new EventModel();
        $proposalModel = new PendingProposalModel();
        $organizerModel = new PendingOrganizerModel();
        $userModel = new UserModel();
        $registrationModel = new EventRegistrationModel(); 

        $stats = [
            'ongoing'            => $eventModel->countAllResults(),
            'upcoming'           => $proposalModel->where('status', 'pending')->countAllResults(),
            'organizer'          => $organizerModel->countAllResults(),
            'total_users'        => $userModel->where('role', 'user')->countAllResults(),
            // --- REMOVED --- 'attendance' key removed from coordinator stats
        ];

        $data['title'] = 'Coordinator Dashboard';
        $data['stats'] = $stats; 

        return view('coordinator/dashboard', $data);
    }

    public function proposals()
    {
        $proposalModel = new PendingProposalModel();
        $data['proposals'] = $proposalModel
            ->join('users', 'users.id = pending_proposals.organizer_id')
            // --- FIX: Changed users.username to users.email ---
            ->select('pending_proposals.*, users.email as organizer_name')
            ->where('status', 'pending')
            ->findAll();
        
        $data['title'] = 'Pending Proposals';
        return view('coordinator/proposals', $data);
    }

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
            // --- FIX: Changed users.username to users.email ---
            ->select('events.*, users.email as organizer_name')
            ->findAll();
            
        $data['title'] = 'Upcoming Events';
        return view('coordinator/upcoming_events', $data);
    }

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
            // Note: The 'username' field does not exist in the users table.
            // We will use the email for the username field if the auth system needs it,
            // but the main 'users' table migration does not have it.
            // The Auth controller's register function *also* uses email as username.
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

    public function approveProposal($id)
    {
        $pendingModel = new PendingProposalModel();
        $eventModel = new EventModel();

        $proposal = $pendingModel->find($id);

        if ($proposal) {
            $eventModel->insert([
                'title' => $proposal['title'],
                'description' => $proposal['description'],
                'date' => $proposal['date'],
                'poster_path' => $proposal['poster_path'],
                'organizer_id' => $proposal['organizer_id'],
            ]);
            $pendingModel->update($id, ['status' => 'approved']);
            return redirect()->to('coordinator/proposals')->with('success', 'Proposal approved and event created.');
        }
        return redirect()->to('coordinator/proposals')->with('error', 'Proposal not found.');
    }

    public function rejectProposal($id)
    {
        $pendingModel = new PendingProposalModel();
        $pendingModel->update($id, ['status' => 'rejected']);
        return redirect()->to('coordinator/proposals')->with('success', 'Proposal rejected.');
    }

    // --- REMOVED ---
    // The attendance() method has been removed from the Coordinator controller.
    // This is now handled only by the Organizer.

    public function certificates()
    {
        $eventModel = new EventModel();
        $data['events'] = $eventModel->findAll(); // Coordinator sees all events
        $data['title'] = 'Publish Certificates';
        return view('coordinator/certificates', $data);
    }
    
    // --- ADDED --- This method was moved from Organizer controller
    public function templates()
    {
        helper('form'); // --- FIX: Load the form helper ---
        $templateModel = new CertificateTemplateModel();
        $coordinatorId = session()->get('id'); // This is now the Coordinator's ID

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'template_name' => 'required|max_length[255]',
                'template_file' => 'uploaded[template_file]|max_size[template_file,10240]|ext_in[template_file,pdf]',
                'name_x' => 'required|integer',
                'name_y' => 'required|integer',
                'event_x' => 'required|integer',
                'event_y' => 'required|integer',
                // --- ADDED ---
                'student_id_x' => 'required|integer',
                'student_id_y' => 'required|integer',
                // --- REMOVED Event Date ---
                // 'date_x' => 'required|integer',
                // 'date_y' => 'required|integer',
            ];

            if (!$this->validate($rules)) {
                return view('coordinator/templates', [
                    'validation' => $this->validator,
                    'templates' => $templateModel->where('organizer_id', $coordinatorId)->findAll(),
                    'title' => 'Manage IEEP Templates'
                ]);
            }

            $file = $this->request->getFile('template_file');
            $fileName = $file->getRandomName();
            $file->move(ROOTPATH . 'writable/templates', $fileName);

            $data = [
                'organizer_id' => $coordinatorId, // Saved with Coordinator's ID
                'template_name' => $this->request->getPost('template_name'),
                'template_path' => 'writable/templates/' . $fileName,
                'name_x' => $this->request->getPost('name_x'),
                'name_y' => $this->request->getPost('name_y'),
                'event_x' => $this->request->getPost('event_x'),
                'event_y' => $this->request->getPost('event_y'),
                // --- ADDED ---
                'student_id_x' => $this->request->getPost('student_id_x'),
                'student_id_y' => $this->request->getPost('student_id_y'),
                // --- REMOVED Event Date ---
                // 'date_x' => $this->request->getPost('date_x'),
                // 'date_y' => $this->request->getPost('date_y'),
            ];

            $templateModel->save($data);
            return redirect()->to('coordinator/templates')->with('success', 'Template uploaded successfully.');
        }

        $data['templates'] = $templateModel->where('organizer_id', $coordinatorId)->findAll();
        $data['title'] = 'Manage IEEP Templates';
        // --- FIX: This view file is now created ---
        return view('coordinator/templates', $data); // This view must be created
    }

    /**
     * --- NEW ---
     * Generates a preview of a template with dummy data.
     */
    public function previewTemplate($id)
    {
        // Load FPDF and FPDI libraries
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        require_once APPPATH . 'ThirdParty/fpdf/fpdi.php';

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

        $pdf = new Fpdi();
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

        // 4. Add Event Date --- REMOVED ---
        // $pdf->SetXY($template['date_x'], $template['date_y']);
        // $pdf->Write(0, date('F j, Y')); // Use today's date for preview

        // Output PDF to browser
        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output('I', 'template_preview.pdf');
    }

    public function publish_certificates($eventId)
    {
        // ... ADDED THIS LINE HERE ...
        // You MUST upload these files.
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        require_once APPPATH . 'ThirdParty/fpdf/fpdi.php'; 

        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        // ... We need to load the FPDI class itself.
        // --- FIX: This requires both fpdf.php and fpdi.php ---
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        require_once APPPATH . 'ThirdParty/fpdf/fpdi.php';

        $eventModel = new EventModel();
        // --- FIX: This requires both fpdf.php and fpdi.php ---
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        require_once APPPATH . 'ThirdParty/fpdf/fpdi.php';

        // --- ADDED: Load models used in this function ---
        $templateModel = new CertificateTemplateModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();
        // --- End Add ---

        // --- FIX: Find the template *before* the loop ---
        $coordinatorId = session()->get('id');
        $template = $templateModel->where('organizer_id', $coordinatorId)->first();
        
        if (!$template) {
            // As a fallback, try to find a system-wide template (e.g., organizer_id = 1 for Admin)
            $template = $templateModel->where('organizer_id', 1)->first(); 
            if(!$template) {
                return redirect()->back()->with('error', 'No IEEP certificate template found. Please upload a template in "Manage Templates".');
            }
        }

        // --- CHECK if template file exists ---
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

            $pdf = new \Fpdi(); 

            $pdf->AddPage();
            
            $templatePath = ROOTPATH . $template['template_path'];
            if (!file_exists($templatePath)) {
                log_message('error', 'Certificate template file not found: ' . $templatePath);
                continue; 
            }
            
            $pageCount = $pdf->setSourceFile($templatePath);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0);

            $pdf->SetFont('Arial', 'B', 16);
            $pdf->SetTextColor(0, 0, 0); // Black

            // Add participant name
            $pdf->SetXY($template['name_x'], $template['name_y']);
            // --- FIX: Use user's name (we will assume 'name' column exists, fallback to email) ---
            $pdf->Write(0, $user['name'] ?? $user['email']);

            // --- ADDED: Add Student ID ---
            $pdf->SetXY($template['student_id_x'], $template['student_id_y']);
            $pdf->Write(0, $user['student_id'] ?? 'N/A');

            // Add event title
            $pdf->SetXY($template['event_x'], $template['event_y']);
        $pdf->Write(0, $event['title']);

        // --- REMOVED: Add event date ---
        // $pdf->SetXY($template['date_x'], $template['date_y']);
        // $pdf->Write(0, date('F j, Y', strtotime($event['date']))); // Format the date nicely

        // Define the output path
        $certDir = ROOTPATH . 'writable/certificates';
            if (!is_dir($certDir)) {
                mkdir($certDir, 0777, true);
            }
            $certPath = $certDir . '/event_' . $eventId . '_user_' . $user['id'] . '.pdf';
            $certSavePath = 'writable/certificates/event_' . $eventId . '_user_' . $user['id'] . '.pdf';

            $pdf->Output('F', $certPath);

            $registrationModel->update($participant['id'], [
                'certificate_published' => 1,
                'certificate_path' => $certSavePath
            ]);
            $generatedCount++;
        }

        return redirect()->to('coordinator/certificates')->with('success', "Published $generatedCount certificates successfully.");
    }
}