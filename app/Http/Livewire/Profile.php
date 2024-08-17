<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Vehicle;
use Livewire\WithPagination;
use Illuminate\Support\Str;
class Profile extends Component
{
    use WithPagination;
    public $userID;
    public $limit = '10';
    public $search;

    protected $queryString = ['limit', 'search'];
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }

    public function mount($userID)
    {
        $this->userID = $userID;
    }
    public function render()
    {
        $user = User::where('id', $this->userID)->first();
        $vehicles = Vehicle::query()->where('vehicleMake', 'like', '%' . $this->search . '%')->where('user_id', $this->userID)->latest()->paginate($this->limit);
        return view('livewire.profile', ['user' => $user, 'vehicles' => $vehicles])->layout('components.dashboard.dashboard-master');
    }
}
