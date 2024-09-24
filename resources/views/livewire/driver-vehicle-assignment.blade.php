<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18">Assign Vehicle to Driver</h4>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <label for="driver">Driver</label>
                    <select wire:model="driver" class="form-control">
                        <option value="">Select a driver</option>
                        @forelse($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @empty
                        @endforelse
                    </select>

                    @error('driver')
                    <span class="text-danger"> {{ $message }} </span><br>
                    @enderror

                    <label class="mt-2" for="driver">Vehicles</label>
                    <select wire:model="vehicle" multiple class="form-control">
                        <option value="">Select vehicles</option>
                        @forelse($vehicles as $vehicle)
                            <option value="{{$vehicle->id}}">{{$vehicle->vehicleMake}} {{$vehicle->vehicleModel}} {{$vehicle->vehicleYear}}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('vehicle')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                    <button class="btn btn-danger mt-4 btn-block w-100" wire:click="submit">
                        Assign
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <select name="limit" wire:model="limit" class="form-control form-control-sm mt-2">
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                        <div class="col-md-7"></div>
                        {{-- <div class="col-md-4">
                            <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search..."
                                class="form-control form-control-sm mt-2">
                        </div> --}}
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-bordered"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Driver</th>
                                    <th>Vehicle</th>
                                    <th>Delete</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>{{ ($records->currentPage() - 1) * $records->perPage() + $loop->iteration }}</td>
                                    <td>{{ $record->user->name }}</td>
                                    <td>{{ $record->vehicle->vehicleMake }} {{$record->vehicle->vehicleModel}} {{$record->vehicle->vehicleYear}}</td>
                                    <td>
                                        <a class="text-light btn btn-danger btn-sm" wire:click="delete({{$record->id}})">
                                        <i class="fa fa-trash"></i> Delete</a></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-danger"> No record available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="loader text-center">
                            <div class="my-2">
                                {{ $records->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
