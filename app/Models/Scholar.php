<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholar extends Model
{
    use HasFactory;

    protected $fillable = [
        'spas_id',
        'lrn', 
        'program_id',
        'status_id',
        'old_id',
        'profile_id',
        'awarded_year',
        'is_completed',
        'is_undergrad',
        'is_enrolled'
    ];

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id', 'id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\ScholarAddress', 'scholar_id');
    } 
    
    public function tracer()
    {
        return $this->hasMany('App\Models\ScholarTracer', 'scholar_id');
    } 

    public function statuses()
    {
        return $this->hasMany('App\Models\ScholarStatus', 'scholar_id');
    } 

    public function enrollments()
    {
        return $this->hasMany('App\Models\ScholarEnrollment', 'scholar_id')->orderBy('created_at','DESC');
    } 

    public function education()
    {
        return $this->hasOne('App\Models\ScholarEducation', 'scholar_id');
    } 

    public function status()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'status_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo('App\Models\ListProgram', 'program_id', 'id');
    }   
    
    public function benefits()
    {
        return $this->hasMany('App\Models\BenefitList', 'scholar_id');
    } 

    public function getUpdatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y g:i a', strtotime($value));
    }
}
