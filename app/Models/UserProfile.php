<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'USER_INDEX';
    protected $primaryKey = 'USER_INDEX';
    public $timestamps = false;

    protected $fillable = [
        'FNAME', 'MNAME', 'LNAME', 'ID_NUMBER',
        'IS_ACTIVE_', 'IS_VALID', 'IS_DEL',
        'CREATE_DATE', 'CURRENT_STATUS', 'AUTH_TYPE_INDEX', 'MEM_TYPE_INDEX'
    ];

    public function login()
    {
        return $this->hasOne(User::class, 'USER_INDEX', 'USER_INDEX');
    }
}
