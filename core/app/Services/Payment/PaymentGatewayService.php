<?php

namespace App\Services\Payment;

use App\Services\Payment\Interfaces\IPaymentGateway;

/**
 * Author:  GAN ZHI KEN
 * 
 */
class PaymentGatewayService
{

    public function __construct(private IPaymentGateway $paymentGateway)
    {
    }

    /**
     * Request payment from payment gateway
     *     
     * @param 
     * @return string Redirect URL to payment gateway
     */
    public function requestPayment(string $packageId, string $packageName, float $price): string
    {
        $queryString = "?package_id=$packageId";
        $successUrl = route('payment.success') . $queryString;
        $cancelUrl = route('payment.cancel') . $queryString;

        $this->paymentGateway->setReturnUrl($successUrl);
        $this->paymentGateway->setCancelUrl($cancelUrl);

        $url = $this->paymentGateway->getPaymentRedirectUrl(
            $packageId,
            $packageName,
            $price,
        );

        if (!empty($url)) {

            return $url;
        }

        throw new \Exception('Payment failed');
    }

    /**
     * Capture payment from payment gateway
     * 
     * @param string $transactionId
     * @return bool
     */
    public function capturePayment(string $transactionId): bool
    {
        return $this->paymentGateway->capturePayment($transactionId);
    }

    /**
     * Get payment details from payment gateway
     * 
     * @param string $transactionId
     * @return array{id:string,item_id:string,status:string,currency:string,amount:float,payment_method:string} 
     * [id,status,currency,amount,payment_method]
     */
    public function getPaymentDetails(string $transactionId): array
    {
        return $this->paymentGateway->getPaymentDetails($transactionId);
    }

    /**
     * Check if payment is completed
     * 
     * @param string $transactionId
     * @return bool
     */
    public function isPaymentCompleted(string $transactionId): bool
    {
        return $this->paymentGateway->isPaymentCompleted($transactionId);
    }

}