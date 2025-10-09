<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'description', 'instructor_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get all courses that a specific student is NOT enrolled in
     */
    public function getAvailableCourses($studentId)
    {
        return $this->select('courses.*')
            ->join('enrollments', 'enrollments.course_id = courses.id AND enrollments.student_id = ' . (int)$studentId, 'left')
            ->where('enrollments.id IS NULL')
            ->findAll();
    }

    /**
     * Get all active courses
     */
    public function getAllActiveCourses()
    {
        return $this->findAll();
    }
}