<div class="container">
    <div class="card p-3" style="margin: 0px auto; width:500px; margin-top:50px;">
        @if(session('message'))
            <span class="bg-success p-2 text-light">{{session('message')}}</span>
        @endif
        <form action="">
            <input type="text" class="form-control" placeholder="Name" wire:model="name">
            @error('name')
                <span class="text-danger"> {{ $message }} </span>
            @enderror
            <br>
            <input type="email" class="form-control" placeholder="Email" wire:model="email">
            @error('email')
            <span class="text-danger"> {{ $message }} </span>
            @enderror
            <br>
            <input type="password" class="form-control" placeholder="Password" wire:model="password">
            @error('password')
            <span class="text-danger"> {{ $message }} </span>
            @enderror
            <br>
            <button class="btn btn-dark btn-sm" wire:click.prevent="createUser">Create</button>
        </form>

        <hr>
        @foreach($users as $user)
            <p>{{ $user->name }}</p>
        @endforeach

        {{ $users->links()}}
        
    </div>
</div>
 