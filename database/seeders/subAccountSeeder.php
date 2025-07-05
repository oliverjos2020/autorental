<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class subAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::whereRaw('LENGTH(account_number) = 10')->get();
        $errorLog = [];
        $successCount = 0;
        $failCount = 0;
        
        foreach ($users as $user) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.env('PAYSTACK_LIVE_KEY'),
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])->post('https://api.paystack.co/subaccount', [
                    'business_name' => $user->business_name,
                    'settlement_bank' => $user->bank_code,
                    'account_number' => (string)$user->account_number,
                    'percentage_charge' => $user->percentage_charge
                ]);
                
                if ($response->json('status') == true) {
                    // Update the user with the account_code
                    $user->account_code = $response->json('data.subaccount_code');
                    $user->save();
                    
                    $this->command->info("Updated user ID {$user->id} with account code: {$user->account_code}");
                    $successCount++;
                } else {
                    // Log the error
                    $errorMessage = "Failed for user ID {$user->id}: " . ($response->json('message') ?? 'Unknown error');
                    $errorLog[] = [
                        'user_id' => $user->id,
                        'account_number' => $user->account_number,
                        'error' => $errorMessage,
                        'response' => $response->json()
                    ];
                    $this->command->error($errorMessage);
                    $failCount++;
                }
                
                // Sleep briefly to avoid rate limiting
                usleep(500000); // 0.5 seconds
                
            } catch (\Exception $e) {
                $errorMessage = "Exception for user ID {$user->id}: " . $e->getMessage();
                $errorLog[] = [
                    'user_id' => $user->id,
                    'account_number' => $user->account_number,
                    'error' => $errorMessage
                ];
                $this->command->error($errorMessage);
                $failCount++;
            }
        }
        
        // Write errors to log file
        if (!empty($errorLog)) {
            Log::channel('daily')->info('Subaccount creation errors: ' . json_encode($errorLog, JSON_PRETTY_PRINT));
            file_put_contents(
                storage_path('logs/subaccount_errors_' . date('Y-m-d_H-i-s') . '.json'), 
                json_encode($errorLog, JSON_PRETTY_PRINT)
            );
        }
        
        $this->command->info("Completed processing {$users->count()} users. Success: {$successCount}, Failed: {$failCount}");
    }
}
