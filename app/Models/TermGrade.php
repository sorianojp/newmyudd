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
}
