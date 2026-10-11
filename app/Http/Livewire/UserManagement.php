<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Station;
use Livewire\WithPagination;
use Exception;
use App\Models\SystemParameter;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public $editingHasSubaccount = false;
    public $limit = '10';
    public $business_name;
    public $bank_code;
    public $account_number;
    public bool $showCreateForm = false;

    // Report filters
    public $roleFilter = '';
    public $stationFilter = '';
    public $payoutFilter = '';
    public $verifiedFilter = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    private const PAYOUT_ROLES = [6, 7, 8];
    private const SORTABLE = ['name', 'email', 'role_id', 'created_at'];
    private const FILTERS = ['search', 'roleFilter', 'stationFilter', 'payoutFilter', 'verifiedFilter', 'dateFrom', 'dateTo', 'limit'];

    public function toggleCreateForm(): void
    {

        $this->showCreateForm = !$this->showCreateForm;
        $this->editingID = null;
        $this->resetValidation();
    }


    protected $queryString = [
        'limit',
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'stationFilter' => ['except' => ''],
        'payoutFilter' => ['except' => ''],
        'verifiedFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updated($name)
    {
        if (in_array($name, self::FILTERS, true)) {
            $this->resetPage();
        }
    }

    public function sortBy($field)
    {
        if (!in_array($field, self::SORTABLE, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'roleFilter', 'stationFilter', 'payoutFilter', 'verifiedFilter', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    /**
     * Users this admin is allowed to see (admins see everyone, others only their station).
     */
    private function scopedQuery()
    {
        $query = User::query();

        if (auth()->user()->role_id != 1) {
            $query->where('station_id', auth()->user()->station_id);
        }

        return $query;
    }

    private function filteredQuery()
    {
        $query = $this->scopedQuery();

        if (trim((string) $this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone_no', 'like', $term)
                    ->orWhere('business_name', 'like', $term);
            });
        }

        if ($this->roleFilter !== '') {
            $query->where('role_id', $this->roleFilter);
        }

        if ($this->stationFilter !== '') {
            $this->stationFilter === 'none'
                ? $query->whereNull('station_id')
                : $query->where('station_id', $this->stationFilter);
        }

        if ($this->payoutFilter === 'ready') {
            $query->whereNotNull('account_code')->where('account_code', '!=', '');
        } elseif ($this->payoutFilter === 'missing') {
            $query->whereIn('role_id', self::PAYOUT_ROLES)
                ->where(fn ($q) => $q->whereNull('account_code')->orWhere('account_code', ''));
        }

        if ($this->verifiedFilter === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($this->verifiedFilter === 'no') {
            $query->whereNull('email_verified_at');
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $field = in_array($this->sortField, self::SORTABLE, true) ? $this->sortField : 'created_at';

        return $query->orderBy($field, $this->sortDirection === 'asc' ? 'asc' : 'desc');
    }

    public function exportCsv()
    {
        $query = $this->filteredQuery()->with(['role', 'station']);

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Role', 'Station', 'Business Name', 'Bank Code', 'Account Number', 'Payout Account', 'Registered']);
            foreach ($query->cursor() as $u) {
                fputcsv($out, [
                    $u->id, $u->name, $u->email, $u->phone_no,
                    $u->role->role ?? '', $u->station->stationName ?? '',
                    $u->business_name, $u->bank_code, $u->account_number,
                    $u->account_code ? 'Yes' : 'No',
                    optional($u->created_at)->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, 'users-' . now()->format('Ymd-His') . '.csv');
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
             * Get the car owner percentage once.
             *
             * Stored on the user for reference only. The real split is
             * computed per booking in PaystackService::buildSplit().
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

                try {
                    $subaccountCode = (new PaystackService())->createSubaccount(
                        $validateData['business_name'],
                        $validateData['bank_code'],
                        (string) $validateData['account_number']
                    );
                } catch (Exception $e) {
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'error',
                        'message' => $e->getMessage(),
                    ]);

                    return;
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

                (new PaystackService())->deactivateSubaccount($subaccountCode);
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
        $this->showCreateForm = false;
    }


    public function edit($id)
    {
        $user = $this->scopedQuery()->findOrFail($id);

        $this->editingID = $user->id;
        $this->editingName = $user->name;
        $this->editingEmail = $user->email;
        $this->editingRole = $user->role_id;
        $this->editingStation = $user->station_id;
        $this->editingBusinessName = $user->business_name;
        $this->editingBankCode = $user->bank_code;
        $this->editingAccountNumber = $user->account_number;
        $this->editingHasSubaccount = !empty($user->account_code);
        $this->showCreateForm = true;
        $this->resetValidation();
    }

    public function cancelEdit()
    {
        $this->resetValidation();
        $this->showCreateForm = false;
        $this->reset('editingHasSubaccount', 'editingID', 'editingName', 'editingEmail', 'editingRole', 'editingStation', 'editingBusinessName', 'editingBankCode', 'editingAccountNumber');
    }

    public function update()
    {
        // try {

        $needsPayout = in_array((int) $this->editingRole, self::PAYOUT_ROLES, true);

        $this->validate([
            'editingName' => ['required', 'string', 'max:255'],
            'editingEmail' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingID)],
            'editingRole' => ['required'],
            'editingBusinessName' => $needsPayout ? ['required', 'string', 'max:255'] : ['nullable'],
            'editingBankCode' => $needsPayout ? ['required'] : ['nullable'],
            'editingAccountNumber' => $needsPayout ? ['required', 'digits:10'] : ['nullable'],
        ]);
        $getUser = $this->scopedQuery()->findOrFail($this->editingID);
        $attributes = [
            'name' => $this->editingName,
            'email' => $this->editingEmail,
            'role_id' => $this->editingRole,
            'station_id' => $this->editingStation,
            'business_name' => $this->editingBusinessName,
            'bank_code' => $this->editingBankCode,
            'account_number' => $this->editingAccountNumber,
            'percentage_charge' => SystemParameter::getValue('CAR_OWNER_PERCENTAGE')
        ];

        // Users in a payout role need a Paystack subaccount; create one if they don't have it yet
        if (in_array((int) $this->editingRole, [6, 7, 8], true) && empty($getUser->account_code)) {
            try {
                $attributes['account_code'] = (new PaystackService())->createSubaccount(
                    (string) $this->editingBusinessName,
                    (string) $this->editingBankCode,
                    (string) $this->editingAccountNumber
                );
            } catch (Exception $e) {
                $this->dispatchBrowserEvent('notify', [
                    'type' => 'error',
                    'message' => $e->getMessage()
                ]);
                return;
            }
        }

        $getUser->update($attributes);
        $this->cancelEdit();
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'User Updated Successfully',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->scopedQuery()->findOrFail($id)->delete();
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
        $scoped = $this->scopedQuery();
        $monthStart = now()->startOfMonth();

        $stats = [
            'total' => (clone $scoped)->count(),
            'new' => (clone $scoped)->where('created_at', '>=', $monthStart)->count(),
            'ready' => (clone $scoped)->whereNotNull('account_code')->where('account_code', '!=', '')->count(),
            'missing' => (clone $scoped)->whereIn('role_id', self::PAYOUT_ROLES)
                ->where(fn ($q) => $q->whereNull('account_code')->orWhere('account_code', ''))->count(),
        ];

        $users = $this->filteredQuery()->with(['role', 'station'])->paginate((int) $this->limit);

        return view('livewire.user-management', [
            'users' => $users,
            'roles' => Role::all(),
            'stations' => Station::all(),
            'stats' => $stats,
            'payoutRoles' => self::PAYOUT_ROLES,
            'filtersActive' => $this->search !== '' || $this->roleFilter !== '' || $this->stationFilter !== ''
                || $this->payoutFilter !== '' || $this->verifiedFilter !== '' || $this->dateFrom || $this->dateTo,
        ])->layout('components.dashboard.dashboard-master');
    }
}
