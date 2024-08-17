<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Role</h4>

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
                        <label for="role">Role name</label>
                        <input type="text" wire:model="role" class="form-control" placeholder="Role Name">
                        @error('role')
                        <span class="text-danger"> {{ $message }} </span>
                        @enderror

                        @if(session('message'))
                        <div class="bg-success p-2 text-light mx-2 mt-3">{{session('message')}}</div>
                        @endif
                    </div>
                    
                    <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="createRole">
                        Create Role
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
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search..." class="form-control form-control-sm mt-2">
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
                            @forelse($roles as $role)
                                <tr>
                                    <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</td>
                                    <td>{{ $role->role }}</td>
                                    <td><a class="btn btn-primary btn-sm text-light" style="cursor:pointer;" wire:click="edit({{$role->id}})"><i class="fa fa-edit"></i> Edit</a> </td>
                                    <td><a  @if(in_array($role->slug, ['admin', 'users', 'partners'])) class="text-light btn btn-secondary btn-sm" @else class="text-light btn btn-danger btn-sm" wire:click="delete({{$role->id}})" @endif ><i class="fa fa-trash"></i> Delete</a></a></td>
                                </tr>
                                
                                    @if($editingID === $role->id)
                                    <tr>
                                    <td colspan="4">
                                        <input type="text" wire:model="editingrole" placeholder="role.." id="" class="form-control mx-1">
                                        @error('editingrole')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <br>
                                        <button type="submit" wire:click="update" class="btn btn-success btn-sm">Update</button> <button type="submit"
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
                            {{ $roles->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


