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
        'scholarship_type',
        'scholarship_name',
        'short_name',
        'description',
        'category',
        'applicable_for',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
}
