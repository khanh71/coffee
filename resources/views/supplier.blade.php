@extends('app')
@section('title','Quản lý Nhà cung cấp')
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
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/form-validation.js')}}"></script>
<script src="{{asset('vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
<script src="{{asset('js/bt-maxLength.js')}}"></script>
<script src="{{asset('vendors/inputmask/jquery.inputmask.bundle.js')}}"></script>
<script src="{{asset('vendors/inputmask/inputmask.binding.js')}}"></script>
<script src="{{asset('vendors/inputmask/phone-vn.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
@if(Session::has('succ')) <script>
    swal({
        title: "Thành công",
        text: "Xóa nhà cung cấp thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Nhà cung cấp này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewSupplier_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditSupplier_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#suppname').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idsupp = button.data('idsupp');
        var suppname = button.data('suppname');
        var suppaddress = button.data('suppaddress');
        var suppphone = button.data('suppphone');
        var model = $(this);
        model.find('#idsupp').val(idsupp);
        model.find('#suppnameedit').val(suppname);
        model.find('#suppaddressedit').val(suppaddress);
        model.find('#suppphoneedit').val(suppphone);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idsupp = button.data('idsupp');
        var model = $(this);
        model.find('#idsuppdel').val(idsupp);
    })
</script>
@endsection

@section('content')
<!--list of supplier-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Nhà cung cấp</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('supplier')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập nhà cung cấp bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                            </span>
                        </div>
                    </form>
                    <div class="col-md-1 text-right"><button class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#new"><i class="mdi mdi-plus"></i></button></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nhà cung cấp</th>
                                <th>Địa chỉ</th>
                                <th>Điện thoại</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $key=>$supplier)
                            <tr id="{{$supplier->idsupplier}}">
                                <td>{{$key+1}}</td>
                                <td>{{$supplier->suppname}}</td>
                                <td>{{$supplier->suppaddress}}</td>
                                <td>{{$supplier->suppphone}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-idsupp="{{$supplier->idsupp}}" data-suppname="{{$supplier->suppname}}" data-suppaddress="{{$supplier->suppaddress}}" data-suppphone="{{$supplier->suppphone}}" data-toggle="modal" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idsupp="{{$supplier->idsupp}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end list of supplier-->

<!--add supplier-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm nhà cung cấp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newSupplierForm" method="post" action="{{route('new-supplier')}}">
                    @if(count($errors->postNewSupplier_Error)>0)
                    @foreach($errors->postNewSupplier_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Tên nhà cung cấp</label>
                        <input type="text" class="form-control text-capitalize" id="suppname" name="suppname" required maxlength="50" value="{{old('suppname')}}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control text-capitalize" id="suppaddress" name="suppaddress" required maxlength="100" value="{{old('suppaddress')}}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control form-control-lg" id="suppphone" name="suppphone" data-inputmask="'alias': 'phonevn'" required value="{{old('suppphone')}}">
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
<!--end add supplier-->

<!--edit supplier-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa nhà cung cấp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editSupplierForm" method="post" action="{{route('edit-supplier')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idsupp" id="idsupp" value="{{old('idsupp')}}">
                    @if(count($errors->postEditSupplier_Error)>0)
                    @foreach($errors->postEditSupplier_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="suppnameedit">Tên nhà cung cấp</label>
                        <input type="text" class="form-control text-capitalize" id="suppnameedit" name="suppnameedit" required maxlength="100" autofocus value="{{old('suppnameedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control text-capitalize" id="suppaddressedit" name="suppaddressedit" required maxlength="100" value="{{old('suppaddressedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control form-control-lg" id="suppphoneedit" name="suppphoneedit" data-inputmask="'alias': 'phonevn'" required value="{{old('suppphoneedit')}}">
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
<!--end edit supplier-->

<!--delete supplier-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa nhà cung cấp này?</p>
                    <p>Xin hãy đảm bảo rằng nhà cung cấp này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-supplier')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idsuppdel" id="idsuppdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete supplier-->
@endsection