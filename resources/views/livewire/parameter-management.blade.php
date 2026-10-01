<div>
{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- PAGE TITLE                                                       --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">
                <i class="mdi mdi-cog-outline me-1"></i> System Parameters
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard2">Dashboard</a></li>
                    <li class="breadcrumb-item active">System Parameters</li>
                </ol>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- ADMIN AUTH GATE                                                  --}}
{{-- ════════════════════════════════════════════════════════════════ --}}
@if (!$authenticated)
<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-lg border-0" style="border-radius:16px; overflow:hidden;">
            {{-- Gradient header --}}
            <div style="background: linear-gradient(135deg, #1a237e 0%, #3949ab 50%, #5c6bc0 100%); padding: 2rem; text-align:center;">
                <div style="width:72px;height:72px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                    <i class="mdi mdi-shield-lock-outline" style="font-size:2.2rem;color:#fff;"></i>
                </div>
                <h4 style="color:#fff;margin:0;font-weight:700;letter-spacing:.5px;">Admin Verification</h4>
                <p style="color:rgba(255,255,255,.75);font-size:.88rem;margin-top:.4rem;">
                    System Parameters are restricted to administrators only.
                </p>
            </div>

            <div class="card-body p-4">
                <p class="text-muted text-center mb-3" style="font-size:.9rem;">
                    Please enter your admin password to access this page.
                </p>

                @if ($authError)
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                        <i class="mdi mdi-alert-circle-outline fs-5"></i>
                        <span>{{ $authError }}</span>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="adminPasswordInput" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-lock-outline text-muted"></i>
                        </span>
                        <input
                            id="adminPasswordInput"
                            type="password"
                            wire:model.defer="adminPassword"
                            wire:keydown.enter="authenticate"
                            class="form-control border-start-0 ps-0"
                            placeholder="Enter your password…"
                            autocomplete="current-password"
                        >
                    </div>
                </div>

                <button wire:click="authenticate" wire:loading.attr="disabled"
                    class="btn btn-primary w-100 py-2 fw-semibold"
                    style="border-radius:8px;background:linear-gradient(135deg,#3949ab,#5c6bc0);border:none;">
                    <span wire:loading.remove wire:target="authenticate">
                        <i class="mdi mdi-lock-open-outline me-1"></i> Unlock Access
                    </span>
                    <span wire:loading wire:target="authenticate">
                        <span class="spinner-border spinner-border-sm me-1"></span> Verifying…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

@else
{{-- ════════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT (authenticated)                                     --}}
{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- ── Toolbar ─────────────────────────────────────────────────── --}}
<div class="row mb-3 align-items-center">
    <div class="col-md-6 d-flex gap-2 flex-wrap">
        <button wire:click="toggleCreateForm"
            class="btn btn-sm fw-semibold {{ $showCreateForm ? 'btn-secondary' : 'btn-primary' }}"
            style="border-radius:8px;">
            @if ($showCreateForm)
                <i class="mdi mdi-close me-1"></i> Cancel
            @else
                <i class="mdi mdi-plus-circle-outline me-1"></i> Add Parameter
            @endif
        </button>
        <button wire:click="lockPage" class="btn btn-sm btn-outline-danger fw-semibold" style="border-radius:8px;">
            <i class="mdi mdi-lock me-1"></i> Lock Page
        </button>
    </div>
    <div class="col-md-6 text-md-end mt-2 mt-md-0">
        <span class="badge py-2 px-3 fw-normal" style="background:#e8eaf6;color:#3949ab;border-radius:8px;font-size:.82rem;">
            <i class="mdi mdi-account-check-outline me-1"></i>
            Authenticated as <strong>{{ Auth::user()->name }}</strong>
        </span>
    </div>
</div>

