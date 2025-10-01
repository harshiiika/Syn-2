<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inquiry extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'inquiries';

    // ❌ REMOVE these (let the driver manage ObjectId):
    // protected $primaryKey = '_id';
    // public $incrementing  = false;
    // protected $keyType    = 'string';

    public $timestamps = true;

    protected $fillable = [
        'student_name',
        'father_name',
        'father_contact',
        'father_whatsapp',
        'student_contact',
        'category',
        'state',
        'city',
        'address',
        'branch_name',
        'ews',
        'service_background',
        'specially_abled',
        'status',
    ];

    protected $casts = [
        'ews'                => 'bool',
        'service_background' => 'bool',
        'specially_abled'    => 'bool',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];
}
