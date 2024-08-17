<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PriceSetup;
use Livewire\WithPagination;
use Illuminate\Support\Str;

use Exception;

class PriceSetupManagement extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $item;
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
            'item' => ['required'],
            'duration' => ['required'],
            'amount' => ['required']
        ]);
        try{
        // PriceSetup::create($validateData);
        PriceSetup::create([
            'item' => $this->item,
            'slug' => Str::of(Str::lower($this->item))->slug('-'),
            'duration' => $this->duration,
            'amount' => $this->amount
        ]);
        $this->reset(['item', 'duration', 'amount']);
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
                'editingitem' => ['required',],
                'editingduration' => ['required'],
                'editingamount' => ['required',],
            ]);

            PriceSetup::find($this->editingID)->update([
                'item' => $this->editingitem,
                'slug' => Str::of(Str::lower($this->editingitem))->slug('-'),
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
        $priceSetups = PriceSetup::query()->where('item', 'like', '%' . $this->search . '%')->latest()->paginate($this->limit);
        return view('livewire.price-setup-management', [
            'priceSetups' => $priceSetups,
        ])->layout('components.dashboard.dashboard-master');

        // return view('livewire.price-setup-management');
    }
}
