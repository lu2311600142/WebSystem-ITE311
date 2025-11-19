<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class Teacher extends BaseController
{
    
    public function dashboard()
    {
        $session = session();
        $userId = $session->get('id');

        
        $notificationModel = new NotificationModel();
        $data['unreadCount'] = $notificationModel->getUnreadCount($userId);
        $data['notifications'] = $notificationModel->getNotificationsForUser($userId, 5);

        // Prepare dashboard data
        $data['title'] = 'Teacher Dashboard';
        $data['username'] = $session->get('username');
        $data['email'] = $session->get('email');
        $data['role'] = $session->get('role');

        return view('teacher_dashboard', $data);  
    }
}
