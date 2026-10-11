@php
    $sortIcon = fn ($f) => $sortBy === $f ? ($sortDirection === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-unfold-more-horizontal text-muted';
    $statusMap = [0 => ['Pending', 'warning'], 1 => ['Confirmed', 'info'], 2 => ['Ongoing', 'primary'], 3 => ['Completed', 'success'], 4 => ['Declined', 'danger']];
    $noDoc = ['', '/storage', '/storage/'];
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Bookings</h4>
            <button wire:click="exportCsv" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-download me-1"></i> Export CSV</button>
        </div>
    </div>

    {{-- Summary cards --}}
    @foreach ([
        ['All bookings', number_format($stats['total']), 'mdi-calendar-multiple', 'secondary', ''],
        ['Pending', number_format($stats['pending']), 'mdi-clock-outline', 'warning', '0'],
        ['Ongoing', number_format($stats['ongoing']), 'mdi-car-connected', 'primary', '2'],
        ['Completed', number_format($stats['completed']), 'mdi-check-circle', 'success', '3'],
        ['Declined', number_format($stats['declined']), 'mdi-close-circle', 'danger', '4'],
        ['Paid revenue', '₦' . number_format($stats['revenue'], 2), 'mdi-cash-multiple', 'info', ''],
    ] as [$label, $value, $icon, $color, $filterVal])
        <div class="col-6 col-lg-2">
            <div class="card" @if($filterVal !== '') wire:click="$set('statusFilter', '{{ $statusFilter === $filterVal ? '' : $filterVal }}')" style="cursor:pointer;" @elseif($label === 'All bookings') wire:click="$set('statusFilter', '')" style="cursor:pointer;" @endif>
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-{{ $color }} bg-soft text-white d-flex align-items-center justify-content-center me-2 flex-shrink-0" style="width:40px;height:40px;">
                            <i class="mdi {{ $icon }} font-size-20"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-muted mb-0 font-size-12">{{ $label }}</p>
                            <h6 class="mb-0 text-truncate">{{ $value }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label font-size-12 mb-1">Search</label>
                        <input type="search" wire:model.debounce.500ms="search" class="form-control form-control-sm"
                            placeholder="Customer, vehicle, plate, owner or booking #">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Booking status</label>
                        <select wire:model="statusFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="0">Pending</option>
                            <option value="1">Confirmed</option>
                            <option value="2">Ongoing</option>
                            <option value="3">Completed</option>
                            <option value="4">Declined</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Payment</label>
                        <select wire:model="paymentFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="1">Paid</option>
                            <option value="0">Unpaid</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Type</label>
                        <select wire:model="typeFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="booking">Booking</option>
                            <option value="ehailing">E-hailing</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Car owner</label>
                        <select wire:model="ownerFilter" class="form-select form-control form-control-sm">
                            <option value="">All owners</option>
                            @foreach ($owners as $o)
                                <option value="{{ $o->id }}">{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($isAdmin)
                        <div class="col-6 col-md-2">
                            <label class="form-label font-size-12 mb-1">Station</label>
                            <select wire:model="stationFilter" class="form-select form-control form-control-sm">
                                <option value="">All stations</option>
                                @foreach ($stations as $st)
                                    <option value="{{ $st->id }}">{{ $st->stationName }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Booked from</label>
                        <input type="date" wire:model="startDate" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Booked to</label>
                        <input type="date" wire:model="endDate" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Rows per page</label>
                        <select wire:model="limit" class="form-select form-control form-control-sm">
                            <option value="10">10</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        @if ($filtersActive)
                            <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100"><i class="mdi mdi-filter-remove-outline me-1"></i> Clear</button>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 text-muted font-size-13">
                    <span>Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }} of {{ number_format($bookings->total()) }} bookings</span>
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Vehicle</th>
                                <th>Owner / Station</th>
                                <th role="button" wire:click="sort('pickupDate')">Trip <i class="mdi {{ $sortIcon('pickupDate') }}"></i></th>
                                <th role="button" class="text-end" wire:click="sort('amount')">Amount <i class="mdi {{ $sortIcon('amount') }}"></i></th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th role="button" wire:click="sort('created_at')">Created <i class="mdi {{ $sortIcon('created_at') }}"></i></th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                @php
                                    [$label, $color] = $statusMap[(int) $booking->status] ?? [$booking->status, 'secondary'];
                                    $active = in_array((int) $booking->status, [1, 2], true);
                                    $overdue = $active && $booking->dropoffDate && \Carbon\Carbon::parse($booking->dropoffDate)->isPast();
                                    $idCard = optional($booking->user)->identity_card;
                                    $license = optional($booking->user)->driverLicense;
                                @endphp
                                <tr wire:key="booking-{{ $booking->id }}">
                                    <td>#{{ $booking->id }}</td>
                                    <td>
                                        <strong>{{ $booking->user->name ?? 'Deleted user' }}</strong>
                                        <div class="text-muted font-size-12">{{ $booking->user->email ?? '' }}</div>
                                        <div class="text-muted font-size-12">{{ $booking->user->phone_no ?? '' }}</div>
                                        <div class="font-size-12">
                                            @if (!in_array($idCard, $noDoc, true)) <a href="{{ $idCard }}" target="_blank">ID card</a> @endif
                                            @if (!in_array($license, $noDoc, true)) · <a href="{{ $license }}" target="_blank">License</a> @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->vehicle->vehicleMake ?? '' }} {{ $booking->vehicle->vehicleModel ?? '' }}</strong>
                                        <div class="text-muted font-size-12">
                                            {{ $booking->vehicle->vehicleYear ?? '' }}
                                            @if (optional($booking->vehicle)->vehicleID) · {{ $booking->vehicle->vehicleID }} @endif
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $booking->type === 'ehailing' ? 'E-hailing' : 'Booking' }}</span>
                                        @if (optional($booking->vehicle)->on_trip == 1)
                                            <span class="badge bg-warning">Vehicle on trip</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $booking->vehicle->user->name ?? '—' }}
                                        <div class="text-muted font-size-12">{{ $booking->vehicle->station->stationName ?? '' }}</div>
                                    </td>
                                    <td class="font-size-13">
                                        @if ($booking->type === 'ehailing')
                                            <i class="mdi mdi-map-marker text-success"></i> {{ $booking->pickup_location }}
                                            <br><i class="mdi mdi-flag-checkered text-danger"></i> {{ $booking->dropoff_location }}
                                        @else
                                            {{ optional($booking->pickupDate ? \Carbon\Carbon::parse($booking->pickupDate) : null)->format('d M Y') }}
                                            → {{ optional($booking->dropoffDate ? \Carbon\Carbon::parse($booking->dropoffDate) : null)->format('d M Y') }}
                                            @if ($booking->pickup_location)
                                                <div class="text-muted font-size-12"><i class="mdi mdi-map-marker"></i> {{ $booking->pickup_location }}</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-end">&#8358;{{ number_format((float) $booking->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->payment_status == 1 ? 'success' : 'danger' }}">{{ $booking->payment_status == 1 ? 'Paid' : 'Unpaid' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        @if ($overdue) <span class="badge bg-danger">Overdue</span> @endif
                                        @if ((int) $booking->status === 4 && $booking->reason)
                                            <div class="text-muted font-size-12 mt-1" title="{{ $booking->reason }}">{{ \Illuminate\Support\Str::limit($booking->reason, 40) }}</div>
                                        @endif
                                    </td>
                                    <td title="{{ optional($booking->created_at)->format('d M Y H:i') }}">
                                        {{ optional($booking->created_at)->format('d M Y') }}
                                        <div class="text-muted font-size-12">{{ optional($booking->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        @if ((int) $booking->status === 0)
                                            <button class="btn btn-success btn-sm" wire:click="approve({{ $booking->id }})"
                                                wire:loading.attr="disabled" wire:target="approve({{ $booking->id }})"
                                                @if ($booking->payment_status != 1) title="Awaiting payment" @endif>
                                                <i class="fa fa-check"></i> Approve
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" wire:click="confirmDecline({{ $booking->id }})">
                                                <i class="fa fa-times"></i> Decline
                                            </button>
                                        @elseif ($active)
                                            <button class="btn btn-warning btn-sm"
                                                onclick="confirm('End this booking and mark the vehicle as available?') || event.stopImmediatePropagation()"
                                                wire:click="end({{ $booking->id }})">
                                                <i class="fa fa-flag-checkered"></i> End booking
                                            </button>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="mdi mdi-calendar-search font-size-24 d-block mb-1"></i> No bookings match your filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>

    {{-- Decline dialog --}}
    @if ($decliningId)
        <div style="position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;display:flex;align-items:center;justify-content:center;padding:16px;">
            <div class="card mb-0" style="width:100%;max-width:460px;">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Decline booking #{{ $decliningId }}</h5>
                    <button type="button" class="btn-close" wire:click="cancelDecline"></button>
                </div>
                <div class="card-body">
                    <label class="form-label">Reason <small class="text-muted">(shown to the customer)</small></label>
                    <textarea wire:model.defer="declineReason" rows="3" class="form-control @error('declineReason') is-invalid @enderror"
                        placeholder="e.g. Vehicle is under maintenance"></textarea>
                    @error('declineReason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <p class="text-muted font-size-12 mt-2 mb-0">If the booking was already paid, the payment is not refunded automatically.</p>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-light btn-sm me-2" wire:click="cancelDecline">Cancel</button>
                        <button class="btn btn-danger btn-sm" wire:click="decline" wire:loading.attr="disabled" wire:target="decline">Decline booking</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
