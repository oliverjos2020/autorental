<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="page-title mb-0 font-size-18">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Welcome to Auto Rental Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

@if (Auth::user()->role_id == 2)
    {{-- ══ STATION ADMIN DASHBOARD ══ --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Total Drivers</p><h4 class="mb-0">{{ $drivers->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar" role="progressbar" style="width: 62%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Total Vehicles</p><h4 class="mb-0">{{ $vehicles->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-warning" role="progressbar" style="width: 78%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Assigned Vehicles</p><h4 class="mb-0">{{ $MappedVehicle->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-danger" role="progressbar" style="width: 75%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Assigned Drivers</p><h4 class="mb-0">{{ $mappedDriver->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div></div></div>
            </div></div></div>
        </div>
    </div>

@elseif(Auth::user()->role_id == 1)
    {{-- ══ ADMIN DASHBOARD ══ --}}
    <div class="row">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Station Admin</p><h4 class="mb-0">{{ $stationAdmin->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar" role="progressbar" style="width: 62%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Drivers</p><h4 class="mb-0">{{ $totalDrivers->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-warning" role="progressbar" style="width: 78%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Mobile App Users</p><h4 class="mb-0">{{ $mobileUsers->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-success" role="progressbar" style="width: 75%"></div></div></div>
            </div></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><div class="row align-items-center">
                <div class="col-8"><p class="mb-2">Total Vehicles</p><h4 class="mb-0">{{ $totalVehicles->count() }}</h4></div>
                <div class="col-4 text-end"><div class="progress progress-sm mt-3"><div class="progress-bar bg-danger" role="progressbar" style="width: 75%"></div></div></div>
            </div></div></div>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════════ --}}
{{-- ══ NEW: BOOKING & REVENUE STATS ROW         ══ --}}
{{-- ══════════════════════════════════════════════ --}}
<div class="row">
    <div class="col-md-3">
        <div class="card border-start border-primary border-3">
            <div class="card-body">
                <p class="mb-1 text-muted font-size-13">Total Bookings</p>
                <h4 class="mb-1">{{ number_format($totalBookings) }}</h4>
                <div class="d-flex justify-content-between">
                    <small class="text-warning">{{ $pendingBookings }} pending</small>
                    <small class="text-info">{{ $ongoingBookings }} ongoing</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-success border-3">
            <div class="card-body">
                <p class="mb-1 text-muted font-size-13">Total Revenue</p>
                <h4 class="mb-1 text-success">&#8358;{{ number_format($totalRevenue, 2) }}</h4>
                <small class="text-muted">This month: &#8358;{{ number_format($monthRevenue, 2) }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-warning border-3">
            <div class="card-body">
                <p class="mb-1 text-muted font-size-13">Vehicles On Trip</p>
                <h4 class="mb-1 text-warning">{{ $vehiclesOnTrip }}</h4>
                <small class="text-success">{{ $vehiclesAvailable }} available</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-info border-3">
            <div class="card-body">
                <p class="mb-1 text-muted font-size-13">Payment Overview</p>
                <h4 class="mb-1">{{ $paidBookings }}<small class="font-size-14 text-muted"> / {{ $totalBookings }}</small></h4>
                <div class="progress progress-sm mt-1">
                    <div class="progress-bar bg-success" style="width: {{ $totalBookings > 0 ? round(($paidBookings/$totalBookings)*100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $totalBookings > 0 ? round(($paidBookings/$totalBookings)*100) : 0 }}% paid</small>
            </div>
        </div>
    </div>
</div>

{{-- ══ CHARTS ROW ══ --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Monthly Bookings ({{ date('Y') }})</h5>
                <div id="bookingChartContainer" style="width:100%; height:300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Monthly Revenue ({{ date('Y') }})</h5>
                <div id="revenueChartContainer" style="width:100%; height:300px;"></div>
            </div>
        </div>
    </div>
</div>

{{-- ══ BOOKING TYPE & RECENT BOOKINGS ══ --}}
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Booking Type Distribution</h5>
                <div id="bookingTypePieChart" style="width:100%; height:280px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Recent Bookings</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead><tr><th>Customer</th><th>Vehicle</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                            <tr>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->vehicle->vehicleMake ?? '' }} {{ $booking->vehicle->vehicleModel ?? '' }}</td>
                                <td>&#8358;{{ number_format($booking->amount, 2) }}</td>
                                <td><span class="badge bg-{{ $booking->payment_status == 1 ? 'success' : 'danger' }}">{{ $booking->payment_status == 1 ? 'Paid' : 'Unpaid' }}</span></td>
                                <td>
                                    @if($booking->status == 0)<span class="badge bg-warning">Pending</span>
                                    @elseif($booking->status == 1)<span class="badge bg-info">Confirmed</span>
                                    @elseif($booking->status == 2)<span class="badge bg-primary">Ongoing</span>
                                    @elseif($booking->status == 3)<span class="badge bg-success">Completed</span>
                                    @endif
                                </td>
                                <td>{{ $booking->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">No recent bookings</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ STATION PERFORMANCE TABLE (Admin Only) ══ --}}
@if(Auth::user()->role_id == 1 && $stationPerformance->count() > 0)
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Station Performance</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead><tr><th>#</th><th>Station</th><th>Vehicles</th><th>On Trip</th><th>Total Bookings</th><th>Revenue</th></tr></thead>
                        <tbody>
                            @foreach($stationPerformance as $index => $station)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $station->stationName }}</strong></td>
                                <td>{{ $station->vehicles_count }}</td>
                                <td><span class="badge bg-warning">{{ $station->on_trip_count }}</span></td>
                                <td>{{ number_format($station->total_bookings) }}</td>
                                <td class="text-success"><strong>&#8358;{{ number_format($station->total_revenue, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ══ ORIGINAL CHART (kept for backward compat) ══ --}}
<div class="row">
    <div id="container" style="width:100%; height:400px;"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stationAdminData = @json($stationAdminGraph);
        const totalDriversGraph = @json($totalDriversGraph);
        const mobileUsersGraph = @json($mobileUsersGraph);
        const totalVehiclesGraph = @json($totalVehiclesGraph);

        // Original chart
        Highcharts.chart('container', {
            chart: { type: 'line' },
            title: { text: 'Graphical Representation of client' },
            @if(Auth::user()->role_id == 2)
            xAxis: { categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] },
            series: [{ name: 'Driver Chart', data: @json(array_values($driverChart)) }]
            @elseif(Auth::user()->role_id == 1)
            xAxis: { categories: ['values'] },
            series: [
                { name: 'Station Admin', data: [0, stationAdminData, 0] },
                { name: 'Drivers', data: [0, totalDriversGraph, 0] },
                { name: 'Mobile App Users', data: [0, mobileUsersGraph, 0] },
                { name: 'Total Vehicles', data: [0, totalVehiclesGraph, 0] }
            ]
            @endif
        });

        // NEW: Monthly Bookings chart
        Highcharts.chart('bookingChartContainer', {
            chart: { type: 'column' },
            title: { text: '' },
            xAxis: { categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] },
            yAxis: { min: 0, title: { text: 'Bookings' }, allowDecimals: false },
            plotOptions: { column: { borderRadius: 4, color: '#556ee6' } },
            legend: { enabled: false },
            series: [{ name: 'Bookings', data: @json(array_values($bookingChart)) }]
        });

        // NEW: Monthly Revenue chart
        Highcharts.chart('revenueChartContainer', {
            chart: { type: 'area' },
            title: { text: '' },
            xAxis: { categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] },
            yAxis: { title: { text: 'Revenue (₦)' }, labels: { formatter: function(){ return '₦'+Highcharts.numberFormat(this.value,0,'.',','); } } },
            tooltip: { pointFormat: 'Revenue: <b>₦{point.y:,.2f}</b>' },
            plotOptions: { area: { fillOpacity: 0.3, color: '#34c38f' } },
            legend: { enabled: false },
            series: [{ name: 'Revenue', data: @json(array_values($revenueChart)) }]
        });

        // NEW: Booking Type pie chart
        Highcharts.chart('bookingTypePieChart', {
            chart: { type: 'pie' },
            title: { text: '' },
            plotOptions: { pie: { allowPointSelect: true, cursor: 'pointer', dataLabels: { enabled: true, format: '<b>{point.name}</b>: {point.y}' }, colors: ['#556ee6','#f1b44c'] } },
            series: [{ name: 'Bookings', data: [
                { name: 'Vehicle Rental', y: @json($bookingTypeBooking) },
                { name: 'E-Hailing', y: @json($bookingTypeEhailing) }
            ]}]
        });
    });
</script>