{{-- ── Create / Edit Inline Panel ──────────────────────────────── --}}
@if ($showCreateForm || $editingID)
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:12px;border-left:4px solid {{ $editingID ? '#f1b44c' : '#556ee6' }} !important;">
            <div class="card-body p-4">
                <h5 class="mb-3 fw-semibold" style="color:{{ $editingID ? '#f1b44c' : '#556ee6' }};">
                    @if ($editingID)
                        <i class="mdi mdi-pencil-outline me-1"></i> Edit Parameter — <code>{{ $editingKey }}</code>
                    @else
                        <i class="mdi mdi-plus-box-outline me-1"></i> New Parameter
                    @endif
                </h5>

                <div class="row g-3">
                    @if (!$editingID)
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Parameter Key <span class="text-danger">*</span></label>
                        <input type="text" wire:model.defer="param_key"
                            class="form-control @error('param_key') is-invalid @enderror"
                            placeholder="e.g. PAYSTACK_SECRET_KEY"
                            style="text-transform:uppercase;">
                        @error('param_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">Uppercase letters, digits and underscores only.</small>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Group <span class="text-danger">*</span></label>
                        <select wire:model.defer="{{ $editingID ? 'editingGroup' : 'param_group' }}"
                            class="form-select @error($editingID ? 'editingGroup' : 'param_group') is-invalid @enderror">
                            @foreach ($groups as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                        @error($editingID ? 'editingGroup' : 'param_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-{{ $editingID ? '8' : '12' }}">
                        <label class="form-label fw-semibold">Value</label>
                        <textarea wire:model.defer="{{ $editingID ? 'editingValue' : 'param_value' }}"
                            rows="2"
                            class="form-control @error($editingID ? 'editingValue' : 'param_value') is-invalid @enderror"
                            placeholder="Parameter value…"></textarea>
                        @error($editingID ? 'editingValue' : 'param_value')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text"
                            wire:model.defer="{{ $editingID ? 'editingDescription' : 'description' }}"
                            class="form-control"
                            placeholder="Short description of what this parameter controls…">
                    </div>

                    <div class="col-md-2 d-flex flex-column justify-content-end gap-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                wire:model.defer="{{ $editingID ? 'editingIsSecret' : 'is_secret' }}"
                                id="{{ $editingID ? 'editSecretSwitch' : 'createSecretSwitch' }}">
                            <label class="form-check-label fw-semibold" for="{{ $editingID ? 'editSecretSwitch' : 'createSecretSwitch' }}">
                                Secret / Sensitive
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                wire:model.defer="{{ $editingID ? 'editingIsActive' : 'is_active' }}"
                                id="{{ $editingID ? 'editActiveSwitch' : 'createActiveSwitch' }}">
                            <label class="form-check-label fw-semibold" for="{{ $editingID ? 'editActiveSwitch' : 'createActiveSwitch' }}">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2 mt-1">
                        @if ($editingID)
                            <button wire:click="update" wire:loading.attr="disabled"
                                class="btn btn-warning btn-sm fw-semibold px-4" style="border-radius:8px;">
                                <span wire:loading.remove wire:target="update"><i class="mdi mdi-content-save-outline me-1"></i>Save Changes</span>
                                <span wire:loading wire:target="update"><span class="spinner-border spinner-border-sm me-1"></span>Saving…</span>
                            </button>
                            <button wire:click="cancelEdit" class="btn btn-outline-secondary btn-sm fw-semibold px-4" style="border-radius:8px;">
                                <i class="mdi mdi-close me-1"></i>Cancel
                            </button>
                        @else
                            <button wire:click="create" wire:loading.attr="disabled"
                                class="btn btn-primary btn-sm fw-semibold px-4" style="border-radius:8px;">
                                <span wire:loading.remove wire:target="create"><i class="mdi mdi-plus me-1"></i>Create Parameter</span>
                                <span wire:loading wire:target="create"><span class="spinner-border spinner-border-sm me-1"></span>Creating…</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ── Parameter Table ─────────────────────────────────────────── --}}
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                {{-- Controls row --}}
                <div class="row mb-3 align-items-center g-2">
                    <div class="col-auto">
                        <select wire:model="limit" class="form-select form-select-sm" style="width:80px;">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select wire:model="filterGroup" class="form-select form-select-sm" style="min-width:160px;">
                            <option value="">All Groups</option>
                            @foreach ($dbGroups as $dg)
                                <option value="{{ $dg }}">{{ $dg }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col ms-auto" style="max-width:280px;">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="mdi mdi-magnify text-muted"></i>
                            </span>
                            <input type="search"
                                wire:model.live.debounce.400ms="search"
                                class="form-control border-start-0 ps-0"
                                placeholder="Search key or description…">
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="parametersTable">
                        <thead style="background:linear-gradient(135deg,#e8eaf6,#f3f4f9);">
                            <tr>
                                <th class="text-center" style="width:48px;">#</th>
                                <th>Key</th>
                                <th style="width:140px;">Group</th>
                                <th>Value</th>
                                <th>Description</th>
                                <th class="text-center" style="width:70px;">Secret</th>
                                <th class="text-center" style="width:70px;">Active</th>
                                <th class="text-center" style="width:120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($parameters as $param)
                            <tr class="{{ $editingID === $param->id ? 'table-warning' : '' }}">
                                <td class="text-center text-muted small">
                                    {{ ($parameters->currentPage() - 1) * $parameters->perPage() + $loop->iteration }}
                                </td>

                                {{-- Key --}}
                                <td>
                                    <code class="fw-bold" style="color:#3949ab;background:#e8eaf6;padding:2px 7px;border-radius:5px;font-size:.82rem;">
                                        {{ $param->param_key }}
                                    </code>
                                </td>

                                {{-- Group --}}
                                <td>
                                    <span class="badge py-1 px-2 fw-normal" style="background:#f3e5f5;color:#7b1fa2;border-radius:6px;font-size:.78rem;">
                                        {{ $param->param_group }}
                                    </span>
                                </td>

                                {{-- Value --}}
                                <td>
                                    @if ($param->is_secret && !in_array($param->id, $revealedValues))
                                        <span class="text-muted" style="letter-spacing:2px;font-size:1rem;">••••••••</span>
                                        <button wire:click="toggleReveal({{ $param->id }})"
                                            class="btn btn-link btn-sm p-0 ms-1 text-muted"
                                            title="Reveal value" style="font-size:.75rem;">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    @else
                                        <span class="text-break font-monospace" style="font-size:.82rem;max-width:220px;display:inline-block;">
                                            {{ $param->param_value ?? '—' }}
                                        </span>
                                        @if ($param->is_secret)
                                        <button wire:click="toggleReveal({{ $param->id }})"
                                            class="btn btn-link btn-sm p-0 ms-1 text-muted"
                                            title="Hide value" style="font-size:.75rem;">
                                            <i class="mdi mdi-eye-off-outline"></i>
                                        </button>
                                        @endif
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="text-muted small">{{ $param->description ?? '—' }}</td>

                                {{-- Secret badge --}}
                                <td class="text-center">
                                    @if ($param->is_secret)
                                        <span class="badge bg-danger-subtle text-danger py-1 px-2" style="border-radius:6px;font-size:.75rem;">
                                            <i class="mdi mdi-shield-key-outline me-1"></i>Yes
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success py-1 px-2" style="border-radius:6px;font-size:.75rem;">No</span>
                                    @endif
                                </td>

                                {{-- Active badge --}}
                                <td class="text-center">
                                    @if ($param->is_active)
                                        <span class="badge bg-success-subtle text-success py-1 px-2" style="border-radius:6px;font-size:.75rem;">Active</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary py-1 px-2" style="border-radius:6px;font-size:.75rem;">Inactive</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button wire:click="edit({{ $param->id }})"
                                            class="btn btn-sm btn-warning text-dark fw-semibold"
                                            style="border-radius:6px;padding:3px 10px;font-size:.78rem;"
                                            title="Edit">
                                            <i class="mdi mdi-pencil me-1"></i>Edit
                                        </button>
                                        <button wire:click="delete({{ $param->id }})"
                                            onclick="return confirm('Delete parameter \'{{ $param->param_key }}\'? This cannot be undone.')"
                                            class="btn btn-sm btn-danger fw-semibold"
                                            style="border-radius:6px;padding:3px 10px;font-size:.78rem;"
                                            title="Delete">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-database-off-outline" style="font-size:2.5rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                                    No parameters found.
                                    @if ($search || $filterGroup)
                                        Try adjusting your search or filter.
                                    @else
                                        Click <strong>Add Parameter</strong> to get started.
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $parameters->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endif
</div>
