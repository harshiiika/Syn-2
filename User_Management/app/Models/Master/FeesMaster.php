<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;

class FeesMaster extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'fees_masters';

    protected $fillable = [
        'course',
        'course_type',
        'class_name',
        'gst_percent',
        'classroom_fee',
        'classroom_gst',
        'classroom_total',
        'classroom_first_installment',
        'classroom_second_installment',
        'classroom_third_installment',
        'live_fee',
        'live_gst',
        'live_total',
        'live_first_installment',
        'live_second_installment',
        'live_third_installment',
        'recorded_fee',
        'recorded_gst',
        'recorded_total',
        'recorded_first_installment',
        'recorded_second_installment',
        'recorded_third_installment',
        'study_fee',
        'study_gst',
        'study_total',
        'test_fee',
        'test_gst',
        'test_total',
        'status'
    ];

    protected $casts = [
        'gst_percent' => 'float',
        'classroom_fee' => 'float',
        'classroom_gst' => 'float',
        'classroom_total' => 'float',
        'classroom_first_installment' => 'float',
        'classroom_second_installment' => 'float',
        'classroom_third_installment' => 'float',
        'live_fee' => 'float',
        'live_gst' => 'float',
        'live_total' => 'float',
        'live_first_installment' => 'float',
        'live_second_installment' => 'float',
        'live_third_installment' => 'float',
        'recorded_fee' => 'float',
        'recorded_gst' => 'float',
        'recorded_total' => 'float',
        'recorded_first_installment' => 'float',
        'recorded_second_installment' => 'float',
        'recorded_third_installment' => 'float',
        'study_fee' => 'float',
        'study_gst' => 'float',
        'study_total' => 'float',
        'test_fee' => 'float',
        'test_gst' => 'float',
        'test_total' => 'float',
    ];

    // Course configurations
  public static $courseConfigs = [
    // Use FULL names to match CREATE dropdown
    'Impulse 11th IIT' => [
        'course_type' => 'Pre-Engineering',
        'class_name' => '11th (XI)',
        'gst_percent' => 18
    ],
    'Momentum 12th NEET' => [
        'course_type' => 'Pre-Medical',
        'class_name' => '12th (XII)',
        'gst_percent' => 18
    ],
    'Intensity 12th IIT' => [
        'course_type' => 'Pre-Engineering',
        'class_name' => '12th (XII)',
        'gst_percent' => 18
    ],
    'Thrust Target IIT' => [
        'course_type' => 'Pre-Engineering',
        'class_name' => 'Target (XII +)',
        'gst_percent' => 18
    ],
    'Seedling 10th' => [
        'course_type' => 'Pre-Foundation',
        'class_name' => '10th (X)',
        'gst_percent' => 18
    ],
    'Anthesis 11th NEET' => [
        'course_type' => 'Pre-Medical',
        'class_name' => '11th (XI)',
        'gst_percent' => 18
    ],
    'Dynamic Target NEET' => [
        'course_type' => 'Pre-Medical',
        'class_name' => 'Target (XII +)',
        'gst_percent' => 18
    ],
    'Radicle 8th' => [
        'course_type' => 'Pre-Foundation',
        'class_name' => '8th (VIII)',
        'gst_percent' => 18
    ],
    'Plumule 9th' => [
        'course_type' => 'Pre-Foundation',
        'class_name' => '9th (IX)',
        'gst_percent' => 18
    ],
    'Nucleus 7th' => [
        'course_type' => 'Pre-Foundation',
        'class_name' => '7th (VII)',
        'gst_percent' => 18
    ]
    ];

    protected static function booted()
    {
        static::saving(function ($fee) {
            // Auto-fill course type and class name based on course
            if ($fee->course && isset(self::$courseConfigs[$fee->course])) {
                $config = self::$courseConfigs[$fee->course];
                $fee->course_type = $config['course_type'];
                $fee->class_name = $config['class_name'];
                
                // Set default GST if not provided
                if (!$fee->gst_percent) {
                    $fee->gst_percent = $config['gst_percent'];
                }
            }

            $gst = $fee->gst_percent ?? 0;

            // Calculate GST and totals for each fee type
            if ($fee->classroom_fee) {
                $fee->classroom_gst = round(($fee->classroom_fee * $gst) / 100, 2);
                $fee->classroom_total = round($fee->classroom_fee + $fee->classroom_gst, 2);
                
                // Calculate installments (dividing total into 3 parts)
                $installmentAmount = round($fee->classroom_total / 3, 2);
                $fee->classroom_first_installment = $installmentAmount;
                $fee->classroom_second_installment = $installmentAmount;
                // Third installment takes any remaining amount due to rounding
                $fee->classroom_third_installment = round($fee->classroom_total - ($installmentAmount * 2), 2);
            }

            if ($fee->live_fee) {
                $fee->live_gst = round(($fee->live_fee * $gst) / 100, 2);
                $fee->live_total = round($fee->live_fee + $fee->live_gst, 2);
                
                $installmentAmount = round($fee->live_total / 3, 2);
                $fee->live_first_installment = $installmentAmount;
                $fee->live_second_installment = $installmentAmount;
                $fee->live_third_installment = round($fee->live_total - ($installmentAmount * 2), 2);
            }

            if ($fee->recorded_fee) {
                $fee->recorded_gst = round(($fee->recorded_fee * $gst) / 100, 2);
                $fee->recorded_total = round($fee->recorded_fee + $fee->recorded_gst, 2);
                
                $installmentAmount = round($fee->recorded_total / 3, 2);
                $fee->recorded_first_installment = $installmentAmount;
                $fee->recorded_second_installment = $installmentAmount;
                $fee->recorded_third_installment = round($fee->recorded_total - ($installmentAmount * 2), 2);
            }

            if ($fee->study_fee) {
                $fee->study_gst = round(($fee->study_fee * $gst) / 100, 2);
                $fee->study_total = round($fee->study_fee + $fee->study_gst, 2);
            }

            if ($fee->test_fee) {
                $fee->test_gst = round(($fee->test_fee * $gst) / 100, 2);
                $fee->test_total = round($fee->test_fee + $fee->test_gst, 2);
            }
        });
    }

    // Helper method to get course config
    public static function getCourseConfig($courseName)
    {
        return self::$courseConfigs[$courseName] ?? null;
    }

    // Get all course names
    public static function getCourseNames()
    {
        return array_keys(self::$courseConfigs);
    }
}