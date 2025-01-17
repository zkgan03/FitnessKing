<?php

namespace App\Services\Payment;

use App\Services\Payment\Interfaces\IPaymentGateway;
use Srmklive\PayPal\Services\PayPal as PaypalClient;


/**
 * Author:  GAN ZHI KEN
 * 
 */
class Paypal implements IPaymentGateway
{

    private PaypalClient $provider;
    private string $returnUrl;
    private string $cancelUrl;

    public function __construct()
    {
        $this->provider = new PaypalClient();
    }

    private function setProviderCredentials()
    {
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function getPaymentRedirectUrl(string $itemId, string $itemName, float $amount): string
    {
        $this->provider->getAccessToken();

        if (empty($this->returnUrl) || empty($this->cancelUrl)) {
            throw new \Exception('Return URL and Cancel URL must be set');
        }

        $this->setProviderCredentials();

        $response = $this->provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    "custom_id" => $itemId,
                    "items" => [
                        [
                            'name' => $itemName,
                            'quantity' => 1,
                            "unit_amount" => [
                                "currency_code" => "USD",
                                "value" => $amount,
                            ]
                        ]
                    ],
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => $amount,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => $amount,
                            ],
                        ]
                    ],
                ],
            ],
            "payment_source" => [
                "paypal" => [
                    "experience_context" => [
                        "payment_method_preference" => "IMMEDIATE_PAYMENT_REQUIRED",
                        "brand_name" => "Fitness King",
                        "locale" => "en-US",
                        "landing_page" => "LOGIN",
                        "shipping_preference" => "NO_SHIPPING",
                        "user_action" => "PAY_NOW",
                        "return_url" => $this->returnUrl,
                        "cancel_url" => $this->cancelUrl,
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['status'] === 'PAYER_ACTION_REQUIRED') {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'payer-action') {
                    return $link['href']; // return the redirect link to user
                }
            }
        }

        // dd($response);
        throw new \Exception('Failed to get payment URL from Paypal API', 500);
    }

    public function capturePayment(string $transactionId): bool
    {
        $this->provider->getAccessToken();

        // capture the payment, so the transaction is completed (money is transferred)
        // status from APPROVED to COMPLETED
        $response = $this->provider->capturePaymentOrder($transactionId);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return true; // payment is success
        }

        // dd($response);
        return false; // payment failed
    }

    public function getPaymentDetails(string $transactionId): array
    {
        $this->provider->getAccessToken();

        $response = $this->provider->showOrderDetails($transactionId);

        return [
            "id" => $response['id'],
            "item_id" => $response['purchase_units'][0]['custom_id'],
            "status" => $response['status'],
            "currency" => $response['purchase_units'][0]['amount']['currency_code'],
            "amount" => $response['purchase_units'][0]['amount']['value'],
            "payment_method" => "paypal",
        ];
    }

    public function isPaymentCompleted(string $transactionId): bool
    {
        $response = $this->getPaymentDetails($transactionId);

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
        $this->returnUrl = $url . "method=paypal";
    }

    public function setCancelUrl(string $url): void
    {
        // if there is a query exist in url, append it to the url
        if (strpos($url, '?') !== false) {
            $url .= "&";
        } else {
            $url .= "?";
        }
        $this->cancelUrl = $url . "method=paypal";
    }
}