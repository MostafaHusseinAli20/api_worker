<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PaymentServices\PaymentService;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentController extends Controller
{
    public $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function processPayment(Request $request, PaymentService $straipPaymentService, $serviceId)
    {
        return $straipPaymentService->payment($request, $this->stripe, $serviceId);
    }
}
