
namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'course_name',
        'course_code',
        'description',
        'teacher_id',
        'credits',
        'status'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'course_name' => 'required|min_length[3]|max_length[100]',
        'course_code' => 'required|min_length[3]|max_length[20]',
    ];

    public function getActiveCourses()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getAvailableCoursesForUser($user_id)
    {
        return $this->select('courses.*')
            ->where('courses.status', 'active')
            ->where("courses.id NOT IN (SELECT course_id FROM enrollments WHERE user_id = $user_id)", null, false)
            ->findAll();
    }
}
