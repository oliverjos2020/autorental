<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Profile Setup</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                    <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                </ol>
            </div>

        </div>
    </div>

    {{-- <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <p>Current registration type: {{ $type }}</p>
                <div class="form-group">
                    <label for="role">Role name</label>
                    <input type="text" wire:model="role" class="form-control" placeholder="Role Name">
                    @error('role')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror

                    @if(session('message'))
                    <div class="bg-success p-2 text-light mx-2 mt-3">{{session('message')}}</div>
                    @endif
                </div>

                <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="createRole">
                    Create Role
                </button>
            </div>
        </div>
    </div> --}}

    <div class="container mt-2">
        <div class="card">
            <div class="card-header">
                <p class="card-title"><strongx>Setting Up Your Profile for Car {{ $type }}</strongx></p>
            </div>
            <div class="card-body">
                {{-- @if ($step == 1) --}}
             
                
                {{-- @elseif ($step == 2) --}}
                <div>
                    <h4><strong>Vehicle Information</strong></h4>
                    
                        @if(!$addtionalMake)
                            
                            <div class="form-group">
                                <label for="vehicleMake">Vehicle Make</label>
                                <select wire:model="vehicleMake" class="form-control">
                                    <option value="">Select Vehicle Make</option>
                                    @foreach($brands as $brand)
                                    <option value="{{$brand->slug}}">{{$brand->brand}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <span class="badge bg-danger">Didn't find your vehicle Make</span> | <a class="text-dark" style="cursor:pointer;" wire:click="addMake">Click
                                Here</a>
                        @else
                            <div class="form-group">
                                <label for="vehicleMake">Put in your vehicle make</label>
                                <input type="text" wire:model="vehicleMake" class="form-control">
                                @error('vehicleMake')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        <div class="form-group mt-2">
                            @php
                                $years = range(2000, date('Y'));
                            @endphp
                            <label for="vehicleYear">Vehicle Year</label>
                            <select wire:model="vehicleYear" class="form-control">
                                <option value="">Select Option</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            @error('vehicleYear') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    
                    {{-- <div class="form-group mt-2">
                        <label for="vehicleMake">Vehicle Make</label>
                        <input type="text" id="vehicleMake" class="form-control" wire:model="vehicleMake" placeholder="e.g Toyota">
                        @error('vehicleMake') <span class="text-danger">{{ $message }}</span> @enderror
                    </div> --}}
    
                    <div class="form-group mt-2">
                        <label for="vehicleModel">Vehicle Model</label>
                        <input type="text" id="vehicleModel" class="form-control" wire:model="vehicleModel" placeholder="e.g Corolla">
                        @error('vehicleModel') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="seats">Seats</label>
                        <select wire:model="seats" class="form-control">
                            <option value="">Select Seat</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                        @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="doors">Doors</label>
                        <select wire:model="doors" class="form-control">
                            <option value="">Select an option</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                        @error('doors') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="transmission">Transmission</label>
                        <select wire:model="transmission" class="form-control">
                            <option value="">Select Transmission</option>
                            <option value="automatic">Automatic</option>
                            <option value="manual">Manual</option>
                        </select>
                        @error('transmission') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="form-group mt-2">
                        <label for="airCondition">Air Condition</label>
                        <select wire:model="airCondition" class="form-control">
                            <option value="">Select Option</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        @error('airCondition') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group mt-2">
                        <label for="moreInfo">More Information</label>
                        <textarea id="elm1" wire:model="moreInfo"></textarea>
                    </div>
                    
                </div>
                {{-- @elseif ($step == 3) --}}
                
                {{-- @elseif ($step == 4) --}}
               
                {{-- @elseif ($step == 5) --}}
                    <div>
                        <div class="form-group row mt-2">
                            <div class="alert alert-info" role="alert">
                                {{-- <h4 class="alert-heading">Well done!</h4> --}}
                                <p>To ensure your car listing stands out, please upload clear images of your car from the following angles:</p>
                                <hr>
                                <ul>
                                    <li>Front View: Capture the full front of your car.</li>
                                    <li>Back View: Capture the entire back of your car.</li>
                                    <li>Interior View: Show the interior, highlighting key features.</li>
                                    <li>All Sides: Provide images of both sides of your car to give a complete view.</li>
                                </ul>
                                {{-- <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p> --}}
                            </div>
                            <label>Images of vehicle <span class="text-danger">*</span></label>
                            <div class="col-xxl-12">
                                <input type="file" class="form-control {{$errors->has('vehImage') ? 'is-invalid' : ''}}" wire:model="vehImage" accept="image/jpg, image/jpeg, image/png" multiple>
                                @if ($errors->has('vehImage.*'))
                                @foreach ($errors->get('vehImage.*') as $error)
                                <span class="text-danger">{{ $error[0] }}</span><br>
                                @endforeach
                                @endif
                                <div class="row mt-3 mb-3">
                                {{-- @if ($vehImage)
                                    @foreach ($vehImage as $image)
                                        @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                            <div class="col-md-3">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid mb-2" style="max-width: 100%">
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif ($existingvehImage)
                                    @foreach ($existingvehImage as $existingImage)
                                        <div class="col-md-3">
                                            <img src="{{ $existingImage->image_path }}" class="img-fluid mb-2" style="max-width: 100%">
                                        </div>
                                    @endforeach
                                @endif --}}
                                @if ($vehImage)
                                    @foreach ($vehImage as $image)
                                        @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                            <div class="col-md-3">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid mb-2" style="max-width: 100%">
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif ($existingvehImage)
                                    @foreach ($existingvehImage as $existingImage)
                                        <div class="col-md-3">
                                            <img src="{{ $existingImage->image_path }}" class="img-fluid mb-2" style="max-width: 100%">
                                        </div>
                                    @endforeach
                                @endif
                                </div>
                                <br>
                                <span class="text-warning">Allowed extensions: *jpg, jpeg, png</span>
                                <div wire:loading wire:target="vehImage">
                                    Uploading...
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- @endif --}}
    
                <div class="mt-4">
                    @if ($step > 1)
                    <button type="button" class="btn btn-secondary" wire:click="previousStep" style="margin-right:15px;">Previous</button>
                    @endif
    
                    @if ($step < 5) <button type="button" class="btn btn-primary btn-block" wire:click="nextStep">Next</button>
                        @else
                        <button type="button" class="btn btn-success" wire:click="submit">Submit</button>
                        @endif

                </div>
                <div class="row">
                    <div class="col-7">
                
                    </div>
                    <div class="col-5">
                        <div class="mt-4">
                            <img style="margin-top:-120px" src="{{asset('assets/images/widget-img.png')}}" alt="" class="img-fluid mx-auto d-block">
                        </div>
                    </div>
                </div>
            </div>
    
            @if (session()->has('message'))
            <div class="card-footer">
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            </div>
            @endif
        </div>
    </div>
    