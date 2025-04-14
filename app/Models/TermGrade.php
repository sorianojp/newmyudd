<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TermGrade extends Model
{
    protected $table = 'GRADE_SHEET';
    protected $primaryKey = 'GS_INDEX';
    public $timestamps = false;

    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'SUB_SEC_INDEX', 'SUB_SEC_INDEX');
    }
    public function remark()
    {
        return $this->belongsTo(Remark::class, 'REMARK_INDEX', 'REMARK_INDEX');
    }
    public function encodedByUser()
    {
        return $this->belongsTo(UserProfile::class, 'ENCODED_BY', 'USER_INDEX');
    }    
}
