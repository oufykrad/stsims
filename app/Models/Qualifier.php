<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qualifier extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'spas_id',
        'school_code',
        'course_id',
        'profile_id',
        'is_undergrad',
        'is_qualified',
        'is_waiting',
        'is_referral',
        'year'
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo('App\Models\ListProgram', 'program_id', 'id');
    }  
}
