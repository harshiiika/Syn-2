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
        // New required fields
        'scholarship_name',
        'short_name',
        'scholarship_type',
        'category',
        'applicable_for',
        'description',
        'status',
        
        // Original fields (keeping for backward compatibility)
        'discount_percentage',
        'min_percentage',
        'max_percentage',
        'min_score',
        'max_score',
        'is_active',
        'applicable_courses',
        'valid_from',
        'valid_to',
        
        // Audit fields
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'discount_percentage' => 'float',
        'min_percentage' => 'float',
        'max_percentage' => 'float',
        'min_score' => 'integer',
        'max_score' => 'integer',
        'is_active' => 'boolean',
        'applicable_courses' => 'array',
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = ['deleted_at', 'valid_from', 'valid_to'];

    protected $attributes = [
        'status' => 'active',
        'is_active' => true,
    ];

    // ==================== CONSTANTS ====================
    
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

    // ==================== STATIC HELPERS ====================
    
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

    // ==================== SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('scholarship_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByApplicableFor($query, $applicableFor)
    {
        return $query->where('applicable_for', $applicableFor);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('scholarship_name', 'like', "%{$search}%")
              ->orWhere('short_name', 'like', "%{$search}%")
              ->orWhere('scholarship_type', 'like', "%{$search}%");
        });
    }

    // ==================== RELATIONSHIPS ====================
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ==================== ACCESSORS & MUTATORS ====================
    
    /**
     * Get the scholarship's display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->short_name ? "{$this->scholarship_name} ({$this->short_name})" : $this->scholarship_name;
    }

    /**
     * Check if scholarship is active
     */
    public function getIsActiveStatusAttribute()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // ==================== METHODS ====================
    
    /**
     * Toggle scholarship status
     */
    public function toggleStatus()
    {
        $this->status = $this->status === self::STATUS_ACTIVE 
            ? self::STATUS_INACTIVE 
            : self::STATUS_ACTIVE;
        
        return $this->save();
    }

    /**
     * Activate scholarship
     */
    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->is_active = true;
        return $this->save();
    }

    /**
     * Deactivate scholarship
     */
    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->is_active = false;
        return $this->save();
    }

    /**
     * Check if scholarship is valid for the given date
     */
    public function isValidForDate($date = null)
    {
        $date = $date ?? now();
        
        // Check if active
        if ($this->status !== self::STATUS_ACTIVE && !$this->is_active) {
            return false;
        }

        // Check valid_from date
        if ($this->valid_from && $date->lt($this->valid_from)) {
            return false;
        }

        // Check valid_to date
        if ($this->valid_to && $date->gt($this->valid_to)) {
            return false;
        }

        return true;
    }

    /**
     * Check if scholarship applies to a specific course
     */
    public function appliesToCourse($courseName)
    {
        // If no courses specified, applies to all
        if (!$this->applicable_courses || empty($this->applicable_courses)) {
            return true;
        }

        return in_array($courseName, $this->applicable_courses);
    }

    /**
     * Get scholarship by percentage range (Board Based)
     */
    public static function getByPercentage($percentage, $courseName = null, $category = null)
    {
        $query = self::where('scholarship_type', self::TYPE_BOARD_EXAMINATION)
            ->where('status', self::STATUS_ACTIVE)
            ->where('min_percentage', '<=', $percentage)
            ->where('max_percentage', '>=', $percentage);

        if ($courseName) {
            $query->where(function($q) use ($courseName) {
                $q->whereNull('applicable_courses')
                  ->orWhereJsonContains('applicable_courses', $courseName);
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('discount_percentage', 'desc')->first();
    }

    /**
     * Get scholarship by test score range (Test Based)
     */
    public static function getByTestScore($score, $courseName = null, $category = null)
    {
        $query = self::where('scholarship_type', self::TYPE_COMPETITION_EXAM)
            ->where('status', self::STATUS_ACTIVE)
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score);

        if ($courseName) {
            $query->where(function($q) use ($courseName) {
                $q->whereNull('applicable_courses')
                  ->orWhereJsonContains('applicable_courses', $courseName);
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        return $query->orderBy('discount_percentage', 'desc')->first();
    }

    /**
     * Get all active scholarships for a specific category
     */
    public static function getForCategory($category)
    {
        return self::where('status', self::STATUS_ACTIVE)
            ->where(function($q) use ($category) {
                $q->where('category', $category)
                  ->orWhere('applicable_for', self::APPLICABLE_ALL);
            })
            ->orderBy('scholarship_name')
            ->get();
    }

    /**
     * Get scholarships applicable for a student
     */
    public static function getApplicableScholarships($category, $applicableFor, $courseName = null)
    {
        $query = self::where('status', self::STATUS_ACTIVE)
            ->where(function($q) use ($category) {
                $q->where('category', $category)
                  ->orWhere('category', 'All');
            })
            ->where(function($q) use ($applicableFor) {
                $q->where('applicable_for', $applicableFor)
                  ->orWhere('applicable_for', self::APPLICABLE_ALL);
            });

        if ($courseName) {
            $query->where(function($q) use ($courseName) {
                $q->whereNull('applicable_courses')
                  ->orWhereJsonContains('applicable_courses', $courseName);
            });
        }

        return $query->orderBy('discount_percentage', 'desc')->get();
    }

    // ==================== BOOT METHOD ====================
    
    protected static function boot()
    {
        parent::boot();

        // Set default status when creating
        static::creating(function ($scholarship) {
            if (!isset($scholarship->status)) {
                $scholarship->status = self::STATUS_ACTIVE;
            }
            if (!isset($scholarship->is_active)) {
                $scholarship->is_active = true;
            }
        });
    }

    // ==================== FACTORY ====================
    
    /**
     * Explicitly tell Laravel where to find the factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\ScholarshipFactory::new();
    }
}