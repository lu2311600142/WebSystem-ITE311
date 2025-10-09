<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'course_id', 'enrolled_at'];
    protected $useTimestamps = false;

    /**
     * Enroll a user in a course
     */
    public function enrollUser($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all courses a user is enrolled in (with course details)
     */
    public function getUserEnrollments($userId)
    {
        return $this->select('courses.*, enrollments.enrolled_at')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.student_id', $userId)
            ->findAll();
    }

    /**
     * Check if a user is already enrolled in a specific course
     */
    public function isAlreadyEnrolled($userId, $courseId)
    {
        return $this->where(['student_id' => $userId, 'course_id' => $courseId])->first() !== null;
    }

    /**
     * Get enrollment count for a specific course
     */
    public function getCourseEnrollmentCount($courseId)
    {
        return $this->where('course_id', $courseId)->countAllResults();
    }
}