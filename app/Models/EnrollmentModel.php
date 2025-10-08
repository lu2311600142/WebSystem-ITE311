


namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'course_id',
        'enrollment_date'
    ];

    protected $useTimestamps = false;
    
    protected $validationRules = [
        'user_id' => 'required|integer',
        'course_id' => 'required|integer',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid integer'
        ],
        'course_id' => [
            'required' => 'Course ID is required',
            'integer' => 'Course ID must be a valid integer'
        ]
    ];

    public function enrollUser($data)
    {
        return $this->insert($data);
    }

    public function getUserEnrollments($user_id)
    {
        return $this->select('enrollments.*, courses.course_name, courses.course_code, courses.description, courses.credits')
            ->join('courses', 'courses.id = enrollments.course_id')
            ->where('enrollments.user_id', $user_id)
            ->findAll();
    }

    public function isAlreadyEnrolled($user_id, $course_id)
    {
        $result = $this->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->first();
        
        return $result !== null;
    }

    public function getCourseEnrollmentCount($course_id)
    {
        return $this->where('course_id', $course_id)->countAllResults();
    }
}