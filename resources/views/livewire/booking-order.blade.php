<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Booking Orders</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                    <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                </ol>
            </div>

        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                    <i class="mdi mdi-alert-circle-outline me-2"></i> Kindly click on approve on any pending request to approve the order
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

                    </button>
                </div>

                <div class="row mt-2">
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
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by name..."
                            class="form-control form-control-sm mt-2">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                @if(Auth::user()->role_id == 1)
                                <th>Merchant</th>
                                @endif
                                <th>Name</th>
                                @if(Auth::user()->role_id == 2)
                                <th>Phone No</th>
                                @endif
                                <th>Vehicle</th>
                                <th>Pickup Date</th>
                                <th>Pickup Time</th>
                                <th>Dropoff Date</th>
                                <th>Dropoff Date</th>
                                <th>Days</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Created</th>
                                @if(Auth::user()->role_id == 2)
                                <th colspan="2">Action</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}
                                </td>
                                <td> <a href="@if(Auth::user()->role_id == 1) '/vendorManagement/approved/?uid={{ $order->vehicle->user->id }}&vehID={{$order->vehicle_id}}' @else # @endif">{{ $order->vehicle->user->name }}</a> </td>
                                @if(Auth::user()->role_id == 1)
                                <td>{{ $order->user->name }}</td>
                                @endif
                                @if(Auth::user()->role_id == 2)
                                <td>{{$order->vehicle->user->phone_no }}</td>
                                @endif
                                <td><a href="/review/{{ $order->vehicle_id}}" target="_blank">{{ $order->vehicle->vehicleMake }} {{ $order->vehicle->vehicleModel }}</a></td>
                                <td>{{ $order->pickupDate }}</td>
                                <td>{{ $order->pickupTime }}</td>
                                <td>{{ $order->dropoffDate }}</td>
                                <td>{{ $order->dropoffTime }}</td>
                                <td>{{ $order->duration }} Day(s)</td>
                                <td>{{ $order->amount }}</td>
                                <td><span class="badge bg-{{$order->payment_status == 1 ? 'success' : 'danger'}}">{{$order->payment_status == 1 ? 'Paid' : 'Pending'}}</span></td>
                                <td>{{ $order->created_at }}</td>
                                @if(Auth::user()->role_id == 2)
                                <td>
                                    @if($order->status == 1)
                                        <a class="btn btn-primary btn-sm" wire:click="approve({{$order->id}}, {{$order->vehicle_id}})"><i class="fas fa-info-circle"></i> Approve</a>
                                    @elseif($order->status == 2)
                                        <a class="btn btn-info btn-sm"><i class="fa fa-check"></i> Trip Ongoing</a>
                                    @elseif($order->status == 3)
                                    <span class="badge bg-success"><i class="fa fa-check"></i> Trip Completed</span>
                                        {{-- <a class="btn btn-success btn-sm"><i class="fa fa-check"></i> Trip Completed</a> --}}
                                    @endif
                                </td>
                                @endif
                                @if($order->status == 2)
                                <td>
                                    
                                    <a class="btn btn-danger btn-sm" wire:click="end({{$order->id}}, {{$order->vehicle_id}})"><i class="fa fa-check"></i> End Trip</a>
                                </td>
                                @endif
                            </tr>
                            
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-danger"> No record available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="loader text-center">
                        <div class="my-2">
                            {{ $orders->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>