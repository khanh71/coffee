@extends('app')
@section('title','Phân quyền người dùng')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/datatables.net/jquery.dataTables.js')}}"></script>
<script src="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('js/data-table.js')}}"></script>
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-warning">
            <div class="glow"></div>Phân quyền {{$pos->posname}}
        </div>
        <div class="row">
            <div class="col-12">
                <form action="{{route('role',$pos->idpos)}}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Quyền</th>
                                    <th>Sử Dụng</th>
                                    <th>Thêm</th>
                                    <th>Sửa</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-uppercase font-weight-bold text-primary" style="font-size: large;"><i class="mdi mdi-chevron-left"></i><i class="mdi mdi-dots-horizontal"></i> quản lý <i class="mdi mdi-dots-horizontal"></i><i class="mdi mdi-chevron-right"></i></td>
                                </tr>
                                <tr>
                                    <td>Quản lý chức vụ</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[0]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[0]" value="1" @if($pos->permissions['position.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[0]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[0]" value="1" @if($pos->permissions['position.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[0]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[0]" value="1" @if($pos->permissions['position.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[0]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[0]" value="1" @if($pos->permissions['position.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý nhân viên</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[1]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[1]" value="1" @if($pos->permissions['employee.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[1]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[1]" value="1" @if($pos->permissions['employee.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[1]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[1]" value="1" @if($pos->permissions['employee.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[1]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[1]" value="1" @if($pos->permissions['employee.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý khu vực</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[2]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[2]" value="1" @if($pos->permissions['zone.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[2]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[2]" value="1" @if($pos->permissions['zone.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[2]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[2]" value="1" @if($pos->permissions['zone.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[2]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[2]" value="1" @if($pos->permissions['zone.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý bàn</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[3]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[3]" value="1" @if($pos->permissions['desk.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[3]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[3]" value="1" @if($pos->permissions['desk.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[3]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[3]" value="1" @if($pos->permissions['desk.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[3]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[3]" value="1" @if($pos->permissions['desk.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý nhà cung cấp</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[4]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[4]" value="1" @if($pos->permissions['supplier.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[4]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[4]" value="1" @if($pos->permissions['supplier.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[4]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[4]" value="1" @if($pos->permissions['supplier.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[4]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[4]" value="1" @if($pos->permissions['supplier.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý nguyên liệu</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[5]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[5]" value="1" @if($pos->permissions['material.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[5]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[5]" value="1" @if($pos->permissions['material.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[5]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[5]" value="1" @if($pos->permissions['material.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[5]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[5]" value="1" @if($pos->permissions['material.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý nhập kho</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[6]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[6]" value="1" @if($pos->permissions['import.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[6]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[6]" value="1" @if($pos->permissions['import.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[6]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[6]" value="1" @if($pos->permissions['import.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[6]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[6]" value="1" @if($pos->permissions['import.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý thực đơn</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[7]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[7]" value="1" @if($pos->permissions['productcate.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[7]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[7]" value="1" @if($pos->permissions['productcate.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[7]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[7]" value="1" @if($pos->permissions['productcate.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[7]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[7]" value="1" @if($pos->permissions['productcate.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý món</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[8]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[8]" value="1" @if($pos->permissions['product.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[8]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[8]" value="1" @if($pos->permissions['product.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[8]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[8]" value="1" @if($pos->permissions['product.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[8]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[8]" value="1" @if($pos->permissions['product.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý khuyến mãi</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[9]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[9]" value="1" @if($pos->permissions['voucher.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[9]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[9]" value="1" @if($pos->permissions['voucher.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[9]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[9]" value="1" @if($pos->permissions['voucher.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[9]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[9]" value="1" @if($pos->permissions['voucher.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quản lý chấm công</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="view[10]" value="0">
                                                <input type="checkbox" class="form-check-input" name="view[10]" value="1" @if($pos->permissions['workday.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-success check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="create[10]" value="0">
                                                <input type="checkbox" class="form-check-input" name="create[10]" value="1" @if($pos->permissions['workday.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-info check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="update[10]" value="0">
                                                <input type="checkbox" class="form-check-input" name="update[10]" value="1" @if($pos->permissions['workday.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-danger check-only">
                                            <label class="form-check-label">
                                                <input type="hidden" name="delete[10]" value="0">
                                                <input type="checkbox" class="form-check-input" name="delete[10]" value="1" @if($pos->permissions['workday.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>phân quyền</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="role" @if($pos->permissions['position.role'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>sửa thông tin cửa hàng</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="shop_update" @if($pos->permissions['shop.update'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-uppercase font-weight-bold text-primary" style="font-size: large;"><i class="mdi mdi-chevron-left"></i><i class="mdi mdi-dots-horizontal"></i> bán hàng <i class="mdi mdi-dots-horizontal"></i><i class="mdi mdi-chevron-right"></i></td>
                                </tr>
                                <tr>
                                    <td>bán hàng</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="sell_view" @if($pos->permissions['sell.view'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>gọi món</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="sell_create" @if($pos->permissions['sell.create'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>ghép bàn</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="sell_merge" @if($pos->permissions['sell.merge'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>trả bàn</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="sell_delete" @if($pos->permissions['sell.delete'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>thanh toán</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="sell_pay" @if($pos->permissions['sell.pay'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-uppercase font-weight-bold text-primary" style="font-size: large;"><i class="mdi mdi-chevron-left"></i><i class="mdi mdi-dots-horizontal"></i> báo cáo <i class="mdi mdi-dots-horizontal"></i><i class="mdi mdi-chevron-right"></i></td>
                                </tr>
                                <tr>
                                    <td>tính lương</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="report_salary" @if($pos->permissions['report.salary'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>báo cáo bán hàng</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="report_sell" @if($pos->permissions['report.sell'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>báo cáo lợi nhuận</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="report_profit" @if($pos->permissions['report.profit'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>chi phí nhập nguyên liệu</td>
                                    <td>
                                        <div class="form-check form-check-primary check-only">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="form-check-input" name="report_cost" @if($pos->permissions['report.cost'] == true) checked @endif>
                                            </label>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success btn-icon-text float-right ml-2" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        <a href="{{route('position')}}" class="btn btn-secondary btn-icon-text float-right"><i class="mdi mdi-cancel btn-icon-prepend"></i>Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection