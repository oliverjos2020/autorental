<div>
    <div>
        <aside class="kenburns-section" id="kenburnsSliderContainer" data-overlay-dark="5">
            <div class="kenburns-inner h-100">
                <div class="v-middle caption">
                    <div class="container">
                        <div class="row h-100">
                            <div class="col-lg-6 col-md-5 mb-30 mt-30">
                                <div class="v-middle2 caption">
                                    <h6>* Premium</h6>
                                    <h1>Rental Car</h1>
                                    <h5>You can rent any of our luxurious cars.</h5>
                                    <a href="#0" class="button-1 mt-15 mb-15">View Details <span
                                            class="ti-arrow-top-right"></span></a>
                                </div>
                            </div>
                            <!-- Rent Now -->
                            <div class="col-lg-6 col-md-7">
                                <div class="booking-box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select wire:model="category" class="form-control">
                                                <option value="">Choose Car Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->item}}</option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                            The category field is required.
                                        </div>
                                        <div class="col-md-6">
                                            <label>Pick Up Location</label>
                                            <select wire:model="pickupLocation" class="form-control">
                                                <option value="">Pick Up Location</option>
                                                @foreach($locations as $location)
                                                <option value="{{$location->slug}}">{{$location->location}}</option>
                                                @endforeach
                                            </select>
                                            @error('pickupLocation')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                            The category field is required.
                                        </div>
                                        <div class="col-md-6">
                                            <label>Pick Up Date</label>
                                            <input wire:model="pickupDate" type="date" class="form-control"
                                                placeholder="Pick Up Date" required>
                                            @error('pickupDate')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label>Drop Off Location</label>
                                            <select wire:model="dropoffLocation" class="form-control">
                                                <option value="">Drop Off Location</option>
                                                @foreach($locations as $location)
                                                <option value="{{$location->location}}">{{$location->location}}</option>
                                                @endforeach
                                            </select>
                                            @error('dropoffLocation')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                            <span class="text-danger">The category field is required.</span>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Return Date</label>
                                            <input type="date" wire:model="returnDate" class="form-control"
                                                placeholder="Return Date">
                                            @error('returnDate')
                                            <span class="text-danger"> {{ $message }} </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            @if(auth()->check())
                                            <button class="btn btn-primary" wire:click.prevent="submitRequest">Submit</button>
                                            @else
                                                <a class="btn btn-primary" href="/login">Submit</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
    <div class="row">
        <div class="booking-box">
            <div class="row">
                <div class="col-md-12">
                    <select wire:model="category" class="form-control">
                        <option value="">Choose Car Category</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->item}}</option>
                        @endforeach
                    </select>
                    @error('category')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Pick Up Location</label>
                    <select wire:model="pickupLocation" class="form-control">
                        <option value="">Pick Up Location</option>
                        @foreach($locations as $location)
                        <option value="{{$location->slug}}">{{$location->location}}</option>
                        @endforeach
                    </select>
                    @error('pickupLocation')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Pick Up Date</label>
                    <input wire:model="pickupDate" type="date" class="form-control" placeholder="Pick Up Date" required>
                    @error('pickupDate')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Drop Off Location</label>
                    <select wire:model="dropoffLocation" class="form-control">
                        <option value="">Drop Off Location</option>
                        @foreach($locations as $location)
                        <option value="{{$location->slug}}">{{$location->location}}</option>
                        @endforeach
                    </select>
                    @error('dropoffLocation')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label>Return Date</label>
                    <input type="date" wire:model="returnDate" class="form-control" placeholder="Return Date">
                    @error('returnDate')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                {{-- <div class="col-md-12">
                    <button wire:click.prevent="submit" class="booking-button mt-15">Rent Now</button>
                </div> --}}
                <button class="btn btn-primary" wire:click.prevent="submitRequest">Submit</button>
            </div>
        </div>
    </div>
</div>