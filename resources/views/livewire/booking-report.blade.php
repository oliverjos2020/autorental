<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Bookings</h4>

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

                <div class="row">
                    <div class="col-md-1">
                        <select name="limit" wire:model="limit" class="form-control form-control-sm mt-2">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-3">
                        <label for="startDate"><small>Payment Status</small></label>
                        <select wire:model="status" class="form-control form-control-sm">
                            <option value="">Select an option</option>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="startDate"><small>Start Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="startDate" class="form-control form-control-sm mt-0">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate"><small>End Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="endDate" class="form-control form-control-sm mt-0">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Vehicle</th>
                                <th>Pick Up</th>
                                <th>Drop Off</th>
                                <th>Amount</th>
                                <th>Pick Up</th>
                                <th>Payment</th>
                                <th>Created</th>
                                {{-- <th>Booking Price</th>
                                <th>Edit</th>
                                <th>Delete</th> --}}

                                {{-- <th>Vehicle Status</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->user->name ?? '' }}</td>
                                <td>{{ $booking->vehicle->vehicleMake ?? '' }} {{ $booking->vehicle->vehicleModel ?? ''}} {{ $booking->vehicle->vehicleYear ?? ''}}</td>
                                <td>{{ $booking->pickupDate ?? '' }}</td>
                                <td>{{ $booking->dropoffDate ?? ''}}</td>
                                <td>&#8358;{{ $booking->amount ?? ''}}</td>
                                <td>{{ $booking->pickup_location ?? ''}}</td>
                                <td><span class="badge bg-{{ $booking->payment_status == '0' ? 'danger' : 'success'}}">{{ $booking->payment_status == '0' ? 'Unpaid' : 'Paid' }}</span></td>
                                <td>{{ $booking->created_at }}</td>
                                {{-- <td>{{ $vehicle->priceSetup->amount }}</td>
                                <td><a class="btn btn-success btn-sm" href="/editVehicles/{{$vehicle->id}}">Edit</a></td>
                                <td><a class="btn btn-danger btn-sm" wire:click="delete({{$vehicle->id}})">Delete</a></td> --}}
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
                            {{ $bookings->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
