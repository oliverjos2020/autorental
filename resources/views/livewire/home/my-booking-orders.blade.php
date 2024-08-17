<div>
    <div class="container text-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="ic text-primary fas fa-home"></i> My Booking Order</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">My Booking Order</li>
            </ol>
        </nav>
    </div>

    <div class="container" style="margin-bottom:30px;">
        <main>
            <div class="row">
                <div class="col-md-12">
                    <section class="b-goods-f__section">
                        <h2 class="b-goods-f__title2">My Booking Order</h2>
                        <div class="row">
                            <div class="col-md-1">
                                <select name="limit" wire:model="limit" class="form-control form-control-sm mt-2">
                                    <option value="10">10</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="500">500</option>
                                </select>
                                <br>
                            </div>
                            <div class="col-md-7"></div>
                            <div class="col-md-4">
                                {{-- <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by make..."
                                    class="form-control form-control-sm mt-2"> --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="b-goods-f__table table table-bordered">
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
                                        {{-- @if(Auth::user()->role_id == 2) --}}
                                        <th colspan="2">Action</th>
                                        {{-- @endif --}}
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($myOrders as $order)
                                    <tr>
                                        <td>{{ ($myOrders->currentPage() - 1) * $myOrders->perPage() + $loop->iteration }}
                                        </td>
                                        <td> <a
                                                href="@if(Auth::user()->role_id == 1) '/vendorManagement/approved/?uid={{ $order->vehicle->user->id }}&vehID={{$order->vehicle_id}}' @else # @endif">{{
                                                $order->user->name }}</a> </td>
                                        @if(Auth::user()->role_id == 1)
                                        <td>{{ $order->user->name }}</td>
                                        @endif
                                        @if(Auth::user()->role_id == 2)
                                        <td>{{$order->vehicle->user->phone_no }}</td>
                                        @endif
                                        <td><a href="/review/{{ $order->vehicle_id}}" target="_blank">{{
                                                $order->vehicle->vehicleMake }} {{ $order->vehicle->vehicleModel }}</a>
                                        </td>
                                        <td>{{ $order->pickupDate }}</td>
                                        <td>{{ $order->pickupTime }}</td>
                                        <td>{{ $order->dropoffDate }}</td>
                                        <td>{{ $order->dropoffTime }}</td>
                                        <td>{{ $order->duration }} Day(s)</td>
                                        <td>{{ $order->amount }}</td>
                                        <td><span
                                                class="badge bg-{{$order->payment_status == 1 ? 'success' : 'danger'}}">{{$order->payment_status
                                                == 1 ? 'Paid' : 'Pending'}}</span></td>
                                        <td>{{ $order->created_at }}</td>
                                        {{-- @if(Auth::user()->role_id == 2) --}}
                                        <td>
                                            @if($order->status == 1)
                                            <a class="btn btn-primary btn-sm" style="color:#fff;"><i
                                                    class="fas fa-info-circle"></i> Trip Approved</a>
                                            @elseif($order->status == 2)
                                            <a class="btn btn-primary btn-sm" style="color:#fff;"><i class="fa fa-check"></i> Trip Ongoing</a>
                                            @elseif($order->status == 3)
                                            <span class="btn btn-primary" style="color:#fff;"><i class="fa fa-check"></i> Trip
                                                Completed</span>
                                            {{-- <a class="btn btn-success btn-sm"><i class="fa fa-check"></i> Trip
                                                Completed</a> --}}
                                            @endif
                                        </td>
                                        {{-- @endif --}}
                                        @if($order->status == 2)
                                        <td>

                                            <a class="btn btn-danger btn-sm"
                                                wire:click="end({{$order->id}}, {{$order->vehicle_id}})"><i
                                                    class="fa fa-check"></i> End Trip</a>
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
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

</div>