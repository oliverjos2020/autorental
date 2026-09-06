<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\Duration;

class DurationManagement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $item;
    public $duration;
    public $editingID;
    public $editingDuration;
    public $editingItem;
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


    public function createCategory()
    {
        $this->validate([
            'item' => ['required'],
            'duration' => ['required', 'unique:durations,duration', 'min:1', 'max:50']
        ]);
        try{
        Duration::create([
            'item' => $this->item,
            'duration' => $this->duration,
            'slug'=>Str::of(Str::lower($this->item))->slug('-')
        ]);
        $this->reset(['item','duration']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Duration Created Successfully',
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
        $data = Duration::find($id);
        $this->editingID = $id;
        $this->editingItem = $data->item;
        $this->editingDuration = $data->duration;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingDuration', 'editingItem');
    }

    public function update()
    {
        try {
            $this->validate(['editingDuration' => 'required', 'editingItem' => 'required']);
            Duration::find($this->editingID)->update([
                'item' => $this->editingItem,
                'duration' => $this->editingDuration,
                'slug' => Str::slug($this->editingItem)
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
            Duration::findOrfail($id)->delete();
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
        $durations = Duration::query()
        ->where('item', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate($this->limit);
        return view('livewire.duration-management', [
            'durations' => $durations
        ])->layout('components.dashboard.dashboard-master');
    }
}