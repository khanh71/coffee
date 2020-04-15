@extends('app')
@section('title','Trang Chủ')
@section('css')
<script src="{{asset('js/highcharts.js')}}"></script>
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script>
    $(document).ready(function() {
        var productBuy = $('#piechart').data('order');
        var chartData = [];
        productBuy.forEach(function(element) {
            var ele = {
                name: element.proname,
                y: parseFloat(element.countPro)
            };
            chartData.push(ele);
        });

        Highcharts.chart('piechart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                name: 'Tỉ Lệ',
                colorByPoint: true,
                data: chartData,
            }]
        });

        var docs = $('#columnchart').data('order');
        var datasum = [];
        var dataday = [];
        docs.forEach(function(element) {
            datasum.push(parseFloat(element.sumTotal));
            var day = new Date(element.billdate);
            dataday.push('Ngày ' + day.getDate());
        });
        console.log(datasum);
        console.log(dataday);
        Highcharts.chart('columnchart', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: dataday,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Doanh Thu'
                }
            },
            tooltip: {
                headerFormat: '',
                pointFormat: '<label><b>{point.y}₫</b></label>',
            },
            plotOptions: {
                column: {
                    pointPadding: 0.4,
                    borderWidth: 0
                }
            },
            series: [{
                showInLegend: false,
                data: datasum

            }]
        });
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <p class="card-title-dashboard text-md-center text-xl-left text-uppercase">nhân viên</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h2 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{$user}}</h2>
                    <i class="mdi mdi-account icon-lg mb-0 mb-md-3 mb-xl-0"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-success text-white">
            <div class="card-body">
                <p class="card-title-dashboard text-md-center text-xl-left text-uppercase">bàn có khách</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h2 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{$desk}}/{{$desks}}</h2>
                    <i class="mdi mdi-sofa icon-lg mb-0 mb-md-3 mb-xl-0"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <p class="card-title-dashboard text-md-center text-xl-left text-uppercase">thực đơn</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h2 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{$cate}}</h2>
                    <i class="mdi mdi-buffer icon-lg mb-0 mb-md-3 mb-xl-0"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <p class="card-title-dashboard text-md-center text-xl-left text-uppercase">món</p>
                <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h2 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">{{$pro}}</h2>
                    <i class="mdi mdi-food icon-lg mb-0 mb-md-3 mb-xl-0"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card grid-margin">
            <div class="card-body">
                <div class="card-title ribbon ribbon-primary">
                    <div class="glow"></div>tỉ lệ món bán ra
                </div>
                <div id="piechart" data-order="{{ $proorder }}"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title ribbon ribbon-primary">
                    <div class="glow"></div>doanh thu tháng này
                </div>
                <div id="columnchart" data-order="{{ $money }}"></div>
            </div>
        </div>
    </div>
</div>
@endsection