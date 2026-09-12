<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">User Management</h4>

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
                    <label>Name</label>
                    <input type="text" wire:model="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name">
                    @error('name')
                        <div class="invalid-feedback"> {{ $message }} </div>
                    @enderror

                </div>
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" wire:model="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="Email">
                    @error('email')
                        <span class="invalid-feedback"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <label for="role">Role</label>
                    <select wire:model="role" class="form-control {{ $errors->has('role') ? 'is-invalid' : '' }}">
                        @if(Auth::user()->role_id == 1)
                            <option value="">Select Role</option>
                            @forelse($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @empty
                            @endforelse
                        @else
                        <option value="">Select Role</option>
                            <option value="3">Driver</option>
                        @endif
                    </select>
                    @error('role')
                    <span class="invalid-feedback"> {{ $message }} </span>
                    @enderror
                </div>

                @if(Auth::user()->role_id == 1)
                <div class="form-group mt-3">
                    <label for="station">Station</label>
                    <select wire:model="station" class="form-control {{ $errors->has('station') ? 'is-invalid' : '' }}">
                            <option value="">Select Station</option>
                        @forelse($stations as $station)
                            <option value="{{ $station->id }}">{{ $station->stationName }}</option>
                        @empty
                        @endforelse
                    </select>
                    @error('station')
                    <span class="invalid-feedback"> {{ $message }} </span>
                    @enderror
                </div>
                @else
                    <div class="form-group mt-3">
                        <label for="station">Station </label>
                        <select wire:model="station" class="form-control {{ $errors->has('station') ? 'is-invalid' : '' }}">
                            <option value="">Select Station</option>
                                <option value="{{Auth::user()->station_id ?? ''}}">{{Auth::user()->station->stationName ?? ''}}</option>
                        </select>
                        @error('station')
                        <span class="invalid-feedback"> {{ $message }} </span>
                        @enderror
                    </div>
                @endif
                @if($owner == 6)
                    <div class="form-group mt-3">
                        <label>Business Name</label>
                        <input type="text" wire:model="business_name" class="form-control {{ $errors->has('business_name') ? 'is-invalid' : '' }}" placeholder="Name">
                        @error('business_name')
                            <span class="invalid-feedback"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label>Select Bank</label>
                        <select wire:model="bank_code" class="form-control {{ $errors->has('bank_code') ? 'is-invalid' : '' }}">
                            <option value="">Select bank</option>
                                                <option value="120001">9mobile 9Payment Service Bank</option>
                                                <option value="801">Abbey Mortgage Bank</option>
                                                <option value="51204">Above Only MFB</option>
                                                <option value="51312">Abulesoro MFB</option>
                                                <option value="044">Access Bank</option>
                                                <option value="063">Access Bank (Diamond)</option>
                                                <option value="602">Accion Microfinance Bank</option>
                                                <option value="50036">Ahmadu Bello University Microfinance Bank</option>
                                                <option value="120004">Airtel Smartcash PSB</option>
                                                <option value="51336">AKU Microfinance Bank</option>
                                                <option value="035A">ALAT by WEMA</option>
                                                <option value="50926">Amju Unique MFB</option>
                                                <option value="51341">AMPERSAND MICROFINANCE BANK</option>
                                                <option value="50083">Aramoko MFB</option>
                                                <option value="401">ASO Savings and Loans</option>
                                                <option value="MFB50094">Astrapolaris MFB LTD</option>
                                                <option value="51229">Bainescredit MFB</option>
                                                <option value="50117">Banc Corp Microfinance Bank</option>
                                                <option value="50931">Bowen Microfinance Bank</option>
                                                <option value="FC40163">Branch International Financial Services Limited
                                                </option>
                                                <option value="565">Carbon</option>
                                                <option value="865">CASHCONNECT MFB</option>
                                                <option value="50823">CEMCS Microfinance Bank</option>
                                                <option value="50171">Chanelle Microfinance Bank Limited</option>
                                                <option value="023">Citibank Nigeria</option>
                                                <option value="50910">Consumer Microfinance Bank</option>
                                                <option value="50204">Corestep MFB</option>
                                                <option value="559">Coronation Merchant Bank</option>
                                                <option value="FC40128">County Finance Limited</option>
                                                <option value="51297">Crescent MFB</option>
                                                <option value="50162">Dot Microfinance Bank</option>
                                                <option value="050">Ecobank Nigeria</option>
                                                <option value="50263">Ekimogun MFB</option>
                                                <option value="098">Ekondo Microfinance Bank</option>
                                                <option value="50126">Eyowo</option>
                                                <option value="51318">Fairmoney Microfinance Bank</option>
                                                <option value="070">Fidelity Bank</option>
                                                <option value="51314">Firmus MFB</option>
                                                <option value="011">First Bank of Nigeria</option>
                                                <option value="214">First City Monument Bank</option>
                                                <option value="107">FirstTrust Mortgage Bank Nigeria</option>
                                                <option value="50315">FLOURISH MFB</option>
                                                <option value="501">FSDH Merchant Bank Limited</option>
                                                <option value="812">Gateway Mortgage Bank LTD</option>
                                                <option value="00103">Globus Bank</option>
                                                <option value="100022">GoMoney</option>
                                                <option value="50739">Goodnews Microfinance Bank</option>
                                                <option value="562">Greenwich Merchant Bank</option>
                                                <option value="058">Guaranty Trust Bank</option>
                                                <option value="51251">Hackman Microfinance Bank</option>
                                                <option value="50383">Hasal Microfinance Bank</option>
                                                <option value="030">Heritage Bank</option>
                                                <option value="120002">HopePSB</option>
                                                <option value="51244">Ibile Microfinance Bank</option>
                                                <option value="50439">Ikoyi Osun MFB</option>
                                                <option value="50442">Ilaro Poly Microfinance Bank</option>
                                                <option value="50457">Infinity MFB</option>
                                                <option value="301">Jaiz Bank</option>
                                                <option value="50502">Kadpoly MFB</option>
                                                <option value="082">Keystone Bank</option>
                                                <option value="50200">Kredi Money MFB LTD</option>
                                                <option value="50211">Kuda Bank</option>
                                                <option value="90052">Lagos Building Investment Company Plc.</option>
                                                <option value="50549">Links MFB</option>
                                                <option value="031">Living Trust Mortgage Bank</option>
                                                <option value="303">Lotus Bank</option>
                                                <option value="50563">Mayfair MFB</option>
                                                <option value="50304">Mint MFB</option>
                                                <option value="50515">Moniepoint MFB</option>
                                                <option value="120003">MTN Momo PSB</option>
                                                <option value="00107">Optimus Bank Limited</option>
                                                <option value="100002">Paga</option>
                                                <option value="999991">PalmPay</option>
                                                <option value="104">Parallex Bank</option>
                                                <option value="311">Parkway - ReadyCash</option>
                                                <option value="999992">Paycom</option>
                                                <option value="50743">Peace Microfinance Bank</option>
                                                <option value="51146">Personal Trust MFB</option>
                                                <option value="50746">Petra Mircofinance Bank Plc</option>
                                                <option value="076">Polaris Bank</option>
                                                <option value="50864">Polyunwana MFB</option>
                                                <option value="105">PremiumTrust Bank</option>
                                                <option value="101">Providus Bank</option>
                                                <option value="51293">QuickFund MFB</option>
                                                <option value="502">Rand Merchant Bank</option>
                                                <option value="90067">Refuge Mortgage Bank</option>
                                                <option value="51286">Rigo Microfinance Bank Limited</option>
                                                <option value="50767">ROCKSHIELD MICROFINANCE BANK</option>
                                                <option value="125">Rubies MFB</option>
                                                <option value="51113">Safe Haven MFB</option>
                                                <option value="951113">Safe Haven Microfinance Bank Limited</option>
                                                <option value="50582">Shield MFB</option>
                                                <option value="51062">Solid Allianze MFB</option>
                                                <option value="50800">Solid Rock MFB</option>
                                                <option value="51310">Sparkle Microfinance Bank</option>
                                                <option value="221">Stanbic IBTC Bank</option>
                                                <option value="068">Standard Chartered Bank</option>
                                                <option value="51253">Stellas MFB</option>
                                                <option value="232">Sterling Bank</option>
                                                <option value="100">Suntrust Bank</option>
                                                <option value="50968">Supreme MFB</option>
                                                <option value="302">TAJ Bank</option>
                                                <option value="090560">Tanadi Microfinance Bank</option>
                                                <option value="51269">Tangerine Money</option>
                                                <option value="51211">TCF MFB</option>
                                                <option value="102">Titan Bank</option>
                                                <option value="100039">Titan Paystack</option>
                                                <option value="50840">U&amp;C Microfinance Bank Ltd (U AND C MFB)</option>
                                                <option value="MFB51322">Uhuru MFB</option>
                                                <option value="50870">Unaab Microfinance Bank Limited</option>
                                                <option value="50871">Unical MFB</option>
                                                <option value="51316">Unilag Microfinance Bank</option>
                                                <option value="032">Union Bank of Nigeria</option>
                                                <option value="033">United Bank For Africa</option>
                                                <option value="215">Unity Bank</option>
                                                <option value="566">VFD Microfinance Bank Limited</option>
                                                <option value="51355">Waya Microfinance Bank</option>
                                                <option value="035">Wema Bank</option>
                                                <option value="057">Zenith Bank</option>
                                                <option value="057">Zenith Bank</option>
                        </select>
                        @error('bank_code')
                            <span class="invalid-feedback"> {{ $message }} </span>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="accountNumber">Account Number</label>
                        <input type="number" class="form-control  {{ $errors->has('account_number') ? 'is-invalid' : '' }}" placeholder="Account Number" wire:model="account_number">
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="accountNumber">Percentage (1 - 100) </label>
                        <input type="number" class="form-control  {{ $errors->has('percentage_charge') ? 'is-invalid' : '' }}"
                            id="accountNumber" placeholder="Percentage" wire:model="percentage_charge">
                        @error('percentage_charge')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="password" wire:model="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password">
                    @error('password')
                    <span class="invalid-feedback"> {{ $message }} </span>
                    @enderror
                </div>


                <button class="btn btn-primary btn-sm mt-3" wire:click.prevent="createUser">
                    Create User
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
                        <input type="search" wire:model.live.debounce.500ms="search" placeholder="Search by name..."
                            class="form-control form-control-sm mt-2">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Station</th>
                                <th>Registered</th>
                                <th>Edit</th>
                                <th>Delete</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td><a href="/profile/{{ $user->id }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->role }}</td>
                                <td>{{ $user->station->stationName ?? 'No station assigned' }}</td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                <td><a class="btn btn-primary btn-sm text-light" style="cursor:pointer;"
                                        wire:click="edit({{$user->id}})"><i class="fa fa-edit"></i> Edit</a> </td>
                                <td><a class="text-light btn btn-danger btn-sm"
                                        wire:click="delete({{$user->id}})"><i class="fa fa-trash"></i>
                                        Delete</a></a></td>
                            </tr>

                            @if($editingID === $user->id)
                            <tr>
                                <td colspan="8">
                                    <input type="text" wire:model="editingName" placeholder="Name"
                                        class="form-control mx-1">
                                    @error('editingName')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <input type="text" wire:model="editingEmail" placeholder="Email"
                                        class="form-control mx-1">
                                    @error('editingEmail')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <select wire:model="editingRole" class="form-control">
                                        <option value="">Select Role</option>
                                        @if(Auth::user()->role_id == 1)
                                            @forelse($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                                            @empty
                                            @endforelse
                                        @else
                                            <option value="3">Driver</option>
                                        @endif
                                                    </select>
                                    @error('editingRole')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">

                                        <select wire:model="editingStation" class="form-control">
                                            <option value="">Select station</option>
                                            @if(Auth::user()->role_id == 1)
                                                @forelse($stations as $station)
                                                    <option value="{{ $station->id }}">{{ $station->stationName }}</option>
                                                @empty
                                                @endforelse
                                            @else
                                                <option value="{{Auth::user()->station_id}}">{{Auth::user()->station->stationName}}</option>
                                            @endif
                                        </select>

                                    @error('editingStation')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <input type="text" wire:model="editingBusinessName" placeholder="Business name"
                                        class="form-control mx-1">
                                    @error('editingBusinessName')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <div class="form-group mt-3">
                                        <label>Select Bank</label>
                                        <select wire:model="editingBankCode" class="form-control {{ $errors->has('editingBankCode') ? 'is-invalid' : '' }}">
                                            <option value="">Select bank</option>
                                                                <option value="120001">9mobile 9Payment Service Bank</option>
                                                                <option value="801">Abbey Mortgage Bank</option>
                                                                <option value="51204">Above Only MFB</option>
                                                                <option value="51312">Abulesoro MFB</option>
                                                                <option value="044">Access Bank</option>
                                                                <option value="063">Access Bank (Diamond)</option>
                                                                <option value="602">Accion Microfinance Bank</option>
                                                                <option value="50036">Ahmadu Bello University Microfinance Bank</option>
                                                                <option value="120004">Airtel Smartcash PSB</option>
                                                                <option value="51336">AKU Microfinance Bank</option>
                                                                <option value="035A">ALAT by WEMA</option>
                                                                <option value="50926">Amju Unique MFB</option>
                                                                <option value="51341">AMPERSAND MICROFINANCE BANK</option>
                                                                <option value="50083">Aramoko MFB</option>
                                                                <option value="401">ASO Savings and Loans</option>
                                                                <option value="MFB50094">Astrapolaris MFB LTD</option>
                                                                <option value="51229">Bainescredit MFB</option>
                                                                <option value="50117">Banc Corp Microfinance Bank</option>
                                                                <option value="50931">Bowen Microfinance Bank</option>
                                                                <option value="FC40163">Branch International Financial Services Limited
                                                                </option>
                                                                <option value="565">Carbon</option>
                                                                <option value="865">CASHCONNECT MFB</option>
                                                                <option value="50823">CEMCS Microfinance Bank</option>
                                                                <option value="50171">Chanelle Microfinance Bank Limited</option>
                                                                <option value="023">Citibank Nigeria</option>
                                                                <option value="50910">Consumer Microfinance Bank</option>
                                                                <option value="50204">Corestep MFB</option>
                                                                <option value="559">Coronation Merchant Bank</option>
                                                                <option value="FC40128">County Finance Limited</option>
                                                                <option value="51297">Crescent MFB</option>
                                                                <option value="50162">Dot Microfinance Bank</option>
                                                                <option value="050">Ecobank Nigeria</option>
                                                                <option value="50263">Ekimogun MFB</option>
                                                                <option value="098">Ekondo Microfinance Bank</option>
                                                                <option value="50126">Eyowo</option>
                                                                <option value="51318">Fairmoney Microfinance Bank</option>
                                                                <option value="070">Fidelity Bank</option>
                                                                <option value="51314">Firmus MFB</option>
                                                                <option value="011">First Bank of Nigeria</option>
                                                                <option value="214">First City Monument Bank</option>
                                                                <option value="107">FirstTrust Mortgage Bank Nigeria</option>
                                                                <option value="50315">FLOURISH MFB</option>
                                                                <option value="501">FSDH Merchant Bank Limited</option>
                                                                <option value="812">Gateway Mortgage Bank LTD</option>
                                                                <option value="00103">Globus Bank</option>
                                                                <option value="100022">GoMoney</option>
                                                                <option value="50739">Goodnews Microfinance Bank</option>
                                                                <option value="562">Greenwich Merchant Bank</option>
                                                                <option value="058">Guaranty Trust Bank</option>
                                                                <option value="51251">Hackman Microfinance Bank</option>
                                                                <option value="50383">Hasal Microfinance Bank</option>
                                                                <option value="030">Heritage Bank</option>
                                                                <option value="120002">HopePSB</option>
                                                                <option value="51244">Ibile Microfinance Bank</option>
                                                                <option value="50439">Ikoyi Osun MFB</option>
                                                                <option value="50442">Ilaro Poly Microfinance Bank</option>
                                                                <option value="50457">Infinity MFB</option>
                                                                <option value="301">Jaiz Bank</option>
                                                                <option value="50502">Kadpoly MFB</option>
                                                                <option value="082">Keystone Bank</option>
                                                                <option value="50200">Kredi Money MFB LTD</option>
                                                                <option value="50211">Kuda Bank</option>
                                                                <option value="90052">Lagos Building Investment Company Plc.</option>
                                                                <option value="50549">Links MFB</option>
                                                                <option value="031">Living Trust Mortgage Bank</option>
                                                                <option value="303">Lotus Bank</option>
                                                                <option value="50563">Mayfair MFB</option>
                                                                <option value="50304">Mint MFB</option>
                                                                <option value="50515">Moniepoint MFB</option>
                                                                <option value="120003">MTN Momo PSB</option>
                                                                <option value="00107">Optimus Bank Limited</option>
                                                                <option value="100002">Paga</option>
                                                                <option value="999991">PalmPay</option>
                                                                <option value="104">Parallex Bank</option>
                                                                <option value="311">Parkway - ReadyCash</option>
                                                                <option value="999992">Paycom</option>
                                                                <option value="50743">Peace Microfinance Bank</option>
                                                                <option value="51146">Personal Trust MFB</option>
                                                                <option value="50746">Petra Mircofinance Bank Plc</option>
                                                                <option value="076">Polaris Bank</option>
                                                                <option value="50864">Polyunwana MFB</option>
                                                                <option value="105">PremiumTrust Bank</option>
                                                                <option value="101">Providus Bank</option>
                                                                <option value="51293">QuickFund MFB</option>
                                                                <option value="502">Rand Merchant Bank</option>
                                                                <option value="90067">Refuge Mortgage Bank</option>
                                                                <option value="51286">Rigo Microfinance Bank Limited</option>
                                                                <option value="50767">ROCKSHIELD MICROFINANCE BANK</option>
                                                                <option value="125">Rubies MFB</option>
                                                                <option value="51113">Safe Haven MFB</option>
                                                                <option value="951113">Safe Haven Microfinance Bank Limited</option>
                                                                <option value="50582">Shield MFB</option>
                                                                <option value="51062">Solid Allianze MFB</option>
                                                                <option value="50800">Solid Rock MFB</option>
                                                                <option value="51310">Sparkle Microfinance Bank</option>
                                                                <option value="221">Stanbic IBTC Bank</option>
                                                                <option value="068">Standard Chartered Bank</option>
                                                                <option value="51253">Stellas MFB</option>
                                                                <option value="232">Sterling Bank</option>
                                                                <option value="100">Suntrust Bank</option>
                                                                <option value="50968">Supreme MFB</option>
                                                                <option value="302">TAJ Bank</option>
                                                                <option value="090560">Tanadi Microfinance Bank</option>
                                                                <option value="51269">Tangerine Money</option>
                                                                <option value="51211">TCF MFB</option>
                                                                <option value="102">Titan Bank</option>
                                                                <option value="100039">Titan Paystack</option>
                                                                <option value="50840">U&amp;C Microfinance Bank Ltd (U AND C MFB)</option>
                                                                <option value="MFB51322">Uhuru MFB</option>
                                                                <option value="50870">Unaab Microfinance Bank Limited</option>
                                                                <option value="50871">Unical MFB</option>
                                                                <option value="51316">Unilag Microfinance Bank</option>
                                                                <option value="032">Union Bank of Nigeria</option>
                                                                <option value="033">United Bank For Africa</option>
                                                                <option value="215">Unity Bank</option>
                                                                <option value="566">VFD Microfinance Bank Limited</option>
                                                                <option value="51355">Waya Microfinance Bank</option>
                                                                <option value="035">Wema Bank</option>
                                                                <option value="057">Zenith Bank</option>
                                                                <option value="057">Zenith Bank</option>
                                        </select>
                                        @error('editingBankCode')
                                            <span class="invalid-feedback"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <input type="text" wire:model="editingAccountNumber" placeholder="Account number"
                                        class="form-control mx-1">
                                    @error('editingAccountNumber')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    <input type="text" wire:model="editingPercentageCharge" placeholder="Percentage charge"
                                        class="form-control mx-1">
                                    @error('editingPercentageCharge')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                            
                            
                            <tr>
                                <td colspan="8">
                                    <button type="submit" wire:click="update"
                                        class="btn btn-success btn-sm">Update</button> <button type="submit"
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
                            {{ $users->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
