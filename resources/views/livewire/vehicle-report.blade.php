<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Vehicle Utilization Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="/dashboard2">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vehicle Report</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Fleet Overview Cards --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <p class="mb-2 text-muted">Total Fleet</p>
                        <h4 class="mb-0">{{ $totalVehicles }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-car-multiple font-size-20 text-primary"></i>
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
                        <p class="mb-2 text-muted">On Trip</p>
                        <h4 class="mb-0 text-warning">{{ $vehiclesOnTrip }}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-warning rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-car-connected font-size-20 text-warning"></i>
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
                        <p class="mb-2 text-muted">Available</p>
                        <h4 class="mb-0 text-success">{{ $vehiclesAvailable }}</h4>
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
                        <p class="mb-2 text-muted">Utilization Rate</p>
                        <h4 class="mb-0 text-info">{{ $utilizationRate }}%</h4>
                    </div>
                    <div class="col-4 text-end">
                        <div class="avatar-sm bg-soft-info rounded-circle d-flex align-items-center justify-content-center mx-auto">
                            <i class="mdi mdi-gauge font-size-20 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Top 5 Most Booked Vehicles</h5>
                <div id="topVehiclesChart" style="width:100%; height:300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Fleet by Category</h5>
                <div id="categoryPieChart" style="width:100%; height:300px;"></div>
            </div>
        </div>
    </div>

    {{-- Vehicle Performance Table --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Vehicle Performance</h5>
                <div class="row mb-3">
                    <div class="col-md-1">
                        <select name="limit" wire:model="limit" class="form-control form-control-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" wire:model.live.debounce.500ms="search" class="form-control form-control-sm" placeholder="Search by make or model...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model="statusFilter" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            <option value="0">Pending</option>
                            <option value="1">Active</option>
                            <option value="2">Approved</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model="tripFilter" class="form-control form-control-sm">
                            <option value="">All Trip Status</option>
                            <option value="0">Available</option>
                            <option value="1">On Trip</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vehicle</th>
                                <th>Owner</th>
                                <th>Station</th>
                                <th>Status</th>
                                <th>Trip Status</th>
                                <th>Total Bookings</th>
                                <th>Revenue Generated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vehicles as $index => $vehicle)
                            <tr>
                                <td>{{ $vehicles->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $vehicle->vehicleMake }} {{ $vehicle->vehicleModel }}</strong>
                                    <br><small class="text-muted">{{ $vehicle->vehicleYear }}</small>
                                </td>
                                <td>{{ $vehicle->user->name ?? 'N/A' }}</td>
                                <td>{{ $vehicle->station->stationName ?? 'N/A' }}</td>
                                <td>
                                    @if($vehicle->status == 0)
                                        <span class="badge bg-secondary">Pending</span>
                                    @elseif($vehicle->status == 1)
                                        <span class="badge bg-primary">Active</span>
                                    @elseif($vehicle->status == 2)
                                        <span class="badge bg-success">Approved</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $vehicle->on_trip == 1 ? 'warning' : 'success' }}">
                                        {{ $vehicle->on_trip == 1 ? 'On Trip' : 'Available' }}
                                    </span>
                                </td>
                                <td class="text-center"><strong>{{ $vehicle->total_bookings }}</strong></td>
                                <td class="text-end">&#8358;{{ number_format($vehicle->total_revenue, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No vehicles found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="my-2">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Top Vehicles Bar Chart
        const topVehicleNames = @json($topVehicles->pluck('vehicleMake')->map(function($make, $key) use ($topVehicles) { return $make . ' ' . $topVehicles[$key]->vehicleModel; })->values());
        const topVehicleCounts = @json($topVehicles->pluck('booking_count'));

        Highcharts.chart('topVehiclesChart', {
            chart: { type: 'bar' },
            title: { text: '' },
            xAxis: {
                categories: topVehicleNames,
                title: { text: null }
            },
            yAxis: {
                min: 0,
                title: { text: 'Number of Bookings' },
                allowDecimals: false
            },
            plotOptions: {
                bar: {
                    dataLabels: { enabled: true },
                    colorByPoint: true,
                    colors: ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1']
                }
            },
            legend: { enabled: false },
            series: [{
                name: 'Bookings',
                data: topVehicleCounts
            }]
        });

        // Category Pie Chart
        const categoryData = @json($categoryStats->map(function($item) { return ['name' => $item->category, 'y' => $item->vehicle_count]; })->values());

        Highcharts.chart('categoryPieChart', {
            chart: { type: 'pie' },
            title: { text: '' },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y} ({point.percentage:.1f}%)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    },
                    colors: ['#556ee6', '#34c38f', '#f1b44c', '#f46a6a', '#50a5f1', '#74788d']
                }
            },
            series: [{
                name: 'Vehicles',
                data: categoryData
            }]
        });
    });
</script>
