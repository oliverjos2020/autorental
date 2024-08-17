<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Todo;

use Livewire\WithPagination;

use Exception;

class TodoList extends Component
{

     

    public $name;
    public $search;

    public $editingtodoID;
    public $editingnewName;

    use WithPagination;

    public function create()
    {
       
        //validate
        //create todo
        //clear input
        //send flash
        $validated = $this->validate([
            'name' => ['required', 'min:2', 'max:50']
        ]);

        Todo::create($validated);

        $this->reset('name');
        session()->flash('message', 'created');
    }

    public function delete($id)
    {
        try{
            Todo::findOrfail($id)->delete();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to delete');
            return;
        }

    }

    public function edit($id)
    {
        $this->editingtodoID = $id;
        $this->editingnewName = Todo::find($id)->name;
    }

    public function toggle($id)
    {
        $todo = Todo::find($id);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function cancelEdit()
    {
        $this->reset('editingtodoID', 'editingnewName');
    }

    public function update()
    {
        // $this->validateOnly('editingnewName', 'required');
         $this->validateOnly('editingnewName', ['editingnewName' => 'required']);
        Todo::find($this->editingtodoID)->update([
            'name' => $this->editingnewName,
        ]);
        $this->cancelEdit();
    }
    public function render()
    {
        // return view('livewire.todo-list');
         return view('livewire.todo-list', ['todos' => Todo::latest()->where('name', 'like', "%{$this->search}%")->paginate(3)])->layout('layouts.guest');
    }
}
