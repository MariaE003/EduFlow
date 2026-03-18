<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    //
    use HasFactory;

    protected $fillable = ['teacher_id','title','description','price'];

    public function teacher(){ 
        return $this->belongsTo(User::class, 'teacher_id'); 
    }
    public function enrollments(){ 
        return $this->hasMany(Enrollment::class); 
    }
    public function wishlists(){ 
        return $this->hasMany(Wishlist::class); 
    }
    public function groups(){ 
        return $this->hasMany(Group::class); 
    }
}
