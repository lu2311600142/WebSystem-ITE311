<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Display a listing of active users
     */
    public function index()
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        // Get all active users (not deleted)
        $data['users'] = $this->userModel->findAll();
        $data['title'] = 'User Management';
        $data['username'] = $session->get('username');
        $data['role'] = $session->get('role');
        $data['loggedInUserId'] = $session->get('id'); // Pass logged-in user ID for security checks

        return view('users/index', $data);
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        $data['title'] = 'Create New User';
        $data['username'] = $session->get('username');
        $data['role'] = $session->get('role');
        $data['validation'] = \Config\Services::validation();

        return view('users/create', $data);
    }

    /**
     * Store a newly created user in storage
     */
    public function store()
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[admin,student,teacher]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare user data
        $currentTime = date('Y-m-d H:i:s');
        $userData = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('name'), // Use name as username if username not provided
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'created_at' => $currentTime,
            'updated_at' => $currentTime
        ];

        try {
            // Use insert method for new records
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            
            // Insert the user directly to avoid model validation issues
            if ($builder->insert($userData)) {
                return redirect()->to('/users')->with('success', 'User created successfully!');
            } else {
                $error = $db->error();
                log_message('error', 'User creation failed: ' . json_encode($error));
                return redirect()->back()->withInput()->with('errors', ['Failed to create user. Database error occurred.']);
            }
        } catch (\Exception $e) {
            log_message('error', 'User creation error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Check for specific database errors
            $errorMessage = 'Failed to create user: ' . $e->getMessage();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'Email already exists. Please use a different email address.';
            } elseif (strpos($e->getMessage(), 'Column') !== false && strpos($e->getMessage(), 'cannot be null') !== false) {
                $errorMessage = 'Please fill in all required fields.';
            }
            
            return redirect()->back()->withInput()->with('errors', [$errorMessage]);
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id = null)
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        if ($id === null) {
            return redirect()->to('/users')->with('error', 'User ID is required.');
        }

        $loggedInUserId = $session->get('id');
        
        // RULE 2: Prevent admin from editing their own account
        if ($id == $loggedInUserId) {
            return redirect()->to('/users')->with('error', 'You cannot edit your own account.');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        $data['user'] = $user;
        $data['title'] = 'Edit User';
        $data['username'] = $session->get('username');
        $data['role'] = $session->get('role');
        $data['validation'] = \Config\Services::validation();

        return view('users/edit', $data);
    }

    /**
     * Update the specified user in storage
     */
    public function update($id = null)
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        if ($id === null) {
            return redirect()->to('/users')->with('error', 'User ID is required.');
        }

        $loggedInUserId = $session->get('id');
        
        // RULE 2: Prevent admin from updating their own account
        if ($id == $loggedInUserId) {
            return redirect()->to('/users')->with('error', 'You cannot update your own account.');
        }

        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        // Verify current password before allowing updates
        $currentPassword = $this->request->getPost('current_password');
        if (empty($currentPassword)) {
            return redirect()->back()->withInput()->with('errors', ['Current password is required to verify your identity.']);
        }

        // Verify the current password matches
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->withInput()->with('errors', ['Current password is incorrect. Please enter the correct password.']);
        }

        // Validation rules
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => "required|valid_email|max_length[100]|is_unique[users.email,id,{$id}]",
            'password' => 'permit_empty|min_length[6]',
            'role' => 'required|in_list[admin,student,teacher]'
        ];

        if (!$this->validate($rules)) {
            $validationErrors = $this->validator->getErrors();
            $errorMessages = [];
            foreach ($validationErrors as $field => $message) {
                $errorMessages[] = ucfirst($field) . ': ' . $message;
            }
            return redirect()->back()->withInput()->with('errors', $errorMessages);
        }

        // Prepare user data
        $userData = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'updated_at' => date('Y-m-d H:i:s') // Update timestamp
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            // Use direct database update to avoid model validation conflicts
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->where('id', $id);
            
            if ($builder->update($userData)) {
                return redirect()->to('/users')->with('success', 'User updated successfully!');
            } else {
                $error = $db->error();
                $errorMessage = 'Failed to update user.';
                if (!empty($error['message'])) {
                    $errorMessage .= ' ' . $error['message'];
                }
                log_message('error', 'User update failed: ' . json_encode($error));
                return redirect()->back()->withInput()->with('errors', [$errorMessage]);
            }
        } catch (\Exception $e) {
            log_message('error', 'User update error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Check for specific database errors
            $errorMessage = 'Failed to update user: ' . $e->getMessage();
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $errorMessage = 'Email already exists. Please use a different email address.';
            } elseif (strpos($e->getMessage(), 'Column') !== false && strpos($e->getMessage(), 'cannot be null') !== false) {
                $errorMessage = 'Please fill in all required fields.';
            }
            
            return redirect()->back()->withInput()->with('errors', [$errorMessage]);
        }
    }

    /**
     * Soft delete the specified user
     */
    public function delete($id = null)
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        if ($id === null) {
            return redirect()->to('/users')->with('error', 'User ID is required.');
        }

        // Prevent self-deletion
        if ($id == $session->get('id')) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account.');
        }

        // Check if user exists
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found.');
        }

        // RULE 1: Prevent deletion of admin users
        if (isset($user['role']) && $user['role'] === 'admin') {
            return redirect()->to('/users')->with('error', 'Admin accounts cannot be deleted.');
        }

        try {
            // Soft delete using CodeIgniter's soft delete
            if ($this->userModel->delete($id)) {
                return redirect()->to('/users')->with('success', 'User deleted successfully!');
            } else {
                return redirect()->to('/users')->with('error', 'Failed to delete user.');
            }
        } catch (\Exception $e) {
            log_message('error', 'User deletion error: ' . $e->getMessage());
            return redirect()->to('/users')->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of deleted users (Trash)
     */
    public function trash()
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        // Get all deleted users (with deleted_at not null)
        // Use withDeleted() and then filter manually, or use onlyDeleted() if available
        $data['users'] = $this->userModel->withDeleted()->where('deleted_at IS NOT NULL')->findAll();
        $data['title'] = 'Deleted Users (Trash)';
        $data['username'] = $session->get('username');
        $data['role'] = $session->get('role');
        $data['loggedInUserId'] = $session->get('id'); // Pass logged-in user ID for security checks

        return view('users/trash', $data);
    }

    /**
     * Restore a soft deleted user
     */
    public function restore($id = null)
    {
        // Check if user is logged in and is admin
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'admin') {
            return redirect()->to('/login')->with('error', 'Access denied. Admin only.');
        }

        if ($id === null) {
            return redirect()->to('/users/trash')->with('error', 'User ID is required.');
        }

        try {
            // Check if user exists in deleted records
            $user = $this->userModel->withDeleted()->find($id);
            if (!$user || empty($user['deleted_at'])) {
                return redirect()->to('/users/trash')->with('error', 'User not found in deleted records.');
            }

            // Restore the user by setting deleted_at to null
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->where('id', $id);
            $builder->update(['deleted_at' => null]);

            return redirect()->to('/users/trash')->with('success', 'User restored successfully!');
        } catch (\Exception $e) {
            log_message('error', 'User restore error: ' . $e->getMessage());
            return redirect()->to('/users/trash')->with('error', 'Failed to restore user: ' . $e->getMessage());
        }
    }
}

