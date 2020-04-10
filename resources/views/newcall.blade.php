@extends('app')
@section('title') Gọi món {{$desk->deskname}} @endsection
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
<script src="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/formpickers.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script type="text/javascript">
    $('#datepicker-popup-daywork').datepicker('update', new Date());
    $('#add').on('click', function() {
        addRow();
    });
    $('tbody').on('click', '#remove', function() {
        var l = $('tbody tr').length;
        if (l > 1)
            $(this).parent().parent().remove();
        total();
    });

    $('tbody').delegate('#idprocate', 'change', function() {
        var tr = $(this).parent().parent();
        var idprocate = $(this).val();
        tr.find('#idpro').remove();
        tr.find('#aaa').append('<select class="form-control form-control-md text-capitalize" id="idpro" name="idpro[]" required>' +
            '<option value = "" selected = "true" disabled = "true" >---Chọn---</option></select>');
        var dataId = {
            'idprocate': idprocate
        };
        $.ajax({
            type: 'GET',
            url: '{{route("find-cate")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                $.each(data, function(k, v) {
                    tr.find('#idpro').append('<option value="' + data[k].idpro + '">' + data[k].proname + '</option>');
                })
            }
        });
    });

    $('tbody').delegate('#idpro', 'change', function() {
        var tr = $(this).parent().parent();
        var idpro = $(this).val();
        tr.find('#billamount').focus();
        tr.find('#billamount').val('');
        var dataId = {
            'idpro': idpro
        };
        $.ajax({
            type: 'GET',
            url: '{{route("find-pro-price")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                tr.find('#billprice').val(data.proprice);
            }
        });
    });

    $('tbody').delegate('#billamount', 'keyup', function() {
        var tr = $(this).parent().parent();
        var num = $(this).val();
        var idpro = tr.find('#idpro').val();
        $('#frm').find('#notenough').remove();
        var dataId = {
            'proid': idpro,
            'num': num
        };
        $.ajax({
            type: 'GET',
            url: '{{route("check")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                if (data == 1) {
                    tr.find('#billamount').val('');
                    $('#frm').prepend('<div class="alert alert-fill-danger" id="notenough" role="alert">' +
                        '<i class="mdi mdi-information-outline"></i> Không đủ nguyên liệu</div>');
                } else
                    $('#frm').find('#notenough').remove();
            }
        });
    });

    $('tbody').delegate('#billamount, #billprice', 'keyup', function() {
        var tr = $(this).parent().parent();
        var billamount = tr.find('#billamount').val();
        var billprice = tr.find('#billprice').val();
        var imptotal = billamount * billprice;
        tr.find('#billtotal').val(imptotal);
        total();
    });

    function total() {
        var total = 0;
        $('.billtotal').each(function(i, e) {
            var imptotal = $(this).val() - 0;
            total += imptotal;
        });
        $('#total').attr('value', formatNumber(total));
    };

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + "₫"
    }

    function addRow() {
        var tr = '<tr>' +
            '<td>' +
            '<select class="form-control form-control-md text-capitalize" id="idprocate" name="idprocate[]" required>' +
            '<option value="0" selected="true">Tất Cả</option>' +
            '@foreach($cates as $cate)' +
            '<option value="{{$cate->idprocate}}">{{$cate->procatename}}</option>' +
            '@endforeach' +
            '</select>' +
            '</td>' +
            '<td id="aaa">' +
            '<select class="form-control form-control-md text-capitalize" id="idpro" name="idpro[]" required>' +
            '<option value="" selected="true" disabled="true">---Chọn---</option>' +
            '@foreach($pros as $pro)' +
            '<option value="{{$pro->idpro}}">{{$pro->proname}}</option>' +
            '@endforeach' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm" id="billamount" name="billamount[]" required min="1" max="1000">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm" id="billprice" name="billprice[]" required min="1" max="1000000000">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm billtotal bg-white" id="billtotal" name="billtotal[]" readonly>' +
            '</td>' +
            '<td class="text-center">' +
            '<a id="remove" class="btn btn-danger btn-sm text-white"><i class="mdi mdi-delete ml-0"></i></a>' +
            '</td>' +
            '</tr>';
        $('tbody').append(tr);
    };
</script>
@endsection

@section('content')
<!--list of import-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-success">
            <div class="glow"></div>Gọi món {{$desk->deskname}}
        </div>
        <div class="row" id="frm">
            @if(Session::has('dupp'))
            <div class="alert alert-fill-danger" role="alert">
                <i class="mdi mdi-information-outline"></i>
                {{Session::get('dupp')}}
            </div>
            @endif
            <form class="col-12" id="newCallForm" method="post" action="{{route('new-call',$desk->iddesk)}}">
                @if(count($errors->postNewCall_Error)>0)
                @foreach($errors->postNewCall_Error->all() as $err)
                <div class="alert alert-fill-danger" role="alert">
                    <i class="mdi mdi-information-outline"></i>
                    {{$err}}
                </div>
                @endforeach
                @endif
                {{csrf_field()}}
                <input type="hidden" name="deskid" value="{{$desk->iddesk}}">
                <div class="form-group">
                    <label class="text-capitalize font-weight-bold">Nhân viên phục vụ</label>
                    <select class="form-control form-control-md text-capitalize" id="iduser" name="iduser" required>
                        @foreach($users as $user)
                        <option value="{{$user->id}}" @if($user->id == Auth::user()->id) selected @endif>{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div><b>Lưu ý: </b>Mỗi món chỉ được nhập 1 hàng.</div>
                <div class="table-responsive mb-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Thực đơn</th>
                                <th class="text-center">Món</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">Thành tiền</th>
                                <th class="text-center"><a id="add" class="btn btn-success btn-sm text-white"><i class="mdi mdi-plus ml-0"></i></a></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control form-control-md text-capitalize" id="idprocate" name="idprocate[]" required>
                                        <option value="0" selected="true">Tất Cả</option>
                                        @foreach($cates as $cate)
                                        <option value="{{$cate->idprocate}}">{{$cate->procatename}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td id="aaa">
                                    <select class="form-control form-control-md text-capitalize" id="idpro" name="idpro[]" required>
                                        <option value="" selected="true" disabled="true">---Chọn---</option>
                                        @foreach($pros as $pro)
                                        <option value="{{$pro->idpro}}">{{$pro->proname}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" id="billamount" name="billamount[]" required min="1" max="1000">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" id="billprice" name="billprice[]" required min="1" max="1000000000">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm billtotal bg-white" id="billtotal" name="billtotal[]" readonly>
                                </td>
                                <td class="text-center">
                                    <a id="remove" class="btn btn-danger btn-sm text-white"><i class="mdi mdi-delete ml-0"></i></a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><b>Tổng cộng:</b></td>
                                <td><input type="text" class="form-control form-control-sm bg-white" id="total" name="total" readonly></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div>
                    <button class="btn btn-success btn-icon-text float-right ml-2" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                    <a href="{{route('/')}}" class="btn btn-secondary btn-icon-text float-right"><i class="mdi mdi-cancel btn-icon-prepend"></i>Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end list of import-->
@endsection