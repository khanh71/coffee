@extends('app')
@section('title','Quản lý Chấm công')
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
<script src="{{asset('vendors/moment/moment.min.js')}}"></script>
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
        text: "Xóa chấm công thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Chấm công này không thể xóa",
        icon: "error"
    });
</script> @endif

@if(Session::has('wderr')) <script>
    swal({
        title: "Lỗi",
        text: "Nhân viên đã được chấm công trong ngày này. Vui lòng chọn ngày khác.",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewWorkday_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditWorkday_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#iduser').focus();
        $('#datepicker-popup-daywork').datepicker('update', new Date());
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idwd = button.data('idwd');
        var name = button.data('name');
        var hour = button.data('hour');
        var model = $(this);
        model.find('#idwd').val(idwd);
        model.find('#nameedit').val(name);
        model.find('#houredit').val(hour);
        model.find('#datepicker-popup-daywork-edit').datepicker('update', new Date({{$dayfrom->year}},{{$dayfrom->month-1}},{{$dayfrom->day}}));
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idwd = button.data('idwd');
        var model = $(this);
        model.find('#idwddel').val(idwd);
    })

    $('#datepicker-popup-dayfrom').datepicker('update', new Date({{$dayfrom->year}},{{$dayfrom->month-1}},{{$dayfrom->day}}));
    $('#datepicker-popup-dayto').datepicker('update', new Date({{$dayto->year}},{{$dayto->month-1}},{{$dayto->day}}));

    $("#datepicker-popup-dayfrom").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });
    $("#datepicker-popup-dayto").datepicker().on('dp.show', function(event) {
        event.stopPropagation();
    });
    $("#datepicker-popup-daywork-edit").datepicker().on('show.bs.modal', function(event) {
        event.stopPropagation();
    });
</script>


@endsection

@section('content')
<!--list of wd-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Chấm công</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form class="form-inline col-md-11" action="{{route('workday')}}" method="POST">
                        {{csrf_field()}}
                        <label class="mr-1">Từ ngày</label>
                        <div id="datepicker-popup-dayfrom" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayfrom" name="dayfrom" required>
                        </div>
                        <label class="mr-1">Đến ngày</label>
                        <div id="datepicker-popup-dayto" class="input-group date datepicker mr-2">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="dayto" name="dayto" required>
                        </div>
                        <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                    </form>
                    <div class="col-md-1 text-right"><button class="btn btn-success btn-icon btn-rounded" data-toggle="modal" data-target="#new"><i class="mdi mdi-plus"></i></button></div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nhân viên</th>
                                <th>Số giờ làm</th>
                                @if($dayfrom == $dayto)
                                <th>Sửa</th>
                                <th>Xóa</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wds as $key=>$wd)
                            @if($dayfrom == $dayto)
                            <tr id="{{$wd->idwd}}">
                                <td>{{$key+1}}</td>
                                <td>{{$wd->name}}</td>
                                <td>{{$wd->hour}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-idwd="{{$wd->idwd}}" data-hour="{{$wd->hour}}" data-name="{{$wd->name}}" data-toggle="modal" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idwd="{{$wd->idwd}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
                                </td>
                            </tr>
                            @else
                            <tr id="{{$wd->idwd}}">
                                <td>{{$key+1}}</td>
                                <td>{{$wd->name}}</td>
                                <td>{{$wd->hour}}</td>
                            </tr>
                            @endif
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
<!--end list of wd-->

<!--add wd-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="ModalLabel">Thêm chấm công</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newWorkdayForm" method="post" action="{{route('new-workday')}}">
                    @if(count($errors->postNewWorkday_Error)>0)
                    @foreach($errors->postNewWorkday_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="wdname">Nhân viên</label>
                        <select class="form-control form-control-lg text-capitalize" id="iduser" name="iduser" value="{{old('iduser')}}">
                            @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Số giờ làm</label>
                        <input type="number" class="form-control form-control-lg" id="hour" name="hour" value="{{old('hour')}}" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày chấm công</label>
                        <div id="datepicker-popup-daywork" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="wddate" name="wddate" value="{{old('wddate')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-rounded btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        <button type="button" class="btn btn-light btn-rounded" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end add wd-->

<!--edit wd-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" id="ModalLabel">Sửa chấm công</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editWorkdayForm" method="post" action="{{route('edit-workday')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idwd" id="idwd" value="{{old('idwd')}}">
                    @if(count($errors->postEditWorkday_Error)>0)
                    @foreach($errors->postEditWorkday_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label for="wdnameedit">Nhân viên</label>
                        <input type="text" class="form-control text-capitalize" id="nameedit" disabled>
                    </div>
                    <div class="form-group">
                        <label>Số giờ làm</label>
                        <input type="number" class="form-control form-control-lg" id="houredit" name="houredit" value="{{old('houredit')}}" required>
                    </div>
                    <div class="form-group">
                        <label>Ngày chấm công</label>
                        <div id="datepicker-popup-daywork-edit" class="input-group date datepicker">
                            <span class="input-group-addon input-group-prepend border-left">
                                <span class="mdi mdi-calendar input-group-text bg-primary text-white"></span>
                            </span>
                            <input type="text" class="form-control" id="wddateedit" name="wddateedit" value="{{old('wddateedit')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success btn-rounded btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        <button type="button" class="btn btn-light btn-rounded" data-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end edit wd-->

<!--delete wd-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa chấm công này?</p>
                    <p>Xin hãy đảm bảo rằng chấm công này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-workday')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idwddel" id="idwddel">
                    <div class="confirm">
                        <button type="button" class="btn btn-primary btn-rounded" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger btn-rounded" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete wd-->
@endsection