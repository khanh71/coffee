@extends('app')
@section('title','Quản lý Nhập kho nguyên liệu')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('js/data-table.js')}}"></script>
<script src="{{asset('js/modal-demo.js')}}"></script>
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
@if(Session::has('succ')) <script>
    swal({
        title: "Thành công",
        text: "Xóa phiếu nhập kho thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Phiếu nhập kho này không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idimp = button.data('idimp');
        var model = $(this);
        model.find('#idimpdel').val(idimp);
    })

    $('#view').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idimp = button.data('idimp');
        var model = $(this);
        model.find('#body-detail').remove();
        model.find('#tb-detail').append('<tbody id="body-detail"></tbody>');
        var dataId = {
            'idimp': idimp
        };
        $.ajax({
            type: 'GET',
            url: '{{route("import-detail-view")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                $.each(data, function(k, v) {
                    $('#body-detail').append('<tr><td class="text-capitalize">' + data[k].maname + '</td>' +
                        '<td class="text-capitalize">' + data[k].unit + '</td>' +
                        '<td>' + data[k].impamount + '</td>' +
                        '<td>' + formatNumber(data[k].impprice) + '</td>' +
                        '<td>' + formatNumber(data[k].imptotal) + '</td></tr>');
                })
            }
        });
    })

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + "₫"
    }

    $('#datepicker-popup-dayfrom').datepicker('update', new Date({{$dayfrom->year}},{{$dayfrom->month-1}},{{$dayfrom->day}}));
    $('#datepicker-popup-dayto').datepicker('update', new Date({{$dayto->year}},{{$dayto->month-1}},{{$dayto->day}}));

    $("#datepicker-popup-dayfrom").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });
    $("#datepicker-popup-dayto").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });
</script>


@endsection

@section('content')
<!--list of import-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Nhập kho nguyên liệu</div>
        <div class="row">
            <div class="col-12">
                @if($mas->count()>0 && $supps->count()>0)
                <div class="row">
                    <form class="form-inline col-md-11" action="{{route('import')}}" method="POST">
                        {{csrf_field()}}
                        <label class="mr-1 font-weight-bold">Từ ngày:</label>
                        <div id="datepicker-popup-dayfrom" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayfrom" name="dayfrom" required>
                        </div>
                        <label class="mr-1 font-weight-bold">Đến ngày:</label>
                        <div id="datepicker-popup-dayto" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayto" name="dayto" required>
                        </div>
                        <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                    </form>
                    <div class="col-md-1 text-right">@can('import.create')<a href="{{route('new-import')}}"><button class="btn btn-success btn-icon btn-rounded"><i class="mdi mdi-plus"></i></button></a>@else <button class="btn btn-success btn-icon btn-rounded" disabled><i class="mdi mdi-plus"></i></button> @endcan</div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Nhà cung cấp</th>
                                <th>Giá trị</th>
                                <th>Ngày nhập</th>
                                <th>Chi tiết</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($imps as $key=>$imp)
                            <tr id="{{$imp->idimp}}">
                                <td>{{$key+1}}</td>
                                <td>{{$imp->suppname}}</td>
                                <td>{{number_format($imp->total).'₫'}}</td>
                                <td>{{date('d/m/Y',strtotime($imp->impdate))}}</td>
                                <td>
                                    <button class="btn btn-dark btn-rounded btn-icon" data-idimp="{{$imp->idimp}}" data-toggle="modal" data-target="#view"><i class="mdi mdi-eye"></i></button>
                                </td>
                                <td>
                                    @can('import.update')<a href="{{route('edit-import',$imp->idimp)}}"><button class="btn btn-info btn-rounded btn-icon"><i class="mdi mdi-pencil"></i></button></a>@else <button class="btn btn-info btn-rounded btn-icon" disabled><i class="mdi mdi-pencil"></i></button> @endcan
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" @can('import.delete') data-idimp="{{$imp->idimp}}" data-toggle="modal" data-target="#delete" @else disabled @endcan><i class="mdi mdi-delete-forever"></i></button>
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
                @elseif($mas->count()==0)
                <div class="text-center">
                    <p>Chưa có nguyên liệu nào trong cửa hàng được thiết lập.</p>
                    <p>Vui lòng thêm nguyên liệu vào cửa hàng và quay lại sau nhé.</p>
                    <a class="btn btn-primary text-capitalize" href="{{route('material')}}">Thêm nguyên liệu</a>
                </div>
                @elseif($supps->count()==0)
                <div class="text-center">
                    <p>Chưa có nhà cung cấp nào trong cửa hàng được thiết lập.</p>
                    <p>Vui lòng thêm nhà cung cấp vào cửa hàng và quay lại sau nhé.</p>
                    <a class="btn btn-primary text-capitalize" href="{{route('supplier')}}">Thêm nhà cung cấp</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--end list of import-->

<!--view import-->
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-eye-outline mr-1"></i>Chi tiết phiếu nhập</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="w-100 text-center text-capitalize mb-2 font-weight-bold">danh sách nhập nguyên liệu</div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tb-detail">
                        <thead>
                            <tr>
                                <th>Nguyên liệu</th>
                                <th>ĐVT</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;"></div>
        </div>
    </div>
</div>
<!--end view import-->

<!--delete import-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa phiếu nhập này?</p>
                    <p>Xin hãy đảm bảo rằng phiếu nhập này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-import')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idimpdel" id="idimpdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete import-->
@endsection