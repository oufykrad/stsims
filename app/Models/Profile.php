<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'mobile',
        'firstname', 
        'lastname', 
        'middlename',
        'suffix',
        'gender',
        'mobile',
        'birthday',
        'information'
    ];

    public function qualifier()
    {
        return $this->hasOne('App\Models\Qualifier', 'profile_id');
    } 

    public function scholar()
    {
        return $this->hasOne('App\Models\Scholar', 'profile_id');
    } 

    public function address()
    {
        return $this->hasOne('App\Models\ProfileAddress', 'profile_id');
    } 

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    // public function getBirthdayAttribute($value)
    // {
    //     if($value == null){
    //         return null;
    //     }
    //     return date('M d, Y', strtotime($value));
    // }

    public function getGenderAttribute($value)
    {
        switch ($value)
		{
			case 1: $sex='Male';break;
			case 2: $sex='Female';break;
			default:$sex=NULL;break;
		}
        return $sex;
    }
}
