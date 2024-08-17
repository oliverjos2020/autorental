<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Exception;

class CategoryManagement extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $category;
    public $editingID;
    public $editingcategory;
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
            'category' => ['required', 'unique:categories,category', 'min:2', 'max:50']
        ]);
        try{
        Category::create([
            'category' => $this->category,
            'slug'=>Str::of(Str::lower($this->category))->slug('-')
        ]);
        $this->reset(['category']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Category Created Successfully',
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
        $this->editingcategory = Category::find($id)->category;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingcategory');
    }

    public function update()
    {
        try {
            $this->validateOnly('editingcategory', ['editingcategory' => 'required']);
            Category::find($this->editingID)->update([
                'category' => $this->editingcategory,
                'slug' => Str::slug($this->editingcategory)
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
            Category::findOrfail($id)->delete();
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
        $categories = Category::query()
        ->where('category', 'like', '%' . $this->search . '%')
        ->latest()
        ->paginate($this->limit);
        return view('livewire.category-management', [
            'categories' => $categories,
        ])->layout('components.dashboard.dashboard-master');
    }
}
