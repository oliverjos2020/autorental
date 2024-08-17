<div>
    <div class="container">
        <div class="card" style="margin:0px auto; margin-top:50px; max-width:600px;">
            <div class="card-header">
                <h4>Create New Account</h4>
            </div>
            <div class="card-body">
                @if(session('message'))
                <span class="bg-success p-2 text-light">{{session('message')}}</span>
                @endif
                <form>
                <div class="form-group">
                    <label for="name" class="font-weight-bold">Name</label>
                    <input type="text" placeholder="name..." wire:model="name" class="form-control">
                    @error('name')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="font-weight-bold" >Email</label>
                    <input type="text" wire:model="email" placeholder="email..." class="form-control">
                    @error('email')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password" class="font-weight-bold">Password</label>
                    <input type="password" wire:model="password" placeholder="password..." class="form-control">
                    @error('password')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="profile" class="font-weight-bold">Profile Picture</label>
                    <input type="file" wire:model="image" accept="image/jpg, image/jpeg, image/png" placeholder="Profile Picture..." class="form-control">
                    
                    @error('image')
                    <span class="text-danger"> {{ $message }} </span>
                    @enderror
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="image">
                    @endif
                    <div wire:loading wire:target="image">

                    </div>

                </div>
                <button type="submit" class="btn btn-dark btn-md" wire:click.prevent="createUser">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
