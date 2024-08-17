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



class UserManagement extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $name;
    public $role;
    public $station;
    public $email;
    public $password;
    public $editingID;
    public $editingName;
    public $editingEmail;
    public $editingPassword;
    public $editingRole;
    public $editingStation;
    public $limit = '10';

    protected $queryString = ['limit', 'search'];

     public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }
    

    public function createUser()
    {
        $validateData = $this->validate([
            
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'role' => ['required'],
            'station' => ['required'],
            'password' => ['required']
        ]);
        // dd($validateData['name']);
        try{
        User::create([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'role_id' => $validateData['role'],
            'station_id' => $validateData['station'],
            'password' => Hash::make($validateData['password'])
        ]);
        // User::create($validateData);
        $this->reset(['name', 'email', 'role', 'station', 'password']);
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
        $this->editingStation = User::find($id)->station->stationName;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingName', 'editingEmail', 'editingRole', 'editingStation');
    }

    public function update()
    {
        try {
            $this->validate([
                'editingName' => ['required'],
                'editingEmail' => ['required'],
                'editingRole' => ['required'],
                'editingStation' => ['required']
            ]);

            User::find($this->editingID)->update([
                'name' => $this->editingName,
                'email' => $this->editingEmail,
                'role_id' => $this->editingRole,
                'station_id' => $this->editingStation
            ]);
            $this->cancelEdit();
        }catch(Exception $e){
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
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
