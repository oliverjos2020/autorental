<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Pickup Location</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                    <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                </ol>
            </div>

        </div>
    </div>


    {{-- <div class="row mb-3">
        <div class="col-md-2">
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal" id="addButtonx">
                Create User
            </button>
        </div>

    </div> --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label for="location">Location name</label>
                    <input type="text" wire:model="location" class="form-control" placeholder="Location Name">
                    @error('location')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="location">Latitude</label>
                    <input type="text" wire:model="latitude" class="form-control" placeholder="Latitude">
                    @error('latitude')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="longitude">Longitude</label>
                    <input type="text" wire:model="longitude" class="form-control" placeholder="Longitude">
                    @error('longitude')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>

                <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="createLocation">
                    Create Location
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-8">
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
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search..."
                            class="form-control form-control-sm mt-2">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Location</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th>Edit</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($locations as $location)
                            <tr>
                                <td>
                                    {{ ($locations->currentPage() - 1) * $locations->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    {{ $location->location }}
                                </td>
                                <td>
                                    {{ $location->longitude }}
                                </td>
                                <td>
                                    {{ $location->latitude }}
                                </td>
                                <td>
                                    <a class="btn btn-primary btn-sm text-light" style="cursor:pointer;" wire:click="edit({{$location->id}})"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td><a class="text-light btn btn-danger btn-sm"
                                        wire:click="delete({{$location->id}})"><i class="fa fa-trash"></i>
                                        Delete</a></a></td>
                            </tr>

                            @if($editingID === $location->id)
                            <tr>
                                <td colspan="2">
                                    <input type="text" wire:model="editinglocation" placeholder="location.." id=""
                                        class="form-control mx-1">
                                    @error('editinglocation')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <br>
                                    <button type="submit" wire:click="update"
                                        class="btn btn-success btn-sm">Update</button> <button type="submit"
                                        wire:click="cancelEdit" class="btn btn-danger btn-sm">Cancel</button>
                                </td>
                                <td colspan ="2">
                                    <input type="text" wire:model="editingLongitude" placeholder="longitude.." id=""
                                        class="form-control mx-1">
                                    @error('editingLongitude')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td colspan ="2">
                                    <input type="text" wire:model="editingLatitude" placeholder="latitude.." id=""
                                        class="form-control mx-1">
                                    @error('editingLatitude')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            @endif

                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger"> No record available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="loader text-center">
                        <div class="my-2">
                            {{ $locations->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
