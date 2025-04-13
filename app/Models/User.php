<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'LOGIN_INFO'; // Authentication info is stored here
    protected $primaryKey = 'LOGIN_INDEX'; // Primary key for the login table
    public $timestamps = false;

    protected $fillable = [
        'USER_ID',
        'PASSWORD',
        'USER_INDEX',
        'IS_LOGGEDIN',
        'IS_VALID',
        'IS_DEL',
        'LAST_RENEW',
        'CREATE_DATE'
    ];

    protected $hidden = [
        'PASSWORD', 'MIXER_'
    ];

    // Define accessor for Laravel authentication password
    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'USER_INDEX', 'USER_INDEX');
    }

    public function finalGrades()
    {
        return $this->hasMany(Grade::class, 'user_index_', 'USER_INDEX');
    }

    public function termGrades()
    {
        return $this->hasMany(TermGrade::class, 'user_index_', 'USER_INDEX');
    }

}
