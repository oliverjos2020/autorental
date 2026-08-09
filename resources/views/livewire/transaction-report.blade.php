<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Transaction Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard2">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transaction Report</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Total Transactions</p>
                        <h4 class="mb-0">{{ number_format($totalCount) }}</h4>
                        <small class="text-muted">&#8358;{{ number_format($totalAmount, 2) }} total value</small>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-swap-horizontal-bold font-size-20 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Successful</p>
                        <h4 class="mb-0 text-success">{{ number_format($successCount) }}</h4>
                        <small class="text-success">&#8358;{{ number_format($successAmount, 2) }} collected</small>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-success rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-check-decagram font-size-20 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Failed</p>
                        <h4 class="mb-0 text-danger">{{ number_format($failedCount) }}</h4>
                        <small class="text-muted">
                            @if($totalCount > 0)
                                {{ round(($failedCount / $totalCount) * 100, 1) }}% failure rate
                            @else
                                0% failure rate
                            @endif
                        </small>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-danger rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-close-circle font-size-20 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Table --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-1">
                        <select name="limit" wire:model="limit" class="form-control form-control-sm mt-2">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><small>Search</small></label>
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control form-control-sm" placeholder="Transaction ID, name, or email...">
                    </div>
                    <div class="col-md-2">
                        <label><small>Status</small></label>
                        <select wire:model="status" class="form-control form-control-sm">
                            <option value="">All</option>
                            <option value="1">Successful</option>
                            <option value="0">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><small>Start Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="startDate" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label><small>End Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="endDate" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Response Code</th>
                                <th>Response Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $txn)
                            <tr>
                                <td>{{ $transactions->firstItem() + $index }}</td>
                                <td><code>{{ $txn->transaction_id }}</code></td>
                                <td>{{ $txn->user->name ?? 'N/A' }}</td>
                                <td>{{ $txn->user->email ?? 'N/A' }}</td>
                                <td>{{ Str::limit($txn->transaction_desc, 30) }}</td>
                                <td>&#8358;{{ number_format($txn->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $txn->status == '1' ? 'success' : 'danger' }}">
                                        {{ $txn->status == '1' ? 'Success' : 'Failed' }}
                                    </span>
                                </td>
                                <td><code>{{ $txn->response_code }}</code></td>
                                <td>{{ Str::limit($txn->response_message, 30) }}</td>
                                <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-danger">No transactions found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-2">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
