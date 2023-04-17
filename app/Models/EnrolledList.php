<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledList extends Model
{
    use HasFactory;

    protected $fillable = ['grades_completed','benefits_completed','school_semester_id','scholar_id'];

    public function semester()
    {
        return $this->belongsTo('App\Models\SchoolSemester', 'school_semester_id', 'id');
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
