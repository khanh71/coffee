@extends('app')
@section('title','Tính lương')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('js/data-table.js')}}"></script>
<script src="{{asset('js/modal-demo.js')}}"></script>
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

    $('#view').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var iduser = button.data('iduser');
        var dayfrom = $('#dayfrom').val();
        var dayto = $('#dayto').val();
        var model = $(this);
        model.find('#body-detail').remove();
        model.find('#tb-detail').append('<tbody id="body-detail"></tbody>');
        var dataId = {
            'iduser': iduser,
            'dayfrom': dayfrom,
            'dayto': dayto
        };
        $.ajax({
            type: 'GET',
            url: '{{route("find-wd")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                $.each(data, function(k, v) {
                    $('#body-detail').append('<tr><td>' + formatDate(data[k].wddate) + '</td>' +
                        '<td>' + data[k].hour + '</td></tr>')
                })
            }
        })
    })

    function formatDate(date) {
        var formattedDate = new Date(date);
        var d = formattedDate.getDate();
        var m = formattedDate.getMonth();
        m += 1;
        if (m < 10)
            m = '0' + m;
        if (d < 10)
            d = '0' + d;
        var y = formattedDate.getFullYear();
        return (d + "/" + m + "/" + y);
    }

    $('#print').on('click', function() {
        var dayfrom = $('#dayfrom').val();
        dayfrom = dayfrom.split('/').join('-')
        var dayto = $('#dayto').val();
        dayto = dayto.split('/').join('-')
        var url = "{{route('print-salary',['dayfrom'=>':dayfrom','dayto'=>':dayto'])}}"
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
            <div class="glow"></div>Tính Lương
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form class="form-inline col-md-12" action="{{route('report-salary')}}" method="POST">
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
                                <th>Tên nhân viên</th>
                                <th>Chức vụ</th>
                                <th>Số giờ làm</th>
                                <th>Hệ số lương</th>
                                <th>Lương cơ bản</th>
                                <th>Tổng lương</th>
                                <th>Xem chấm công</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salary as $key=>$sa)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$sa->name}}</td>
                                <td>{{$sa->posname}}</td>
                                <td>{{$sa->hour}}</td>
                                <td>{{$sa->coefficient}}</td>
                                <td>{{number_format($sa->basesalary).'₫'}}</td>
                                <td>{{number_format($sa->basesalary*$sa->coefficient*$sa->hour).'₫'}}</td>
                                <td>
                                    <button class="btn btn-dark btn-rounded btn-icon" data-iduser="{{$sa->id}}" data-toggle="modal" data-target="#view"><i class="mdi mdi-eye"></i></button>
                                </td>
                            </tr>
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

<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-eye-outline mr-1"></i>Chi tiết chấm công</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tb-detail">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Số giờ làm</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;"></div>
        </div>
    </div>
</div>
@endsection