[1mdiff --git a/app/Controllers/Auth.php b/app/Controllers/Auth.php[m
[1mindex 7dc5259..9022242 100644[m
[1m--- a/app/Controllers/Auth.php[m
[1m+++ b/app/Controllers/Auth.php[m
[36m@@ -9,130 +9,49 @@[m [mclass Auth extends BaseController[m
     public function register()[m
     {[m
         helper(['form']);[m
[31m-[m
[32m+[m[41m        [m
         if ($this->request->getMethod() === 'POST') {[m
[31m-            $role = $this->request->getPost('role');[m
[31m-[m
[31m-            // --- 1. Define Conditional Validation Rules ---[m
[32m+[m[32m            // 1. Define Validation Rules[m
             $rules = [[m
[31m-                'name'      => 'required|max_length[100]',[m
[31m-                'email'     => 'required|valid_email|is_unique[users.email]|max_length[150]',[m
[31m-                'password'  => 'required|min_length[8]',[m
[31m-                'role'      => 'required|in_list[user,organizer]',[m
[31m-                'terms'     => 'required|in_list[1]',[m
[31m-            ];[m
[31m-[m
[31m-            // Add custom error messages for the terms checkbox[m
[31m-            $messages = [[m
[31m-                'terms' => [[m
[31m-                    'required' => 'You must agree to the Terms & Services to register.',[m
[31m-                    'in_list'  => 'You must agree to the Terms & Services to register.'[m
[31m-                ],[m
[31m-                'email' => [[m
[31m-                    'is_unique' => 'This email is already registered.'[m
[31m-                ][m
[32m+[m[32m                'name'     => 'required|max_length[100]',[m
[32m+[m[32m                'email'    => 'required|valid_email|is_unique[users.email]|max_length[150]',[m
[32m+[m[32m                'password' => 'required|min_length[8]',[m
[32m+[m[32m                'student_id' => 'permit_empty|is_unique[users.student_id]',[m[41m [m
[32m+[m[32m                'ic_number' => 'permit_empty|is_unique[users.ic_number]',[m
[32m+[m[32m                'class' => 'permit_empty',[m
[32m+[m[32m                'phone' => 'permit_empty',[m
[32m+[m[32m                'role' => 'required|in_list[user,organizer,coordinator]', // Add role validation[m
             ];[m
 [m
[31m-            // Conditional Rules for Student ('user')[m
[31m-            if ($role === 'user') {[m
[31m-                $rules['class']       = 'required';[m
[31m-                $rules['student_id']  = 'required|is_unique[users.student_id]';[m
[31m-                $rules['phone']       = 'required|numeric|min_length[10]';[m
[31m-                $rules['ic_number']   = 'required|min_length[12]|is_unique[users.ic_number]';[m
[31m-[m
[31m-                // Add custom messages for student uniqueness[m
[31m-                $messages['student_id']['is_unique'] = 'This Matric Number is already registered.';[m
[31m-                $messages['ic_number']['is_unique']  = 'This IC Number is already registered.';[m
[31m-[m
[31m-            } elseif ($role === 'organizer') {[m
[31m-                // Conditional Rules for Organizer[m
[31m-                $rules['staff_id'] = 'required|is_unique[users.staff_id]';[m
[31m-[m
[31m-                // Add custom message for staff uniqueness[m
[31m-                $messages['staff_id']['is_unique'] = 'This Staff ID is already registered.';[m
[31m-            }[m
[31m-[m
[31m-            // Perform validation[m
[31m-            if (!$this->validate($rules, $messages)) {[m
[32m+[m[32m            if (!$this->validate($rules)) {[m
                 return view('auth/register', ['validation' => $this->validator]);[m
             }[m
 [m
[31m-            // --- 2. Prepare Conditional Data ---[m
[32m+[m[32m            // 2. Prepare data[m
             $data = [[m
[31m-                'name'     => $this->request->getPost('name'),[m
[31m-                'email'    => $this->request->getPost('email'),[m
[31m-                'password' => $this->request->getPost('password'), // Model will hash this via beforeInsert[m
[31m-                'role'     => $role,[m
[32m+[m[32m                'name'       => $this->request->getPost('name'),[m
[32m+[m[32m                'email'      => $this->request->getPost('email'),[m
[32m+[m[32m                'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),[m
[32m+[m[32m                'class'      => $this->request->getPost('class'),[m
[32m+[m[32m                'student_id' => $this->request->getPost('student_id'),[m
[32m+[m[32m                'phone'      => $this->request->getPost('phone'),[m
[32m+[m[32m                'ic_number'  => $this->request->getPost('ic_number'),[m
[32m+[m[32m                'role'       => $this->request->getPost('role'), // Get role from form[m
             ];[m
 [m
[31m-            // Initialize all conditional fields to NULL[m
[31m-            $data['class']       = null;[m
[31m-            $data['student_id']  = null;[m
[31m-            $data['phone']       = null;[m
[31m-            $data['ic_number']   = null;[m
[31m-            $data['staff_id']    = null;[m
[31m-[m
[31m-            if ($role === 'user') {[m
[31m-                // Student fields only[m
[31m-                $data['class']       = $this->request->getPost('class');[m
[31m-                $data['student_id']  = $this->request->getPost('student_id');[m
[31m-                $data['phone']       = $this->request->getPost('phone');[m
[31m-                $data['ic_number']   = $this->request->getPost('ic_number');[m
[31m-            } elseif ($role === 'organizer') {[m
[31m-                // Organizer field only[m
[31m-                $data['staff_id'] = $this->request->getPost('staff_id');[m
[31m-            }[m
[31m-[m
[31m-            // --- 3. Save Based on Role ---[m
[31m-if ($role === 'organizer') {[m
[31m-    // Save to pending_organizers table instead[m
[31m-    $pendingModel = new \App\Models\PendingOrganizerModel();[m
[31m-[m
[31m-    try {[m
[31m-        if (!$pendingModel->save($data)) {[m
[31m-            $dbError = $pendingModel->db()->error();[m
[31m-            $errorMessage = 'Failed to submit organizer registration.';[m
[32m+[m[32m            $userModel = new UserModel();[m
 [m
[31m-            if (!empty($dbError['message'])) {[m
[31m-                $errorMessage .= ' Database Error: ' . $dbError['message'];[m
[31m-            }[m
[31m-[m
[31m-            session()->setFlashdata('error', $errorMessage);[m
[31m-            return redirect()->back()->withInput();[m
[31m-        }[m
[31m-    } catch (\Exception $e) {[m
[31m-        session()->setFlashdata('error', 'Database error: ' . $e->getMessage());[m
[31m-        return redirect()->back()->withInput();[m
[31m-    }[m
[31m-[m
[31m-    // Show message for organizer approval[m
[31m-    return redirect()->to(base_url('auth/login'))[m
[31m-                     ->with('success', 'Your registration has been submitted and is pending approval by the IEEP Coordinator.');[m
[31m-[m
[31m-} else {[m
[31m-    // Regular user registration[m
[31m-    $userModel = new \App\Models\UserModel();[m
[31m-[m
[31m-    try {[m
[31m-        if (!$userModel->save($data)) {[m
[31m-            $dbError = $userModel->db()->error();[m
[31m-            $errorMessage = 'Failed to save user.';[m
[31m-[m
[31m-            if (!empty($dbError['message'])) {[m
[31m-                $errorMessage .= ' Database Error: ' . $dbError['message'];[m
[32m+[m[32m            try {[m
[32m+[m[32m                if (!$userModel->save($data)) {[m
[32m+[m[32m                    session()->setFlashdata('error', $userModel->errors());[m
[32m+[m[32m                    return redirect()->back()->withInput();[m
[32m+[m[32m                }[m
[32m+[m[32m            } catch (\Exception $e) {[m
[32m+[m[32m                session()->setFlashdata('error', 'Database error: ' . $e->getMessage());[m
[32m+[m[32m                return redirect()->back()->withInput();[m
             }[m
 [m
[31m-            session()->setFlashdata('error', $errorMessage);[m
[31m-            return redirect()->back()->withInput();[m
[31m-        }[m
[31m-    } catch (\Exception $e) {[m
[31m-        session()->setFlashdata('error', 'Database error: ' . $e->getMessage());[m
[31m-        return redirect()->back()->withInput();[m
[31m-    }[m
[31m-[m
[31m-    return redirect()->to(base_url('auth/login'))[m
[31m-                     ->with('success', 'Registration successful, please login.');[m
[31m-}[m
[32m+[m[32m            return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful, please login.');[m
         }[m
 [m
         return view('auth/register');[m
[36m@@ -141,7 +60,7 @@[m [mif ($role === 'organizer') {[m
     public function login()[m
     {[m
         helper(['form']);[m
[31m-[m
[32m+[m[41m        [m
         if ($this->request->getMethod() === 'POST') {[m
             $rules = [[m
                 'email'    => 'required|valid_email',[m
[36m@@ -159,15 +78,14 @@[m [mif ($role === 'organizer') {[m
                     if (password_verify($password, $user['password'])) {[m
                         // Check if role exists, default to 'user' if not[m
                         $role = isset($user['role']) ? $user['role'] : 'user';[m
[31m-[m
[32m+[m[41m                        [m
                         $userData = [[m
                             'id'         => $user['id'],[m
                             'name'       => $user['name'],[m
                             'email'      => $user['email'],[m
                             'role'       => $role,[m
[31m-                            'isLoggedIn' => true[m
[32m+[m[32m                            'isLoggedIn' => TRUE[m
                         ];[m
[31m-[m
                         session()->set($userData);[m
 [m
                         return $this->redirectToDashboard($role);[m
[36m@@ -183,8 +101,8 @@[m [mif ($role === 'organizer') {[m
                 return view('auth/login', ['validation' => $this->validator]);[m
             }[m
         }[m
[31m-[m
[31m-        return view('auth/login'); // This loads the initial login form[m
[32m+[m[41m        [m
[32m+[m[32m        return view('auth/login');[m
     }[m
 [m
     private function redirectToDashboard($role)[m
[36m@@ -207,4 +125,4 @@[m [mif ($role === 'organizer') {[m
         session()->destroy();[m
         return redirect()->to(base_url('auth/login'));[m
     }[m
[31m-}[m
[32m+[m[32m}[m
\ No newline at end of file[m
