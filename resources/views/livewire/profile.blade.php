<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">User Management</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                    <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                </ol>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile-widgets py-3">

                        <div class="text-center">
                            <div class="">
                               
                                @php
                                $getName = $user->name;
                                    $name = explode(" ", $getName);
                                    $count = count($name);
                                    if($count > 1):
                                        $name1 = $name[0];
                                        $name2 = $name[1];
                                        $displayName = $name1[0] . ' ' . $name2[0];
                                    else:
                                        $name1 = $name[0];
                                        $displayName = $name1[0];
                                    endif;
                                @endphp
                                <div class="mx-auto bg-info text-light" style="width: 6rem; height: 6rem; justify-content: center; display: flex; align-items: center; font-size:30px; border-radius:50%">
                                    {{ $displayName }}
                                </div>
                                 
                                <div class="online-circle"><i class="fas fa-circle text-success"></i>
                                </div>
                            </div>

                            <div class="mt-3 ">
                                <a class="text-reset fw-medium font-size-16">{{ $user->name }}</a>
                                <p class="text-body mt-1 mb-1">{{ $user->role->role}}</p>

                                <span class="badge bg-success">Joined: {{ $user->created_at->diffForHumans()}}</span>
                                {{-- <span class="badge bg-danger">Message</span> --}}
                            </div>

                           

                            
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Personal Information</h5>


                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Fullname</p>
                        <h6 class="">{{ $user->name}}</h6>
                    </div>
                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Email Address</p>
                        <h6 class=""><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Phone number</p>
                        <h6 class="">{{ $user->phone_no}}</h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Address</p>
                        <h6 class="">{{ $user->address}}</h6>
                    </div>

                </div>
            </div>
            <div class="card">
                <div class="card-body">

                  

                    <div id="carouselExampleCaption" class="carousel slide pointer-event" data-bs-ride="carousel">
                        <div class="carousel-inner" role="listbox">
                            @if($user->role_id == 3)
                            <h3>Driver License</h3>
                                @if(!empty($user->driverLicense))
                                <div class="carousel-item active">
                                    <img src="{{ asset($user->driverLicense) }}" alt="Driver License" class="d-block img-fluid">
                                    <div class="carousel-caption d-none d-md-block text-white-50">
                                        <h5 class="text-white">Driver License</h5>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-danger">No Identification document uploaded</div>
                                @endif
                            @elseif($user->role_id == 2)
                            <h3>Driver License</h3>
                            @if(!empty($user->identificationDocument))
                            <div class="carousel-item active">
                                <img src="{{ asset($user->identificationDocument) }}" alt="Driver License" class="d-block img-fluid">
                                <div class="carousel-caption d-none d-md-block text-white-50">
                                    <h5 class="text-white">Driver License</h5>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-danger">No Identification document uploaded</div>
                            @endif
                            @endif

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleCaption" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleCaption" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-9">
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Vehicles</h4>
                    <div class="row">
                        <div class="col-md-1">
                            <select name="limit" wire:model="limit" class="form-control form-control-sm mt-2">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-4">
                            <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by make..."
                                class="form-control form-control-sm mt-2">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-bordered"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Category</th>
                                        <th>Air Condition</th>
                                        <th>Transmission</th>
                                        <th>Seats</th>
                                        <th>Booking Price</th>
                                        <th>Vehicle Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($vehicles as $vehicle)
                                    <tr>
                                        <td><img src="{{ asset($vehicle->photos->first()->image_path) }}" style="height:50px; width:70px;" alt="{{ $vehicle->vehicleMake }}"></td>
                                        <td>{{ $vehicle->vehicleMake }}</td>
                                        <td>{{ $vehicle->vehicleModel }}</td>
                                        <td>{{ $vehicle->vehicleYear }}</td>
                                        <td><span>{{ $vehicle->priceSetup->item }}</span></td>
                                        <td>{{ $vehicle->airCondition }}</td>
                                        <td><span>{{ $vehicle->transmission }}</span></td>
                                        <td>{{ $vehicle->seats }}</td>
                                        <td>{{ $vehicle->priceSetup->amount }}</td>
                                        <td>
                                            @if($vehicle->status == 1)
                                                <a class="badge bg-primary btn-sm"><i class="fas fa-sync-alt"> Pending</a>
                                            @elseif($vehicle->status == 2)
                                                <a class="badge bg-success btn-sm"><i class="fa fa-check"></i> Approved</a>
                                            @elseif($vehicle->status == 3)
                                                <a class="badge bg-danger btn-sm"><i class="fas fa-info-circle"></i> Declined</a>
                                            @endif
                                        </td>
                                    </tr>
        
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-danger"> No record available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="loader text-center">
                                <div class="my-2">
                                    {{ $vehicles->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>


    </div>