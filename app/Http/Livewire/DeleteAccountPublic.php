<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeleteAccountPublic extends Component
{
    public $email = '';
    public $challengeCode = '';
    public $challengeInput = '';
    public $showConfirmation = false;
    
    protected $rules = [
        'email' => 'required|email|exists:users,email',
        'challengeInput' => 'required|string',
    ];

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please provide a valid email address.',
        'email.exists' => 'No account found with this email address.',
        'challengeInput.required' => 'Security code is required.',
    ];

    public function mount()
    {
        $this->generateChallengeCode();
    }

    public function generateChallengeCode()
    {
        // Generate a random 6-character alphanumeric code
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Excluding similar looking characters
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }
        $this->challengeCode = $code;
        $this->challengeInput = '';
    }

    public function refreshCode()
    {
        $this->generateChallengeCode();
        $this->dispatchBrowserEvent('code-refreshed', ['message' => 'Security code refreshed']);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function deleteAccount()
    {
        $this->validate();

        // Verify challenge code matches
        if (strtoupper(trim($this->challengeInput)) !== $this->challengeCode) {
            $this->addError('challengeInput', 'The security code you entered does not match. Please try again.');
            $this->generateChallengeCode();
            return;
        }

        // Find and delete the user
        $user = User::where('email', $this->email)->first();

        if ($user) {
            // Store user name for confirmation message
            $userName = $user->name;
            
            // Delete the user
            $user->delete();
 
            // Show success confirmation
            $this->showConfirmation = true;
            $this->dispatchBrowserEvent('account-deleted', [
                'message' => 'Account deleted successfully',
                'userName' => $userName
            ]);

            // Reset form
            $this->reset(['email', 'challengeInput']);
            $this->generateChallengeCode();
        } else {
            $this->addError('email', 'Account could not be found or has already been deleted.');
        }
    }

    public function closeConfirmation()
    {
        $this->showConfirmation = false;
    }

    public function render()
    {
        return view('livewire.delete-account-public')
            ->layout('layouts.guest');
    }
}
