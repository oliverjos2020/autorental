<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\Paypal\Services\Paypal as PayPalClient;
use App\Models\BookingOrder;

class PaymentController extends Controller
{
    public function processPaypal(Request $request)
    {
        // $provider = new PayPalClient;
        $provider = \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('processSuccess'),
                "cancel_url" => route('processCancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00",
                    ],
                ],
            ],
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('checkout')
                ->with('error', 'Something went wrong.');

        } else {
            return redirect()
                ->route('checkout')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }

    }

    public function processSuccess(Request $request)
    {
        // $provider = new PayPalClient;
        $provider = \PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            BookingOrder::where('user_id', Auth()->User()->id)->update([
                
                'payment_status' => 1,
                'status' => 1,
    
            ]);
            return redirect()
                ->route('checkout')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('checkout')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function processCancel(Request $request)
    {
        return redirect()
            ->route('checkout')
            ->with('error', 'You have canceled the transaction.');
    }

}
