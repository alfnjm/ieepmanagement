<?php

namespace App\Controllers;

use App\Models\PendingProposalModel;
use CodeIgniter\Controller;
use App\Models\RegistrationModel;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use App\Models\CertificateTemplateModel;

class Organizer extends BaseController
{
    // 1️⃣ Organizer Dashboard
    public function dashboard()
    {
        $data['stats'] = [
            'events'       => 3,
            'proposals'    => 2,
            'participants' => 120,
            'certificates' => 15
        ];

        return view('organizer/dashboard', $data);
    }

    // 2️⃣ Create Event Page
    public function createEvent()
    {
        return view('organizer/create_event');
    }

    // 3️⃣ Submit Proposal (FULLY FIXED)
    public function submitProposal()
    {
        // ✅ Create model before using it
        $proposalModel = new PendingProposalModel();

        $poster = $this->request->getFile('poster_image');
        $proposal = $this->request->getFile('proposal_file');

        $posterName = null;
        $proposalName = null;

        // Upload poster
        if ($poster && $poster->isValid() && !$poster->hasMoved()) {
            $posterName = $poster->getRandomName();
            $poster->move('uploads/posters', $posterName);
        }

        // Upload proposal document
        if ($proposal && $proposal->isValid() && !$proposal->hasMoved()) {
            $proposalName = $proposal->getRandomName();
            $proposal->move('uploads/proposals', $proposalName);
        }

        // Combine semesters
        $eligibleSemesters = implode(', ', $this->request->getPost('eligible_semesters') ?? []);

        // Get logged-in organizer's ID
        $organizerId = session()->get('id');

        // Insert into database
        $proposalModel->insert([
            'event_name'         => $this->request->getPost('event_name'),
            'event_date'         => $this->request->getPost('event_date'),
            'event_time'         => $this->request->getPost('event_time'),
            'event_location'     => $this->request->getPost('event_location'),
            'program_start'      => $this->request->getPost('program_start'),
            'program_end'        => $this->request->getPost('program_end'),
            'eligible_semesters' => $eligibleSemesters,
            'event_description'  => $this->request->getPost('event_description'),
            'poster_image'       => $posterName,
            'proposal_file'      => $proposalName,
            'status'             => 'Pending',
            'organizer_id'       => $organizerId
        ]);

        return redirect()->to(base_url('organizer/myProposals'))
                 ->with('success', 'Event proposal submitted successfully and pending approval.');
    }

