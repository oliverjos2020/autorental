<?php

namespace Database\Seeders;

use App\Models\SystemParameter;
use Illuminate\Database\Seeder;

class PaymentSplitParametersSeeder extends Seeder
{
    /**
     * Adds the payment split parameters if they don't exist yet.
     * Existing values are never overwritten.
     */
    public function run()
    {
        $params = [
            ['ASSOCIATION_PERCENTAGE', '2', 'Share of the booking amount paid to the association (%)'],
            ['CAR_OWNER_PERCENTAGE', '96.5', 'Share of the booking amount paid to the car owner (%)'],
            ['APP_PERCENTAGE', '1.5', 'Share of the booking amount kept for app maintenance (%)'],
            ['VAT_PERCENTAGE', '7.5', 'VAT added on top of the booking amount (%)'],
            ['ASSOCIATION_SUBACCOUNT_CODE', null, 'Paystack subaccount code (ACCT_xxx) of the association'],
            ['APP_SUBACCOUNT_CODE', null, 'Paystack subaccount code (ACCT_xxx) for app maintenance'],
        ];

        foreach ($params as [$key, $value, $description]) {
            SystemParameter::firstOrCreate(
                ['param_key' => $key],
                ['param_value' => $value, 'param_group' => 'Payment Split', 'description' => $description]
            );
        }
    }
}
