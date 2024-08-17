<div>
    
    <div class="group-sections">
        <div class="container" style="margin-top:-80px">
            <div class="b-main-filter bg-primary">
                <div class="row align-items-center">
                    <div class="col-lg">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select wire:model="category" class="home-input is-invalid" title="Select Vehicle Type">
                                        <option value="">Choose Car Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->item}}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                    <span class="text-light"> {{ $message }} </span>
                                    @enderror
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="home-input" wire:model="brand" title="Select Brand">
                                        <option value="">Choose Car Brand</option>
                                        @foreach($brands as $brand)
                                        <option value="{{$brand->brand}}">{{$brand->brand}}</option>
                                        @endforeach
                                    </select>
                                    @error('brand')
                                    <span class="text-light"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="home-input" wire:model="transmission">
                                        <option value="">Choose Transmission</option>
                                        <option value="automatic">Automatic</option>
                                        <option value="manual">Manual</option>
                                    </select>
                                    @error('transmission')
                                    <span class="text-light"> {{ $message }} </span>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-lg-auto">
                        @if(auth()->check())
                            <button class="btn btn-secondary btn-lg ml-lg-3" wire:click.prevent="submitRequest"><i class="ic icon-magnifier"></i> search</button>
                        @else
                            <a class="btn btn-secondary btn-lg ml-lg-3" href="/login"><i class="ic icon-magnifier"></i> search</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end .b-main-filter-->

        
    </div>
</div>