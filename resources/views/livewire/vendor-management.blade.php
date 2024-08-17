<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Vendor Management</h4>

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
                                <th>Name</th>
                                <th>Category</th>
                                <th>Vehicle</th>
                                <th>Model</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $vehicle)
                            <tr>
                                <td>{{ ($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $vehicle->user->name }}</td>
                                <td>{{ $vehicle->category->category }}</td>
                                <td>{{ $vehicle->vehicleMake }}</td>
                                <td>{{ $vehicle->vehicleModel }}</td>
                                <td>
                                    <a href="/vendor/{{$vehicle->id}}" class="btn btn-primary btn-sm text-light" style="cursor:pointer;">
                                        <i class="fa fa-edit"></i> 
                                        View information
                                    </a> 
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-danger"> No record available</td>
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