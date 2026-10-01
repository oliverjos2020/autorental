<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Station;
use Livewire\WithPagination;
use Exception;
use App\Models\SystemParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class UserManagement extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $name;
    public $role;
    public $owner = false;
    public $station;
    public $email;
    public $password;
    public $editingID;
    public $editingName;
    public $editingEmail;
    public $editingPassword;
    public $editingRole;
    public $editingStation;
    public $editingBusinessName;
    public $editingBankCode;
    public $editingAccountNumber;
    public $limit = '10';
    public $business_name;
    public $bank_code;
    public $account_number;
    public bool $showCreateForm = false;

    public function toggleCreateForm(): void
    {

        $this->showCreateForm = !$this->showCreateForm;
        $this->editingID = null;
        // dd($this->showCreateForm);
        // $this->reset(['param_key','param_value','param_group','description','is_secret','is_active']);
        // $this->param_group = 'General';
        // $this->is_active   = true;
    }


    protected $queryString = ['limit', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }
    public function updatedRole()
    {
        $this->owner = $this->role;
    }


    public function createUser1()
    {
        $validateData = $this->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'role' => ['required'],
            'station' => ['required'],
            'password' => ['required'],
            'business_name' => $this->role == 6 ? ['required'] : ['nullable'],
            'bank_code' => $this->role == 6 ? ['required'] : ['nullable'],
            'account_number' => $this->role == 6 ? ['required'] : ['nullable']
        ]);
        // dd($validateData['name']);
        try {
            if (in_array($this->role, [6, 7, 8])) {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('PAYSTACK_TEST_KEY'), // Replace with your actual Paystack API key
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])->post('https://api.paystack.co/subaccount', [
                            'business_name' => $this->business_name,
                            'settlement_bank' => $this->bank_code,
                            'account_number' => $this->account_number,
                            'percentage_charge' => 0
                        ]);
                // Log::info("Received API Response: " . json_encode($response->json(), JSON_PRETTY_PRINT));
                if ($response['status'] == true) {
                    User::create([
                        'name' => $validateData['name'],
                        'email' => $validateData['email'],
                        'role_id' => $validateData['role'],
                        'station_id' => $validateData['station'],
                        'password' => Hash::make($validateData['password']),
                        'business_name' => $this->business_name,
                        'bank_code' => $this->bank_code,
                        'account_number' => $this->account_number,
                        'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE'),
                        'account_code' => $response['data']['subaccount_code']
                    ]);
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'success',
                        'message' => 'User Created Successfully',
                    ]);
                    // return;
                } elseif ($response['status'] == false) {
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'error',
                        'message' => $response['message']
                    ]);
                    return;
                }


            } else {
                User::create([
                    'name' => $validateData['name'],
                    'email' => $validateData['email'],
                    'role_id' => $validateData['role'],
                    'station_id' => $validateData['station'],
                    'password' => Hash::make($validateData['password']),
                    'business_name' => $this->business_name,
                    'bank_code' => $this->bank_code,
                    'account_number' => $this->account_number,
                    'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE')
                ]);
            }


            // User::create($validateData);
            $this->reset(['name', 'email', 'role', 'station', 'password', 'business_name', 'bank_code', 'account_number']);
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'User Created Successfully',
            ]);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }
    }

    public function createUser()
    {
        $paystackRoles = [6, 7, 8];

        $requiresPaystack = in_array((int) $this->role, $paystackRoles, true);

        $validateData = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'], 
            'role' => ['required'],
            'station' => ['required'],
            'password' => ['required', 'string', 'min:8'],

            'business_name' => $requiresPaystack
                ? ['required', 'string', 'max:255']
                : ['nullable', 'string', 'max:255'],

            'bank_code' => $requiresPaystack
                ? ['required', 'string']
                : ['nullable', 'string'],

            'account_number' => $requiresPaystack
                ? ['required', 'digits:10']
                : ['nullable', 'string'],
        ]);

        $subaccountCode = null;

        try {

            /*
             * Get the default percentage once.
             *
             * IMPORTANT:
             * This is the default percentage_charge for the Paystack
             * subaccount. It is NOT the percentage we use for each trip.
             *
             * The actual trip percentage will be supplied later during
             * transaction initialization using the dynamic split.
             */
            $percentageCharge = (float) SystemParameter::getValue(
                'CAR_OWNER_PERCENTAGE'
            );

            /*
             * Prepare local user data
             */
            $userData = [
                'name' => $validateData['name'],
                'email' => $validateData['email'],
                'role_id' => $validateData['role'],
                'station_id' => $validateData['station'],
                'password' => Hash::make($validateData['password']),

                'business_name' => $validateData['business_name'] ?? null,
                'bank_code' => $validateData['bank_code'] ?? null,
                'account_number' => $validateData['account_number'] ?? null,

                'percentage_charge' => $percentageCharge,
            ];

            /*
             * Create Paystack subaccount for applicable roles
             */
            if ($requiresPaystack) {

                $response = Http::withToken(
                    config('services.paystack.secret_key')
                )
                    ->acceptJson()
                    ->post('https://api.paystack.co/subaccount', [
                        'business_name' => $validateData['business_name'],
                        'settlement_bank' => $validateData['bank_code'],
                        'account_number' => $validateData['account_number'],

                        /*
                         * This is the default Paystack subaccount setting.
                         * It does NOT determine the split percentage for each
                         * individual booking.
                         */
                        'percentage_charge' => $percentageCharge,
                    ]);

                $responseData = $response->json();

                /*
                 * Paystack request failed
                 */
                if (!$response->successful() || !($responseData['status'] ?? false)) {

                    $message = $responseData['message']
                        ?? 'Unable to create Paystack subaccount.';

                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'error',
                        'message' => $message,
                    ]);

                    return;
                }

                /*
                 * Get Paystack subaccount code
                 */
                $subaccountCode = $responseData['data']['subaccount_code'] ?? null;

                if (!$subaccountCode) {
                    throw new Exception(
                        'Paystack created the subaccount but no subaccount code was returned.'
                    );
                }

                /*
                 * Save Paystack account code against the user
                 */
                $userData['account_code'] = $subaccountCode;
            }

            /*
             * Create the local user
             */
            User::create($userData);

            /*
             * Reset form
             */
            $this->reset([
                'name',
                'email',
                'role',
                'station',
                'password',
                'business_name',
                'bank_code',
                'account_number',
            ]);

            /*
             * Show success message
             */
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'User Created Successfully',
            ]);

        } catch (Exception $e) {

            /*
             * If Paystack created the subaccount but the local user creation
             * failed, deactivate the subaccount so you don't leave an
             * unwanted active Paystack account.
             */
            if ($subaccountCode) {

                try {

                    Http::withToken(
                        config('services.paystack.secret_key')
                    )
                        ->acceptJson()
                        ->put(
                            "https://api.paystack.co/subaccount/{$subaccountCode}",
                            [
                                'active' => false,
                            ]
                        );

                } catch (Exception $rollbackException) {

                    Log::error('Unable to deactivate Paystack subaccount', [
                        'subaccount_code' => $subaccountCode,
                        'error' => $rollbackException->getMessage(),
                    ]);
                }
            }

            /*
             * Log the actual exception
             */
            Log::error('User creation failed', [
                'email' => $this->email,
                'role' => $this->role,
                'error' => $e->getMessage(),
            ]);

            /*
             * Show a clean error to the user
             */
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Unable to create user. Please try again.',
            ]);
        }
    }


    public function edit($id)
    {
        $this->editingID = $id;
        $this->editingName = User::find($id)->name;
        $this->editingEmail = User::find($id)->email;
        $this->editingRole = User::find($id)->role->id;
        $this->editingStation = User::find($id)->station->id ?? null;
        $this->editingBusinessName = User::find($id)->business_name ?? null;
        $this->editingBankCode = User::find($id)->bank_code ?? null;
        $this->editingAccountNumber = User::find($id)->account_number ?? null;
        $this->showCreateForm = true;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingName', 'editingEmail', 'editingRole', 'editingStation', 'editingBusinessName', 'editingBankCode', 'editingAccountNumber');
    }

    public function update()
    {
        // try {

        $this->validate([
            'editingName' => ['required'],
            'editingEmail' => ['required'],
            'editingRole' => ['required'],
        ]);
        $getUser = User::find($this->editingID);
        if (empty($getUser->account_code)) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYSTACK_TEST_KEY'), // Replace with your actual Paystack API key
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
            ])->post('https://api.paystack.co/subaccount', [
                        'business_name' => $this->editingBusinessName,
                        'settlement_bank' => $this->editingBankCode,
                        'account_number' => (string) $this->editingAccountNumber,
                        'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE')
                    ]);
            if ($response['status'] == true) {
                User::find($this->editingID)->update([
                    'name' => $this->editingName,
                    'email' => $this->editingEmail,
                    'role_id' => $this->editingRole,
                    'station_id' => $this->editingStation,
                    'business_name' => $this->editingBusinessName,
                    'bank_code' => $this->editingBankCode,
                    'account_number' => $this->editingAccountNumber,
                    'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE')
                ]);
            } elseif ($response['status'] == false) {
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'error',
                    'message' => $response['message']
                ]);
                return;
            }
        } else {
            User::find($this->editingID)->update([
                'name' => $this->editingName,
                'email' => $this->editingEmail,
                'role_id' => $this->editingRole,
                'station_id' => $this->editingStation,
                'business_name' => $this->editingBusinessName,
                'bank_code' => $this->editingBankCode,
                'account_number' => $this->editingAccountNumber,
                'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE')
            ]);
        }
        $this->cancelEdit();
    }

    public function delete($id)
    {
        try {
            User::findOrfail($id)->delete();
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Deleted Successfully',
            ]);

        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }

    }
    public function render()
    {
        if (auth()->user()->role_id == 1):
            $userManagement = User::where('name', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        else:
            $station = auth()->user()->station_id;
            $userManagement = User::where('name', 'like', '%' . $this->search . '%')->where('station_id', $station)->latest()->paginate($this->limit);
        endif;
        $roles = Role::all();
        $stations = Station::all();
        return view('livewire.user-management', [
            'users' => $userManagement,
            'roles' => $roles,
            'stations' => $stations
        ])->layout('components.dashboard.dashboard-master');
    }
}
