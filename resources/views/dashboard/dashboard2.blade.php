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
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Total Drivers</p>
                            <h4 class="mb-0" id="totalCollection">{{ $drivers->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.06 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Total Vehicles</p>
                            <h4 class="mb-0" id="totalCount">{{ $vehicles->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 3.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 78%"
                                        aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Assigned Vehicles</p>
                            <h4 class="mb-0" id="totalSettlement">{{ $MappedVehicle->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Assigned Drivers</p>
                            <h4 class="mb-0" id="totalSettlement">{{ $mappedDriver->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@elseif(Auth::user()->role_id == 1)
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <p class="mb-2">Station Admin</p>
                            <h4 class="mb-0" id="totalCollection">{{ $stationAdmin->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.06 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Drivers</p>
                            <h4 class="mb-0" id="totalCount">{{ $totalDrivers->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 3.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 78%"
                                        aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Mobile App Users</p>
                            <h4 class="mb-0" id="totalSettlement">{{ $mobileUsers->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 75%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
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
                            <p class="mb-2">Total Vehicles</p>
                            <h4 class="mb-0" id="totalSettlement">{{ $totalVehicles->count() }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-end">
                                <div>
                                    <!-- 2.12 % <i class="mdi mdi-arrow-up text-success ms-1"></i> -->
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="row">
    <div id="container" style="width:100%; height:400px;"></div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stationAdminData = @json($stationAdminGraph);
        const totalDriversGraph = @json($totalDriversGraph);
        const mobileUsersGraph = @json($mobileUsersGraph);
        const totalVehiclesGraph = @json($totalVehiclesGraph);
        const chart = Highcharts.chart('container', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Graphical Representation of client'
            },

            // yAxis: {
            //     title: {
            //         text: 'Fruit eaten'
            //     }
            // },

                @if(Auth::user()->role_id == 2)
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                },series: [

                {
                    name: 'Driver Chart',
                    data: @json(array_values($driverChart))
                }
            ]
                @elseif(Auth::user()->role_id == 1)
                xAxis: {
                // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    categories: ['values']
                },series: [
                {
                    name: 'Station Admin',
                    data: [0, stationAdminData, 0]
                },
                {
                    name: 'Drivers',
                    data: [0, totalDriversGraph, 0]
                },
                {
                    name: 'Mobile App Users',
                    data: [0, mobileUsersGraph, 0]
                },
                {
                    name: 'Total Vehicles',
                    data: [0, totalVehiclesGraph, 0]
                }]
                @endif


        });
    });
</script>
