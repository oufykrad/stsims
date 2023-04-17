<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCampus extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus',
        'shortcut',
        'address',
        'is_main',
        'municipality_code',
        'school_id',
        'term_id',
        'grading_id',
    ];

    public function municipality()
    {
        return $this->belongsTo('App\Models\LocationMunicipality', 'municipality_code', 'code');
    }

    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'id');
    }

    public function courses()
    {
        return $this->hasMany('App\Models\SchoolCourse', 'school_id');
    } 

    public function semesters()
    {
        return $this->hasMany('App\Models\SchoolSemester', 'school_id')->orderBy('created_at','DESC');
    } 

    public function scholars()
    {
        return $this->hasMany('App\Models\ScholarEducation', 'school_id');
    } 

    public function term()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'term_id', 'id');
    }

    public function grading()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'grading_id', 'id');
    }

    public function getCampusAttribute($value){
        return strtoupper($value);
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
