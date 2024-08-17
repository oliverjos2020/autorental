{{-- <div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18">Pay</h4>
    
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <!-- <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li> -->
                        <!-- <li class="breadcrumb-item active">Welcome to Tax Drive Dashboard</li> -->
                    </ol>
                </div>
    
            </div>
        </div>
    <form id="payment-form" wire:submit.prevent="createOrder">
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="text" id="amount" wire:model="amount" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Pay with PayPal</button>
    </form>

    <div id="paypal-button-container"></div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&components=buttons,hosted-fields&currency=USD"></script>
    <script>
        $(document).ready(function() {
            $('#payment-form').on('submit', function(event) {
                event.preventDefault();

                var amount = $('#amount').val();
                $.ajax({
                    // url: "{{ route('createOrder') }}",
                    method: "POST",
                    data: { amount: amount },
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    success: function(data) {
                        paypal.HostedFields.render({
                            createOrder: function() {
                                return data.id;
                            },
                            styles: {
                                'input': {
                                    'font-size': '16px',
                                    'color': '#3A3A3A'
                                },
                                '.valid': {
                                    'color': '#0C0'
                                },
                                '.invalid': {
                                    'color': '#C00'
                                }
                            },
                            fields: {
                                number: {
                                    selector: '#card-number',
                                    placeholder: '4111 1111 1111 1111'
                                },
                                cvv: {
                                    selector: '#cvv',
                                    placeholder: '123'
                                },
                                expirationDate: {
                                    selector: '#expiration-date',
                                    placeholder: 'MM/YY'
                                }
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    $.ajax({
                                        url: "{{ route('onApprove') }}",
                                        method: "POST",
                                        data: details,
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        success: function() {
                                            alert('Payment successful!');
                                        }
                                    });
                                });
                            }
                        }).then(function(hostedFieldsInstance) {
                            $('#submit').on('click', function(event) {
                                event.preventDefault();
                                hostedFieldsInstance.submit({
                                    // Optional fields you may include with your submit call
                                    cardholderName: $('#card-holder-name').val()
                                });
                            });
                        });
                    }
                });
            });
        });
    </script>
</div> --}}
