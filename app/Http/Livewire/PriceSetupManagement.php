<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PriceSetup;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Duration;

use Exception;

class PriceSetupManagement extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $category_id;
    public $duration;
    public $amount;
    public $editingID;
    public $editingitem;
    public $editingduration;
    public $editingamount;
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


    public function createPriceSetup()
    {
        $validateData = $this->validate([
            // 'brand' => ['required', 'unique:car_brands,brand', 'min:2', 'max:50']
            'category_id' => ['required'],
            'duration' => ['required'],
            'amount' => ['required']
        ]);
        try{
        // PriceSetup::create($validateData);
        PriceSetup::create([
            'category_id' => $this->category_id,
            // 'slug' => Str::of(Str::lower($this->duration))->slug('-'),
            'duration' => $this->duration,
            'amount' => $this->amount
        ]);
        $this->reset(['category_id', 'duration', 'amount']);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Item Setup Successfully',
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
        $this->editingitem = PriceSetup::find($id)->item;
        $this->editingduration = PriceSetup::find($id)->duration;
        $this->editingamount = PriceSetup::find($id)->amount;
    }

    public function cancelEdit()
    {
        $this->reset('editingID', 'editingitem', 'editingduration', 'editingamount');
    }

    public function update()
    {
        // try {
            // $this->validateOnly('editingitem', ['editingitem' => 'required', 'editingduration' => 'required', 'editingamount' => 'required']);
            $this->validate([
                // 'editingitem' => ['required'],
                'editingduration' => ['required'],
                'editingamount' => ['required',],
            ]);

            PriceSetup::find($this->editingID)->update([
                // 'item' => $this->editingitem,
                'slug' => Str::of(Str::lower($this->editingduration))->slug('-'),
                'duration' => $this->editingduration,
                'amount' => $this->editingamount
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
            PriceSetup::findOrfail($id)->delete();
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
        $priceSetups = PriceSetup::query()->where('duration', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        $category = Category::all();
        $duration = Duration::all();
        return view('livewire.price-setup-management', [
            'priceSetups' => $priceSetups, 'categories' => $category, 'durations' => $duration
        ])->layout('components.dashboard.dashboard-master');

        // return view('livewire.price-setup-management');
    }
}
