<?php 
namespace App\Repositories;

use App\Models\Interest;

class InterestRepository{//acces db
    public function getAll(){
        $coures=Interest::all();
        return $coures;
    }
}