@extends('app')
@section('title','Quản lý khuyến mãi')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
<link rel="stylesheet" href="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">
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
        text: "Xóa khuyến mãi thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "khuyến mãi này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewVoucher_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditVoucher_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#sale").on('keyup', function() {
        var val = $(this).val();
        if (val == '' || val <= 100)
            $('#syl').text('%');
        else
            $('#syl').text('₫');
    });

    $("#saleedit").on('keyup', function() {
        var val = $(this).val();
        if (val == '' || val <= 100)
            $('#syledit').text('%');
        else
            $('#syledit').text('₫');
    });

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#vouchername').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idvoucher = button.data('idvoucher');
        var vouchername = button.data('vouchername');
        var sale = button.data('sale');
        var startday = button.data('startday');
        var endday = button.data('endday');
        var model = $(this);
        model.find('#idvoucher').val(idvoucher);
        model.find('#vouchernameedit').val(vouchername);
        model.find('#saleedit').val(sale);
        if (sale <= 100)
            model.find('#syledit').text('%');
        else
            model.find('#syledit').text('₫');
        model.find('#datepicker-popup-startday-edit').datepicker('update', new Date(startday));
        model.find('#datepicker-popup-endday-edit').datepicker('update', new Date(endday))
    })

    $("#datepicker-popup-startday-edit").datepicker().on('show.bs.modal', function(event) {
        event.stopPropagation();
    });

    $("#datepicker-popup-endday-edit").datepicker().on('show.bs.modal', function(event) {
        event.stopPropagation();
    });

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idvoucher = button.data('idvoucher');
        var model = $(this);
        model.find('#idvoucherdel').val(idvoucher);
    })
</script>
@endsection

@section('content')
<!--list of voucher-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý khuyến mãi</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('voucher')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập khuyến mãi bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                            </span>
                        </div>
                    </form>
                    <div class="col-md-1 text-right"><button class="btn btn-success btn-icon btn-rounded" @can('voucher.create') data-toggle="modal" data-target="#new" @else disabled @endcan><i class="mdi mdi-plus"></i></button></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên khuyến mãi</th>
                                <th>Giảm</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vouchers as $key=>$voucher)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$voucher->vouchername}}</td>
                                <td>@if($voucher->sale<=100) {{$voucher->sale.'%'}} @else {{number_format($voucher->sale).'₫'}} @endif</td> <td>{{date('d/m/Y',strtotime($voucher->startday))}}</td>
                                <td>{{date('d/m/Y',strtotime($voucher->endday))}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" @can('voucher.update') data-idvoucher="{{$voucher->idvoucher}}" data-vouchername="{{$voucher->vouchername}}" data-sale="{{$voucher->sale}}" data-startday="{{$voucher->startday}}" data-endday="{{$voucher->endday}}" data-toggle="modal" data-target="#edit" @else disabled @endcan><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" @can('voucher.delete') data-idvoucher="{{$voucher->idvoucher}}" data-toggle="modal" data-target="#delete" @else disabled @endcan><i class="mdi mdi-delete-forever"></i></button>
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
<!--end list of voucher-->

<!--add voucher-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm khuyến mãi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newVoucherForm" method="post" action="{{route('new-voucher')}}">
                    @if(count($errors->postNewVoucher_Error)>0)
                    @foreach($errors->postNewVoucher_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="vouchername">Tên khuyến mãi</label>
                        <input type="text" class="form-control text-capitalize" id="vouchername" name="vouchername" required maxlength="100" required value="{{old('vouchername')}}">
                    </div>
                    <div class="form-group">
                        <label>Giảm giá</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white" id="syl">%</span>
                            </div>
                            <input type="number" class="form-control form-control-lg" id="sale" name="sale" value="{{old('sale')}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <div id="datepicker-popup-startday" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="startday" name="startday" value="{{old('startday')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <div id="datepicker-popup-endday" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="endday" name="endday" value="{{old('endday')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-success btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end add voucher-->

<!--edit voucher-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa khuyến mãi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editVoucherForm" method="post" action="{{route('edit-voucher')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idvoucher" id="idvoucher" value="{{old('idvoucher')}}">
                    @if(count($errors->postEditVoucher_Error)>0)
                    @foreach($errors->postEditVoucher_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="vouchername">Tên khuyến mãi</label>
                        <input type="text" class="form-control text-capitalize" id="vouchernameedit" name="vouchernameedit" required maxlength="100" required value="{{old('vouchernameedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Giảm giá</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary text-white" id="syledit">%</span>
                            </div>
                            <input type="number" class="form-control form-control-lg" id="saleedit" name="saleedit" value="{{old('saleedit')}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ngày bắt đầu</label>
                        <div id="datepicker-popup-startday-edit" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="startdayedit" name="startdayedit" value="{{old('startdayedit')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Ngày kết thúc</label>
                        <div id="datepicker-popup-endday-edit" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="enddayedit" name="enddayedit" value="{{old('enddayedit')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-info btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end edit voucher-->

<!--delete voucher-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa khuyến mãi này?</p>
                    <p>Xin hãy đảm bảo rằng khuyến mãi này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-voucher')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idvoucherdel" id="idvoucherdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete voucher-->
@endsection