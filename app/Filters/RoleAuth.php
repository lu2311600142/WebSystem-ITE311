<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleAuth implements FilterInterface
{
    /**
     * before() - Checks user role before allowing access to routes
     * 
     * @param RequestInterface $request
     * @param array|null $arguments - Expected format: ['admin'] or ['teacher', 'admin']
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Get user's role from session
        $userRole = $session->get('role');

        // If no specific roles are required, allow access
        if (empty($arguments)) {
            return;
        }

        // Check if user's role matches any of the allowed roles
        if (in_array($userRole, $arguments)) {
            // User has permission, allow access
            return;
        }

        // User doesn't have permission
        // Redirect based on their role
        switch ($userRole) {
            case 'admin':
                return redirect()->to('/admin/dashboard')->with('error', 'Access Denied: You already have admin access.');
            case 'teacher':
                return redirect()->to('/teacher/dashboard')->with('error', 'Access Denied: Insufficient Permissions.');
            case 'student':
            default:
                return redirect()->to('/announcements')->with('error', 'Access Denied: Insufficient Permissions.');
        }
    }

    /**
     * after() - Perform any actions after the controller
     * 
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after controller execution
    }
}