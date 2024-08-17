<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Role;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Exception;



class RoleManagement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $role;
    public $editingID;
    public $editingrole;
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
    

    public function createRole()
    {
        $validated = $this->validate([
            'role' => ['required', 'unique:roles,role', 'min:2', 'max:50']
        ]);
        try{
        Role::create([
            'role' => $this->role,
            'slug'=>Str::of(Str::lower($this->role))->slug('-')
        ]);
        $this->reset(['role']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Role Created Successfully',
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
        $this->editingrole = Role::find($id)->role;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingrole');
    }

    public function update()
    {
        try {
            $this->validateOnly('editingrole', ['editingrole' => 'required']);
            Role::find($this->editingID)->update([
                'role' => $this->editingrole,
                'slug' => Str::slug($this->editingrole)
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
            Role::findOrfail($id)->delete();
            session()->flash('delete', 'Deleted Successfully');
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
        // return view('livewire.role', ['roles' => Role::latest()->where('role', 'like', "%{$this->search}%")->paginate($this->limit)])->layout('components.dashboard.dashboard-master');
        $roles = Role::query()
        ->where('role', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate($this->limit);

    return view('livewire.role', [
        'roles' => $roles,
    ])->layout('components.dashboard.dashboard-master');

    }
}
