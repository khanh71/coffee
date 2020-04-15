@extends('app')
@section('title','Báo cáo lợi nhuận')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('js/data-table.js')}}"></script>
<script src="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/formpickers.js')}}"></script>
<script src="{{asset('js/jquery.printPage.js')}}"></script>
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script type="text/javascript">
    $('#datepicker-popup-dayfrom').datepicker('update', new Date('{{$dayfrom->year}}', '{{$dayfrom->month-1}}', '{{$dayfrom->day}}'));
    $('#datepicker-popup-dayto').datepicker('update', new Date('{{$dayto->year}}', '{{$dayto->month-1}}', '{{$dayto->day}}'));

    $("#datepicker-popup-dayfrom").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });
    $("#datepicker-popup-dayto").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });

    $('#print').on('click', function() {
        var dayfrom = $('#dayfrom').val();
        dayfrom = dayfrom.split('/').join('-')
        var dayto = $('#dayto').val();
        dayto = dayto.split('/').join('-')
        var url = "{{route('print-profit',['dayfrom'=>':dayfrom','dayto'=>':dayto'])}}"
        url = url.replace(':dayfrom', dayfrom)
        url = url.replace(':dayto', dayto)
        $(this).attr('href', url);
    })

    $(document).ready(function() {
        $('#print').printPage();
    });
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary">
            <div class="glow"></div>Báo Cáo Lợi Nhuận
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form class="form-inline col-md-12" action="{{route('report-profit')}}" method="POST">
                        {{csrf_field()}}
                        <label class="mr-1">Từ ngày</label>
                        <div id="datepicker-popup-dayfrom" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayfrom" name="dayfrom" required>
                        </div>
                        <label class="mr-1">Đến ngày</label>
                        <div id="datepicker-popup-dayto" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayto" name="dayto" required>
                        </div>
                        <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Lọc</button>
                    </form>
                </div>
                <a class="btn btn-success btn-icon-text float-right text-white mt-2" id="print"><i class="mdi mdi-printer btn-icon-prepend"></i>In</a>
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên món</th>
                                <th>Đơn giá</th>
                                <th>Giá nhập</th>
                                <th>Số lượng</th>
                                <th>Doanh thu</th>
                                <th>Lợi nhuận</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $cate = '';
                            @endphp
                            @forelse($profit as $key=>$pro)
                            @if($pro->procatename == $cate)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$pro->proname}}</td>
                                <td>{{number_format($pro->proprice).'₫'}}</td>
                                <td>{{number_format($pro->price).'₫'}}</td>
                                <td>{{$pro->billamount}}</td>
                                <td>{{number_format($pro->proprice*$pro->billamount).'₫'}}</td>
                                <td>{{number_format($pro->proprice*$pro->billamount - $pro->price*$pro->billamount).'₫'}}</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="7" class="text-left font-weight-bold">Thực đơn: {{$pro->procatename}}</td>
                            </tr>
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$pro->proname}}</td>
                                <td>{{number_format($pro->proprice).'₫'}}</td>
                                <td>{{number_format($pro->price).'₫'}}</td>
                                <td>{{$pro->billamount}}</td>
                                <td>{{number_format($pro->proprice*$pro->billamount).'₫'}}</td>
                                <td>{{number_format($pro->proprice*$pro->billamount - $pro->price*$pro->billamount).'₫'}}</td>
                            </tr>
                            @php
                            $cate = $pro->procatename;
                            @endphp
                            @endif
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection