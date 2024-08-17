<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Vendor Data - {{$user->name}}</h4>
    
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                    <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                </ol>
            </div>
    
        </div>
    </div>
    <div class="col-md-3">
        <div class="{{$step == 1 ? 'bg-primary text-light p-3 rounded' : 'bg-not-active text-primary p-3 rounded'}}">
            1. Personal Information
        </div>
    </div>
    <div class="col-md-2">
        <div class="{{$step == 2 ? 'bg-primary text-light p-3 rounded' : 'bg-not-active text-primary p-3 rounded'}}">
            2. Vehicle Information
        </div>
    </div>
    <div class="col-md-3">
        <div class="{{$step == 3 ? 'bg-primary text-light p-3 rounded' : 'bg-not-active text-primary p-3 rounded'}}">
            3. Means of Identification
        </div>
    </div>
    <div class="col-md-2">
        <div class="{{$step == 4 ? 'bg-primary text-light p-3 rounded' : 'bg-not-active text-primary p-3 rounded'}}">
            4. Bank Account
        </div>
    </div>
    <div class="col-md-2">
        <div class="{{$step == 5 ? 'bg-primary text-light p-3 rounded' : 'bg-not-active text-primary p-3 rounded'}}">
            5. Vehicle Images
        </div>
    </div>
    <div class="col-md-12 mt-2">
        <div class="card p-3">
            <div class="card-body">
                @if ($step == 1)
                <div>
                    <div class="row mt-3">
                       
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Mobile Number:</th>
                                <td>{{$user->phone_no}}</td>
                                
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{$user->address}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @elseif ($step == 2)
                    <div>
                        <table class="table table-bordered table-striped">
                        <tr>
                            <th>Vehicle Make:</th>
                            <td>{{$vehicle->vehicleMake}}</td>
                            <th>Vehicle Model:</th>
                            <td>{{$vehicle->vehicleModel}}</td>
                        </tr>
                        <tr>
                            <th>Seats:</th>
                            <td>{{$vehicle->seats}}</td>
                            <th>Doors:</th>
                            <td>{{$vehicle->doors}}</td>
                        </tr>
                        <tr>
                            <th>Transmission:</th>
                            <td>{{$vehicle->transmission}}</td>
                            <th>Passengers:</th>
                            <td>{{$vehicle->passengers}}</td>
                        </tr>
                        <tr>
                            <th>Air Condition:</th>
                            <td>{{$vehicle->airCondition}}</td>
                            <th>Drivers License:</th>
                            <td>{{$vehicle->driverLicense}}</td>
                        </tr>
                        <tr>
                            <th>Pickup Location</th>
                            <td>{{$vehicle->location}}</td>
                        </tr>
                        </table>
                    
                    </div>
                @elseif ($step == 3)
                <div>
                    <table class="table table-bordered">
                        <tr>
                            <th>Passport Photograph</th>
                            <td class="text-center">
                                <a href="{{asset($user->passport)}}" target="_blank">
                                    <img src="{{asset($user->passport)}}" class="img-fluid" style="max-height:200px">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Means of identification</th>
                            <td>{{$user->meansOfIdentification}}</td>
                        </tr>

                        <tr>
                            <th>Identification Document</th>
                            <td class="text-center">
                                <a href="{{asset($user->identificationDocument)}}" target="_blank">
                                <img src="{{asset($user->identificationDocument)}}" class="img-fluid" style="max-height:200px">
                                </a>
                            </td>
                        </tr>
                    </table>
                    
                </div>
                @elseif ($step == 4)
                    <div>
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Bank:</th>
                                <td>{{$user->bank}}</td>
                        
                            </tr>
                            <tr>
                                <th>Account Number:</th>
                                <td>{{$user->accountNumber}}</td>
                            </tr>
                        
                        <tr>
                            <th>Account Type:</th>
                            <td>{{$user->accountType}}</td>
                        </tr>
                    </table>
                    
                    </div>
                @elseif($step == 5)
                    <div>
                        <div class="row">
                            @forelse($photos as $photo)
                                <div class="col-md-3" style="display: flex; justify-content: center;">
                                    <a href="{{ asset($photo->image_path) }}" target="_blank">
                                        <img src="{{asset( $photo->image_path )}}" class="img-fluid mb-2" style="max-width: 100%">
                                    </a>
                                </div>
                            @empty
                                <div class="alert alert-danger text-center">No images available</div>
                            @endforelse
                        </div>
                        @if($showTextarea)
                        <div class="form-group mt-3">
                            <textarea id="reasonTextarea" wire:model="reason" class="form-control"
                                placeholder="Please provide the reason for denying..."
                                style="margin-top: 10px; width: 100%; height: 100px; font-size: 16px;"></textarea>
                            @error('reason')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm mt-2" wire:click="submitDeny">submit</button>
                        </div>
                        
                        @endif
                        @if($showApprove)
                        <div class="form-group">
                            <label for="SelectPrice">Select Vehicle placement category</label>
                            <select wire:model="category" class="form-control">
                                @foreach($priceCategory as $category)
                                    <option value="{{$category->id}}">{{$category->item}}</option>
                                @endforeach
                            </select>
                            
                            <button type="button" class="btn btn-success btn-sm mt-3" wire:click="approve">Approve</button>
                        </div>
                        
                        @endif
                    </div>
                @endif
                <div class="mt-4">
                    @if ($step > 1)
                    <button type="button" style="margin-right:15px;" class="btn btn-secondary" wire:click="previousStep">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;
                    @endif
                
                    @if ($step < 5) 
                    <button type="button" class="btn btn-primary btn-block px-3" wire:click="nextStep">Next</button>
                    @else
                        @if($vehicle->status == '2')

                        @elseif($vehicle->status == '1' || $vehicle->status == '3')
                            <button type="button" style="margin-right:15px;" class="btn btn-danger" wire:click="deny">Deny</button>
                                @if(!$showApprove)
                                    <button type="button" class="btn btn-success" wire:click="showApprove">Approve</button>
                                @endif
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
   
</div>