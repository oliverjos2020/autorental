<div>
    <div class="container card p-4" style="max-width:400px; margin:0 auto; margin-top:150px;">
        <div>
            @if(session()->has('error'))
                <div class="text-danger">{{session('error')}}</div>
            @endif
            <label for="location">Your Location</label>
            <input type="text" class="form-control" id="location" wire:model.live.debounce.500ms="location">
            <ul>
                @foreach($suggestionsLocation as $suggestion1)
                    <li wire:click="selectSuggestion('location', '{{ $suggestion1['description'] }}')">{{ $suggestion1['description'] }}</li>
                @endforeach
            </ul>
            
        </div>

        <div>
            <label for="destination">Destination</label>
            <input type="text" class="form-control" id="destination" wire:model.live.debounce.500ms="destination">
            <ul>
                @foreach($suggestionsDestination as $suggestion2)
                    <li wire:click="selectSuggestion('destination', '{{ $suggestion2['description'] }}')">{{ $suggestion2['description'] }}</li>
                @endforeach
            </ul>
        </div>

        <button wire:click="redirectToResults" class="btn btn-primary">Book Ride</button>
    </div>
</div>
