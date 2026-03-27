<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{

     use HasFactory;

    protected $fillable = ['course_id','group_number'];

    public function course(){ 
        return $this->belongsTo(Course::class); 
    }
    public function members(){ 
        return $this->hasMany(GroupMember::class); 
    }
}
