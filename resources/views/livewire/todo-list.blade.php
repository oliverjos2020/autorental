<div class="container">
    @if(session('error'))
        <div class="alert alert-danger"><strong>Error:</strong> {{session('error')}}</div>
    @endif;
    <div class="card p-3" style="margin: 0px auto; width:500px; margin-top:50px;">
       <div class="card-header">
        <h3>Create New Todo</h3>
       </div>
       <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <form>
        <div class="form-group">
            <label for="todo">* Todo</label>
            <input type="text" wire:model.debounce.500ms="name" class="form-control">
            @error('name')
                <span class="text-danger"> {{ $message }}</span>
            @enderror
        </div>
        <button type="submit" wire:click.prevent="create" class="btn btn-primary">Create</button>
        </form>
       </div>
    </div>

    <div style="margin: 0px auto; width:500px; margin-top:50px;">
        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search..." class="form-control">
        <div class="mt-2">
            @foreach($todos as $todo)
                <div wire:key="{{ $todo->id }}" class="row border-primary shadow mt-4">
                    <div class="col-md-6">
                        <div class="d-flexx flex-items-middle">
                            <input type="checkbox" wire:click="toggle({{$todo->id}})" {{$todo->completed ? 'checked': ''}}>
                            @if($editingtodoID === $todo->id)
                                <input type="text" wire:model="editingnewName" placeholder="Todo.." id="" class="form-control mx-1">
                                @error('editingnewName')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <br>
                                <button type="submit" wire:click="update" class="btn btn-success btn-sm">Update</button> <button type="submit" wire:click="cancelEdit" class="btn btn-danger btn-sm">Cancel</button>
                            @else
                            <h6 class="mx-1">{{$todo->name}}</h6>
                            @endif
                        </div>
                        
                        <p>{{$todo->created_at}}</p>
                    </div>
                    <div class="col-md-2">
                    
                    </div>
                    <div class="col-md-4">
                        <a class="text-success" wire:click="edit({{$todo->id}})">Edit</a> | <a class="text-danger" wire:click="delete({{$todo->id}})">Delete</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="my-2">
            {{ $todos->links()}}
        </div>
    </div>
</div>