    // 4️⃣ My Proposals (unchanged)
    public function myProposals()
    {
        $proposalModel = new \App\Models\PendingProposalModel();
        $eventModel    = new \App\Models\EventModel();

        $organizerId = session()->get('id');
        if (!$organizerId) {
            return view('organizer/my_proposals', ['myProposals' => []]);
        }

        // Get Pending or Rejected Proposals
        $otherProposals = $proposalModel
            ->select('id, event_name, created_at, status, proposal_file')
            ->where('organizer_id', $organizerId)
            ->findAll();

        // Get Approved Events from the main events table
        $approvedEvents = $eventModel
            ->select('id, title as event_name, created_at, status, "N/A" as proposal_file')
            ->where('organizer_id', $organizerId)
            ->findAll();

        // Combine both arrays
        $allProposals = array_merge($otherProposals, $approvedEvents);

        // Sort by date (newest first)
        usort($allProposals, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        $data['myProposals'] = $allProposals;

        return view('organizer/my_proposals', $data);
    }

    public function certificates()
    {
        $organizerId = session()->get('id');
        $db = \Config\Database::connect();

        // ✅ Build the query consistently
        $builder = $db->table('registrations');
        $builder->select('
            registrations.id as reg_id, 
            users.name as user_name, 
            users.student_id, 
            events.title as event_name
        ');
        $builder->join('users', 'users.id = registrations.user_id');
        $builder->join('events', 'events.id = registrations.event_id');
        
        // --- Add ALL the correct filters ---
        $builder->where('events.organizer_id', $organizerId);
        $builder->where('events.status', 'approved'); 
        $builder->where('registrations.is_attended', 1); 
        // ------------------------------------

        $certificates_issued = $builder->get()->getResultArray();

        $data['certificates_issued'] = $certificates_issued;
        return view('organizer/certificates', $data);
    }

    // NEW FUNCTION: Allows organizer to view a specific student's cert
    public function viewCertificate($registrationId)
    {
        $organizerId = session()->get('id');
        if (!$organizerId) {
            return redirect()->to('/auth/login')->with('error', 'Please log in.');
        }

        $db = \Config\Database::connect();

        // 1️⃣ Get registration + verify ownership
        $registration = $db->table('registrations')
            ->select('registrations.*, events.organizer_id, events.title AS event_title, events.date AS event_date, users.name AS user_name, users.student_id')
            ->join('events', 'events.id = registrations.event_id')
            ->join('users', 'users.id = registrations.user_id')
            ->where('registrations.id', $registrationId)
            ->get()
            ->getRowArray();

        if (empty($registration) || $registration['organizer_id'] != $organizerId) {
            return redirect()->to('organizer/certificates')->with('error', 'You do not have permission to view this certificate.');
        }

        // Cleaned 'if' check:
        if ($registration['is_attended'] != 1) { 
            return redirect()->to('organizer/certificates')->with('error', 'This certificate is not yet ready (participant did not attend).');
        }

        // 2️⃣ Generate PDF file if not yet generated
        if (empty($registration['certificate_path']) || !file_exists($registration['certificate_path'])) {
            $filePath = $this->_generateCertificate(
                ['name' => $registration['user_name'], 'id' => $registration['user_id']],
                ['title' => $registration['event_title'], 'date' => $registration['event_date'], 'id' => $registration['event_id']]
            );

            if ($filePath) {
                $db->table('registrations')
                    ->where('id', $registrationId)
                    ->update(['certificate_path' => $filePath]);
            }
        } else {
            $filePath = $registration['certificate_path'];
        }

        // 3️⃣ Stream certificate to browser
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="certificate.pdf"')
            ->setBody(file_get_contents($filePath));
    }

    public function attendance()
    {
        $db = \Config\Database::connect();
        $registrationModel = new \App\Models\RegistrationModel();
        $organizerId = session()->get('id');

        // ✅ Step 1: Fetch participants (using 'is_attended')
        $builder = $db->table('registrations');
        $builder->select('
            registrations.id AS reg_id,
            users.name,
            users.student_id,
            events.title AS event_title,
            events.date AS event_date,
            registrations.is_attended
        ');
        $builder->join('users', 'users.id = registrations.user_id');
        $builder->join('events', 'events.id = registrations.event_id');
        $builder->where('events.organizer_id', $organizerId);
        $builder->where('events.status', 'approved');
        $participants = $builder->get()->getResultArray();

        // ✅ Step 2: If POST, update attendance (using 'is_attended')
        if ($this->request->getMethod() === 'post') {
            $attendanceData = $this->request->getPost('attendance') ?? [];

            foreach ($participants as $participant) {
                $registrationId = $participant['reg_id'];
                $attended = isset($attendanceData[$registrationId]) ? 1 : 0;

                // 🧠 Use direct query builder update
                $db->table('registrations')
                    ->where('id', $registrationId)
                    ->update(['is_attended' => $attended]); 
            }

            return redirect()->to('organizer/attendance')
                            ->with('success', 'Attendance updated successfully.');
        }

        // ✅ Step 3: Display the attendance view
        return view('organizer/attendance', [
            'participants' => $participants
        ]);
    }

    private function _generateCertificate($user, $event)
    {
        if (!class_exists('setasign\Fpdi\Fpdi')) {
            log_message('error', 'FPDI library not installed. Run: composer require setasign/fpdi');
            return false;
        }

        // --- DYNAMIC TEMPLATE ---
        // 1. Get the event's chosen template ID (you need to make sure this is passed in)
        // You might need to fetch the event from the DB again to get its 'certificate_template_id'
        $db = \Config\Database::connect();
        $eventData = $db->table('events')->where('id', $event['id'])->get()->getRowArray();
        $templateId = $eventData['certificate_template_id'] ?? null;
        
        // 2. Get the template details (path and coordinates)
        $template = $this->_getTemplateDetails($templateId);

        $templatePath = $template['template_path'];
        // --- END DYNAMIC TEMPLATE ---

        if (!file_exists($templatePath)) {
            log_message('error', 'Certificate template not found: ' . $templatePath);
            return false;
        }

        // Ensure certificate folder exists
        $outputDir = WRITEPATH . 'uploads/certificates/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $pdf = new Fpdi();
        $pdf->setSourceFile($templatePath);
        $tplId = $pdf->importPage(1);
        $pdf->AddPage();
        $pdf->useTemplate($tplId, 0, 0, 210);

        // Set font and text positions
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(0, 0, 0);

        // Name (Use dynamic coordinates from $template)
        $pdf->SetXY($template['name_x'], $template['name_y']);
        $pdf->Write(0, strtoupper($user['name']));

        // Event Title
        $pdf->SetFont('helvetica', '', 14);
        $pdf->SetXY($template['event_x'], $template['event_y']);
        $pdf->Write(0, 'for attending "' . $event['title'] . '"');

        // Event Date
        $pdf->SetXY($template['date_x'], $template['date_y']);
        $pdf->Write(0, 'Date: ' . date('F j, Y', strtotime($event['date'])));

        // Save PDF
        $fileName = 'CERT_' . $event['id'] . '_' . $user['id'] . '.pdf';
        $filePath = $outputDir . $fileName;
        $pdf->Output($filePath, 'F');

        return $filePath;
    }

    public function participants()
    {
        $organizerId = session()->get('id');
        if (!$organizerId) {
            return redirect()->to('/auth/login')->with('error', 'Please log in first.');
        }

        $db = \Config\Database::connect();

        $builder = $db->table('registrations');
        $builder->select('
            registrations.id AS reg_id,
            events.title AS event_title,
            users.name AS participant_name,
            users.student_id,
            users.email,
            events.date AS event_date
        ');
        $builder->join('events', 'events.id = registrations.event_id');
        $builder->join('users', 'users.id = registrations.user_id');
        $builder->where('events.organizer_id', $organizerId);
        $builder->where('registrations.is_attended', 1); 
        $builder->where('events.status', 'approved');

        $participants = $builder->get()->getResultArray();

        return view('organizer/participants', ['participants' => $participants]);
    }

    public function templates()
    {
        $templateModel = new \App\Models\CertificateTemplateModel();
        $organizerId = session()->get('id');

        // Handle the template upload
        if ($this->request->getMethod() === 'post') {
            
            // --- ERROR CHECK 1: Check session ---
            if (!$organizerId) {
                return redirect()->to('organizer/templates')->with('error', 'Your session expired. Please log in and try again.');
            }

            $file = $this->request->getFile('template_file');

            // --- ERROR CHECK 2: Validate file ---
            if (!$file || !$file->isValid()) {
                $error = $file ? $file->getErrorString() : 'No file was selected.';
                return redirect()->to('organizer/templates')->with('error', 'File Error: ' . $error);
            }

            if ($file->hasMoved()) {
                return redirect()->to('organizer/templates')->with('error', 'File has already been moved.');
            }

            // --- ERROR CHECK 3: Check/Create Directory ---
            $templateDir = WRITEPATH . 'templates';
            if (!is_dir($templateDir)) {
                // Try to create it
                if (!mkdir($templateDir, 0777, true)) {
                    return redirect()->to('organizer/templates')->with('error', 'Failed to create templates directory in writable folder.');
                }
            }

            if (!is_writable($templateDir)) {
                 return redirect()->to('organizer/templates')->with('error', 'The directory writable/templates is not writable.');
            }

            $fileName = $file->getRandomName();

            // --- ERROR CHECK 4: Move file ---
            if (!$file->move($templateDir, $fileName)) {
                return redirect()->to('organizer/templates')->with('error', 'Failed to move the uploaded file.');
            }

            // --- All checks passed, NOW insert into DB ---
            try {
                $templateModel->insert([
                    'organizer_id'   => $organizerId, // Use the checked ID
                    'template_name'  => $this->request->getPost('template_name'),
                    'template_path'  => $fileName,
                    'name_x'         => $this->request->getPost('name_x'),
                    'name_y'         => $this->request->getPost('name_y'),
                    'event_x'        => $this->request->getPost('event_x'),
                    'event_y'        => $this->request->getPost('event_y'),
                    'date_x'         => $this->request->getPost('date_x'),
                    'date_y'         => $this->request->getPost('date_y'),
                ]);
            } catch (\Exception $e) {
                // Log the real error for you to see
                log_message('error', '[Template Upload] ' . $e->getMessage());
                // Delete the orphaned file
                unlink($templateDir . '/' . $fileName);
                return redirect()->to('organizer/templates')->with('error', 'Database error. Could not save template.');
            }

            return redirect()->to('organizer/templates')->with('success', 'Template uploaded successfully!');
        }

        // Get all existing templates for the view
        $data['templates'] = $templateModel->where('organizer_id', $organizerId)->findAll();
        return view('organizer/templates', $data);
    }

    // --- ADD THIS HELPER FUNCTION ---
    // Helper to get template details (or default)
    private function _getTemplateDetails($templateId)
    {
        if ($templateId) {
            $templateModel = new \App\Models\CertificateTemplateModel();
            $template = $templateModel->find($templateId);
            if ($template) {
                $template['template_path'] = WRITEPATH . 'templates/' . $template['template_path'];
                return $template;
            }
        }

        // Return default values if no template is found
        return [
            'template_path' => WRITEPATH . 'templates/example_cert.pdf',
            'name_x'        => 60,
            'name_y'        => 120,
            'event_x'       => 60,
            'event_y'       => 150,
            'date_x'        => 60,
            'date_y'        => 160,
        ];
    }

}
