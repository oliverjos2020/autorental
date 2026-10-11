<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">My Vehicles</h4>
            <div>
                <button wire:click="exportCsv" class="btn btn-outline-secondary btn-sm me-1"><i class="mdi mdi-download me-1"></i> Export CSV</button>
                <a href="/register-vehicle" class="btn btn-primary btn-sm"><i class="mdi mdi-plus-circle-outline me-1"></i> Add Vehicle</a>
            </div>
        </div>
    </div>

    {{-- Fleet Overview Cards --}}
    @foreach ([
        ['Total Vehicles', $stats['total'], 'mdi-car-multiple', 'primary', '', ''],
        ['Car Owners', $stats['owners'], 'mdi-account-key', 'secondary', '', ''],
        ['On Trip', $stats['onTrip'], 'mdi-car-connected', 'warning', 'tripFilter', '1'],
        ['Available', $stats['available'], 'mdi-check-circle', 'success', 'tripFilter', '0'],
        ['Unpublished', $stats['unpublished'], 'mdi-eye-off', 'danger', 'publicationFilter', 'pending'],
    ] as [$label, $value, $icon, $color, $filterKey, $filterVal])
        <div class="col-6 col-lg">
            <div class="card" @if($filterKey) wire:click="$set('{{ $filterKey }}', '{{ $$filterKey === $filterVal ? '' : $filterVal }}')" style="cursor:pointer;" @endif>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2 text-muted">{{ $label }}</p>
                            <h4 class="mb-0 text-{{ $color }}">{{ $value }}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <div class="avatar-sm bg-soft-{{ $color }} rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                <i class="mdi {{ $icon }} font-size-20 text-{{ $color }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @php
        $sortIcon = fn ($f) => $sortBy === $f ? ($sortDirection === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-unfold-more-horizontal text-muted';
        $statusMap = [0 => ['Pending', 'secondary'], 1 => ['Active', 'primary'], 2 => ['Approved', 'success'], 3 => ['Declined', 'danger']];
    @endphp
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label font-size-12 mb-1">Search</label>
                        <input type="search" wire:model.debounce.500ms="search" class="form-control form-control-sm" placeholder="Make, model, plate or owner">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label font-size-12 mb-1">Car owner</label>
                        <select wire:model="ownerFilter" class="form-select form-control form-control-sm">
                            <option value="">All owners</option>
                            @foreach ($owners as $o)
                                <option value="{{ $o->id }}">{{ $o->name }}{{ $o->business_name ? ' — ' . $o->business_name : '' }}</option>
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
                        <label class="form-label font-size-12 mb-1">Category</label>
                        <select wire:model="categoryFilter" class="form-select form-control form-control-sm">
                            <option value="">All categories</option>
                            @foreach ($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Trip status</label>
                        <select wire:model="tripFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="0">Available</option>
                            <option value="1">On trip</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Approval</label>
                        <select wire:model="statusFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="0">Pending</option>
                            <option value="1">Active</option>
                            <option value="2">Approved</option>
                            <option value="3">Declined</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Listing</label>
                        <select wire:model="publicationFilter" class="form-select form-control form-control-sm">
                            <option value="">All</option>
                            <option value="published">Published</option>
                            <option value="pending">Unpublished</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Rows per page</label>
                        <select wire:model="limit" class="form-select form-control form-control-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        @if ($filtersActive)
                            <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100"><i class="mdi mdi-filter-remove-outline me-1"></i> Clear</button>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 text-muted font-size-13">
                    <span>Showing {{ $vehicles->firstItem() ?? 0 }}–{{ $vehicles->lastItem() ?? 0 }} of {{ number_format($vehicles->total()) }} vehicles</span>
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 text-muted font-size-13">
                    <span>Showing {{ $vehicles->firstItem() ?? 0 }}–{{ $vehicles->lastItem() ?? 0 }} of {{ number_format($vehicles->total()) }} vehicles</span>
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th role="button" wire:click="sort('vehicleMake')">Vehicle <i class="mdi {{ $sortIcon('vehicleMake') }}"></i></th>
                                <th>Owner</th>
                                <th>Station</th>
                                <th>Specs</th>
                                <th>Booking price</th>
                                <th>Approval</th>
                                <th role="button" wire:click="sort('on_trip')">Trip status <i class="mdi {{ $sortIcon('on_trip') }}"></i></th>
                                <th role="button" class="text-center" wire:click="sort('total_bookings')">Bookings <i class="mdi {{ $sortIcon('total_bookings') }}"></i></th>
                                <th role="button" wire:click="sort('created_at')">Added <i class="mdi {{ $sortIcon('created_at') }}"></i></th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicles as $vehicle)
                                @php [$statusLabel, $statusColor] = $statusMap[(int) $vehicle->status] ?? [$vehicle->status, 'secondary']; @endphp
                                <tr wire:key="vehicle-{{ $vehicle->id }}">
                                    <td>{{ $vehicles->firstItem() + $loop->index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ url($vehicle->photos->first()->image_path ?? 'logo/auto-logo.png') }}" alt="{{ $vehicle->vehicleMake }}"
                                                class="rounded me-2" style="width:56px;height:40px;object-fit:cover;">
                                            <div>
                                                <strong>{{ $vehicle->vehicleMake }} {{ $vehicle->vehicleModel }}</strong>
                                                <div class="text-muted font-size-12">
                                                    {{ $vehicle->vehicleYear }}
                                                    @if ($vehicle->vehicleID) · {{ $vehicle->vehicleID }} @endif
                                                    @if ($vehicle->category_name) · {{ $vehicle->category_name }} @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($vehicle->user)
                                            <a href="/profile/{{ $vehicle->user->id }}" class="fw-semibold">{{ $vehicle->user->name }}</a>
                                            <div class="text-muted font-size-12">{{ $vehicle->user->business_name ?: $vehicle->user->phone_no }}</div>
                                        @else
                                            <span class="text-muted">Unknown</span>
                                        @endif
                                    </td>
                                    <td>{{ $vehicle->station->stationName ?? '—' }}</td>
                                    <td class="font-size-13">
                                        <span class="badge bg-{{ $vehicle->transmission == 'automatic' ? 'warning' : 'danger' }}">{{ $vehicle->transmission }}</span>
                                        <div class="text-muted font-size-12 mt-1">
                                            {{ $vehicle->seats }} seats
                                            @if ($vehicle->airCondition === 'yes') · A/C @endif
                                        </div>
                                    </td>
                                    <td>@if (optional($vehicle->priceSetup)->amount) &#8358;{{ number_format((float) $vehicle->priceSetup->amount, 2) }} @else — @endif</td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                        @if ($vehicle->publication_status === 'published')
                                            <div class="font-size-12 text-success mt-1"><i class="mdi mdi-eye"></i> Published</div>
                                        @else
                                            <div class="font-size-12 text-muted mt-1"><i class="mdi mdi-eye-off"></i> Unpublished</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($vehicle->on_trip == 1)
                                            <span class="badge bg-warning">On trip</span>
                                            @if ($vehicle->current_renter)
                                                <div class="font-size-12 text-muted mt-1">
                                                    {{ $vehicle->current_renter }}
                                                    @if ($vehicle->current_dropoff) · back {{ \Carbon\Carbon::parse($vehicle->current_dropoff)->format('d M') }} @endif
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge bg-success">Available</span>
                                        @endif
                                    </td>
                                    <td class="text-center"><strong>{{ $vehicle->total_bookings }}</strong></td>
                                    <td title="{{ optional($vehicle->created_at)->format('d M Y H:i') }}">{{ optional($vehicle->created_at)->format('d M Y') }}</td>
                                    <td class="text-end text-nowrap">
                                        <a class="btn btn-outline-primary btn-sm" href="/editVehicles/{{ $vehicle->id }}"><i class="fa fa-edit"></i> Edit</a>
                                        <button class="btn btn-outline-danger btn-sm"
                                            onclick="confirm('Delete {{ e(addslashes($vehicle->vehicleMake . ' ' . $vehicle->vehicleModel)) }}? This cannot be undone.') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $vehicle->id }})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-5">
                                        <i class="mdi mdi-car-search font-size-24 d-block mb-1"></i> No vehicles match your filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $vehicles->links() }}</div>
            </div>
        </div>
    </div>
</div>
