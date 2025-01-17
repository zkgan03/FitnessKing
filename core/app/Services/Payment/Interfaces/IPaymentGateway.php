<?php

namespace App\Services\Payment\Interfaces;

/**
 * Author:  GAN ZHI KEN
 * 
 */
interface IPaymentGateway
{
    /**
     *  Set the return URL for the payment gateway
     * 
     * @param string $url
     * @return void
     */
    public function setReturnUrl(string $url): void;

    /**
     * Set the cancel URL for the payment gateway
     * 
     * @param string $url
     * @return void
     */
    public function setCancelUrl(string $url): void;

    /**
     * Get the payment redirect URL, this URL will be used to redirect the user to the payment gateway
     * 
     * @param string $itemName
     * @param float $amount
     * @return string
     */
    public function getPaymentRedirectUrl(string $itemId, string $itemName, float $amount): string;

    /**
     * Capture the payment from the payment gateway
     * 
     * @param string $transactionId
     * @return bool
     */
    public function capturePayment(string $transactionId): bool;

    /**
     * Get payment details from payment gateway
     * 
     * @param string $transactionId
     * @return array{id:string,item_id:string,status:string,currency:string,amount:float,payment_method:string} 
     * [id,status,currency,amount,payment_method]
     */
    public function getPaymentDetails(string $transactionId): array;

    /**
     * Check if the payment is completed
     * 
     * @param string $transactionId
     * @return bool
     */
    public function isPaymentCompleted(string $transactionId): bool;

}