@extends('app')
@section('title') Thanh toán {{$desk->deskname}} @endsection
@section('css')
<link rel="stylesheet" href="{{asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/sweetalert/sweetalert.min.js')}}"></script>
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
<script src="{{asset('vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('js/formpickers.js')}}"></script>
<script src="{{asset('js/jquery.printPage.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script type="text/javascript">
    var total = '{{$bill->total}}';
    $('#idvoucher').on('change', function() {
        var idvoucher = $(this).val();
        if (idvoucher == 0) {
            $('#giam').text('0₫');
            $('#tong').text(formatNumber(total))
            $('#total').val(total)
        } else {
            var dataId = {
                'idvoucher': idvoucher
            };
            $.ajax({
                type: 'GET',
                url: '{{route("find-voucher")}}',
                dataType: 'json',
                data: dataId,
                success: function(data) {
                    if (data.sale <= 100) {
                        $('#giam').text(data.sale + '%');
                        $('#tong').text(formatNumber(total * (1 - data.sale / 100)))
                        $('#total').val(total * (1 - data.sale / 100))
                    } else {
                        $('#giam').text(formatNumber(data.sale));
                        if (total - data.sale >= 0) {
                            $('#tong').text(formatNumber(total - data.sale))
                            $('#total').val(total - data.sale)
                        } else {
                            $('#tong').text('0₫')
                            $('#total').val('0')
                        }
                    }
                }
            });
        }
    });

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + "₫"
    }

    $('#print').on('click', function() {
        var idvoucher = $('#idvoucher').val();
        var url = "{{route('print',['deskid'=>':deskid','voucherid'=>':voucherid'])}}"
        url = url.replace(':deskid', '{{$desk->iddesk}}')
        url = url.replace(':voucherid', idvoucher)
        $(this).attr('href', url);
    })

    $(document).ready(function() {
        $('#print').printPage();
    });
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="card-title ribbon ribbon-warning">
            <div class="glow"></div>Thanh toán {{$desk->deskname}}
        </div>
        <div class="row">
            <form class="col-12" id="newPayForm" method="post" action="{{route('pay',$desk->iddesk)}}">
                {{csrf_field()}}
                <input type="hidden" name="deskid" value="{{$desk->iddesk}}">
                <div class="form-group">
                    <label class="font-weight-bold">Khuyến Mãi</label>
                    <select class="form-control form-control-lg text-capitalize" id="idvoucher" name="idvoucher">
                        <option value="0">Không</option>
                        @foreach($vouchers as $v)
                        <option value="{{$v->idvoucher}}">{{$v->vouchername}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Món</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-center">Đơn giá</th>
                                <th class="text-center">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billde as $key=>$billd)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td class="text-center">{{$billd->proname}}</td>
                                <td class="text-center">{{$billd->billamount}}</td>
                                <td class="text-center">{{number_format($billd->billprice).'₫'}}</td>
                                <td class="text-center">{{number_format($billd->billtotal).'₫'}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="container-fluid w-100">
                    <p class="text-right mb-2"><b>Tạm Tính: </b>{{number_format($bill->total).'₫'}}</p>
                    <p class="text-right"><b>Giảm: </b><span id="giam">0₫</span></p>
                    <h4 class=" text-right mb-5"><span>Tổng Cộng: </span><span id="tong">{{number_format($bill->total).'₫'}}</span></h4>
                    <input type="hidden" name="total" id="total" value="{{$bill->total}}">
                </div>
                <div class="container-fluid w-100">
                    <a class="btn btn-primary btn-icon-text float-left text-white" id="print"><i class="mdi mdi-printer btn-icon-prepend"></i>In</a>
                    <button class="btn btn-warning btn-icon-text float-right ml-2" type="submit"><i class="mdi mdi-telegram btn-icon-prepend"></i>Thanh Toán</button>
                    <a href="{{route('/')}}" class="btn btn-secondary btn-icon-text float-right"><i class="mdi mdi-cancel btn-icon-prepend"></i>Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection