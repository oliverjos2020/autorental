<?php

namespace App\Services;

use App\Models\SystemParameter;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private const BASE_URL = 'https://api.paystack.co';

    private function client()
    {
        return Http::withToken(config('services.paystack.secret_key'))->acceptJson();
    }

    /**
     * Create a Paystack subaccount and return its subaccount_code.
     *
     * percentage_charge is mandatory on Paystack's side, but it is ignored
     * whenever a dynamic split is sent at transaction initialization.
     *
     * @throws Exception with Paystack's message when creation fails
     */
    public function createSubaccount(string $businessName, string $bankCode, string $accountNumber): string
    {
        $response = $this->client()->post(self::BASE_URL . '/subaccount', [
            'business_name' => $businessName,
            'settlement_bank' => $bankCode,
            'account_number' => $accountNumber,
            'percentage_charge' => 0,
        ]);

        $data = $response->json();

        if (!$response->successful() || !($data['status'] ?? false) || empty($data['data']['subaccount_code'])) {
            throw new Exception($data['message'] ?? 'Unable to create Paystack subaccount.');
        }

        return $data['data']['subaccount_code'];
    }

    public function deactivateSubaccount(string $subaccountCode): void
    {
        try {
            $this->client()->put(self::BASE_URL . "/subaccount/{$subaccountCode}", ['active' => false]);
        } catch (Exception $e) {
            Log::error('Unable to deactivate Paystack subaccount', [
                'subaccount_code' => $subaccountCode,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Work out how a booking amount is charged and split. All figures in kobo.
     *
     * VAT is added on top of the booking amount. The association, car owner
     * and app shares are taken from the base amount only, as flat shares, so
     * whatever is left of the total (the VAT) stays in the main Paystack account.
     *
     * @return array{base:int, vat:int, total:int, shares:array}
     * @throws Exception when the percentages or subaccounts are not configured properly
     */
    public function buildSplit(float $amount, string $carOwnerSubaccount): array
    {
        $associationPct = (float) SystemParameter::getValue('ASSOCIATION_PERCENTAGE', 2);
        $carOwnerPct = (float) SystemParameter::getValue('CAR_OWNER_PERCENTAGE', 96.5);
        $appPct = (float) SystemParameter::getValue('APP_PERCENTAGE', 1.5);
        $vatPct = (float) SystemParameter::getValue('VAT_PERCENTAGE', 0.0);

        if (abs($associationPct + $carOwnerPct + $appPct - 100) > 0.0001) {
            throw new Exception('Association, car owner and app percentages must add up to 100.');
        }

        $associationAccount = SystemParameter::getValue('ASSOCIATION_SUBACCOUNT_CODE');
        $appAccount = SystemParameter::getValue('APP_SUBACCOUNT_CODE');

        if (!$associationAccount || !$appAccount) {
            throw new Exception('ASSOCIATION_SUBACCOUNT_CODE and APP_SUBACCOUNT_CODE must be set in system parameters.');
        }

        $base = (int) round($amount * 100);
        $vat = (int) round($base * $vatPct / 100);

        $association = (int) round($base * $associationPct / 100);
        $app = (int) round($base * $appPct / 100);
        // Car owner takes the remainder so the three shares always equal the base exactly.
        $carOwner = $base - $association - $app;

        return [
            'base' => $base,
            'vat' => $vat,
            'total' => $base + $vat,
            // 'total' => $base,
            'shares' => [
                'association' => ['subaccount' => $associationAccount, 'share' => $association],
                'car_owner' => ['subaccount' => $carOwnerSubaccount, 'share' => $carOwner],
                'app' => ['subaccount' => $appAccount, 'share' => $app],
            ],
        ];
    }

    /**
     * Initialize a Paystack transaction with a flat dynamic split.
     *
     * @return array Paystack's response body
     */
    public function initializeSplitTransaction(string $email, array $split, string $reference, array $metadata = []): array
    {
        $response = $this->client()->post(self::BASE_URL . '/transaction/initialize', [
            'email' => $email,
            'amount' => $split['total'],
            'currency' => 'NGN',
            'reference' => $reference,
            'metadata' => $metadata + [
                'vat' => $split['vat'] / 100,
                'split' => array_map(fn ($s) => $s['share'] / 100, $split['shares']),
            ],
            'split' => [
                'type' => 'flat',
                'bearer_type' => 'account',
                'subaccounts' => array_values($split['shares']),
            ],
        ]);

        return $response->json() ?? ['status' => false, 'message' => 'Invalid response from Paystack'];
    }

    /**
     * Ask Paystack which of the split's subaccounts it does not recognise.
     * Typical causes: a code created with the live key while the test key is
     * in use (or the reverse), or a placeholder/typo in system parameters.
     *
     * @return array<string,string> share name (association|car_owner|app) => subaccount code
     */
    public function missingSubaccounts(array $split): array
    {
        $missing = [];

        foreach ($split['shares'] as $name => $share) {
            $response = $this->client()->get(self::BASE_URL . '/subaccount/' . urlencode($share['subaccount']));

            if (!$response->successful() || !($response->json('status') ?? false)) {
                $missing[$name] = $share['subaccount'];
            }
        }

        return $missing;
    }
}
