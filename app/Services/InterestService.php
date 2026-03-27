<?php
namespace App\services;
use App\Repositories\InterestRepository;

class InterestService{
    protected $InterestRepo;
    public function __construct(InterestRepository $InterestRepo){
        $this->InterestRepo=$InterestRepo;
    }
    public function allInterest(){
        return $this->InterestRepo->getAll();
    }
    
}




?>