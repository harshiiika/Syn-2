<?php

namespace App\Models\Master;

use MongoDB\Laravel\Eloquent\Model;

class FeesMaster extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'fees_masters';

    protected $fillable = [
        'course',
        'gst_percentage',
        'classroom_course',
        'classroom_gst',
        'classroom_total',
        'live_online_course',
        'live_online_gst',
        'live_online_total',
        'recorded_online_course',
        'recorded_online_gst',
        'recorded_online_total',
        'study_material_only',
        'study_material_gst',
        'study_material_total',
        'test_series_only',
        'test_series_gst',
        'test_series_total',
        'status'
    ];

    protected $casts = [
        'gst_percentage' => 'float',
        'classroom_course' => 'float',
        'classroom_gst' => 'float',
        'classroom_total' => 'float',
        'live_online_course' => 'float',
        'live_online_gst' => 'float',
        'live_online_total' => 'float',
        'recorded_online_course' => 'float',
        'recorded_online_gst' => 'float',
        'recorded_online_total' => 'float',
        'study_material_only' => 'float',
        'study_material_gst' => 'float',
        'study_material_total' => 'float',
        'test_series_only' => 'float',
        'test_series_gst' => 'float',
        'test_series_total' => 'float',
    ];

    protected static function booted()
    {
        static::saving(function ($fee) {
            $gst = $fee->gst_percentage ?? 0;

            $calc = fn($base) => [round(($base * $gst)/100, 2), round($base + ($base * $gst)/100, 2)];

            if ($fee->classroom_course) [$fee->classroom_gst, $fee->classroom_total] = $calc($fee->classroom_course);
            if ($fee->live_online_course) [$fee->live_online_gst, $fee->live_online_total] = $calc($fee->live_online_course);
            if ($fee->recorded_online_course) [$fee->recorded_online_gst, $fee->recorded_online_total] = $calc($fee->recorded_online_course);
            if ($fee->study_material_only) [$fee->study_material_gst, $fee->study_material_total] = $calc($fee->study_material_only);
            if ($fee->test_series_only) [$fee->test_series_gst, $fee->test_series_total] = $calc($fee->test_series_only);
        });
    }
}
