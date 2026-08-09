<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Revenue Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard2">Dashboard</a></li>
                    <li class="breadcrumb-item active">Revenue Report</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Total Revenue</p>
                        <h4 class="mb-0 text-success">&#8358;{{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-success rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-currency-ngn font-size-20 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Total Transactions</p>
                        <h4 class="mb-0">{{ number_format($totalTransactions) }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-receipt font-size-20 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Successful</p>
                        <h4 class="mb-0 text-success">{{ number_format($successfulTransactions) }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-success rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-check-circle font-size-20 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Avg. Order Value</p>
                        <h4 class="mb-0">&#8358;{{ number_format($averageOrderValue, 2) }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-chart-line font-size-20 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Monthly Revenue ({{ date('Y') }})</h5>
                <div id="revenueChart" style="width:100%; height:350px;"></div>
            </div>
        </div>
    </div>

    {{-- Filters & Table --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Transaction Details</h5>
                <div class="row mb-3">
                    <div class="col-md-1">
                        <select name="limit" wire:model="limit" class="form-control form-control-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model="period" class="form-control form-control-sm">
                            <option value="all">All Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model="status" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            <option value="1">Successful</option>
                            <option value="0">Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="startDate"><small>Start Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="startDate" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate"><small>End Date</small></label>
                        <input type="date" wire:model.live.debounce.500ms="endDate" class="form-control form-control-sm">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Response</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $index => $txn)
                            <tr>
                                <td>{{ $transactions->firstItem() + $index }}</td>
                                <td><code>{{ $txn->transaction_id }}</code></td>
                                <td>{{ $txn->user->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($txn->transaction_desc, 30) }}</td>
                                <td>&#8358;{{ number_format($txn->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $txn->status == '1' ? 'success' : 'danger' }}">
                                        {{ $txn->status == '1' ? 'Successful' : 'Failed' }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($txn->response_message, 25) }}</td>
                                <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No transactions found</td>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const revenueData = @json(array_values($revenueChart));
        Highcharts.chart('revenueChart', {
            chart: { type: 'area' },
            title: { text: '' },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            yAxis: {
                title: { text: 'Revenue (₦)' },
                labels: {
                    formatter: function() {
                        return '₦' + Highcharts.numberFormat(this.value, 0, '.', ',');
                    }
                }
            },
            tooltip: {
                pointFormat: 'Revenue: <b>₦{point.y:,.2f}</b>'
            },
            plotOptions: {
                area: {
                    fillOpacity: 0.3,
                    color: '#28a745'
                }
            },
            series: [{
                name: 'Monthly Revenue',
                data: revenueData
            }]
        });
    });
</script>
