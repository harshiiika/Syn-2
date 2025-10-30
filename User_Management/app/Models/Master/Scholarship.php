<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Scholarship extends Eloquent
{
    use HasFactory, SoftDeletes;

    protected $connection = 'mongodb';
    protected $collection = 'scholarships';

   protected $fillable = [
        'scholarship_name',
        'scholarship_type', // 'Test Based', 'Board Based', 'Competition Based'
        'discount_percentage',
        'min_percentage', // For board-based scholarships
        'max_percentage', // For board-based scholarships
        'min_score', // For test-based scholarships
        'max_score', // For test-based scholarships
        'description',
        'is_active',
        'applicable_courses', // Array of course names
        'valid_from',
        'valid_to',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'discount_percentage' => 'float',
        'min_percentage' => 'float',
        'max_percentage' => 'float',
        'min_score' => 'integer',
        'max_score' => 'integer',
        'is_active' => 'boolean',
        'applicable_courses' => 'array',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    protected $dates = ['deleted_at'];

    // Scholarship Types
    const TYPE_CONTINUING_EDUCATION = 'Continuing Education Scholarship';
    const TYPE_BOARD_EXAMINATION   = 'Board Examination Scholarship';
    const TYPE_SPECIAL             = 'Special Scholarship';
    const TYPE_COMPETITION_EXAM    = 'Competition Exam Scholarship';

    // Categories
    const CATEGORY_OBC      = 'OBC';
    const CATEGORY_GENERAL  = 'General';
    const CATEGORY_SC       = 'SC';
    const CATEGORY_ST       = 'ST';

    // Applicable For
    const APPLICABLE_EWS      = 'EWS';
    const APPLICABLE_PWD      = 'Person with Disability';
    const APPLICABLE_DEFENCE  = 'Defence/Police';
    const APPLICABLE_ALL      = 'All';

    // Status
    const STATUS_ACTIVE   = 'active';
    const STATUS_INACTIVE = 'inactive';

    // Static helpers
    public static function getTypes()
    {
        return [
            self::TYPE_CONTINUING_EDUCATION,
            self::TYPE_BOARD_EXAMINATION,
            self::TYPE_SPECIAL,
            self::TYPE_COMPETITION_EXAM,
        ];
    }

    public static function getCategories()
    {
        return [
            self::CATEGORY_OBC,
            self::CATEGORY_GENERAL,
            self::CATEGORY_SC,
            self::CATEGORY_ST,
        ];
    }

    public static function getApplicableFor()
    {
        return [
            self::APPLICABLE_EWS,
            self::APPLICABLE_PWD,
            self::APPLICABLE_DEFENCE,
            self::APPLICABLE_ALL,
        ];
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('scholarship_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Explicitly tell Laravel where to find the factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\ScholarshipFactory::new();
    }

     /**
     * Check if scholarship is valid for the given date
     */
    public function isValidForDate($date = null)
    {
        $date = $date ?? now();
        
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_from && $date < $this->valid_from) {
            return false;
        }

        if ($this->valid_to && $date > $this->valid_to) {
            return false;
        }

        return true;
    }

     /**
     * Check if scholarship applies to a specific course
     */
    public function appliesToCourse($courseName)
    {
        if (!$this->applicable_courses || empty($this->applicable_courses)) {
            return true; // If no courses specified, applies to all
        }

        return in_array($courseName, $this->applicable_courses);
    }

    /**
     * Get scholarship by percentage range
     */
    public static function getByPercentage($percentage, $courseName = null)
    {
        $query = self::where('scholarship_type', 'Board Based')
            ->where('is_active', true)
            ->where('min_percentage', '<=', $percentage)
            ->where('max_percentage', '>=', $percentage);

        if ($courseName) {
            $query->where(function($q) use ($courseName) {
                $q->whereNull('applicable_courses')
                  ->orWhere('applicable_courses', 'all', [$courseName]);
            });
        }

        return $query->orderBy('discount_percentage', 'desc')->first();
    }

    /**
     * Get scholarship by test score range
     */
    public static function getByTestScore($score, $courseName = null)
    {
        $query = self::where('scholarship_type', 'Test Based')
            ->where('is_active', true)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score);

        if ($courseName) {
            $query->where(function($q) use ($courseName) {
                $q->whereNull('applicable_courses')
                  ->orWhere('applicable_courses', 'all', [$courseName]);
            });
        }

        return $query->orderBy('discount_percentage', 'desc')->first();
    }
}
