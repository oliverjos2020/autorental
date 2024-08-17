
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="page-title mb-0 font-size-18">Dashboard</h4>
        
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Welcome to Auto-Rental Dashboard</li>
                        </ol>
                    </div>
        
                </div>
            </div>
        </div>
        <!-- end page title -->
        @if(Auth::user()->role_id == 2)
        {{-- @forelse(Auth::user()->vehicle as $vehicle)
            @if($vehicle->category->category == 'Booking')

            @endif
        @endforelse --}}
                {{-- <div class="row h-25">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row justify-content-end">
                                    <div class="col-7">
                                        <div class="text-dark-50">
                                            <h4 class="text-dark"><strong>Register Your Car for Rentals</strong></h4>
                                        </div>
                                        <h6>Car Rental</h6>
                                        <div>
                                            <a href="/registration/new/rental" class="btn btn-outline-success btn-sm">View more</a>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="mt-4">
                                            <img src="assets/images/widget-img.png" alt="" class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <div class="row justify-content-end">
                                    <div class="col-7">
                                        <div class="text-light-50">
                                            <h4 class="text-light"><strong>Register as a Ride Provider</strong></h4>
                                        </div>
                                        <h6 class="text-light">Car Booking</h6>
                                        <div>
                                            <a href="/registration/new/booking" class="btn btn-outline-light btn-sm">View more</a>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="mt-4">
                                            <img src="assets/images/widget-img.png" alt="" class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row justify-content-end">
                                    <div class="col-7">
                                        <div class="text-dark-50">
                                            <h4 class="text-dark"><strong>Register Your Car for Entertainment</strong></h4>
                                        </div>
                                        <h6>Car Entertainment</h6>
                                        <div>
                                            <a href="/registration/new/entertainment" class="btn btn-outline-success btn-sm">View more</a>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="mt-4">
                                            <img src="assets/images/widget-img.png" alt="" class="img-fluid mx-auto d-block">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <p class="mb-2">Pending Booking Orders</p>
                                        <h4 class="mb-0" id="totalCollection">{{$orders->count()}}</h4>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <div>
                                                <!-- 2.06 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                            </div>
                                            <div class="progress progress-sm mt-3">
                                                <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <p class="mb-2">Approved Booking Orders</p>
                                        <h4 class="mb-0" id="totalCount">{{$approvedRequest->count()}}</h4>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <div>
                                                <!-- 3.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                            </div>
                                            <div class="progress progress-sm mt-3">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 78%"
                                                    aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <p class="mb-2">Completed Booking Trips</p>
                                        <h4 class="mb-0" id="totalSettlement">{{$completedTrips->count()}}</h4>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <div>
                                                <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                            </div>
                                            <div class="progress progress-sm mt-3">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <p class="mb-2">Number of active vehicles</p>
                                        <h4 class="mb-0" id="totalSettlement">{{$partnerVehicles->count()}}</h4>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-end">
                                            <div>
                                                <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                            </div>
                                            <div class="progress progress-sm mt-3">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 75%"
                                                    aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            
            
        @elseif(Auth::user()->role_id == 1)
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Total Users</p>
                                    <h4 class="mb-0" id="totalCollection">{{$users->count()}}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-end">
                                        <div>
                                            <!-- 2.06 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                        </div>
                                        <div class="progress progress-sm mt-3">
                                            <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Transaction Vehicle Approved</p>
                                    <h4 class="mb-0" id="totalCount">{{$approved->count()}}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-end">
                                        <div>
                                            <!-- 3.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                        </div>
                                        <div class="progress progress-sm mt-3">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 78%"
                                                aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Total Vehicle Pending</p>
                                    <h4 class="mb-0" id="totalSettlement">{{$pending->count()}}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-end">
                                        <div>
                                            <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                        </div>
                                        <div class="progress progress-sm mt-3">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 75%"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
