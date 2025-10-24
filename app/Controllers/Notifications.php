<?php

namespace App\Controllers;

use App\Models\NotificationModel;
use CodeIgniter\HTTP\ResponseInterface;

class Notifications extends BaseController
{
    public function get()
    {
        $session = session();
        $userId = (int) $session->get('id');
        if (!$session->get('isLoggedIn') || $userId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $model = new NotificationModel();
        $count = $model->getUnreadCount($userId);
        $list = $model->getNotificationsForUser($userId, 5);

        return $this->response->setJSON([
            'status' => 'ok',
            'unread' => $count,
            'notifications' => $list,
        ]);
    }

    public function mark_as_read($id = null)
    {
        $session = session();
        $userId = (int) $session->get('id');
        if (!$session->get('isLoggedIn') || $userId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)
                ->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $notifId = (int) $id;
        if ($notifId <= 0) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)
                ->setJSON(['status' => 'error', 'message' => 'Invalid ID']);
        }

        $model = new NotificationModel();

        // Ensure the notification belongs to current user
        $notif = $model->find($notifId);
        if (!$notif || (int) $notif['user_id'] !== $userId) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_FORBIDDEN)
                ->setJSON(['status' => 'error', 'message' => 'Forbidden']);
        }

        $ok = $model->markAsRead($notifId);
        return $this->response->setJSON([
            'status' => $ok ? 'ok' : 'error',
        ]);
    }
}
