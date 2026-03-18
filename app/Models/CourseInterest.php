<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseInterest extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','interest'];

    public function student(){ 
        return $this->belongsTo(User::class, 'student_id'); 
    }
}
