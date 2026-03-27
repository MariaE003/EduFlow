<?php
namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentService
{
    public function __construct(){
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    // creer PaymentIntent
    public function createPayment($amount, $currency = 'usd')
    {
        return PaymentIntent::create([
            'amount' => $amount * 100, 
            'currency' => $currency,
        ]);
    }
}