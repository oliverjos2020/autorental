<div>
    @if(\Session::has('error'))
    <div class="text-center" style="text-align: center !important; background: #cc4040; color: #fff; padding: 5px;">
        {{ \Session::get('error') }}
    </div>
    {{ \Session::forget('error') }}
    @endif


    @if(\Session::has('success'))
    <div class="text-center" style="text-align: center !important; background: #40cc6c; color: #fff; padding: 5px;">
        {{ \Session::get('success') }}
    </div>
    {{ \Session::forget('success') }}
    @endif

    <div class="container text-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/"><i class="ic text-primary fas fa-home"></i> Checkout</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>
    </div>

    <div class="container" style="margin-bottom:30px;">
        <main>
            <div class="row">
                <div class="col-md-12">
                    <section class="b-goods-f__section">
                        <h2 class="b-goods-f__title2">Pricing Details</h2>
                        <div class="table-responsive">
                            @forelse($orders as $order)
                            <table class="b-goods-f__table table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Make</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Pickup/Dropoff Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            {{-- <img src="{{asset('img/cars/c8.jpg')}}" style="max-height: 80px"> --}}
                                            <img src="{{asset( $order->vehicle->photos->first()->image_path )}}"
                                                style="max-height: 80px" alt="{{ $order->vehicle->vehicleMake }}" />
                                        </td>
                                        <td>
                                            {{$order->vehicle->vehicleMake}}
                                        </td>
                                        <td>
                                            {{$order->vehicle->vehicleModel}}
                                        </td>
                                        <td>
                                            {{$order->vehicle->vehicleYear}}
                                        </td>
                                        <td>
                                            {{$order->vehicle->location}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Pickup date {{$order->pickupDate}}
                                                {{$order->pickupTime}}</strong></td>
                                        <td colspan="2"><strong>Dropoff date {{$order->dropoffDate}}
                                                {{$order->dropoffTime}}</strong></td>
                                        <td><strong>Location: {{$order->vehicle->location}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Category: {{$order->vehicle->priceSetup->item}}
                                            </strong></td>
                                        <td colspan="2"><strong>Days of hire: {{ $order->duration }} </strong></td>
                                        <td><strong>Total Amount: ${{ number_format($order->amount, 2, ',', '.')}}
                                            </strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <button type="submit" style="background:red; color:white;"
                                                class="btn btn-danger btn-sm"
                                                wire:click="deleteOrder({{ $order->id }})">Delete</button>
                                        </td>
                                    </tr>
                                    </tfoot>
                            </table>
                            @empty

                            @endforelse
                        </div>
                    </section>
                    @if(count($orders) > 0)
                    <form method="GET" action="{{ route('processPaypal') }}">
                        @csrf
                        <input type="hidden" name="amount" value="{{$totalAmount}}">
                        <div class="text-right">
                            <a style="color:#fff" class="btn btn-secondary btn-sm mr-sm-2 mb-1 mb-sm-0"
                                href="/review/{{$reviewId}}">Cancel</a>
                            <button class="btn btn-primary btn-sm">Pay With Paypal ({{$totalAmount}})</button>
                        </div>
                    </form>
                    @endif
                </div>
                

                <div id="paypal-button-container"></div>

                <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_SANDBOX_CLIENT_ID') }}"></script>
            </div>
        </main>
    </div>
</div>
{{-- <script
    src="https://www.paypal.com/sdk/js?client-id=AYhy25KmTjDNZDCvrmriP4PfzNf1xY939tywQcyG90wOETn_OnZ_ef9nCGlOwNABLWzclfJRkIHOGOk8&components=buttons,funding-eligibility">
</script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>