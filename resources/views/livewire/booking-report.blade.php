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
                        <input type="date" wire:model.live.debounce.500ms="startDate"
                            class="form-control form-control-sm mt-0">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate"><small>End Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="endDate"
                            class="form-control form-control-sm mt-0">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Pick Up</th>
                                <th>Drop Off</th>
                                <th>Amount</th>
                                <th>Pick Up Location</th>
                                <th>Payment</th>
                                <th>Type</th>
                                <th>Identity Document</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->user->name ?? '' }}</td>
                                    <td>{{ $booking->user->email ?? '' }}</td>
                                    <td>{{ $booking->user->phone ?? 'N/A' }}</td>
                                    <td>{{ $booking->vehicle->vehicleMake ?? '' }}
                                        {{ $booking->vehicle->vehicleModel ?? ''}} {{ $booking->vehicle->vehicleYear ?? ''}}
                                    </td>
                                    <td>{{ $booking->vehicle->user->name ?? '' }}</td>
                                    <td>{{ $booking->pickupDate ?? '' }}</td>
                                    <td>{{ $booking->dropoffDate ?? ''}}</td>
                                    <td>&#8358;{{ $booking->amount ?? ''}}</td>
                                    <td>{{ $booking->pickup_location ?? ''}}</td>
                                    <td><span
                                            class="badge bg-{{ $booking->payment_status == '0' ? 'danger' : 'success'}}">{{ $booking->payment_status == '0' ? 'Unpaid' : 'Paid' }}</span>
                                    </td>
                                    <td>{{ $booking->type }}</td>
                                    <td><a class="btn btn-dark btn-sm" href="{{$booking->user->identity_card}}"
                                            target="_blank">View ID Card</a></td>
                                    <td>{{ $booking->created_at }}</td>
                                    <td>
                                        @if($booking->status == 0)
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="approve({{$booking->id}})">Approve</button>
                                        @elseif($booking->status == 1)
                                            <button class="btn btn-success btn-sm"><i class="fa fa-check"></i> Approved</button>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="14" class="text-center text-danger"> No record available</td>
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