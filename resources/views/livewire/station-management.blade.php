<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Station</h4>

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
                        <label for="station">Station name</label>
                        <input type="text" wire:model="station" class="form-control" placeholder="Station Name">
                        @error('station')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror

                        @if(session('message'))
                        <div class="bg-success p-2 text-light mx-2 mt-3">{{session('message')}}</div>
                        @endif
                    </div>
                    <div class="form-group mt-3">
                        <label for="location">Location</label>
                        <select wire:model="location" class="form-control">
                                <option value="">Select Location</option>
                            @forelse($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location }}</option>
                            @empty
                            @endforelse
                        </select>
                        @error('location')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                    
                    <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="create">
                        Create Station
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
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by station..." class="form-control form-control-sm mt-2">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Role</th>
                                <th>Edit</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stations as $station)
                                <tr>
                                    <td>{{ ($stations->currentPage() - 1) * $stations->perPage() + $loop->iteration }}</td>
                                    <td>{{ $station->stationName }}</td>
                                    <td><a class="btn btn-primary btn-sm text-light" style="cursor:pointer;" wire:click="edit({{$station->id}})"><i class="fa fa-edit"></i> Edit</a> </td>
                                    <td><a  @if(in_array($station->slug, ['admin', 'users', 'partners'])) class="text-light btn btn-secondary btn-sm" @else class="text-light btn btn-danger btn-sm" wire:click="delete({{$station->id}})" @endif ><i class="fa fa-trash"></i> Delete</a></a></td>
                                </tr>
                                
                                    @if($editingID === $station->id)
                                    <tr>
                                    <td colspan="2">
                                        <input type="text" wire:model="editingStation" placeholder="station.." id="" class="form-control mx-1">
                                        @error('editingStation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <br>
                                        <button type="submit" wire:click="update" class="btn btn-success btn-sm">Update</button> <button type="submit"
                                            wire:click="cancelEdit" class="btn btn-danger btn-sm">Cancel</button>
                                    </td>
                                    <td colspan="2">
                                        <select wire:model="editingLocation" class="form-control">
                                            @forelse($stations as $station)
                                                <option value="{{ $station->id }}">{{ $station->stationName }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @error('editingLocation')
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
                            {{ $stations->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


