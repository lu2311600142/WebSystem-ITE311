<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
    }

    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]',
            ];

            if (! $this->validate($rules)) {
                return view('auth/login', ['validation' => $this->validator]);
            }

            $userModel = new UserModel();
            $user = $userModel->where('email', $this->request->getVar('email'))->first();

            if ($user && password_verify($this->request->getVar('password'), $user['password'])) {
                $this->session->set([
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'logged_in' => true
                ]);
                return redirect()->to('/dashboard');
            } else {
                return view('auth/login', ['error' => 'Invalid email or password']);
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'username' => 'required|min_length[3]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'matches[password]',
            ];

            if (! $this->validate($rules)) {
                return view('auth/register', ['validation' => $this->validator]);
            }

            $userModel = new UserModel();
            $userModel->save([
                'username' => $this->request->getVar('username'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            ]);

            return redirect()->to('/login')->with('success', 'Registration successful. Please login.');
        }

        return view('auth/register');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
