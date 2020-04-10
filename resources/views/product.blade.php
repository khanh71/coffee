@extends('app')
@section('title','Quản lý Món')
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
        text: "Xóa món thành công",
        icon: "success"
    });
</script> @endif

@if(Session::has('error')) <script>
    swal({
        title: "Lỗi",
        text: "Món này đã được sử dụng, không thể xóa",
        icon: "error"
    });
</script> @endif
<script type="text/javascript">
    $('#view').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idpro = button.data('idpro');
        var model = $(this);
        model.find('#body-detail').remove();
        model.find('#tb-detail').append('<tbody id="body-detail"></tbody>');
        var dataId = {
            'idpro': idpro
        };
        $.ajax({
            type: 'GET',
            url: '{{route("product-detail-view")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                $.each(data, function(k, v) {
                    $('#body-detail').append('<tr><td class="text-capitalize">' + data[k].maname + '</td>' +
                        '<td>' + data[k].number + '</td>' +
                        '<td>' + data[k].unit + '</td></tr>');
                })
            }
        });
    })

    $('#delete').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var idpro = button.data('idpro');
        var model = $(this);
        model.find('#idprodel').val(idpro);
    })
</script>
@endsection

@section('content')
<!--list of product-->
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-primary"><div class="glow"></div>Quản lý món</div>
        <div class="row">
            <div class="col-12">
                @if($mas->count()>0 && $cates->count()>0)
                <div class="row">
                    <form action="{{route('product')}}" method="post" class="col-md-11">
                        {{csrf_field()}}
                        <div class="form-group input-group">
                            <input name="search" type="text" class="form-control text-capitalize" placeholder="Nhập tên món bạn cần tìm vào đây nhé..." value="{{$search}}" autofocus>
                            <span class="input-group-append">
                                <button type="submit" class="btn btn-icon-text btn-primary"><i class="mdi mdi-magnify btn-icon-prepend"></i>Tìm Kiếm</button>
                            </span>
                        </div>
                    </form>
                    <div class="col-md-1 text-right"><a href="{{route('new-product')}}"><button class="btn btn-success btn-icon btn-rounded"><i class="mdi mdi-plus"></i></button></a></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên món</th>
                                <th>Giá bán</th>
                                <th>Thực đơn</th>
                                <th>Công thức</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pros as $key=>$pro)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$pro->proname}}</td>
                                <td>{{number_format($pro->proprice).'₫'}}</td>
                                <td>{{$pro->procatename}}</td>
                                <td>
                                    <button class="btn btn-dark btn-rounded btn-icon" data-idpro="{{$pro->idpro}}" data-toggle="modal" data-target="#view"><i class="mdi mdi-eye"></i></button>
                                </td>
                                <td>
                                    <a href="{{route('edit-product',$pro->idpro)}}"><button class="btn btn-info btn-rounded btn-icon"><i class="mdi mdi-pencil"></i></button></a>
                                </td>
                                <td>
                                    <button class="btn btn-icon btn-rounded btn-danger" data-idpro="{{$pro->idpro}}" data-toggle="modal" data-target="#delete"><i class="mdi mdi-delete-forever"></i></button>
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
                @elseif($cates->count()==0)
                <div class="text-center">
                    <p>Chưa có món nào trong cửa hàng được thiết lập.</p>
                    <p>Vui lòng thêm món vào cửa hàng và quay lại sau nhé.</p>
                    <a class="btn btn-primary text-capitalize" href="{{route('category')}}">Thêm món</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--end list of product-->

<!--view formula-->
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-eye-outline mr-1"></i>Công thức</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tb-detail">
                        <thead>
                            <tr>
                                <th>Nguyên liệu</th>
                                <th>Số lượng</th>
                                <th>ĐVT</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;"></div>
        </div>
    </div>
</div>
<!--end view formula-->

<!--delete zone-->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body-delete">
                <div class="confirm-message">
                    <img src="{{asset('images/alert.png')}}" alt="warning icon">
                    <h3 class="text-uppercase">Xóa?</h3>
                    <p>Bạn thực sự muốn xóa món này?</p>
                    <p>Xin hãy đảm bảo rằng món này chưa được sử dụng.</p>
                </div>
                <form class="forms-sample" method="POST" action="{{route('delete-product')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="idprodel" id="idprodel">
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