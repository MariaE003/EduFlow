<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    use HasFactory;

    protected $fillable = ['enrollment_id','stripe_payment_id','amount','status'];

    public function enrollment(){ 
        return $this->belongsTo(Enrollment::class); 
    }
}
