@extends('app')
@section('title','Quản lý Bàn')
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
        text: "Xóa bàn thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Bàn này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewDesk_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditDesk_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#deskname').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var iddesk = button.data('iddesk');
        var deskname = button.data('deskname');
        var zoneid = button.data('zoneid');
        var model = $(this);
        model.find('#iddesk').val(iddesk);
        model.find('#desknameedit').val(deskname);
        model.find('#idzoneedit').val(zoneid);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var iddesk = button.data('iddesk');
        var model = $(this);
        model.find('#iddeskdel').val(iddesk);
    })
</script>
@endsection

@section('content')
<!--list of desk-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Bàn</div>
        <div class="row">
            <div class="col-12">
                @if($zones->count()>0)
                <div class="row">
                    <form action="{{route('desk')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập bàn bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                            </span>
                        </div>
                    </form>
                    <div class="col-md-1 text-right"><button class="btn btn-success btn-icon btn-rounded" @can('desk.create') data-toggle="modal" data-target="#new" @else disabled @endcan><i class="mdi mdi-plus"></i></button></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên bàn</th>
                                <th>Khu vực</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($desks as $key=>$desk)
                            <tr id="{{$desk->iddesk}}">
                                <td>{{$key+1}}</td>
                                <td>{{$desk->deskname}}</td>
                                <td>{{$desk->zonename}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" @can('desk.update') data-iddesk="{{$desk->iddesk}}" data-deskname="{{$desk->deskname}}" data-zoneid="{{$desk->zoneid}}" data-toggle="modal" data-target="#edit" @else disabled @endcan><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" @can('desk.delete') data-iddesk="{{$desk->iddesk}}" data-toggle="modal" data-target="#delete" @else disabled @endcan><i class="mdi mdi-delete-forever"></i></button>
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
                @else
                <div class="text-center">
                    <p>Chưa có khu vực nào trong cửa hàng được thiết lập.</p>
                    <p>Vui lòng thêm khu vực vào cửa hàng và quay lại sau nhé.</p>
                    <a class="btn btn-primary text-capitalize" href="{{route('zone')}}">Thêm khu vực</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--end list of desk-->

<!--add desk-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm bàn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newDeskForm" method="post" action="{{route('new-desk')}}">
                    @if(count($errors->postNewDesk_Error)>0)
                    @foreach($errors->postNewDesk_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="deskname">Tên Bàn</label>
                        <input type="text" class="form-control text-capitalize" id="deskname" name="deskname" required maxlength="50" value="{{old('deskname')}}">
                    </div>
                    <div class="form-group">
                        <label>Khu vực</label>
                        <select class="form-control form-control-lg text-capitalize" id="idzone" name="idzone" value="{{old('idzone')}}">
                            @foreach($zones as $zone)
                            <option value="{{$zone->idzone}}">{{$zone->zonename}}</option>
                            @endforeach
                        </select>
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
<!--end add desk-->

<!--edit desk-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa Bàn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editDeskForm" method="post" action="{{route('edit-desk')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="iddesk" id="iddesk" value="{{old('iddesk')}}">
                    @if(count($errors->postEditDesk_Error)>0)
                    @foreach($errors->postEditDesk_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="desknameedit">Tên Bàn</label>
                        <input type="text" class="form-control text-capitalize" id="desknameedit" name="desknameedit" required maxlength="50" autofocus value="{{old('desknameedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Khu vực</label>
                        <select class="form-control form-control-lg text-capitalize" id="idzoneedit" name="idzoneedit" value="{{old('idzoneedit')}}">
                            @foreach($zones as $zone)
                            <option value="{{$zone->idzone}}">{{$zone->zonename}}</option>
                            @endforeach
                        </select>
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
<!--end edit desk-->

<!--delete desk-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa bàn này?</p>
                    <p>Xin hãy đảm bảo rằng bàn này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-desk')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="iddeskdel" id="iddeskdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete desk-->
@endsection