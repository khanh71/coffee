@extends('app')
@section('title','Sửa Thực đơn')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
<script src="{{asset('vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('js/bt-maxLength.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script type="text/javascript">
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
        tr.find('#number').focus();
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
                tr.find('#impunit').val(data.unit);
            }
        });
    });

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
            '<input type="number" class="form-control form-control-sm" id="number" name="number[]" required min="0" max="1000000">' +
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
        <div class="card-title ribbon ribbon-info"><div class="glow"></div>Sửa thực đơn</div>
        <div class="row">
            <form class="col-12" id="newProductForm" method="post" action="{{route('edit-product',$pro->idpro)}}">
                @if(count($errors->postEditProduct_Error)>0)
                @foreach($errors->postEditProduct_Error->all() as $err)
                <div class="alert alert-fill-danger" role="alert">
                    <i class="mdi mdi-information-outline"></i>
                    {{$err}}
                </div>
                @endforeach
                @endif
                {{csrf_field()}}
                <input type="hidden" name="idpro" value="{{$pro->idpro}}">
                <div class="mb-3">
                    <fieldset>
                        <legend>Thông tin thực đơn</legend>
                        <div class="form-group">
                            <label><b>Danh mục:</b></label>
                            <select class="form-control form-control-md text-capitalize" id="idprocate" name="idprocate" value="{{$pro->procateid}}">
                                @foreach($procates as $procate)
                                <option value="{{$procate->idprocate}}">{{$procate->procatename}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Tên thực đơn:</b></label>
                            <input type="text" class="form-control text-capitalize" id="proname" name="proname" value="{{$pro->proname}}" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label><b>Giá bán:</b></label>
                            <input type="number" class="form-control" id="proprice" name="proprice" value="{{$pro->proprice}}" required min="1" max="100000000">
                        </div>
                    </fieldset>

                </div>
                <div class="table-responsive mb-3 text-left">
                    <fieldset>
                        <legend>Công thức</legend>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Nguyên liệu</th>
                                    <th class="text-center">ĐVT</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-center"><a id="add" class="btn btn-info btn-sm text-white"><i class="mdi mdi-plus ml-0"></i></a></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fos as $fo)
                                <tr>
                                    <td>
                                        <select class="form-control form-control-md text-capitalize" id="idma" name="idma[]" required>
                                            <option value="" disabled="true">--chọn--</option>
                                            @foreach($mas as $ma)
                                            <option value="{{$ma->idma}}" @if($ma->idma == $fo->maid) selected @endif>{{$ma->maname}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm bg-white text-capitalize" id="impunit" readonly value="{{$fo->unit}}">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" id="number" name="number[]" required value="{{$fo->number}}" min="0" max="1000000">
                                    </td>
                                    <td class="text-center">
                                        <a id="remove" class="btn btn-danger btn-sm text-white"><i class="mdi mdi-delete ml-0"></i></a>
                                        <input type="hidden" name="idfo[]" value="{{$fo->idfo}}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                <div>
                    <button class="btn btn-success btn-icon-text float-right ml-2" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                    <a href="{{route('product')}}" class="btn btn-secondary btn-icon-text float-right"><i class="mdi mdi-cancel btn-icon-prepend"></i>Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end list of import-->
@endsection