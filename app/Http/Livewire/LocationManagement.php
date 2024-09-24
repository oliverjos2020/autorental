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
    public $latitude;
    public $longitude;
    public $editingID;
    public $editinglocation;
    public $editingLatitude;
    public $editingLongitude;
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
            'location' => ['required', 'unique:locations,location', 'min:2', 'max:50'],
            'latitude' => ['required'],
            'longitude' => ['required']
        ]);

        try{
        Location::create([
            'location' => $this->location,
            'slug'=> Str::of(Str::lower($this->location))->slug('-'),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ]);
        $this->reset(['location', 'latitude', 'longitude']);
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
        $this->editingLatitude = Location::find($id)->latitude;
        $this->editingLongitude = Location::find($id)->longitude;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editinglocation', 'editingLatitude', 'editingLongitude');
    }

    public function update()
    {
        // try {
            $this->validate(['editinglocation' => ['required'], 'editingLatitude' => ['required'], 'editingLongitude' => ['required']]);
            Location::find($this->editingID)->update([
                'location' => $this->editinglocation,
                'slug' => Str::slug($this->editinglocation),
                'latitude' => $this->editingLatitude,
                'longitude' => $this->editingLongitude
            ]);
            $this->cancelEdit();
        // }catch(Exception $e){
        //     $this->dispatchBrowserEvent('notify', [
        //         'type' => 'error',
        //         'message' => $e->getMessage(),
        //     ]);
        //     return;

        // }
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
