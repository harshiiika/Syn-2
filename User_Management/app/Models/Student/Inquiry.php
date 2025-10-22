<?php

namespace App\Models\Student;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $connection = 'mongodb'; // 👈 ensure it uses MongoDB
    protected $collection = 'inquiries'; // 👈 use MongoDB collection name

    protected $fillable = [
        'student_name',
        'father_name',
        'father_contact',
        'father_whatsapp',
        'student_contact',
        'category',
        'course_name',
        'delivery_mode',
        'course_content',
        'branch',
        'state',
        'city',
        'address',
        'ews',
        'defense',
        'specially_abled',
        'status',
        'remarks',
        'follow_up_date',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'follow_up_date' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\Student\InquiryFactory::new();
    }
}