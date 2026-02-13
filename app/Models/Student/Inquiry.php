<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // ensure it uses MongoDB
    protected $collection = 'inquiries'; // use MongoDB collection name

 
    protected $fillable = [
        // Basic Details
        'student_name',
        'father_name',
        'mother',
        'dob',
        'father_contact',
        'father_whatsapp',
        'motherContact',
        'student_contact',
        'category',
        'gender',
        
        // Parent Details
        'fatherOccupation',
        'fatherGrade',
        'motherOccupation',
        
        // Address Details
        'state',
        'city',
        'pinCode',
        'address',
        'belongToOtherCity',
        'economicWeakerSection',
        'armyPoliceBackground',
        'speciallyAbled',
        
        // Course Details
        'courseType',
        'course_type',
        'course_name',
        'deliveryMode',
        'delivery_mode',
        'medium',
        'board',
        'courseContent',
        'course_content',
        
        // Academic Details
        'previousClass',
        'previousMedium',
        'schoolName',
        'previousBoard',
        'passingYear',
        'percentage',
        
        // Scholarship Eligibility
        'isRepeater',
        'scholarshipTest',
        'lastBoardPercentage',
        'competitionExam',
        
        // Batch Details
        'batchName',
        'batch',
        'batch_id',
        'course_id',
        
        // Status
        'status',
        'transferred_to_pending',
        'pending_student_id',
        
        // Scholarship & Fees Details
        'eligible_for_scholarship',
        'scholarship_name',
        'total_fee_before_discount',
        'discretionary_discount',
        'discretionary_discount_type',
        'discretionary_discount_value',
        'discretionary_discount_reason',
        'discount_percentage',
        'discounted_fee',
        'fees_breakup',
        'total_fees',
        'gst_amount',
        'total_fees_inclusive_tax',
        'single_installment_amount',
        'installment_1',
        'installment_2',
        'installment_3',
        'fees_calculated_at',
        
        // Metadata
        'branch',
        'branch_id',
        'session',
        'history',
        'transferred_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'dob' => 'date',
        'percentage' => 'float',
        'lastBoardPercentage' => 'float',
        'total_fee_before_discount' => 'float',
        'discount_percentage' => 'float',
        'discretionary_discount_value' => 'float',
        'discounted_fee' => 'float',
        'total_fees' => 'float',
        'gst_amount' => 'float',
        'total_fees_inclusive_tax' => 'float',
        'single_installment_amount' => 'float',
        'installment_1' => 'float',
        'installment_2' => 'float',
        'installment_3' => 'float',
        'fees_calculated_at' => 'datetime',
        'transferred_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'history' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Student\InquiryFactory::new();
    }

    // /**
    //  * Get the scholarship associated with this inquiry
    //  */
    // public function scholarship()
    // {
    //     if (!$this->scholarship_id) {
    //         return null;
    //     }
        
    //     return Scholarship::find($this->scholarship_id);
    // }

    
    /**
     * Calculate total discount amount
     */
    public function getTotalDiscountAmount()
    {
        $scholarshipDiscount = $this->total_fee_before_discount - $this->scholarship_discounted_fees;
        
        if (!$this->has_discretionary_discount) {
            return $scholarshipDiscount;
        }

        $discretionaryDiscount = 0;
        if ($this->discretionary_discount_type === 'percentage') {
            $discretionaryDiscount = ($this->scholarship_discounted_fees * $this->discretionary_discount_value) / 100;
        } else {
            $discretionaryDiscount = $this->discretionary_discount_value;
        }

        return $scholarshipDiscount + $discretionaryDiscount;
    }

    /**
     * Get discount breakdown
     */
    public function getDiscountBreakdown()
    {
        $breakdown = [
            'original_fee' => $this->total_fee_before_discount,
            'scholarship_discount' => $this->total_fee_before_discount - $this->scholarship_discounted_fees,
            'scholarship_discount_percentage' => $this->scholarship_discount_percentage,
            'after_scholarship' => $this->scholarship_discounted_fees,
        ];

        if ($this->has_discretionary_discount) {
            if ($this->discretionary_discount_type === 'percentage') {
                $breakdown['discretionary_discount'] = ($this->scholarship_discounted_fees * $this->discretionary_discount_value) / 100;
                $breakdown['discretionary_discount_percentage'] = $this->discretionary_discount_value;
            } else {
                $breakdown['discretionary_discount'] = $this->discretionary_discount_value;
                $breakdown['discretionary_discount_fixed'] = $this->discretionary_discount_value;
            }
            $breakdown['discretionary_reason'] = $this->discretionary_discount_reason;
        } else {
            $breakdown['discretionary_discount'] = 0;
        }

        $breakdown['final_fee'] = $this->final_fees;
        $breakdown['total_discount'] = $this->total_fee_before_discount - $this->final_fees;
        $breakdown['total_discount_percentage'] = ($this->total_fee_before_discount > 0) 
            ? (($this->total_fee_before_discount - $this->final_fees) / $this->total_fee_before_discount) * 100 
            : 0;

        return $breakdown;
    }
}