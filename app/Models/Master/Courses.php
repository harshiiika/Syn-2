<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model as Eloquent;
use App\Models\Student\SMstudents;
use Illuminate\Support\Str;

class Courses extends Eloquent
{
    protected $connection = 'mongodb';     
    protected $collection = 'courses';    
    
    protected $fillable = [
        'course_name',
        'name',
        'course_type',
        'class_name',
        'content',
        'course_code',
        'subjects',
        'status',
        'description',
        'duration',
        'created_by',
        'updated_by',
        'student_collection_name'  // Stores the course-specific collection name
    ];

    protected $casts = [
        'subjects' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Boot method - Auto-generate collection name when course is created
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            $course->student_collection_name = $course->generateStudentCollectionName();
        });

        static::updating(function ($course) {
            // If course name changed, update collection name
            if ($course->isDirty('course_name')) {
                $oldCollectionName = $course->getOriginal('student_collection_name');
                $newCollectionName = $course->generateStudentCollectionName();
                
                if ($oldCollectionName !== $newCollectionName) {
                    $course->renameStudentCollection($oldCollectionName, $newCollectionName);
                    $course->student_collection_name = $newCollectionName;
                }
            }
        });
    }

    /**
     * Generate standardized collection name from course name
     * Example: "Anthesia Class 11th" → "students_anthesia_class_11th"
     */
       public function generateStudentCollectionName()
    {
        $slug = Str::slug($this->course_name, '_');
        return 'students_' . strtolower($slug);
    }

    /**
     * Get the actual student collection name
     */
    public function getStudentCollectionName()
    {
        return $this->student_collection_name ?? $this->generateStudentCollectionName();
    }

    /**
     * Relationship: Get all students in this course (from course-specific collection)
     */
    public function students()
    {
        return SMstudents::on('mongodb')
            ->from($this->getStudentCollectionName())
            ->where('course_id', $this->_id);
    }


    // /**
    //  * Get the actual student collection name
    //  */
    // public function getStudentCollectionName()
    // {
    //     return $this->student_collection_name ?? $this->generateStudentCollectionName();
    // }

    
    /**
     * Get count of fully paid students in course collection
     */
      public function getFullyPaidStudentsCount()
    {
        $collectionName = $this->getStudentCollectionName();
        
        try {
            return \DB::connection('mongodb')
                ->getCollection($collectionName)
                ->count([
                    'course_id' => $this->_id,
                    'fee_status' => 'Paid'
                ]);
        } catch (\Exception $e) {
            \Log::warning("Collection {$collectionName} does not exist yet");
            return 0;
        }
    }
    /**
     * Rename student collection when course name changes
     */
    private function renameStudentCollection($oldName, $newName)
    {
        try {
            $db = \DB::connection('mongodb')->getMongoDB();
            
            // Check if old collection exists
            $collections = iterator_to_array($db->listCollections(['filter' => ['name' => $oldName]]));
            
            if (!empty($collections)) {
                $db->command([
                    'renameCollection' => 'mongodb.' . $oldName,
                    'to' => 'mongodb.' . $newName,
                    'dropTarget' => false
                ]);
                
                \Log::info("Renamed collection: {$oldName} → {$newName}");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to rename collection: " . $e->getMessage());
        }
    }

    /**
     * Relationship: Course has many Batches
     */
    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_id', '_id');
    }

    /**
     * Scope: Active courses only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Accessor: Get 'name' attribute
     */
    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->course_name;
    }

    /**
     * Accessor: Get 'content' attribute
     */
    public function getContentAttribute()
    {
        return $this->attributes['content'] ?? $this->class_name;
    }

    /**
     * Mutator: Set 'name' when setting course_name
     */
    public function setCourseNameAttribute($value)
    {
        $this->attributes['course_name'] = $value;
        $this->attributes['name'] = $value;
    }

    /**
     * Mutator: Set 'content' when setting class_name
     */
    public function setClassNameAttribute($value)
    {
        $this->attributes['class_name'] = $value;
        $this->attributes['content'] = $value;
    }

    /**
     * Get all course-specific collections
     */
    public static function getAllStudentCollections()
    {
        $db = \DB::connection('mongodb')->getMongoDB();
        $collections = $db->listCollections(['filter' => ['name' => ['$regex' => '^students_']]]);
        
        $result = [];
        foreach ($collections as $collection) {
            $result[] = $collection->getName();
        }
        
        return $result;
    }

     public function getStudentsCount()
    {
        $collectionName = $this->getStudentCollectionName();
        
        try {
            return \DB::connection('mongodb')
                ->getCollection($collectionName)
                ->count(['course_id' => $this->_id]);
        } catch (\Exception $e) {
            \Log::warning("Collection {$collectionName} does not exist yet");
            return 0;
        }
    }
     public function createStudentInBothCollections($studentData)
    {
        try {
            // 1. Save to main smstudents collection
            $mainStudent = SMstudents::create($studentData);
            
            // 2. Save to course-specific collection
            $courseCollection = $this->getStudentCollectionName();
            $courseStudent = new SMstudents($studentData);
            $courseStudent->setTable($courseCollection);
            $courseStudent->save();
            
            \Log::info("  Student saved to BOTH collections", [
                'main_collection' => 's_mstudents',
                'course_collection' => $courseCollection,
                'student_id' => $mainStudent->_id,
                'course_student_id' => $courseStudent->_id
            ]);
            
            return [
                'main_student' => $mainStudent,
                'course_student' => $courseStudent
            ];
            
        } catch (\Exception $e) {
            \Log::error(" Failed to save student to both collections: " . $e->getMessage());
            throw $e;
        }
    }

}