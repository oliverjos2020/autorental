<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Location;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Exception;

class LocationManagement extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $location;
    public $editingID;
    public $editinglocation;
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
    

    public function createLocation()
    {
        $this->validate([
            'location' => ['required', 'unique:locations,location', 'min:2', 'max:50']
        ]);
        try{
        Location::create([
            'location' => $this->location,
            'slug'=>Str::of(Str::lower($this->location))->slug('-')
        ]);
        $this->reset(['location']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Location Created Successfully',
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
        $this->editinglocation = Location::find($id)->location;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editinglocation');
    }

    public function update()
    {
        try {
            $this->validateOnly('editinglocation', ['editinglocation' => 'required']);
            Location::find($this->editingID)->update([
                'location' => $this->editinglocation,
                'slug' => Str::slug($this->editinglocation)
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
            Location::findOrfail($id)->delete();
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
        $locations = Location::query()->where('location', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        return view('livewire.location-management', [
            'locations' => $locations,
        ])->layout('components.dashboard.dashboard-master');

    }
}
