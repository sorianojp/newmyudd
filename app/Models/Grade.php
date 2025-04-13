<?php

namespace App\Models;
use App\Models\SubSection;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'G_SHEET_FINAL';
    protected $primaryKey = 'GS_INDEX';
    public $timestamps = false;

    protected $fillable = [
        'GRADE_NAME',
        'GRADE',
        'REMARK_INDEX',
        'CREDIT_EARNED',
        'user_index_',
        'IS_VALID',
        'IS_DEL',
        'CREATE_DATE'
        // add more if needed
    ];

    public function subSection()
    {
        return $this->belongsTo(SubSection::class, 'SUB_SEC_INDEX', 'SUB_SEC_INDEX');
    }

}
