@extends('app')
@section('title','Tạo phiếu nhập kho')
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

    $('tbody').delegate('#idma', 'change', function() {
        var tr = $(this).parent().parent();
        tr.find('#impamount').focus();
        var idma = tr.find('#idma').val();
        var dataId = {
            'idma': idma
        };
        $.ajax({
            type: 'GET',
            url: '{{route("import-find-price")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                tr.find('#impprice').val(data.maprice);
                tr.find('#impunit').val(data.unit);
            }
        });
    });

    $('tbody').delegate('#impamount, #impprice', 'keyup', function() {
        var tr = $(this).parent().parent();
        var impamount = tr.find('#impamount').val();
        var impprice = tr.find('#impprice').val();
        var imptotal = impamount * impprice;
        tr.find('#imptotal').val(imptotal);
        total();
    });

    function total() {
        var total = 0;
        $('.imptotal').each(function(i, e) {
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
            '<select class="form-control form-control-md text-capitalize" id="idma" name="idma[]" required>' +
            '<option value="" selected="true" disabled="true">--chọn--</option>' +
            '@foreach($mas as $ma)' +
            '<option value="{{$ma->idma}}">{{$ma->maname}}</option>' +
            '@endforeach' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="text" class="form-control form-control-sm bg-white text-capitalize" id="impunit" readonly>' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm" id="impamount" name="impamount[]" required min="0" max="1000000" step="0.01">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm" id="impprice" name="impprice[]" required min="1" max="1000000000">' +
            '</td>' +
            '<td>' +
            '<input type="number" class="form-control form-control-sm imptotal bg-white" id="imptotal" name="imptotal[]" readonly>' +
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
        <div class="card-title ribbon ribbon-success"><div class="glow"></div>Tạo phiếu nhập kho</div>
        <div class="row">
            <form class="col-12" id="newImportForm" method="post" action="{{route('new-import')}}">
                @if(count($errors->postNewImport_Error)>0)
                @foreach($errors->postNewImport_Error->all() as $err)
                <div class="alert alert-fill-danger" role="alert">
                    <i class="mdi mdi-information-outline"></i>
                    {{$err}}
                </div>
                @endforeach
                @endif
                {{csrf_field()}}
                <div class="mb-3">
                    <fieldset>
                        <legend>thông tin phiếu nhập</legend>
                        <div class="form-group">
                            <label><b>Nhà cung cấp:</b></label>
                            <select class="form-control form-control-md text-capitalize" id="idsupp" name="idsupp" value="{{old('idsupp')}}">
                                @foreach($supps as $supp)
                                <option value="{{$supp->idsupp}}">{{$supp->suppname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Ngày nhập:</b></label>
                            <div id="datepicker-popup-daywork" class="input-group date datepicker">
                                <span class="input-group-addon input-group-prepend border-left">
                                    <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                                </span>
                                <input type="text" class="form-control" id="impdate" name="impdate" value="{{old('impdate')}}" required>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="table-responsive mb-3">
                    <fieldset>
                        <legend>chi tiết nhập nguyên liệu</legend>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Nguyên liệu</th>
                                    <th class="text-center">ĐVT</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center">Thành tiền</th>
                                    <th class="text-center"><a id="add" class="btn btn-success btn-sm text-white"><i class="mdi mdi-plus ml-0"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control form-control-md text-capitalize" id="idma" name="idma[]" required>
                                            <option value="" selected="true" disabled="true">--chọn--</option>
                                            @foreach($mas as $ma)
                                            <option value="{{$ma->idma}}">{{$ma->maname}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm bg-white text-capitalize" id="impunit" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" id="impamount" name="impamount[]" required min="0" max="1000000" step="0.01">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" id="impprice" name="impprice[]" required min="1" max="1000000000">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm imptotal bg-white" id="imptotal" name="imptotal[]" readonly>
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
                    </fieldset>
                </div>
                <div>
                    <button class="btn btn-success btn-icon-text float-right ml-2" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                    <a href="{{route('import')}}" class="btn btn-secondary btn-icon-text float-right"><i class="mdi mdi-cancel btn-icon-prepend"></i>Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end list of import-->
@endsection