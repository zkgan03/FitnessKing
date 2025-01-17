<?php

namespace App\Services\Payment;


use App\Services\Payment\Interfaces\IPaymentGateway;
use Http;
use Illuminate\Http\Client\PendingRequest;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class CardPayment implements IPaymentGateway
{
    private string $returnUrl;
    private string $cancelUrl;
    private PendingRequest $cardClient;

    public function __construct()
    {
        $this->cardClient = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => env('CARD_PAYMENT_API_KEY')
        ])->baseUrl(env('CARD_PAYMENT_API_BASE_URL'));
    }

    public function getPaymentRedirectUrl(string $itemId, string $itemName, float $amount): string
    {
        if (empty($this->returnUrl) || empty($this->cancelUrl)) {
            throw new \Exception('Return URL and Cancel URL must be set', 500);
        }

        $response = $this->cardClient->post('Payment/request-payment', [
            'custom_id' => $itemId,
            'amount' => $amount,
            'item_name' => $itemName,
            'success_url' => $this->returnUrl,
            'cancel_url' => $this->cancelUrl
        ]);

        // dd($response);

        if ($response->status() != 200 || $response->json()['status'] != 'INITIATED') {
            throw new \Exception('Failed to get payment URL from Card Payment API', 500);
        }

        return $response->json()['redirectUrl'];
    }

    public function capturePayment(string $transactionId): bool
    {
        // dd($transactionId);
        $response = $this->cardClient->post('Payment/capture-payment', [
            'transaction_id' => $transactionId
        ]);

        // dd($response->json());

        if ($response->json()['status'] == 'COMPLETED') {
            return true;
        }

        return false;
    }

    public function getPaymentDetails(string $transactionId): array
    {
        $response = $this->cardClient->get('Payment/payment-details', [
            'transactionId' => $transactionId
        ]);

        // dd($response->json());

        return [
            'id' => $response['transactionId'],
            "item_id" => $response["customId"],
            'status' => $response['status'],
            'currency' => $response['currency'],
            'amount' => $response["amount"],
            'payment_method' => "card"
        ];
    }

    public function isPaymentCompleted(string $transactionId): bool
    {
        $response = $this->getPaymentDetails($transactionId);

        // dd($response);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return true;
        }

        return false;
    }

    public function setReturnUrl(string $url): void
    {
        // if there is a query exist in url, append it to the url
        if (strpos($url, '?') !== false) {
            $url .= "&";
        } else {
            $url .= "?";
        }
        $this->returnUrl = $url . "method=card";
    }

    public function setCancelUrl(string $url): void
    {

        // if there is a query exist in url, append it to the url
        if (strpos($url, '?') !== false) {
            $url .= "&";
        } else {
            $url .= "?";
        }
        $this->cancelUrl = $url . "method=card";
    }
}