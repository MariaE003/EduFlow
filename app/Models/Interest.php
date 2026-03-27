<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    //
    protected $fillable=[
        'name'
    ];
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function courses(){
        return $this->hasMany(Courses::Class);
    }
}
