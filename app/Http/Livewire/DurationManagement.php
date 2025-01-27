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
    public $duration;
    public $editingID;
    public $editingDuration;
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
            'duration' => ['required', 'unique:durations,duration', 'min:2', 'max:50']
        ]);
        try{
        Duration::create([
            'duration' => $this->duration,
            'slug'=>Str::of(Str::lower($this->duration))->slug('-')
        ]);
        $this->reset(['duration']);
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
        $this->editingID = $id;
        $this->editingDuration = Duration::find($id)->duration;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingDuration');
    }

    public function update()
    {
        try {
            $this->validateOnly('editingDuration', ['editingDuration' => 'required']);
            Duration::find($this->editingID)->update([
                'duration' => $this->editingDuration,
                'slug' => Str::slug($this->editingDuration)
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
        ->where('duration', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate($this->limit);
        return view('livewire.duration-management', [
            'durations' => $durations
        ])->layout('components.dashboard.dashboard-master');
    }
}
