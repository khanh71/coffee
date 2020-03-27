@extends('app')
@section('title','Quản lý quán cà phê chuyên nghiệp')
@section('css')@endsection

@section('javascript')
<script src="{{asset('vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('js/dashboard.js')}}"></script>
@endsection
@section('content')
<div class="row">
    <div class="col-12 col-xl-6 grid-margin stretch-card">
        <div class="row w-100 flex-grow">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Website Audience Metrics</p>
                        <p class="text-muted">25% more traffic than previous week</p>
                        <div class="row mb-3">
                            <div class="col-md-7">
                                <div class="d-flex justify-content-between traffic-status">
                                    <div class="item">
                                        <p class="mb-">Users</p>
                                        <h5 class="font-weight-bold mb-0">93,956</h5>
                                        <div class="color-border"></div>
                                    </div>
                                    <div class="item">
                                        <p class="mb-">Bounce Rate</p>
                                        <h5 class="font-weight-bold mb-0">58,605</h5>
                                        <div class="color-border"></div>
                                    </div>
                                    <div class="item">
                                        <p class="mb-">Page Views</p>
                                        <h5 class="font-weight-bold mb-0">78,254</h5>
                                        <div class="color-border"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <ul class="nav nav-pills nav-pills-custom justify-content-md-end" id="pills-tab-custom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-home-tab-custom" data-toggle="pill" href="#pills-health" role="tab" aria-controls="pills-home" aria-selected="true">
                                            Day
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-profile-tab-custom" data-toggle="pill" href="#pills-career" role="tab" aria-controls="pills-profile" aria-selected="false">
                                            Week
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-contact-tab-custom" data-toggle="pill" href="#pills-music" role="tab" aria-controls="pills-contact" aria-selected="false">
                                            Month
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <canvas id="audience-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="card-title">Weekly Balance</p>
                            <p class="text-success font-weight-medium">20.15 %</p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mb-3">
                            <h5 class="font-weight-normal mb-0 mb-md-1 mb-lg-0 mr-3">$22.736</h5>
                            <p class="text-muted mb-0">Avg Sessions</p>
                        </div>
                        <canvas id="balance-chart" height="130"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="card-title">Today Task</p>
                            <p class="text-success font-weight-medium">45.39 %</p>
                        </div>
                        <div class="d-flex align-items-center flex-wrap mb-3">
                            <h5 class="font-weight-normal mb-0 mb-md-1 mb-lg-0 mr-3">17.247</h5>
                            <p class="text-muted mb-0">Avg Sessions</p>
                        </div>
                        <canvas id="task-chart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-6 grid-margin stretch-card">
        <div class="row w-100 flex-grow">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <p class="card-title">Regional Load</p>
                        <p class="text-muted">Last update: 2 Hours ago</p>
                        <div class="regional-chart-legend d-flex align-items-center flex-wrap mb-1" id="regional-chart-legend"></div>
                        <canvas height="280" id="regional-chart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center mb-4">
                            <p class="card-title mb-0 mr-1">Today activity</p>
                            <div class="badge badge-info badge-pill">2</div>
                        </div>
                        <div class="d-flex flex-wrap pt-2">
                            <div class="mr-4 mb-lg-2 mb-xl-0">
                                <p>Time On Site</p>
                                <h4 class="font-weight-bold mb-0">77.15 %</h4>
                            </div>
                            <div>
                                <p>Page Views</p>
                                <h4 class="font-weight-bold mb-0">14.15 %</h4>
                            </div>
                        </div>
                    </div>
                    <canvas height="150" id="activity-chart"></canvas>
                </div>
            </div>
            <div class="col-md-12 stretch-card">
                <div class="card">
                    <div class="card-body pb-0">
                        <p class="card-title">Server Status 247</p>
                        <div class="d-flex justify-content-between flex-wrap">
                            <p class="text-muted">Last update: 2 Hours ago</p>
                            <div class="d-flex align-items-center flex-wrap server-status-legend mt-3 mb-3 mb-md-0">
                                <div class="item mr-3">
                                    <div class="d-flex align-items-center">
                                        <div class="color-bullet"></div>
                                        <h5 class="font-weight-bold mb-0">128GB</h5>
                                    </div>
                                    <p class="mb-">Total Usage</p>
                                </div>
                                <div class="item mr-3">
                                    <div class="d-flex align-items-center">
                                        <div class="color-bullet"></div>
                                        <h5 class="font-weight-bold mb-0">92%</h5>
                                    </div>
                                    <p class="mb-">Memory Usage</p>
                                </div>
                                <div class="item mr-3">
                                    <div class="d-flex align-items-center">
                                        <div class="color-bullet"></div>
                                        <h5 class="font-weight-bold mb-0">16%</h5>
                                    </div>
                                    <p class="mb-">Disk Usage</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <canvas height="170" id="status-chart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-6 col-xl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <canvas height="260" class="mt-2" id="customers-chart"></canvas>
                    <h5 class="mb-2 mt-4">New Customers</h5>
                    <h6 class="font-weight-normal">Jun 2018</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <canvas height="260" class="mt-2" id="comments-chart"></canvas>
                    <h5 class="mb-2 mt-4">Total Comments</h5>
                    <h6 class="font-weight-normal">Avg Sessions</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <canvas height="260" class="mt-2" id="total-sales-chart"></canvas>
                    <h5 class="mb-2 mt-4">Total Sales</h5>
                    <h6 class="font-weight-normal">Last 30 days</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <canvas height="260" class="mt-2" id="orders-chart"></canvas>
                    <h5 class="mb-2 mt-4">Total Orders</h5>
                    <h6 class="font-weight-normal">Last 15 days</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-facebook d-flex align-items-center">
            <div class="card-body py-5">
                <div class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-facebook text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                        <h5 class="text-white font-weight-bold">2.62 Subscribers</h5>
                        <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-google d-flex align-items-center">
            <div class="card-body py-5">
                <div class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-google-plus text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                        <h5 class="text-white font-weight-bold">3.4k Followers</h5>
                        <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card bg-twitter d-flex align-items-center">
            <div class="card-body py-5">
                <div class="d-flex flex-row align-items-center flex-wrap justify-content-md-center justify-content-xl-start py-1">
                    <i class="mdi mdi-twitter text-white icon-lg"></i>
                    <div class="ml-3 ml-md-0 ml-xl-3">
                        <h5 class="text-white font-weight-bold">3k followers</h5>
                        <p class="mt-2 text-white card-text">You main list growing</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div id="sales-legend" class="sales-legend"></div>
                <canvas id="sales-chart" class="pt-3"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <!-- <p class="card-title">Best Sellers</p> -->
                <div id="seller-legend" class="seller-legend"></div>
                <canvas id="sellers-chart"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- row end -->
