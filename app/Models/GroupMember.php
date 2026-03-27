<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class GroupMember extends Model
{

    use HasFactory;

    protected $fillable = ['group_id','student_id'];

    public function group(){ 
        return $this->belongsTo(Group::class); 
    }
    public function student(){ 
        return $this->belongsTo(User::class,'student_id'); 
    }
}
