<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class Clicker extends Component
{
    public $name;
    public $email;
    public $password;

    use WithPagination;


    public function createUser()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'email', 'unique:users', 'min:2'],
            'password' => ['required', 'min:6']
        ]);
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($validated['password'])
        ]);
        $this->reset(['name', 'email', 'password']);
        request()->session()->flash('message','Created Successfully');
    }
    public function render()
    {
        $users = User::paginate(1);
        return view('livewire.clicker', ['users' => $users]);
    }
}
