<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    protected $table = 'STUDENT_ENROLLMENT'; // or your actual table name
    protected $primaryKey = 'SE_INDEX'; // or primary key if different
    public $timestamps = false;

    protected $fillable = [
        'USER_INDEX',
        'SUB_SEC_INDEX',
        'SY_FROM',
        'SY_TO',
        'SEMESTER'
    ];

    public function subjectSection()
    {
        return $this->belongsTo(SubjectSection::class, 'SUB_SEC_INDEX', 'SUB_SEC_INDEX');
    }
}
