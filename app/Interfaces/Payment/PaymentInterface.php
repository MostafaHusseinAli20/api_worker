<?php

namespace App\Interfaces\Payment;

interface PaymentInterface 
{
    public function payment($request, $stripe, $serviceId);
}