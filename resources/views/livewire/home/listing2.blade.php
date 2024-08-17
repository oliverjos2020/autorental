<div>
    <div class="container text-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="home.html"><i class="ic text-primary fas fa-home"></i> Vehicle
                        Listing</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Select Vehicle</li>
            </ol>
        </nav>
    </div>
    <div class="b-steps">
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
                <button class="b-steps__btn bg-primary" type="button" disabled="disabled">3</button><span
                    class="b-steps__info">Review & Book</span>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                <aside class="l-sidebar">
                    <div class="widget section-sidebar bg-gray">
                        <h3 class="widget-title bg-dark row justify-content-between align-items-center no-gutters">
                            <i class="ic flaticon-porsche bg-primary col-auto"></i><span
                                class="widget-title__inner col">Search Filters</span>
                        </h3>
                        <div class="widget-content">
                            <div class="widget-inner">
                                <form class="b-filter">
                                    <div class="b-filter__main">
                                        <div class="b-filter__row">

                                            <select wire:model="make" class="home-input" data-width="100%">
                                                <option value="">Select Make</option>
                                                @forelse($brands as $brand)
                                                <option value="{{ $brand->slug }}">{{$brand->brand}}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="b-filter__row">
                                            <select wire:model="transmission" class="home-input" data-width="100%">
                                                <option value="">Select Transmission</option>
                                                <option value="automatic">Automatic</option>
                                                <option value="manual">Manual</option>
                                            </select>
                                        </div>
                                        <div class="b-filter__row">
                                            <select wire:model="category" class="home-input" data-width="100%">
                                                <option value="">Select Vehicle Category</option>
                                                @forelse($categories as $category)
                                                <option value="{{ $category->id }}">{{$category->item}}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="b-filter__row">
                                            <select wire:model="location" class="home-input" data-width="100%">
                                                <option value="">Select Pickup Location</option>
                                                @forelse($locations as $location)
                                                <option value="{{ $location->slug }}">{{$location->location}}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                        <div class="b-filter__row">
                                            <select wire:model="hire" class="home-input" data-width="100%">
                                                <option value="">Select Hire Type</option>
                                                @forelse($hireTypes as $hire)
                                                <option value="{{ $hire->id }}">{{$hire->category}}</option>
                                                @empty

                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <button wire:click.prevent="resetButton"
                                        class="b-filter__reset btn btn-default btn-lg w-100">Reset Filters</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
            <div class="col-xl-9">
                <div class="b-filter-goods">
                    <div class="row align-items-center no-gutters justify-content-between">
                        <div class="b-filter-goods__wrap col-sm-auto">
                            <div class="b-filter-goods__select">
                                <a href="#"><i class="btns-switch__item js-view-th active  ic fa fa-th"></i></a>
                                {{-- <select class="selectpicker" data-width="170px" title="Newest First"
                                    multiple="multiple" data-max-options="1" data-style="ui-select ui-select_sm">
                                    <option>A-Z</option>
                                    <option>Z-A</option>
                                </select> --}}
                            </div>
                            <div class="b-filter-goods__select">
                                <select class="home-input" wire:model="limit" style="border: 1px solid #ddd;">
                                    <option value="12" selected="selected">12 Items</option>
                                    <option value="15">15 Items</option>
                                    <option value="20">20 Items</option>
                                    <option value="30">30 Items</option>
                                    <option value="40">40 Items</option>
                                    <option value="50">50 Items</option>
                                </select>
                            </div>
                            {{-- <button class="b-filter-goods__btn btn" type="button">Compare<span
                                    class="badge bg-primary">2</span></button> --}}
                                    
                        </div>
                        <div class="btns-switch col-auto">
                            {{-- <a href="inventory-listings.html"><i
                                    class="btns-switch__item js-view-list  ic fa fa-th-list"></i></a> --}}
                            {{-- <a href="#"><i class="btns-switch__item js-view-th active  ic fa fa-th"></i></a> --}}
                            <div class="text-center">
                                <div class="my-2">
                                    {{ $vehicles->links()}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="b-filter-goods__info">Showing results 1 to 10 of total 145</div> --}}
                </div>
                <!-- end .b-filter-goods-->

                <div class="section-goods">
                    <div class="b-goods-group row">
                        @forelse($vehicles as $vehicle)
                        <div class="col-lg-4 col-md-6">
                            <div class="b-goods b-goods_back_sm">
                                <button class="flip-btn"><span></span><span
                                        class="flip-btn-mdl"></span><span></span></button>
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="flip__front">
                                            <div class="b-goods__img"><img class="img-scale"
                                                    src="{{ $vehicle->photos->first()->image_path }}"
                                                    alt="{{ $vehicle->vehicleMake }}" /></div>
                                            <div class="b-goods__inner">
                                                <div class="b-goods__header">
                                                    <div class="b-goods__name"><a href="/review/{{$vehicle->id}}">{{$vehicle->vehicleMake}}
                                                        {{$vehicle->vehicleModel}}</a></div>
                                                    <ul class="ui-rating list-unstyled">
                                                        <li class="active"><i class="fas fa-star"></i></li>
                                                        <li class="active"><i class="fas fa-star"></i></li>
                                                        <li class="active"><i class="fas fa-star"></i></li>
                                                        <li class="active"><i class="fas fa-star"></i></li>
                                                        <li><i class="fas fa-star"></i></li>
                                                    </ul>
                                                    <div class="b-goods__check"><img class="img-fluid"
                                                            src="{{asset( $vehicle->photos->first()->image_path )}}"
                                                            alt="{{ $vehicle->vehicleMake }}" /></div>
                                                </div>
                                                <div class="b-goods__info"></div>
                                                <div class="b-goods__footer">
                                                    <div class="b-goods__main-descr">
                                                        {{-- <i class="ic flaticon-speedometer"></i> --}}
                                                        ${{ number_format($vehicle->priceSetup->amount, 2, ',', '.') }}
                                                    </div>
                                                    <div class="b-goods__price">
                                                        <div class="b-goods__price-main bg-primary"
                                                            style="font-size:13px">
                                                            {{$vehicle->priceSetup->item}}</div>
                                                        <div class="b-goods__price-msrp">MSRP: $35,480</div>
                                                    </div>
                                                    <ul class="b-goods-descr list-unstyled">
                                                        <li class="b-goods-descr__item b-goods-descr__item_main"><i
                                                                class="ic flaticon-speedometer"></i>53,000 mi</li>
                                                        <li class="b-goods-descr__item">{{$vehicle->doors}} Doors</li>
                                                        <li class="b-goods-descr__item">{{$vehicle->transmission}}</li>
                                                        <li class="b-goods-descr__item">{{$vehicle->seats}} Seats </li>
                                                        <li class="b-goods-descr__item">{{$vehicle->vehicleYear}}</li>
                                                    </ul>
                                                    <button class="b-goods__link text-primary"
                                                        type="button">Compare</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flip__back">
                                            <div class="b-goods__header">
                                                <div class="b-goods__name">{{$vehicle->vehicleMake}}
                                                    {{$vehicle->vehicleModel}}</div>
                                                <div class="b-goods__category">{{ $vehicle->priceSetup->item }}</div>
                                                <div class="flip-btn-hide"></div>
                                            </div>
                                            <div class="b-goods-info">
                                                <div class="b-goods-info__item row no-gutters justify-content-between">
                                                    <span class="b-goods-info__title col-auto">Cruise Control
                                                        Option</span><span
                                                        class="b-goods-info__desc col-auto">Standard</span>
                                                </div>
                                                <div class="b-goods-info__item row no-gutters justify-content-between">
                                                    <span class="b-goods-info__title col-auto">Transmission</span><span
                                                        class="b-goods-info__desc col-auto">{{$vehicle->transmission}}</span>
                                                </div>
                                                <div class="b-goods-info__item row no-gutters justify-content-between">
                                                    <span class="b-goods-info__title col-auto">Air Condition</span><span
                                                        class="b-goods-info__desc col-auto">{{$vehicle->airCondition}}</span>
                                                </div>
                                                <div class="b-goods-info__item row no-gutters justify-content-between">
                                                    <span class="b-goods-info__title col-auto">Price</span><span
                                                        class="b-goods-info__desc col-auto">{{
                                                        $vehicle->priceSetup->amount
                                                        }} / hour</span>
                                                </div>
                                                <div class="b-goods-info__item row no-gutters justify-content-between">
                                                    <span class="b-goods-info__title col-auto">Doors</span><span
                                                        class="b-goods-info__desc col-auto">{{$vehicle->doors}}</span>
                                                </div>
                                            </div>
                                            <div class="flip__footer">
                                                <button class="btn btn-primary btn-lg w-100">Read More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end .b-goods-->

                        </div>
                        @empty
                        <div class="alert alert-danger">No vehicle has been listed</div>
                        @endforelse
                    </div>
                    <div class="text-center">
                        <div class="my-2">
                            {{ $vehicles->links()}}
                        </div>
                    </div>
                    {{-- <nav class="mt-3" aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-left"></i></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fas fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav> --}}
                </div>
            </div>
        </div>
    </div>
</div>