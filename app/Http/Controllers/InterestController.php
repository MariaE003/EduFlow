<?php

namespace App\Http\Controllers;
use App\services\InterestService;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    protected $InterestService;
    public function __construct(InterestService $InterestService){
        $this->InterestService=$InterestService;
    }

    public function index(){
        return $this->InterestService->allInterest();
    }
    
}
