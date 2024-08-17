<div>
    <div class="container text-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="ic text-primary fas fa-home"></i> Review</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Review</li>
            </ol>
        </nav>
    </div>
    
    
    <div class="b-steps d-none d-sm-block">
        <div class="container">
            <div class="b-steps__item">
                <button class="b-steps__btn bg-primary" type="button">1</button><span class="b-steps__info">Vehicle
                    Selection</span>
            </div>
            <div class="b-steps__item">
                <button class="b-steps__btn bg-primary" type="button">2</button><span class="b-steps__info">Add
                    Extras</span>
            </div>
            <div class="b-steps__item">
                <button class="b-steps__btn bg-primary" type="button">3</button><span class="b-steps__info">Review &
                    Book</span>
            </div>
        </div>
    </div>
    {{-- <div class="l-main-content"> --}}
 
        <div class="container">
            <main>
                <section class="b-goods-f">
                    <div class="ui-subtitle">Vehicle Details</div>
                    <h1 class="ui-title text-uppercase">{{$vehicle->vehicleMake}} {{$vehicle->vehicleModel}}
                        {{$vehicle->vehicleYear}}</h1>
                    <div class="ui-decor bg-primary"></div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div wire:ignore class="b-goods-f__slider">
                                <div class="ui-slider-main js-slider-for" wire:ignore>
                                    
                                    @foreach($vehicle->photos as $photo)
                                    {{-- <img class="img-scale" src="{{asset($photo->image_path)}}"
                                        alt="{{$photo->image_path}}" /> --}}
                                    <img wire:key="image-{{ $photo->id }}" class="img-scale"
                                        src="{{asset($photo->image_path)}}" alt="{{$photo->image_path}}" />
                                    @endforeach
                                </div>
                                <div class="ui-slider-nav js-slider-nav">
                                    @foreach($vehicle->photos as $photo)
                                    {{-- <img class="img-scale" src="{{asset($photo->image_path)}}"
                                        alt="{{$photo->image_path}}" /> --}}
                                    <img wire:key="image-{{ $photo->id }}" class="img-scale"
                                        src="{{asset($photo->image_path)}}" alt="{{$photo->image_path}}" />
                                    @endforeach
                                </div>
                            </div>

                            <div class="b-goods-f-checks d-none d-sm-block">
                                <div class="b-goods-f-checks__section">
                                    <div class="b-goods-f-checks__title text-primary">Vehicle Category</div>
                                    <div class="row no-gutters justify-content-between">
                                        <div class="col-sm-auto">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-inputx" checked="checked" id="customCheck1"
                                                    type="checkbox" />
                                                <label class="custom-control-labelx"
                                                    for="customCheck1">{{$vehicle->priceSetup->item}} / day</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto b-goods-f-checks__price">${{
                                            number_format($vehicle->priceSetup->amount, 2, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="b-goods-f-checks__section">
                                    <div class="b-goods-f-checks__title text-primary">Days of hire</div>
                                    <div class="row no-gutters justify-content-between">
                                        <div class="col-sm-auto">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-inputx" id="customCheck2"
                                                    type="checkbox" />
                                                <label class="custom-control-labelx" for="customCheck2">
                                                    <div wire:listen="daysCalculated">
                                                        <p id="days">{{ $days }} day(s)</p>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-auto b-goods-f-checks__price">${{ number_format($days *
                                            $vehicle->priceSetup->amount, 2, ',', '.')}} </div>
                                    </div>

                                </div>

                            </div>
                            
                        </div>
                        <div class="col-lg-4">
                            
                                <aside class="l-sidebar mt-4 mt-lg-0">
                                    <div class="widget section-sidebar bg-gray">
                                        <h3
                                            class="widget-title bg-dark row justify-content-between align-items-center no-gutters">
                                            <i class="ic flaticon-car-2 bg-primary col-auto"></i><span
                                                class="widget-title__inner col">Your Reservation</span>
                                        </h3>
                                        <div class="widget-content">
                                            <div class="widget-card">
                                                @if(auth()->check())
                                                <div class="widget-card-descr">
                                                    <div class="widget-card-descr__item">
                                                        <div class="widget-card-descr__title">Vehicle Pickup</div>
                                                        <div class="widget-card-descr__info">
                                                            {{$vehicle->location}} </div>
                                                    </div> 
                                                    <div class="widget-card-descr__item">
                                                        <div class="widget-card-descr__title">Vehicle Drop Off</div>
                                                        <div class="widget-card-descr__info">{{$vehicle->location}}</div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="pickupDate">Pick-up Date</label>
                                                            <input type="date" wire:model="pickupDate" min="{{ date('Y-m-d') }}"
                                                                placeholder="Pick-up date" class="review-input">
                                                                @error('pickupDate')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="pickupTime">Pick-up Time</label>
                                                            <input type="time" pattern="hh:mm a" wire:model="pickupTime" 
                                                                placeholder="Pick-up Time" class="review-input">
                                                                @error('pickupTime')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="dropOff Date">Drop-off Date</label>
                                                            <input type="date" wire:model="dropoffDate" min="{{ date('Y-m-d') }}"
                                                                placeholder="Drop-off date" class="review-input"
                                                                wire:click="calculate">
                                                                @error('dropoffDate')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="dropOffTime">Drop-off Time</label>
                                                            <input type="time" pattern="hh:mm a" wire:model="dropoffTime"
                                                                placeholder="Drop-off Time" class="review-input">
                                                                @error('dropoffTime')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="dropOffTime">Drivers License</label>
                                                            <input type="file" wire:model="driversLicense"
                                                            accept="image/jpg, image/jpeg, image/png" class="review-input">
                                                            <br>
                                                            {{-- @if ($driversLicense)
                                                                <img src="{{ $driversLicense->temporaryUrl() }}" class="image mt-3" style="max-width: 300px">
                                                            @endif --}}
                                                            @if ($driversLicense instanceof \Livewire\TemporaryUploadedFile)
                                                            <img src="{{ $driversLicense->temporaryUrl() }}" class="image mt-3" style="max-width: 300px">
                                                            @elseif ($existingdriversLicense)
                                                            <img src="{{ $existingdriversLicense }}" class="image mt-3" style="max-width: 300px">
                                                            @endif
                                                            <br>
                                                            @error('driversLicense')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="widget-card-number no-gutter widget-card-descr__item">
                                                        <div class="b-filter__row">
                                                            <label for="dropOffTime">Insurance</label>
                                                            <input type="file" wire:model="insurance"
                                                            accept="image/jpg, image/jpeg, image/png" class="review-inputx">
                                                            <br>
                                                            {{-- @if ($insurance)
                                                                <img src="{{ $insurance->temporaryUrl() }}" class="image mt-3" style="max-width: 300px">
                                                            @endif --}}
                                                            @if ($insurance instanceof \Livewire\TemporaryUploadedFile)
                                                            <img src="{{ $insurance->temporaryUrl() }}" class="image mt-3" style="max-width: 300px">
                                                            @elseif ($existingInsurance)
                                                            <img src="{{ $existingInsurance }}" class="image mt-3" style="max-width: 300px">
                                                            @endif
                                                            <br>
                                                            @error('insurance')
                                                                    <span style="color:red" class="text-danger"> {{ $message }} </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <a style="color:#fff" class="btn btn-primary text-light btn-lg d-none d-sm-block" wire:click="proceed"> Proceed</a>
                                                @else
                                                <a style="color:#fff" class="btn btn-primary text-light btn-lg d-none d-sm-block" href="/login"> Login to continue</a>
                                                @endif
                                                {{-- <br> --}}
                                                {{-- <div id="paypal-button-container" wire:ignore></div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="b-goods-f-checks d-md-none">
                                        <div class="b-goods-f-checks__section">
                                            <div class="b-goods-f-checks__title text-primary">Vehicle Category</div>
                                            <div class="row no-gutters justify-content-between">
                                                <div class="col-sm-auto">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-inputx" checked="checked" id="customCheck1"
                                                            type="checkbox" />
                                                        <label class="custom-control-labelx"
                                                            for="customCheck1">{{$vehicle->priceSetup->item}} / day</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-auto b-goods-f-checks__price">${{
                                                    number_format($vehicle->priceSetup->amount, 2, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <div class="b-goods-f-checks__section">
                                            <div class="b-goods-f-checks__title text-primary">Days of hire</div>
                                            <div class="row no-gutters justify-content-between">
                                                <div class="col-sm-auto">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-inputx" checked="checked" id="customCheck1"
                                                            type="checkbox" />
                                                        <label class="custom-control-labelx" for="customCheck2">
                                                            <div wire:listen="daysCalculated">
                                                                <p id="days">{{ $days }} day(s)</p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-auto b-goods-f-checks__price">${{ number_format($days *
                                                    $vehicle->priceSetup->amount, 2, ',', '.')}} </div>
                                            </div>
        
                                        </div>
                                        
                                        @if(auth()->check())
                                        <a style="color:#fff" class="btn btn-primary btn-lg d-sm-none" wire:click="proceed"> Proceed</a>
                                        @else
                                        <a style="color:#fff" class="btn btn-primary btn-lg d-sm-none" href="/login">Login to proceed</a>
                                        @endif
                                    </div>
                                </aside>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            
                        </div>

                    </div>
                </section>
            </main>
        </div>
</div>
{{-- <script src="https://www.paypal.com/sdk/js?client-id=AYhy25KmTjDNZDCvrmriP4PfzNf1xY939tywQcyG90wOETn_OnZ_ef9nCGlOwNABLWzclfJRkIHOGOk8&components=buttons,funding-eligibility"></script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
