<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    /**
     * register() - Displays the registration form and processes form submission.
     */
    public function register()
    {
        // Handle POST request (registration submission)
        if ($this->request->getMethod() === 'POST') {
            $userModel = new UserModel();

            $rules = [
                'name' => 'required|min_length[3]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Prepare user data
            $userData = [
                'username' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'role' => 'student' // Default role based on your existing database
            ];

            // Save user data
            try {
                if ($userModel->save($userData)) {
                    return redirect()->to('/login')->with('success', 'Account created successfully! Please login.');
                } else {
                    $errors = $userModel->errors();
                    return redirect()->back()->withInput()->with('errors', $errors ?: ['Registration failed. Please try again.']);
                }
            } catch (\Exception $e) {
                log_message('error', 'Registration error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('errors', ['Registration failed: ' . $e->getMessage()]);
            }
        }

        // Handle GET request (show registration form)
        return view('auth/register');
    }

    /**
     * login() - Displays the login form and processes form submission.
     */
    public function login()
    {
        // Handle POST request (login submission)
        if ($this->request->getMethod() === 'POST') {
            $session = session();
            $userModel = new UserModel();

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Validation rules
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('error', 'Please provide valid email and password.');
            }

            try {
                // Find user by email
                $user = $userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    // Set session data
                    $sessionData = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'isLoggedIn' => true,
                    ];
                    $session->set($sessionData);

                    return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['username'] . '!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Login error: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Login failed. Please try again.');
            }
        }

        // Handle GET request (show login form)
        return view('auth/login');
    }

    /**
     * logout() - Destroys the user's session and redirects them.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }

    /**
     * dashboard() - A protected page that only logged-in users can see.
     */
    public function dashboard()
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        $data = [
            'title' => 'User Dashboard',
            'username' => $session->get('username'),
            'email' => $session->get('email'),
            'role' => $session->get('role')
        ];

        return view('auth/dashboard', $data);
    }
}