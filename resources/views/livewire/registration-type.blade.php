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

                    @if (session('message'))
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
                <p class="card-title">
                    <strongx>Setting Up Your Profile for Car </strongx>
                </p>
            </div>
            <div class="card-body">
                {{-- @if ($step == 1) --}}


                {{-- @elseif ($step == 2) --}}
                <div>
                    <h4><strong>Vehicle Information</strong></h4>
                    <div class="row">
                        <div class="col-md-6">
                            @if (!$addtionalMake)
                                <div class="form-group">
                                    <label for="vehicleMake">Vehicle Make</label>
                                    <select wire:model="vehicleMake" class="form-control">
                                        <option value="">Select Vehicle Make</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->slug }}">{{ $brand->brand }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicleMake')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="badge bg-danger">Didn't find your vehicle Make</span> | <a
                                    class="text-dark" style="cursor:pointer;" wire:click="addMake">Click
                                    Here</a>
                            @else
                                <div class="form-group">
                                    <label for="vehicleMake">Put in your vehicle make</label>
                                    <input type="text" wire:model="vehicleMake" placeholder="Vehicle Make"
                                        class="form-control">
                                    @error('vehicleMake')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                @php
                                    $years = range(2000, date('Y'));
                                @endphp
                                <label for="vehicleYear">Vehicle Year</label>
                                <select wire:model="vehicleYear" class="form-control">
                                    <option value="">Select Option</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                                @error('vehicleYear')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="vehicleModel">Vehicle Model</label>
                                <input type="text" id="vehicleModel" class="form-control" wire:model="vehicleModel"
                                    placeholder="e.g Corolla">
                                @error('vehicleModel')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
                                @error('seats')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="doors">Doors</label>
                                <select wire:model="doors" class="form-control">
                                    <option value="">Select an option</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </select>
                                @error('doors')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="transmission">Transmission</label>
                                <select wire:model="transmission" class="form-control">
                                    <option value="">Select Transmission</option>
                                    <option value="automatic">Automatic</option>
                                    <option value="manual">Manual</option>
                                </select>
                                @error('transmission')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="motor">Motor</label>
                                <input type="text" wire:model="motor" placeholder="Input Motor" class="form-control">
                                @error('motor')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="fuelCapacity">Fuel Capacity</label>
                                <input type="text" wire:model="fuelCapacity" placeholder="Input Fuel Capacity"
                                    class="form-control">
                                @error('fuelCapacity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="maxSpeed">Max Speed</label>
                                <input type="text" wire:model="maxSpeed" placeholder="Input Max Speed"
                                    class="form-control">
                                @error('maxSpeed')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="maxPower">Max Power</label>
                                <input type="text" wire:model="maxPower" placeholder="Input Max Power"
                                    class="form-control mt-2">
                                @error('maxPower')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <input type="checkbox" value="yes" wire:model="airCondition" class="mx-2"> Air Condition
                            <input type="checkbox" value="yes" wire:model="keylessEntry" class="mx-2"> Keyless Entry
                            <input type="checkbox" value="yes" wire:model="musicPlayer" class="mx-2"> Standard Music Player
                            <input type="checkbox" value="yes" wire:model="airBags" class="mx-2"> Air Bags
                        </div>
                        <div class="form-group mt-2" wire:ignore>
                            <label for="moreInfo">More Information</label>
                            <textarea wire:model="moreInfo" class="form-control" placeholder="Start typing..." style="height:80px"></textarea>
                            @error('moreInfo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    {{-- @elseif ($step == 3) --}}

                    {{-- @elseif ($step == 4) --}}

                    {{-- @elseif ($step == 5) --}}
                    <div>
                        <div class="form-group row mt-2 p-2">
                            <div class="alert alert-info" role="alert">
                                {{-- <h4 class="alert-heading">Well done!</h4> --}}
                                <p>To ensure your car listing stands out, please upload clear images of your car from
                                    the following angles:</p>
                                <hr>
                                <ul>
                                    <li>Front View: Capture the full front of your car.</li>
                                    <li>Back View: Capture the entire back of your car.</li>
                                    <li>Interior View: Show the interior, highlighting key features.</li>
                                    <li>All Sides: Provide images of both sides of your car to give a complete view.
                                    </li>
                                </ul>
                                {{-- <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p> --}}
                            </div>
                            <label>Images of vehicle <span class="text-danger">*</span></label>
                            <div class="col-xxl-12">
                                <input type="file"
                                    class="form-control {{ $errors->has('vehImage') ? 'is-invalid' : '' }}"
                                    wire:model="vehImage" accept="image/jpg, image/jpeg, image/png" multiple>
                                @if ($errors->has('vehImage.*'))
                                    @foreach ($errors->get('vehImage.*') as $error)
                                        <span class="text-danger">{{ $error[0] }}</span><br>
                                    @endforeach
                                @endif
                                @if ($errors->has('vehImage'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('vehImage') }}
                                    </div>
                                @endif

                                <div class="row mt-1 mb-1">
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
                                    {{-- @if ($vehImage)
                                    @foreach ($vehImage as $image)
                                        @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                            <div class="col-md-3">
                                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid mb-2" style="max-width: 100%">
                                            </div>
                                        @endif
                                    @endforeach
                                @endif --}}


                                    @if ($vehImage)
                                        @foreach ($vehImage as $image)
                                            @if ($image instanceof \Livewire\TemporaryUploadedFile)
                                                <div class="col-md-3">
                                                    <img src="{{ asset($image->temporaryUrl()) }}"
                                                        class="img-fluid mb-2" style="max-width: 100%">
                                                </div>
                                            @endif
                                        @endforeach
                                    @elseif ($existingvehImage)
                                        @foreach ($existingvehImage as $existingImage)
                                            <div class="col-md-3">
                                                <img src="{{ asset($existingImage->image_path) }}"
                                                    class="img-fluid mb-2" style="max-width: 100%">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <br>
                            </div>
                            <span class="text-warning">Allowed extensions: *jpg, jpeg, png</span>
                            <div wire:loading wire:target="vehImage">
                                Uploading...
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="SelectPrice">Select Vehicle placement category</label>
                            <select wire:model="category" class="form-control">
                                <option value="">Select Vehicle category</option>
                                @foreach ($priceCategory as $category)
                                    <option value="{{ $category->id }}">{{ $category->item }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <button wire:click="submit" class="btn btn-primary" id="submitButton">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- @endif --}}


            <div class="row">
                <div class="col-7">

                </div>
                <div class="col-5">
                    <div class="mt-4">
                        <img style="margin-top:-120px" src="{{ asset('assets/images/widget-img.png') }}"
                            alt="" class="img-fluid mx-auto d-block">
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
<script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
{{-- <script>
        tinymce.init({
            selector: '#elm1',
            setup: function (editor) {
                editor.on('change', function () {
                    var content = tinymce.get('elm1').getContent();
                    console.log("Content on change:", content); // Log to ensure content is retrieved
                    @this.set('moreInfo', content);
                });
            }
        });

        document.getElementById('submitButton').addEventListener('click', function(event) {
            event.preventDefault();

            // Retrieve content from TinyMCE
            var content = tinymce.get('elm1').getContent();

            // Log the content to ensure it's being retrieved
            console.log("Retrieved content from TinyMCE:", content);

            // Set the content to the Livewire property
            @this.set('moreInfo', content);

            // Trigger the Livewire submit method
            @this.call('submit'); // Directly call the submit method

        });
    </script> --}}
