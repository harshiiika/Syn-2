<?php

namespace App\Models\Student;

use MongoDB\Laravel\Eloquent\Model;


class Inquiry extends Model
{
    /** Use MongoDB connection */
    protected $connection = 'mongodb';

    /** Collection name (v5.x uses $table) */
    protected $table = 'inquiries';

    /** Mongo uses _id (ObjectId) */
    protected $primaryKey = '_id';
    public $incrementing  = false;
    protected $keyType    = 'string';

    /** Auto-manage created_at / updated_at */
    public $timestamps = true;

    /** Mass-assignable fields */
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

    /** Helpful type casting */
    protected $casts = [
        'ews'                 => 'bool',
        'service_background'  => 'bool',
        'specially_abled'     => 'bool',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];
}
