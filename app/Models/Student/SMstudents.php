<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Master\Batch;
use App\Models\Master\Courses;
use Illuminate\Support\Str;


class SMstudents extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 's_mstudents';
    
    public $timestamps = true;
    protected $guarded = [];
    
    protected $fillable = [
        //   PRIMARY FIELDS (used in view)
        'roll_no',
        'student_name',
        'name',                // Alias for student_name
        'email',
        'phone',
        'shift_id',

        // Basic Details (from onboarding)
        'father_name',
        'mother_name',
        'dob',
        'father_contact',
        'father_whatsapp',
        'mother_contact',
        'category',
        'gender',
        'father_occupation',
        'mother_occupation',
        'state',
        'city',
        'pincode',
        'address',
        'belongs_other_city',
        'economic_weaker_section',
        'army_police_background',
        'specially_abled',
        'previous_class',
        'academic_medium',
        'school_name',
        'academic_board',
        'passing_year',
        'percentage',
        'is_repeater',
        'scholarship_test',
        'last_board_percentage',
        'competition_exam',
        'batch_id',
        'batch_name',
        'course_id',
        'course_name',
        'course_type',
        'delivery_mode',
        'course_content',
        'medium',
        'board',
        
        'name',                  // maps to student_name
        'father',                // maps to father_name
        'mother',                // maps to mother_name
        'mobileNumber',          // maps to father_contact
        'fatherWhatsapp',        // maps to father_whatsapp
        'motherContact',         // maps to mother_contact
        'studentContact',        // maps to phone
        'pinCode',               // maps to pincode
        'belongToOtherCity',     // maps to belongs_other_city
        'economicWeakerSection', // maps to economic_weaker_section
        'armyPoliceBackground',  // maps to army_police_background
        'speciallyAbled',        // maps to specially_abled
        'courseType',            // maps to course_type
        'courseName',            // maps to course_name
        'deliveryMode',          // maps to delivery_mode
        'courseContent',         // maps to course_content
        'previousClass',         // maps to previous_class
        'previousMedium',        // maps to academic_medium
        'schoolName',            // maps to school_name
        'previousBoard',         // maps to academic_board
        'passingYear',           // maps to passing_year
        'isRepeater',            // maps to is_repeater
        'scholarshipTest',       // maps to scholarship_test
        'lastBoardPercentage',   // maps to last_board_percentage
        'competitionExam',       // maps to competition_exam
        'batchName',             // maps to batch_name
        
        // Scholarship & Fees
        'eligible_for_scholarship',
        'scholarship_id',
        'scholarship_name',
        'total_fee_before_discount',
        'discretionary_discount',
        'discretionary_discount_type',
        'discretionary_discount_value',
        'discretionary_discount_reason',
        'discount_percentage',
        'discount_amount',
        'discounted_fee',
        'fees_breakup',
        'total_fees',
        'gst_amount',
        'total_fees_inclusive_tax',
        'single_installment_amount',
        'installment_1',
        'installment_2',
        'installment_3',
        'paid_fees',
        'paid_amount',         // Alias for paid_fees
        'remaining_fees',
        'pending_amount',      // Alias for remaining_fees
        'fee_status',          // CRITICAL: Paid, Pending, 2nd Installment due, etc.
        'fee_installments',    // Number of installments
        'paidAmount',
    
        // Arrays
        'fees',
        'other_fees',
        'transactions',
        'paymentHistory',
        'activities',
        'history',
        'documents',
        
        // Status & Meta
        'status',
        'admission_date',
        'transferred_from',
        'transferred_at',
        'created_by',
        'updated_by',
        'session',
        'branch',

    'father_grade',
    'paidAmount',
    'remainingAmount',
    'paymentHistory',
    'last_payment_date',
    'admission_date',
    'activities',
    
    // Documents
    'passport_photo',
    'caste_certificate',
    'scholarship_proof',
    'secondary_marksheet',
    'senior_secondary_marksheet',
   'roll_no', 'student_name', 'name', 'email', 'phone', 'shift_id',
   'father_name', 'mother_name', 'dob', 'father_contact', 'father_whatsapp',
   'mother_contact', 'category', 'gender', 'father_occupation', 'mother_occupation',
    'state', 'city', 'pincode', 'address', 'belongs_other_city',
        'economic_weaker_section', 'army_police_background', 'specially_abled',
        'previous_class', 'academic_medium', 'school_name', 'academic_board',
        'passing_year', 'percentage', 'is_repeater', 'scholarship_test',
        'last_board_percentage', 'competition_exam', 'batch_id', 'batch_name',
        'course_id', 'course_name', 'course_type', 'delivery_mode', 'course_content',
        'medium', 'board', 'status', 'admission_date', 'session', 'branch',
        'fees', 'other_fees', 'transactions', 'paymentHistory', 'activities', 'history',
        'passport_photo', 'marksheet', 'caste_certificate', 'scholarship_proof',
        'secondary_marksheet', 'senior_secondary_marksheet',
        'eligible_for_scholarship', 'scholarship_name', 'total_fee_before_discount',
        'discretionary_discount', 'discretionary_discount_type', 'discretionary_discount_value',
        'discretionary_discount_reason', 'discount_percentage', 'discount_amount',
        'discounted_fee', 'fees_breakup', 'total_fees', 'gst_amount',
        'total_fees_inclusive_tax', 'single_installment_amount',
        'installment_1', 'installment_2', 'installment_3',
        'paid_fees', 'paid_amount', 'remaining_fees', 'pending_amount',
        'fee_status', 'fee_installments', 'paidAmount',
        'transferred_from', 'transferred_at', 'created_by', 'updated_by', 
];
    
    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'datetime',
        'transferred_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'percentage' => 'float',
        'last_board_percentage' => 'float',
        'total_fee_before_discount' => 'decimal:2',
        'discount_percentage' => 'float',
        'discount_amount' => 'decimal:2',
        'discounted_fee' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_fees_inclusive_tax' => 'decimal:2',
        'single_installment_amount' => 'decimal:2',
        'installment_1' => 'decimal:2',
        'installment_2' => 'decimal:2',
        'installment_3' => 'decimal:2',
        'paid_fees' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_fees' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'fee_installments' => 'integer',
        'batchStartDate' => 'date',
        'fees_calculated_at' => 'datetime',
        'percentage' => 'float',
        'last_board_percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'total_fee_before_discount' => 'float',
        'discount_percentage' => 'float',
        'discounted_fee' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'total_fees_inclusive_tax' => 'float',
        'single_installment_amount' => 'float',
        'installment_1' => 'float',
        'installment_2' => 'float',
        'installment_3' => 'float',
        'paid_fees' => 'float',
        'remaining_fees' => 'float',
        'paymentHistory' => 'array',
    'activities' => 'array',
    'last_payment_date' => 'date',
        'paidAmount' => 'float',
        'fees_breakup' => 'array',
        'history' => 'array',
        'documents' => 'array',
        
    ];

  public static function byCourse($courseNameOrId)
    {
        try {
            // Determine if it's an ID or name
            if (strlen($courseNameOrId) === 24) {
                $course = Courses::find($courseNameOrId);
            } else {
                $course = Courses::where('course_name', $courseNameOrId)->first();
            }

            if (!$course) {
                return collect([]);
            }

            $collectionName = $course->getStudentCollectionName();
            
            // Create a new instance pointing to the course collection
            $instance = new static;
            $instance->setTable($collectionName);

            return $instance->newQuery();
            
        } catch (\Exception $e) {
            \Log::error('Error querying students by course: ' . $e->getMessage());
            return collect([]);
        }
    }


    /**
     * Relationship: Student belongs to a Batch
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', '_id');
    }

    /**
     * Relationship: Student belongs to a Course
     */
    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', '_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', '_id');
    }
    
    /**
     *   ACCESSOR: Get student_name from either 'student_name' or 'name' field
     */
    public function getStudentNameAttribute($value)
    {
        return $value ?? $this->attributes['name'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get father_name from either 'father_name' or 'father' field
     */
    public function getFatherNameAttribute($value)
    {
        return $value ?? $this->attributes['father'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get mother_name from either 'mother_name' or 'mother' field
     */
    public function getMotherNameAttribute($value)
    {
        return $value ?? $this->attributes['mother'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get father_contact from either 'father_contact' or 'mobileNumber'
     */
    public function getFatherContactAttribute($value)
    {
        return $value ?? $this->attributes['mobileNumber'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get father_whatsapp from either field
     */
    public function getFatherWhatsappAttribute($value)
    {
        return $value ?? $this->attributes['fatherWhatsapp'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get mother_contact from either field
     */
    public function getMotherContactAttribute($value)
    {
        return $value ?? $this->attributes['motherContact'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get phone from either 'phone' or 'studentContact'
     */
    public function getPhoneAttribute($value)
    {
        return $value ?? $this->attributes['studentContact'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get pincode
     */
    public function getPincodeAttribute($value)
    {
        return $value ?? $this->attributes['pinCode'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get course_type
     */
    public function getCourseTypeAttribute($value)
    {
        return $value ?? $this->attributes['courseType'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get course_name
     */
    public function getCourseNameAttribute($value)
    {
        return $value ?? $this->attributes['courseName'] ?? ($this->course->name ?? 'N/A');
    }
    
    /**
     *   ACCESSOR: Get delivery_mode
     */
    public function getDeliveryModeAttribute($value)
    {
        return $value ?? $this->attributes['deliveryMode'] ?? $this->attributes['delivery'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get academic_medium
     */
    public function getAcademicMediumAttribute($value)
    {
        return $value ?? $this->attributes['previousMedium'] ?? 'N/A';
    }
    
    /**
     *   ACCESSOR: Get academic_board
     */
    public function getAcademicBoardAttribute($value)
    {
        return $value ?? $this->attributes['previousBoard'] ?? 'N/A';
    }

     protected static function boot()
    {
        parent::boot();

        // BEFORE SAVE: Set the correct collection
        static::creating(function ($student) {
            $student->setCollectionByCourse();
        });

        // AFTER COURSE UPDATE: Move to new collection if course changed
        static::updating(function ($student) {
            if ($student->isDirty('course_id') || $student->isDirty('course_name')) {
                $student->moveToCorrectCollection();
            }
        });
    }

    /**
     *  SET COLLECTION DYNAMICALLY BASED ON COURSE
     */
    public function setCollectionByCourse()
    {
        $courseName = $this->course_name ?? $this->course->course_name ?? null;
        
        if ($courseName) {
            $this->collection = $this->generateCollectionName($courseName);
            \Log::info('Collection set dynamically', [
                'course' => $courseName,
                'collection' => $this->collection
            ]);
        }
    }

    /**
     *  GENERATE COLLECTION NAME FROM COURSE NAME
     * Example: "Thrust Target IIT" → "students_thrust_target_iit"
     */
    private function generateCollectionName($courseName)
    {
        $slug = Str::slug($courseName, '_');
        return 'students_' . strtolower($slug);
    }

    /**
     *  MOVE STUDENT TO NEW COLLECTION (when course changes)
     */
    public function moveToCorrectCollection()
    {
        $oldCollection = $this->getTable();
        $this->setCollectionByCourse();
        $newCollection = $this->getTable();

        if ($oldCollection !== $newCollection) {
            \Log::info('Moving student to new collection', [
                'student_id' => $this->_id,
                'from' => $oldCollection,
                'to' => $newCollection
            ]);

            // Copy to new collection
            $newStudent = $this->replicate();
            $newStudent->setTable($newCollection);
            $newStudent->save();

            // Delete from old collection
            $oldModel = new static;
            $oldModel->setTable($oldCollection);
            $oldModel->where('_id', $this->_id)->delete();

            return $newStudent;
        }

        return $this;
    }


    /**
     *  STATIC METHOD: Get all course-based collections
     */
    public static function getAllCourseCollections()
    {
        $db = \DB::connection('mongodb')->getMongoDB();
        $collections = $db->listCollections();
        
        $studentCollections = [];
        foreach ($collections as $collection) {
            $name = $collection->getName();
            if (str_starts_with($name, 'students_')) {
                $studentCollections[] = $name;
            }
        }
        
        return $studentCollections;
    }

     public static function getAllFromAllCollections()
    {
        $allStudents = collect([]);
        
        // 1. Get from main collection
        $mainStudents = static::all();
        $allStudents = $allStudents->merge($mainStudents);
        
        // 2. Get all course collections
        $courseCollections = Courses::getAllStudentCollections();
        
        foreach ($courseCollections as $collectionName) {
            try {
                $instance = new static;
                $instance->setTable($collectionName);
                $courseStudents = $instance->get();
                $allStudents = $allStudents->merge($courseStudents);
            } catch (\Exception $e) {
                \Log::warning("Could not read from collection: {$collectionName}");
            }
        }
        
        // Remove duplicates by roll_no
        return $allStudents->unique('roll_no');
    }

       public static function createInBothCollections($data)
    {
        \DB::beginTransaction();
        
        try {
            // 1. Get course information
            $courseId = $data['course_id'] ?? null;
            $courseName = $data['course_name'] ?? null;
            
            if (!$courseId && !$courseName) {
                throw new \Exception('Course information is required');
            }
            
            // Find course
            $course = null;
            if ($courseId) {
                $course = Courses::find($courseId);
            } elseif ($courseName) {
                $course = Courses::where('course_name', $courseName)->first();
            }
            
            if (!$course) {
                throw new \Exception('Course not found');
            }
            
            // Ensure course_id and course_name are set
            $data['course_id'] = (string)$course->_id;
            $data['course_name'] = $course->course_name;
            
            // 2. Save to MAIN collection (s_mstudents)
            $mainStudent = new static($data);
            $mainStudent->setTable('s_mstudents');
            $mainStudent->save();
            
            \Log::info('  Saved to main collection', [
                'collection' => 's_mstudents',
                'student_id' => $mainStudent->_id,
                'roll_no' => $mainStudent->roll_no
            ]);
            
            // 3. Save to COURSE-SPECIFIC collection
            $courseCollectionName = $course->getStudentCollectionName();
            $courseStudent = new static($data);
            $courseStudent->setTable($courseCollectionName);
            $courseStudent->save();
            
            \Log::info('  Saved to course collection', [
                'collection' => $courseCollectionName,
                'student_id' => $courseStudent->_id,
                'roll_no' => $courseStudent->roll_no
            ]);
            
            \DB::commit();
            
            return [
                'main_student' => $mainStudent,
                'course_student' => $courseStudent,
                'course_collection' => $courseCollectionName
            ];
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error(' Failed to create student in both collections', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

}