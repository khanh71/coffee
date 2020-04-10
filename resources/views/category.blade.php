@extends('app')
@section('title','Quản lý Thực đơn')
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
        text: "Xóa thực đơn thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Thực đơn này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewCate_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditCate_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#procatename').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idprocate = button.data('idprocate');
        var procatename = button.data('procatename');
        var model = $(this);
        model.find('#idprocate').val(idprocate);
        model.find('#procatenameedit').val(procatename);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idprocate = button.data('idprocate');
        var model = $(this);
        model.find('#idprocatedel').val(idprocate);
    })
</script>
@endsection

@section('content')
<!--list of procate-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Thực đơn</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('category')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập thực đơn bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
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
                                <th>Tên thực đơn</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($procates as $key=>$procate)
                            <tr id="{{$procate->idprocate}}">
                                <td>{{$key+1}}</td>
                                <td>{{$procate->procatename}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-idprocate="{{$procate->idprocate}}" data-procatename="{{$procate->procatename}}" data-toggle="modal" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idprocate="{{$procate->idprocate}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end list of procate-->

<!--add procate-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm thực đơn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newCateForm" method="post" action="{{route('new-category')}}">
                    @if(count($errors->postNewCate_Error)>0)
                    @foreach($errors->postNewCate_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Tên thực đơn</label>
                        <input type="text" class="form-control text-capitalize" id="procatename" name="procatename" required maxlength="100" value="{{old('procatename')}}">
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
<!--end add procate-->

<!--edit procate-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa thực đơn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editCateForm" method="post" action="{{route('edit-category')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idprocate" id="idprocate" value="{{old('idprocate')}}">
                    @if(count($errors->postEditCate_Error)>0)
                    @foreach($errors->postEditCate_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label>Tên thực đơn</label>
                        <input type="text" class="form-control text-capitalize" id="procatenameedit" name="procatenameedit" required maxlength="100" autofocus value="{{old('procatenameedit')}}">
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
<!--end edit procate-->

<!--delete procate-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa thực đơn này?</p>
                    <p>Xin hãy đảm bảo rằng thực đơn này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-category')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idprocatedel" id="idprocatedel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete procate-->
@endsection