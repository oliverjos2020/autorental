<div>
    <section class="banner-header section-padding bg-img" data-overlay-dark="5" data-background="{{ asset('img/slider/1.jpg')}}">
        <div class="v-middle">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h6>Select Your Car</h6>
                        <h1>Listing</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- divider line -->
    <div class="line-vr-section"></div>
    <section class="cars4 section-padding">
        <div class="container" style="max-width:1400px !important;">
            <div class="cars1">
                <div class="row">
                    <div class="col-md-3">
                        <div class="col-lg-12 col-md-12 mb-30">
                            <div class="sidebar-list" style="max-width:350px;">
                                <div class="search">
                                    <form>
                                        <input type="text" wire:model.live.debounce.500ms="search"
                                            placeholder="Search car brand ...">
                                        {{-- <button type="submit"><i class="ti-search text-light"
                                                aria-hidden="true"></i></button> --}}
                                    </form>
                                </div>
                                <div class="item">
                                    <h5>Select Brand</h5>
                                    <div class="filter-radio-group">
                                        @forelse($brands as $brand)
                                        <div class="form-group">
                                            <input type="radio" id="{{$brand->id}}" name="radio-group_one"
                                                value="{{$brand->slug}}"
                                                wire:click="filterByBrand('{{ $brand->slug }}')">
                                            <label for="{{$brand->id}}">{{$brand->brand}}</label>
                                        </div>
                                        @empty

                                        @endforelse

                                    </div>
                                    <h5>Car Type</h5>
                                    <div class="filter-radio-group">
                                        @forelse($categories as $category)
                                        <div class="form-group">
                                            <input type="radio" id="c-{{$category->id}}" name="radio-group_two"
                                                value="{{$category->slug}}">
                                            <label for="c-{{$category->id}}">{{$category->item}}</label>
                                        </div>
                                        @empty

                                        @endforelse

                                    </div>
                                    <h5>Transmission</h5>
                                    <div class="filter-radio-group">
                                        <div class="form-group">
                                            <input type="radio" id="test12" name="radio-group_three" value="automatic">
                                            <label for="test12">Automatic</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="radio" id="test13" name="radio-group_three" value="manual">
                                            <label for="test13">Manual</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">

                            @forelse($vehicles as $vehicle)

                            <div class="col-lg-6 col-md-12 mb-60">
                                <div class="item">
                                    <div class="img">
                                        @if($vehicle->photos->isNotEmpty())
                                        <img src="{{ $vehicle->photos->first()->image_path }}"
                                            alt="{{ $vehicle->vehicleMake }}">
                                        @else
                                        <img src="https://th.bing.com/th/id/R.8582e4dbeeaa8b7b442b4457b22f263b?rik=8S0VImIdrKDEGA&pid=ImgRaw&r=0"
                                            alt="Default Image">
                                        @endif
                                    </div>
                                    <div class="con active">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="title"><a href="#">{{$vehicle->vehicleMake}}
                                                        {{$vehicle->vehicleModel}}</a></div>
                                                <div class="details"> <span><i class="omfi-door"></i>
                                                        {{$vehicle->doors}} Doors</span> <span><i
                                                            class="omfi-transmission"></i>
                                                        {{$vehicle->transmission}}</span> <span><i
                                                            class="omfi-passengers"></i> {{$vehicle->passengers}}
                                                        Passengers</span></div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="book">
                                                    <div><a href="car-details.html" class="btn"><span>Details</span></a>
                                                    </div>
                                                    <div><span class="price">${{ $vehicle->priceSetup->amount }}</span><span>/day</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="alert alert-danger">No vehicle has been listed</div>
                            @endforelse

                            <div class="text-center">
                                <div class="my-2">
                                    {{ $vehicles->links()}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
    </section>
</div>