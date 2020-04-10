@extends('app')
@section('title','Quản lý nhân viên')
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
        text: "Xóa nhân viên thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Nhân viên này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewEmployee_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditEmployee_Error) > 0)
    $('#edit').modal('show');
    @endif


    $("#new").on('shown.bs.modal', function() {
        $(this).find('#name').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var iduser = button.data('iduser');
        var name = button.data('name');
        var address = button.data('address');
        var birthday = button.data('birthday');
        var phone = button.data('phone');
        var startday = button.data('startday');
        var idposition = button.data('idposition');
        var basesalary = button.data('basesalary');
        var model = $(this);

        model.find('#iduser').val(iduser);
        model.find('#nameedit').val(name);
        model.find('#addressedit').val(address);
        model.find('#birthdayedit').val(birthday);
        model.find('#phoneedit').val(phone);
        model.find('#addressedit').val(address);
        model.find('#startdayedit').val(startday);
        model.find('#idposedit').val(idposition);
        model.find('#basesalaryedit').val(basesalary);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var iduser = button.data('iduser');
        var model = $(this);
        model.find('#iduserdel').val(iduser);
    })
</script>
@endsection

@section('content')
<!--list of empolyee-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Nhân viên</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('employee')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập nhân viên bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
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
                                <th>Họ tên</th>
                                <th>Địa chỉ</th>
                                <th>Ngày sinh</th>
                                <th>Số điện thoại</th>
                                <th>Ngày vào làm</th>
                                <th>Chức vụ</th>
                                <th>Lương cơ bản</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $key=>$emp)
                            <tr id="{{$emp->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{$emp->name}}</td>
                                <td>{{$emp->address}}</td>
                                <td>{{$emp->birthday}}</td>
                                <td>{{$emp->phone}}</td>
                                <td>{{$emp->startday}}</td>
                                <td>{{$emp->posname}}</td>
                                <td>{{number_format($emp->basesalary).'₫'}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" data-toggle="modal" data-iduser="{{$emp->id}}" data-name="{{$emp->name}}" data-address="{{$emp->address}}" data-birthday="{{$emp->birthday}}" data-phone="{{$emp->phone}}" data-startday="{{$emp->startday}}" data-idposition="{{$emp->posid}}" data-basesalary="{{$emp->basesalary}}" data-target="#edit"><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-toggle="modal" data-iduser="{{$emp->id}}" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{$employees->appends(['search'=>$search])->links('pagination')}}
                </div>
            </div>
        </div>
    </div>
</div>
<!--end list of empolyee-->

<!--add empolyee-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-account-plus mr-1"></i>Thêm nhân viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newEmployeeForm" method="post" action="{{route('new-employee')}}">
                    @if(count($errors->postNewEmployee_Error)>0)
                    @foreach($errors->postNewEmployee_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Họ tên</label>
                        <input type="text" class="form-control form-control-lg text-capitalize" id="name" name="name" maxLength='50' required value="{{old('name')}}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control form-control-lg text-capitalize" id="address" name="address" maxLength='150' required value="{{old('address')}}">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="text" class="form-control form-control-lg" id="birthday" name="birthday" data-inputmask="'alias': 'date','placeholder': '_'" required value="{{old('birthday')}}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control form-control-lg" id="phone" name="phone" data-inputmask="'alias': 'phonevn'" required value="{{old('phone')}}">
                    </div>
                    <div class="form-group">
                        <label>Ngày vào làm</label>
                        <input type="text" class="form-control form-control-lg" id="startday" name="startday" data-inputmask="'alias': 'date','placeholder': '_'" required value="{{old('startday')}}">
                    </div>
                    <div class="form-group">
                        <label>Chức vụ</label>
                        <select class="form-control form-control-lg text-capitalize" id="idpos" name="idpos" value="{{old('idpos')}}">
                            @foreach($positions as $pos)
                            <option value="{{$pos->idpos}}">{{$pos->posname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lương cơ bản</label>
                        <input class="form-control text-capitalize" id="basesalary" name="basesalary" data-inputmask="'alias': 'currency'" required value="{{old('basesalary')}}">
                    </div>
                    <div class="form-group">
                        <label>Tên đăng nhập</label>
                        <input type="text" class="form-control form-control-lg" id="email" name="email" maxLength='50' required value="{{$gener_username}}">
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu</label>
                        <input type="text" class="form-control form-control-lg" id="password" name="password" maxLength='50' required value="{{$gener_pass}}">
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
<!--end add empolyee-->

<!--edit empolyee-->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-account-convert mr-1"></i>Sửa nhân viên</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editEmployeeForm" method="post" action="{{route('edit-employee')}}">
                    @if(count($errors->postEditEmployee_Error)>0)
                    @foreach($errors->postEditEmployee_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <input type="hidden" name="iduser" id="iduser" value="{{old('iduser')}}">
                    <div class="form-group">
                        <label>Họ tên</label>
                        <input type="text" class="form-control form-control-lg text-capitalize" id="nameedit" name="nameedit" maxLength='50' required value="{{old('nameedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ</label>
                        <input type="text" class="form-control form-control-lg text-capitalize" id="addressedit" name="addressedit" maxLength='150' required value="{{old('addressedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label>
                        <input type="text" class="form-control form-control-lg" id="birthdayedit" name="birthdayedit" data-inputmask="'alias': 'date','placeholder': '_'" required value="{{old('birthdayedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" class="form-control form-control-lg" id="phoneedit" name="phoneedit" data-inputmask="'alias': 'phonevn'" required value="{{old('phoneedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Ngày vào làm</label>
                        <input type="text" class="form-control form-control-lg" id="startdayedit" name="startdayedit" data-inputmask="'alias': 'date','placeholder': '_'" required value="{{old('startdayedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Chức vụ</label>
                        <select class="form-control form-control-lg text-capitalize" id="idposedit" name="idposedit" value="{{old('idposedit')}}">
                            @foreach($positions as $pos)
                            <option value="{{$pos->idpos}}">{{$pos->posname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lương cơ bản</label>
                        <input class="form-control text-capitalize" id="basesalaryedit" name="basesalaryedit" data-inputmask="'alias': 'currency'" required value="{{old('basesalaryedit')}}">
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
<!--end edit empolyee-->

<!--delete empolyee-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa nhân viên này?</p>
                    <p>Xin hãy đảm bảo rằng nhân viên này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-employee')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="iduserdel" id="iduserdel">
                    <div class="confirm">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                        <button class="btn btn-danger" type="submit">Xóa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end delete empolyee-->

@endsection