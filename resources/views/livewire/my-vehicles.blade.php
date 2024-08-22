<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">My Vehicles</h4>

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
                    <div class="col-md-7"></div>
                    <div class="col-md-4">
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by make..."
                            class="form-control form-control-sm mt-2">
                    </div>
                </div>
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
                                <th>Edit</th>
                                <th>Delete</th>

                                {{-- <th>Vehicle Status</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                            <tr>
                                <td><img src="{{ url($vehicle->photos->first()->image_path) }}" style="height:50px; width:70px;" alt="{{ $vehicle->vehicleMake }}"></td>
                                <td>{{ $vehicle->vehicleMake }}</td>
                                <td>{{ $vehicle->vehicleModel }}</td>
                                <td>{{ $vehicle->vehicleYear }}</td>
                                <td>{{ $vehicle->priceSetup->item}}</td>
                                <td>{{ $vehicle->airCondition }}</td>
                                <td><span class="badge bg-{{ $vehicle->transmission == 'automatic' ? 'warning' : 'danger'}}">{{ $vehicle->transmission }}</span></td>
                                <td>{{ $vehicle->seats }}</td>
                                <td>{{ $vehicle->priceSetup->amount }}</td>
                                {{-- <td>
                                    @if($vehicle->status == 1)
                                        <a class="btn btn-primary btn-sm"><i class="fas fa-sync-alt"> Pending</a>
                                    @elseif($vehicle->status == 2)
                                        <a class="btn btn-success btn-sm"><i class="fa fa-check"></i> Approved</a>
                                    @elseif($vehicle->status == 3)
                                        <a class="btn btn-danger btn-sm"><i class="fas fa-info-circle"></i> Declined</a>
                                    @endif
                                </td> --}}
                                <td><a class="btn btn-success btn-sm" href="/editVehicles/{{$vehicle->id}}">Edit</a></td>
                                <td><a class="btn btn-danger btn-sm" wire:click="delete({{$vehicle->id}})">Delete</a></td>
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
