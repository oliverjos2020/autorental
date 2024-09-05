<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">User Management</h4>

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
                    <label for="name">Name</label>
                    <input type="text" wire:model="name" class="form-control" placeholder="Name">
                    @error('name')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" wire:model="email" class="form-control" placeholder="Email">
                    @error('email')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="role">Role</label>
                    <select wire:model="role" class="form-control">
                        @if(Auth::user()->role_id == 1)
                            <option value="">Select Role</option>
                            @forelse($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @empty
                            @endforelse
                        @else
                        <option value="">Select Role</option>
                            <option value="3">Driver</option>
                        @endif
                    </select>
                    @error('role')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                @if(Auth::user()->role_id !== 1)
                    <div class="form-group mt-3">
                        <label for="station">Station</label>
                        <select wire:model="station" class="form-control">
                            <option value="">Select Station</option>
                                <option value="{{Auth::user()->station_id}}">{{Auth::user()->station->stationName}}</option>
                        </select>
                        @error('station')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror
                    </div>
                @else
                <div class="form-group mt-3">
                    <label for="station">Station</label>
                    <select wire:model="station" class="form-control">
                            <option value="">Select Station</option>
                        @forelse($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->stationName }}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('station')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                @endif
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" wire:model="password" class="form-control" placeholder="Password">
                    @error('password')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                

                <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="createUser">
                    Create User
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
                                <th>Email</th>
                                <th>Role</th>
                                <th>Station</th>
                                <th>Registered</th>
                                <th>Edit</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td><a href="/profile/{{ $user->id }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->role }}</td>
                                <td>{{ $user->station->stationName ?? '' }}</td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                <td><a class="btn btn-primary btn-sm text-light" style="cursor:pointer;"
                                        wire:click="edit({{$user->id}})"><i class="fa fa-edit"></i> Edit</a> </td>
                                <td><a class="text-light btn btn-danger btn-sm"
                                        wire:click="delete({{$user->id}})"><i class="fa fa-trash"></i>
                                        Delete</a></a></td>
                            </tr>

                            @if($editingID === $user->id) 
                            <tr>
                                <td colspan="2">
                                    <input type="text" wire:model="editingName" placeholder="Name"
                                        class="form-control mx-1">
                                    @error('editingName')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td colspan="2">
                                    <input type="text" wire:model="editingEmail" placeholder="Email"
                                        class="form-control mx-1">
                                    @error('editingEmail')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td colspan="2">
                                    <select wire:model="editingRole" class="form-control">
                                        <option value="">Select Role</option>
                                        @if(Auth::user()->role_id == 1)
                                            @forelse($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                                            @empty
                                            @endforelse
                                        @else
                                            <option value="3">Driver</option>
                                        @endif
                                                    </select>
                                    @error('editingRole')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td colspan="2">
                                    
                                        <select wire:model="editingStation" class="form-control">
                                            <option value="">Select station</option>
                                            @if(Auth::user()->role_id == 1)
                                                @forelse($stations as $station)
                                                    <option value="{{ $station->id }}">{{ $station->stationName }}</option>
                                                @empty
                                                @endforelse
                                            @else
                                                <option value="{{Auth::user()->station_id}}">{{Auth::user()->station->stationName}}</option>
                                            @endif
                                        </select>
                                    
                                    @error('editingStation')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>

                            </tr>
                            <tr>
                                <td colspan="8">
                                    <button type="submit" wire:click="update"
                                        class="btn btn-success btn-sm">Update</button> <button type="submit"
                                        wire:click="cancelEdit" class="btn btn-danger btn-sm">Cancel</button>
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
                            {{ $users->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>