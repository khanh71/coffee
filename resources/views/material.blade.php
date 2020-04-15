@extends('app')
@section('title','Quản lý Nguyên liệu')
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
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
@if(Session::has('succ')) <script>
    swal({
        title: "Thành công",
        text: "Xóa nguyên liệu thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Nguyên liệu này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    @if(count($errors -> postNewMaterial_Error) > 0)
    $('#new').modal('show');
    @endif
    @if(count($errors -> postEditMaterial_Error) > 0)
    $('#edit').modal('show');
    @endif

    $("#new").on('shown.bs.modal', function() {
        $(this).find('#maname').focus();
    });
    $('#edit').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idma = button.data('idma');
        var maname = button.data('maname');
        var maamount = button.data('maamount');
        var maprice = button.data('maprice');
        var unit = button.data('unit');
        var model = $(this);
        model.find('#idma').val(idma);
        model.find('#manameedit').val(maname);
        model.find('#maamountedit').val(maamount);
        model.find('#mapriceedit').val(maprice);
        model.find('#unitedit').val(unit);
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idma = button.data('idma');
        var model = $(this);
        model.find('#idmadel').val(idma);
    })
</script>
@endsection

@section('content')
<!--list of zone-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý Nguyên liệu</div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <form action="{{route('material')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập nguyên liệu bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                            </span>
                        </div>
                    </form>
                    <div class="col-md-1 text-right"><button class="btn btn-success btn-icon btn-rounded" @can('material.create') data-toggle="modal" data-target="#new" @else disabled @endcan><i class="mdi mdi-plus"></i></button></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nguyên liệu</th>
                                <th>Số lượng tồn</th>
                                <th>Giá nhập</th>
                                <th>Đơn vị tính</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($materials as $key=>$material)
                            <tr @if($material->maamount==0) class="text-danger" @endif>
                                <td>{{$key+1}}</td>
                                <td>{{$material->maname}}</td>
                                <td>{{$material->maamount}}</td>
                                <td>{{number_format($material->maprice).'₫'}}</td>
                                <td>{{$material->unit}}</td>
                                <td>
                                    <button class="btn btn-info btn-rounded btn-icon" @can('material.update') data-idma="{{$material->idma}}" data-maname="{{$material->maname}}" data-maname="{{$material->maname}}" data-maamount="{{$material->maamount}}" data-maprice="{{$material->maprice}}" data-unit="{{$material->unit}}" data-toggle="modal" data-target="#edit" @else disabled @endcan><i class="mdi mdi-pencil"></i></button>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" @can('material.delete') data-idma="{{$material->idma}}" data-toggle="modal" data-target="#delete" @else disabled @endcan><i class="mdi mdi-delete-forever"></i></button>
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
<!--end list of zone-->

<!--add zone-->
<div class="modal fade" id="new" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-plus-box-outline mr-1"></i>Thêm nguyên liệu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="newMaterialForm" method="post" action="{{route('new-material')}}">
                    @if(count($errors->postNewMaterial_Error)>0)
                    @foreach($errors->postNewMaterial_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Tên nguyên liệu</label>
                        <input type="text" class="form-control text-capitalize" id="maname" name="maname" required maxlength="100" value="{{old('maname')}}">
                    </div>
                    <div class="form-group">
                        <label>Giá nhập</label>
                        <input type="number" class="form-control text-capitalize" id="maprice" name="maprice" value="{{old('maprice')}}">
                    </div>
                    <div class="form-group">
                        <label>Đơn vị tính</label>
                        <input type="text" class="form-control text-capitalize" id="unit" name="unit" required maxlength="50" value="{{old('unit')}}">
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
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa nguyên liệu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="forms-sample" id="editMaterialForm" method="post" action="{{route('edit-material')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idma" id="idma" value="{{old('idma')}}">
                    @if(count($errors->postEditMaterial_Error)>0)
                    @foreach($errors->postEditMaterial_Error->all() as $err)
                    <div class="alert alert-fill-danger" role="alert">
                        <i class="mdi mdi-information-outline"></i>
                        {{$err}}
                    </div>
                    @endforeach
                    @endif
                    <div class="form-group">
                        <label>Tên nguyên liệu</label>
                        <input type="text" class="form-control text-capitalize" id="manameedit" name="manameedit" required maxlength="100" value="{{old('manameedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Số lượng tồn</label>
                        <input type="number" class="form-control text-capitalize" id="maamountedit" name="maamountedit" value="{{old('maamountedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Giá nhập</label>
                        <input type="number" class="form-control text-capitalize" id="mapriceedit" name="mapriceedit" value="{{old('mapriceedit')}}">
                    </div>
                    <div class="form-group">
                        <label>Đơn vị tính</label>
                        <input type="text" class="form-control text-capitalize" id="unitedit" name="unitedit" required maxlength="50" value="{{old('unitedit')}}">
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
                    <p>Bạn thực sự muốn xóa nguyên liệu này?</p>
                    <p>Xin hãy đảm bảo rằng nguyên liệu này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-material')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idmadel" id="idmadel">
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