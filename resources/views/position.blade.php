@extends('app')
@section('title','Quản lý chức vụ')
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
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
@if(Session::has('succ')) <script>
    swal({
        title: "Thành công",
        text: "Xóa chức vụ thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Chức vụ này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewPosition_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditPosition_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#posname').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idpos = button.data('idpos');
        var posname = button.data('posname');
        var coefficient = button.data('coefficient');
        var model = $(this);
        model.find('#idpos').val(idpos);
        model.find('#posnameedit').val(posname);
        model.find('#coefficientedit').val(coefficient);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idpos = button.data('idpos');
        var model = $(this);
        model.find('#idposdel').val(idpos);
    })
</script>
@endsection

@section('content')
<!--list of position-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Chức vụ</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('position')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập chức vụ bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
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
                                <th>Tên chức vụ</th>
                                <th>Hệ số lương</th>
                                <th>Phân quyền</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($positions as $key=>$pos)
                            <tr id="{{$pos->idpos}}">
                                <td>{{$key+1}}</td>
                                <td>{{$pos->posname}}</td>
                                <td>{{$pos->coefficient}}</td>
                                <td>
                                    @can('position.role')<a href="{{route('role',$pos->idpos)}}"><button class="btn btn-warning btn-rounded btn-icon"><i class="mdi mdi-key"></i></button></a>@else <button class="btn btn-warning btn-rounded btn-icon" disabled><i class="mdi mdi-key"></i></button> @endcan
                                </td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-idpos="{{$pos->idpos}}" data-posname="{{$pos->posname}}" data-coefficient="{{$pos->coefficient}}" data-toggle="modal" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idpos="{{$pos->idpos}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end list of position-->

<!--add position-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newPositionForm" method="post" action="{{route('new-position')}}">
                    @if(count($errors->postNewPosition_Error)>0)
                    @foreach($errors->postNewPosition_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="posname">Tên chức vụ</label>
                        <input type="text" class="form-control text-capitalize" id="posname" name="posname" maxlength="50" value="{{old('posname')}}">
                    </div>
                    <div class="form-group">
                        <label>Hệ số lương</label>
                        <input type="number" class="form-control text-capitalize" id="coefficient" name="coefficient" value="{{old('coefficient')}}">
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
<!--end add position-->

<!--edit position-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa chức vụ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editPositionForm" method="post" action="{{route('edit-position')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idpos" id="idpos" value="{{old('idpos')}}">
                    @if(count($errors->postEditPosition_Error)>0)
                    @foreach($errors->postEditPosition_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="posnameedit">Tên chức vụ</label>
                        <input type="text" class="form-control text-capitalize" id="posnameedit" name="posnameedit" required maxlength="50" autofocus value="{{old('posnameedit')}}">
                    </div>
                    <div class="form-group">
                        <label for="coefficientedit">Hệ số lương</label>
                        <input type="number" class="form-control text-capitalize" id="coefficientedit" name="coefficientedit" required min='0' max='20' value="{{old('coefficientedit')}}">
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
<!--end edit position-->

<!--delete position-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa chức vụ này?</p>
                    <p>Xin hãy đảm bảo rằng chức vụ này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-position')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idposdel" id="idposdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete position-->
@endsection