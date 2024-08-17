<?php

namespace App\Services;

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPalService
{
    private $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('paypal.client_id'),
                config('paypal.secret')
            )
        );

        // $this->apiContext->setConfig(config('paypal.settings'));
        $this->apiContext->setConfig([
            'mode' => env('PAYPAL_MODE', 'sandbox'),
        ]);
    }

    public function createOrder($amount)
    {
        // Ensure the amount is a valid numeric value
        $amount = number_format((float) $amount, 2, '.', '');

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $amountObj = new Amount();
        $amountObj->setCurrency('USD')
            ->setTotal($amount);

        $transaction = new Transaction();
        $transaction->setAmount($amountObj)
            ->setDescription('Payment description');

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(route('paypal.success'))
            ->setCancelUrl(route('paypal.cancel'));

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($this->apiContext);
        } catch (\Exception $ex) {
            throw new \Exception('Error creating PayPal payment: ' . $ex->getMessage());
        }

        return $payment;
    }

    public function executePayment($paymentId, $payerId)
    {
        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        try {
            $result = $payment->execute($execution, $this->apiContext);
        } catch (\Exception $ex) {
            throw new \Exception('Error executing PayPal payment: ' . $ex->getMessage());
        }

        return $result;
    }

    public function generateClientToken()
    {
        try {
            return $this->apiContext->getCredential()->getAccessToken(['grant_type' => 'client_credentials']);
        } catch (\Exception $ex) {
            throw new \Exception('Unable to generate client token: ' . $ex->getMessage());
        }
    }
}
