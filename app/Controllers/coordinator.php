<?php

namespace App\Controllers;

use App\Models\PendingProposalModel;
use App\Models\EventModel;
use App\Models\PendingOrganizerModel;
use App\Models\UserModel;
use App\Models\EventRegistrationModel;
use App\Models\CertificateTemplateModel;

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
            'attendance'         => $registrationModel->where('is_attended', 1)->countAllResults(),
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

    public function attendance()
    {
        $eventModel = new EventModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();

        $data['events'] = $eventModel->findAll(); // Coordinator sees all events
        $data['participants'] = [];
        $data['selected_event'] = null;

        if ($this->request->getPost('event_id')) {
            $eventId = $this->request->getPost('event_id');
            $data['selected_event'] = $eventId;
            
            if ($this->request->getPost('participants')) {
                $participants = $this->request->getPost('participants'); 
                $allRegistrations = $registrationModel->where('event_id', $eventId)->findAll();
                
                foreach ($allRegistrations as $reg) {
                    $attended = in_array($reg['user_id'], $participants) ? 1 : 0;
                    $registrationModel->update($reg['id'], ['is_attended' => $attended]);
                }
                session()->setFlashdata('success', 'Attendance updated successfully.');
            }

            $data['participants'] = $registrationModel
                ->where('event_id', $eventId)
                ->join('users', 'users.id = event_registrations.user_id')
                // --- FIX: Changed users.username to users.email and aliased as username ---
                ->select('users.id, users.email as username, users.email, event_registrations.is_attended')
                ->findAll();
        }

        $data['title'] = 'Mark Attendance';
        // --- FIX: This view file is now created ---
        return view('coordinator/attendance', $data); 
    }

    public function certificates()
    {
        $eventModel = new EventModel();
        $data['events'] = $eventModel->findAll(); // Coordinator sees all events
        $data['title'] = 'Publish Certificates';
        return view('coordinator/certificates', $data);
    }
    
    public function templates()
    {
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
            ];

            $templateModel->save($data);
            return redirect()->to('coordinator/templates')->with('success', 'Template uploaded successfully.');
        }

        $data['templates'] = $templateModel->where('organizer_id', $coordinatorId)->findAll();
        $data['title'] = 'Manage IEEP Templates';
        // --- FIX: This view file is now created ---
        return view('coordinator/templates', $data);
    }

    public function publish_certificates($eventId)
    {
        // This assumes you have fpdf.php and fpdi.php in app/ThirdParty/fpdf/
        // You MUST upload these files.
        require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';
        require_once APPPATH . 'ThirdParty/fpdf/fpdi.php'; 

        $eventModel = new EventModel();
        $event = $eventModel->find($eventId);
        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }
        
        $coordinatorId = session()->get('id');
        
        $templateModel = new CertificateTemplateModel();
        $registrationModel = new EventRegistrationModel();
        $userModel = new UserModel();

        $template = $templateModel->where('organizer_id', $coordinatorId)->first();

        if (!$template) {
             $template = $templateModel->where('organizer_id', 1)->first(); // Check for Admin's template
             if(!$template) {
                return redirect()->back()->with('error', 'No IEEP certificate template found. Please upload a template in "Manage Templates".');
             }
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
            // --- FIX: Use email as username, as username does not exist ---
            $pdf->Write(0, $user['email']);

            // Add event title
            $pdf->SetXY($template['event_x'], $template['event_y']);
            $pdf->Write(0, $event['title']);

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