<div class="row">
    <div class="col-md-12 grid-margin-stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-title">Detailed Reports</p>
                        <p class="text-muted mb-5">Overall Sales Revenue and Profits Performance Online and Offline for Q1
                            to Q4.</p>
                        <canvas id="reports-chart" class="mb-2"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center justify-content-between mt-4 mt-md-0">
                            <div class="dropdown">
                                <button class="btn bg-transparent text-muted p-0 dropdown-toggle" type="button" id="dropdownMenuSizeButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Monthly Report
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton2">
                                    <h6 class="dropdown-header">Settings</h6>
                                    <a class="dropdown-item" href="javascript:;">Action</a>
                                    <a class="dropdown-item" href="javascript:;">Another action</a>
                                    <a class="dropdown-item" href="javascript:;">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:;">Separated link</a>
                                </div>
                            </div>
                            <div class="d-flex">
                                <a href="javascript:;" class="mr-2">
                                    <i class="mdi mdi-wrap icon-md text-body"></i>
                                </a>
                                <a href="javascript:;">
                                    <i class="mdi mdi-close-circle-outline icon-md text-body"></i>
                                </a>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mt-2">
                                <p class="mb-0">oneplus 6T</p>
                                <p class="font-weight-medium mb-0">40%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">Iphone x</p>
                                <p class="font-weight-medium mb-0">55%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">iphone 8s</p>
                                <p class="font-weight-medium mb-0">89%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">Iphone 7</p>
                                <p class="font-weight-medium mb-0">40%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">samsung s8</p>
                                <p class="font-weight-medium mb-0">70%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <p class="mb-0">Iphone 6</p>
                                <p class="font-weight-medium mb-0">45%</p>
                            </div>
                            <div class="progress progress-md progress-sm mt-2">
                                <div class="progress-bar bg-dark" role="progressbar" style="width: 45%" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection