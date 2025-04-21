<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class FixUserAccountNumbersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $users = User::all();

        foreach ($users as $user) {
            $accountNumber = $user->account_number;

            // Skip if not numeric or null
            if (!is_numeric($accountNumber) || is_null($accountNumber)) {
                continue;
            }

            $length = strlen($accountNumber);

            if ($length === 8 || $length === 9 || $length === 7) {
                $zerosToAdd = 10 - $length;
                $newAccountNumber = str_pad($accountNumber, 10, '0', STR_PAD_LEFT);

                $user->account_number = $newAccountNumber;
                $user->save();

                // echo "Updated User ID {$user->id}: {$accountNumber} → {$newAccountNumber}\n";
                $this->command->info("Updated User ID {$user->id}: {$accountNumber} → {$newAccountNumber}");
            }
        }

        echo "✅ Done fixing account numbers.\n";
    
    }
}
