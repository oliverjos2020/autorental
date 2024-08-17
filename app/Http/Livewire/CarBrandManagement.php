<?php

namespace App\Http\Livewire;
use App\Models\CarBrand;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Exception;

use Livewire\Component;

class CarBrandManagement extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $brand;
    public $editingID;
    public $editingbrand;
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
    

    public function createBrand()
    {
        $this->validate([
            'brand' => ['required', 'unique:car_brands,brand', 'min:2', 'max:50']
        ]);
        try{
        CarBrand::create([
            'brand' => $this->brand,
            'slug'=>Str::of(Str::lower($this->brand))->slug('-')
        ]);
        $this->reset(['brand']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Brand Created Successfully',
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
        $this->editingbrand = CarBrand::find($id)->brand;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingbrand');
    }

    public function update()
    {
        try {
            $this->validateOnly('editingbrand', ['editingbrand' => 'required']);
            CarBrand::find($this->editingID)->update([
                'brand' => $this->editingbrand,
                'slug' => Str::slug($this->editingbrand)
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
            CarBrand::findOrfail($id)->delete();
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
        $brands = CarBrand::query()->where('brand', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        return view('livewire.car-brand-management', [
            'brands' => $brands,
        ])->layout('components.dashboard.dashboard-master');
    }
}
