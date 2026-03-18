<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    //
     use HasFactory;

    protected $fillable = ['course_id','name','max_students'];

    public function course(){ 
        return $this->belongsTo(Course::class); 
    }
    public function members(){ 
        return $this->hasMany(GroupMember::class); 
    }
}
