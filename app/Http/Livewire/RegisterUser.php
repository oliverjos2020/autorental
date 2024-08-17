<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class RegisterUser extends Component
{

    use WithFileUploads;
    public $name;
    public $email;
    public $password;
    public $image;
    public function createUser()
    {
        $validated = $this->validate([
            'name' => ['required', 'min:2', 'max:50'],
            'email' => ['required', 'email', 'unique:users', 'min:2'],
            'password' => ['required', 'min:6'],
            'image' => ['nullable', 'sometimes', 'image', 'max:1024']
        ]);

        if($this->image){
            $imageFile = $this->image->store('uploads', 'public');
        }
        
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($validated['password']),
            'image' => $imageFile
        ]);
        $this->reset(['name', 'email', 'password', 'image']);
        request()->session()->flash('message','Created Successfully');
    }
    public function render()
    {
        return view('livewire.register-user')->layout('layouts.guest');
    }
}
