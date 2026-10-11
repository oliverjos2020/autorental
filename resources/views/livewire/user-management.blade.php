@php
    $forming = $showCreateForm || $editingID;
    $currentRole = $editingID ? $editingRole : $role;
    $needsPayout = in_array((int) $currentRole, $payoutRoles, true);
    $isAdmin = Auth::user()->role_id == 1;
    $roleColors = [1 => 'danger', 2 => 'warning', 3 => 'info', 4 => 'secondary', 5 => 'success', 6 => 'primary', 7 => 'primary', 8 => 'primary'];
    $sortIcon = fn ($f) => $sortField === $f ? ($sortDirection === 'asc' ? 'mdi-arrow-up' : 'mdi-arrow-down') : 'mdi-unfold-more-horizontal text-muted';
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">User Management</h4>
            <div>
                <button wire:click="exportCsv" class="btn btn-secondary btn-sm me-1">
                    <i class="mdi mdi-download me-1"></i> Export CSV
                </button>
                <button wire:click="toggleCreateForm" class="btn {{ $forming ? 'btn-secondary' : 'btn-light' }} btn-sm">
                    @if ($forming)
                        <i class="mdi mdi-close me-1"></i> Cancel
                    @else
                        <i class="mdi mdi-plus-circle-outline me-1"></i> Create User
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="col-12">
        <div class="row">
            @foreach ([
                ['Total users', $stats['total'], 'mdi-account-multiple', 'primary', ''],
                ['New this month', $stats['new'], 'mdi-account-plus', 'success', ''],
                ['Payout ready', $stats['ready'], 'mdi-bank', 'info', 'ready'],
                ['Missing payout account', $stats['missing'], 'mdi-bank-remove', 'danger', 'missing'],
            ] as [$label, $value, $icon, $color, $filter])
                <div class="col-6 col-lg-3">
                    <div class="card mb-3" @if($filter) wire:click="$set('payoutFilter', '{{ $payoutFilter === $filter ? '' : $filter }}')" style="cursor:pointer;" @endif>
                        <div class="card-body d-flex align-items-center py-3">
                            <div class="rounded-circle text-white bg-{{ $color }} bg-soft d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;">
                                <i class="mdi {{ $icon }} font-size-22 text-white"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0 font-size-13">{{ $label }}</p>
                                <h4 class="mb-0">{{ number_format($value) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Create / edit form --}}
    @if ($forming)
        <div class="col-12">
            <div class="card border-primary">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="mdi {{ $editingID ? 'mdi-account-edit' : 'mdi-account-plus' }} me-1"></i>
                        {{ $editingID ? 'Edit User' : 'Create User' }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="{{ $editingID ? 'cancelEdit' : 'toggleCreateForm' }}"></button>
                </div>
                <div class="card-body">
                    <h6 class="text-uppercase text-muted font-size-12 mb-3">Account details</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full name</label>
                            <input type="text" wire:model.defer="{{ $editingID ? 'editingName' : 'name' }}"
                                class="form-control @error($editingID ? 'editingName' : 'name') is-invalid @enderror" placeholder="e.g. Ada Okafor">
                            @error($editingID ? 'editingName' : 'name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model.defer="{{ $editingID ? 'editingEmail' : 'email' }}"
                                class="form-control @error($editingID ? 'editingEmail' : 'email') is-invalid @enderror" placeholder="name@example.com">
                            @error($editingID ? 'editingEmail' : 'email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Role</label>
                            <select wire:model="{{ $editingID ? 'editingRole' : 'role' }}"
                                class="form-select form-control @error($editingID ? 'editingRole' : 'role') is-invalid @enderror">
                                <option value="">Select role</option>
                                @if ($isAdmin)
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->id }}">{{ $r->role }}</option>
                                    @endforeach
                                @else
                                    <option value="3">Driver</option>
                                @endif
                            </select>
                            @error($editingID ? 'editingRole' : 'role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Station</label>
                            <select wire:model.defer="{{ $editingID ? 'editingStation' : 'station' }}"
                                class="form-select form-control @error($editingID ? 'editingStation' : 'station') is-invalid @enderror">
                                <option value="">Select station</option>
                                @if ($isAdmin)
                                    @foreach ($stations as $st)
                                        <option value="{{ $st->id }}">{{ $st->stationName }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ Auth::user()->station_id }}">{{ Auth::user()->station->stationName ?? '' }}</option>
                                @endif
                            </select>
                            @error($editingID ? 'editingStation' : 'station') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        @if (!$editingID)
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Password <small class="text-muted">(min 8 characters)</small></label>
                                <div class="input-group">
                                    <input type="password" id="newUserPassword" wire:model.defer="password"
                                        class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="var i=document.getElementById('newUserPassword');i.type=i.type==='password'?'text':'password';">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($needsPayout)
                        <hr>
                        <h6 class="text-uppercase text-muted font-size-12 mb-2">Payout details</h6>
                        <div class="alert alert-info py-2 font-size-13">
                            <i class="mdi mdi-information-outline me-1"></i>
                            A Paystack subaccount is created from these details. Bookings pay this account its share automatically.
                            @if ($editingID && $editingHasSubaccount)
                                <br><strong>This user already has a subaccount.</strong> Changing bank details here does not update it on Paystack.
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Business / account name</label>
                                <input type="text" wire:model.defer="{{ $editingID ? 'editingBusinessName' : 'business_name' }}"
                                    class="form-control @error($editingID ? 'editingBusinessName' : 'business_name') is-invalid @enderror" placeholder="Business name">
                                @error($editingID ? 'editingBusinessName' : 'business_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5 mb-3">
                                <label class="form-label">Bank</label>
                                <select wire:model.defer="{{ $editingID ? 'editingBankCode' : 'bank_code' }}"
                                    class="form-select form-control @error($editingID ? 'editingBankCode' : 'bank_code') is-invalid @enderror">
                                    <option value="">Select bank</option>
                                    @include('livewire.partials.bank-options')
                                </select>
                                @error($editingID ? 'editingBankCode' : 'bank_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Account number</label>
                                <input type="text" inputmode="numeric" maxlength="10" wire:model.defer="{{ $editingID ? 'editingAccountNumber' : 'account_number' }}"
                                    class="form-control @error($editingID ? 'editingAccountNumber' : 'account_number') is-invalid @enderror" placeholder="10 digits">
                                @error($editingID ? 'editingAccountNumber' : 'account_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mt-2">
                        <button class="btn btn-light btn-sm me-2" wire:click.prevent="{{ $editingID ? 'cancelEdit' : 'toggleCreateForm' }}">Cancel</button>
                        <button class="btn btn-primary btn-sm" wire:click.prevent="{{ $editingID ? 'update' : 'createUser' }}" wire:loading.attr="disabled">
                            <span wire:loading wire:target="{{ $editingID ? 'update' : 'createUser' }}" class="spinner-border spinner-border-sm me-1"></span>
                            {{ $editingID ? 'Save changes' : 'Create user' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Report --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label font-size-12 mb-1">Search</label>
                        <input type="search" wire:model.debounce.500ms="search" placeholder="Name, email, phone or business"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Role</label>
                        <select wire:model="roleFilter" class="form-select form-control form-control-sm">
                            <option value="">All roles</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r->id }}">{{ $r->role }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($isAdmin)
                        <div class="col-6 col-md-2">
                            <label class="form-label font-size-12 mb-1">Station</label>
                            <select wire:model="stationFilter" class="form-select form-control form-control-sm">
                                <option value="">All stations</option>
                                <option value="none">No station</option>
                                @foreach ($stations as $st)
                                    <option value="{{ $st->id }}">{{ $st->stationName }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Payout account</label>
                        <select wire:model="payoutFilter" class="form-select form-control form-control-sm">
                            <option value="">Any</option>
                            <option value="ready">Has subaccount</option>
                            <option value="missing">Missing (payout roles)</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Email verified</label>
                        <select wire:model="verifiedFilter" class="form-select form-control form-control-sm">
                            <option value="">Any</option>
                            <option value="yes">Verified</option>
                            <option value="no">Not verified</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Registered from</label>
                        <input type="date" wire:model="dateFrom" class="form-control form-control-sm">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label font-size-12 mb-1">Registered to</label>
                        <input type="date" wire:model="dateTo" class="form-control form-control-sm">
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
                            <button wire:click="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="mdi mdi-filter-remove-outline me-1"></i> Clear filters
                            </button>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 text-muted font-size-13">
                    <span>
                        Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ number_format($users->total()) }}
                        {{ $filtersActive ? 'matching' : '' }} users
                    </span>
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th role="button" wire:click="sortBy('name')">Name <i class="mdi {{ $sortIcon('name') }}"></i></th>
                                <th role="button" wire:click="sortBy('email')">Contact <i class="mdi {{ $sortIcon('email') }}"></i></th>
                                <th role="button" wire:click="sortBy('role_id')">Role <i class="mdi {{ $sortIcon('role_id') }}"></i></th>
                                <th>Station</th>
                                <th>Payout</th>
                                <th role="button" wire:click="sortBy('created_at')">Registered <i class="mdi {{ $sortIcon('created_at') }}"></i></th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                @php $isPayoutRole = in_array((int) $user->role_id, $payoutRoles, true); @endphp
                                <tr wire:key="user-{{ $user->id }}">
                                    <td>{{ $users->firstItem() + $loop->index }}</td>
                                    <td>
                                        <a href="/profile/{{ $user->id }}" class="fw-semibold">{{ $user->name }}</a>
                                        @if ($user->business_name)
                                            <div class="text-muted font-size-12">{{ $user->business_name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                        @if ($user->email_verified_at)
                                            <i class="mdi mdi-check-decagram text-success" title="Email verified"></i>
                                        @endif
                                        @if ($user->phone_no)
                                            <div class="text-muted font-size-12">{{ $user->phone_no }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $roleColors[$user->role_id] ?? 'secondary' }}">{{ $user->role->role ?? 'No role' }}</span>
                                    </td>
                                    <td>{{ $user->station->stationName ?? '—' }}</td>
                                    <td>
                                        @if ($user->account_code)
                                            <span class="badge bg-success">Subaccount</span>
                                            <code class="text-muted">{{ $user->account_code }}</code>
                                        @elseif ($isPayoutRole)
                                            <span class="badge bg-danger">Missing</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td title="{{ optional($user->created_at)->format('d M Y H:i') }}">
                                        {{ optional($user->created_at)->format('d M Y') }}
                                        <div class="text-muted font-size-12">{{ optional($user->created_at)->diffForHumans() }}</div>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <button class="btn btn-outline-primary btn-sm" wire:click="edit({{ $user->id }})"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-outline-danger btn-sm"
                                            onclick="confirm('Delete {{ e(addslashes($user->name)) }}? This cannot be undone.') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $user->id }})"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="mdi mdi-account-search font-size-24 d-block mb-1"></i>
                                        No users match your filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</div>
