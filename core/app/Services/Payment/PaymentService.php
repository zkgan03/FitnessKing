<?php

namespace App\Services\Payment;

use App\Models\Payment;

/**
 * Author:  GAN ZHI KEN
 * 
 */
class PaymentService
{

    public function getPaymentById(int $id): Payment
    {
        return Payment::find($id);
    }

    public function createAndAddPayment(float $amount, string $payment_method, string $currency): Payment
    {
        return Payment::create([
            'payment_currency' => $currency,
            'payment_amount' => $amount,
            'payment_method' => $payment_method,
        ]);
    }

}