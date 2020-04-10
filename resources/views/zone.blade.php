@extends('app')
@section('title','Quản lý Khu vực')
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
        text: "Xóa khu vực thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Khu vực này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewZone_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditZone_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#zonename').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idzone = button.data('idzone');
        var zonename = button.data('zonename');
        var model = $(this);
        model.find('#idzone').val(idzone);
        model.find('#zonenameedit').val(zonename);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idzone = button.data('idzone');
        var model = $(this);
        model.find('#idzonedel').val(idzone);
    })
</script>
@endsection

@section('content')
<!--list of zone-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Khu vực</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('zone')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập khu vực bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
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
                                <th>Tên khu vực</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($zones as $key=>$zone)
                            <tr id="{{$zone->idzone}}">
                                <td>{{$key+1}}</td>
                                <td>{{$zone->zonename}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-idzone="{{$zone->idzone}}" data-zonename="{{$zone->zonename}}" data-toggle="modal" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idzone="{{$zone->idzone}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
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
<!--end list of zone-->

<!--add zone-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm khu vực</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newZoneForm" method="post" action="{{route('new-zone')}}">
                    @if(count($errors->postNewZone_Error)>0)
                    @foreach($errors->postNewZone_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="zonename">Tên khu vực</label>
                        <input type="text" class="form-control text-capitalize" id="zonename" name="zonename" required maxlength="100" value="{{old('zonename')}}">
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
<!--end add zone-->

<!--edit zone-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa khu vực</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editZoneForm" method="post" action="{{route('edit-zone')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idzone" id="idzone" value="{{old('idzone')}}">
                    @if(count($errors->postEditZone_Error)>0)
                    @foreach($errors->postEditZone_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="zonenameedit">Tên khu vực</label>
                        <input type="text" class="form-control text-capitalize" id="zonenameedit" name="zonenameedit" required maxlength="100" autofocus value="{{old('zonenameedit')}}">
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
<!--end edit zone-->

<!--delete zone-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa khu vực này?</p>
                    <p>Xin hãy đảm bảo rằng khu vực này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-zone')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idzonedel" id="idzonedel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete zone-->
@endsection