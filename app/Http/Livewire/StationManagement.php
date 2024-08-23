<?php

namespace App\Http\Livewire;
use App\Models\Station;
use App\Models\Location;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Exception;

class StationManagement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $station;
    public $location;
    public $editingID;
    public $editingStation;
    public $editingLocation;
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
    

    public function create()
    {
        $this->validate([
            'station' => ['required', 'unique:stations,stationName', 'min:2', 'max:50']
        ]);
        try{
            Station::create([
                'stationName' => $this->station,
                'slug'=>Str::of(Str::lower($this->station))->slug('-'),
                'location_id' => $this->location
            ]);

            $this->reset(['station','location']);
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Station Created Successfully',
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
        $this->editingStation = Station::find($id)->stationName;
        $this->editingLocation = Station::find($id)->location_id;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingLocation', 'editingStation');
    }

    public function update()
    {
        try {
            $this->validate([
                'editingStation' => ['required'],
                'editingLocation' => ['required']
            ]);

            Station::find($this->editingID)->update([
                'stationName' => $this->editingStation,
                'slug' => Str::slug($this->editingStation),
                'location_id' => $this->editingLocation
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
            Station::findOrfail($id)->delete();
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
        $stations = Station::query()->where('stationName', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        $location = Location::all();
        return view('livewire.station-management', [
            'stations' => $stations, 'locations' => $location
        ])->layout('components.dashboard.dashboard-master');
    }
}
