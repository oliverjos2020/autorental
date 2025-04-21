<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Station;
use Livewire\WithPagination;
use Exception;
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
    public $editingPercentageCharge;
    public $limit = '10';
    public $business_name;
    public $bank_code;
    public $account_number;
    public $percentage_charge;


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


    public function createUser()
    {
        $validateData = $this->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'role' => ['required'],
            'station' => ['required'],
            'password' => ['required'],
            'business_name' => $this->role == 6 ? ['required'] : ['nullable'],
            'bank_code' => $this->role == 6 ? ['required'] : ['nullable'],
            'account_number' => $this->role == 6 ? ['required'] : ['nullable'],
            'percentage_charge' => $this->role == 6 ? ['required'] : ['nullable']
        ]);
        // dd($validateData['name']);
        try{
            if($this->role == 6)
            {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.env('PAYSTACK_LIVE_KEY'), // Replace with your actual Paystack API key
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])->post('https://api.paystack.co/subaccount', [
                    'business_name' => $this->business_name,
                    'settlement_bank' => $this->bank_code,
                    'account_number' => $this->account_number,
                    'percentage_charge' => $this->percentage_charge
                ]);
                // Log::info("Received API Response: " . json_encode($response->json(), JSON_PRETTY_PRINT));
                if($response['status'] == true)
                {
                    User::create([
                        'name' => $validateData['name'],
                        'email' => $validateData['email'],
                        'role_id' => $validateData['role'],
                        'station_id' => $validateData['station'],
                        'password' => Hash::make($validateData['password']),
                        'business_name' => $this->business_name,
                        'bank_code' => $this->bank_code,
                        'account_number' => $this->account_number,
                        'percentage_charge' => $this->percentage_charge,
                        'account_code' => $response['data']['subaccount_code']
                    ]);
                    $this->dispatchBrowserEvent('notify', [
                        'type' =>'success',
                        'message' => 'User Created Successfully',
                    ]);
                    // return;
                }elseif($response['status'] == false){
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'error',
                        'message' => $response['message']
                    ]);
                    return;
                }


            }else{
                User::create([
                    'name' => $validateData['name'],
                    'email' => $validateData['email'],
                    'role_id' => $validateData['role'],
                    'station_id' => $validateData['station'],
                    'password' => Hash::make($validateData['password']),
                    'business_name' => $this->business_name,
                    'bank_code' => $this->bank_code,
                    'account_number' => $this->account_number,
                    'percentage_charge' => $this->percentage_charge
                ]);
            }


        // User::create($validateData);
        $this->reset(['name', 'email', 'role', 'station', 'password', 'business_name', 'bank_code', 'account_number', 'percentage_charge']);
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
        $this->editingPercentageCharge = User::find($id)->percentage_charge ?? null;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingName', 'editingEmail', 'editingRole', 'editingStation', 'editingBusinessName', 'editingBankCode', 'editingAccountNumber', 'editingPercentageCharge');
    }

    public function update()
    {
        // try {

        // dd($this->editingStation);
            $this->validate([
                'editingName' => ['required'],
                'editingEmail' => ['required'],
                'editingRole' => ['required'],
                // 'editingStation' => ['required']
            ]);

        $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.env('PAYSTACK_LIVE_KEY'), // Replace with your actual Paystack API key
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                ])->post('https://api.paystack.co/subaccount', [
                    'business_name' => $this->editingBusinessName,
                    'settlement_bank' => $this->editingBankCode,
                    'account_number' => (string)$this->editingAccountNumber,
                    'percentage_charge' => $this->editingPercentageCharge
                ]);
                // Log::info("Received API Response: " . json_encode($response->json(), JSON_PRETTY_PRINT));
                if($response['status'] == true)
                {
                    User::find($this->editingID)->update([
                        'name' => $this->editingName,
                        'email' => $this->editingEmail,
                        'role_id' => $this->editingRole,
                        'station_id' => $this->editingStation,
                        'business_name' => $this->editingBusinessName,
                        'bank_code' => $this->editingBankCode,
                        'account_number' => $this->editingAccountNumber,
                        'percentage_charge' => $this->editingPercentageCharge,
                    ]);
                    $this->cancelEdit();
                }elseif($response['status'] == false){
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'error',
                        'message' => $response['message']
                    ]);
                    return;
                }
    }

    public function delete($id)
    {
        try{
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
        if(auth()->user()->role_id == 1):
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
