<?php

namespace App\Controllers;

use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\NotificationModel; 

class Course extends BaseController
{
    /**
     * Display all courses
     */
    public function index()
    {
        $courseModel = new CourseModel();
        $data['courses'] = $courseModel->findAll();
        return view('courses/index', $data);
    }

    public function enroll()
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized. Please login first.'
            ])->setStatusCode(401);
        }

        // Check if user is a student
        if ($session->get('role') !== 'student') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only students can enroll in courses.'
            ])->setStatusCode(403);
        }

        $courseId = $this->request->getPost('course_id');

        // Validate course ID
        if (empty($courseId) || !is_numeric($courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid course ID provided.'
            ])->setStatusCode(400);
        }

        $courseModel = new CourseModel();
        $enrollmentModel = new EnrollmentModel();
        
        $course = $courseModel->find($courseId);

        // Check if course exists
        if (!$course) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Course not found.'
            ])->setStatusCode(404);
        }

        $userId = $session->get('id');

        // Check if already enrolled
        if ($enrollmentModel->isAlreadyEnrolled($userId, $courseId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You are already enrolled in this course.'
            ])->setStatusCode(409);
        }

        // Prepare enrollment data
        $enrollmentData = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'enrollment_date' => date('Y-m-d H:i:s')
        ];

        try {
            if ($enrollmentModel->enrollUser($enrollmentData)) {

                //  LAB 8: CREATE NOTIFICATION
                try {
                    $notifModel = new NotificationModel();
                    $notifModel->insert([
                        'user_id'    => (int) $userId,
                        'message'    => 'You have been enrolled in ' . esc($course['title']),
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                } catch (\Throwable $t) {
                    log_message('error', 'Notification create failed: ' . $t->getMessage());
                }

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully enrolled in ' . esc($course['title']) . '!',
                    'course_title' => $course['title']
                ]);
            } 
            else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to enroll. Please try again.'
                ])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            log_message('error', 'Enrollment error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred during enrollment.'
            ])->setStatusCode(500);
        }
    }
}
