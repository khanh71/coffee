@extends('app')
@section('title','Quản lý quán cà phê chuyên nghiệp')
@section('css')
<link rel="stylesheet" href="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.css')}}">
@endsection

@section('javascript')
<script src="{{asset('vendors/jquery-toast-plugin/jquery.toast.min.js')}}"></script>
<script src="{{asset('js/toastDemo.js')}}"></script>
<script src="{{asset('js/tooltips.js')}}"></script>
@if(count($errors)>0 || Session::has('err')) <script>
    showDangerToast();
</script> @endif
@if(Session::has('success')) <script>
    showSuccessToast();
</script> @endif
<script>
    $('#view').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var deskid = button.data('deskid');
        var model = $(this);
        model.find('#body-detail').remove();
        model.find('#tb-detail').append('<tbody id="body-detail"></tbody>');
        var dataId = {
            'deskid': deskid
        };
        $.ajax({
            type: 'GET',
            url: '{{route("view-menu")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                $.each(data, function(k, v) {
                    $('#body-detail').append('<tr><td class="text-capitalize">' + data[k].proname + '</td>' +
                        '<td>' + data[k].billamount + '</td>' +
                        '<td>' + formatNumber(data[k].billprice) + '</td>' +
                        '<td>' + formatNumber(data[k].billtotal) + '</td></tr>');
                })
            }
        });
    })

    $('#merge').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var deskid = button.data('deskid');
        var deskname = button.data('deskname');
        var model = $(this);
        model.find('#olddesk').val(deskname);
        model.find('#idolddesk').val(deskid);
        model.find('#newdesk').remove();
        model.find('#nodesk').remove();
        model.find('.modal-footer').remove();
        var dataId = {
            'deskid': deskid
        };
        $.ajax({
            type: 'GET',
            url: '{{route("find-desk")}}',
            dataType: 'json',
            data: dataId,
            success: function(data) {
                if ($.trim(data)) {
                    model.find('#divdesk').append('<select class="form-control form-control-lg text-capitalize" id="newdesk" name="newdesk"></select>')
                    $.each(data, function(k, v) {
                        model.find('#newdesk').append('<option value="' + data[k].iddesk + '">' + data[k].deskname + '</option>')
                    })
                    model.find('#frmmerge').append('<div class="modal-footer">' +
                        '<button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>' +
                        '<button class="btn btn-danger btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>' +
                        '</div>')
                } else
                    model.find('#divdesk').append('<div class="text-center" id="nodesk">Không có bàn nào phù hợp để ghép.</div>')
            }
        })
    })

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") + "₫"
    }

    $(document).ready(function() {
        $('[rel="tooltip"]').tooltip({
            trigger: "hover"
        });
    });
</script>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title ribbon ribbon-primary">
                    <div class="glow"></div>Bán hàng
                </div>
                <div class="col-md-6 mx-auto mb-3">
                    <fieldset>
                        <legend>thông tin cửa hàng</legend>
                        <div class="text-capitalize">
                            <label><b>Tên Cửa Hàng:</b> {{$shop->shopname}}</label>
                        </div>
                        <div class="text-capitalize">
                            <label><b>Địa chỉ:</b> {{$shop->shopaddress}}</label>
                        </div>
                    </fieldset>
                </div>
                <div class="row col-md-10 mx-auto mb-3">
                    <div class="col-md-5 d-flex align-items-center">
                        <i class="mdi mdi-coffee-outline text-dark icon-md mr-1"></i>Bàn chưa có khách
                    </div>
                    <div class="col-md-5 d-flex align-items-center">
                        <i class="mdi mdi-coffee-outline text-success icon-md mr-1"></i>Bàn đã có khách
                    </div>
                </div>
                <div id="abc">
                    @forelse($zones as $zone)
                    <div class="row">
                        <div class="col-12">
                            <div class="card-column text-center">
                                <div class="text-uppercase title text-center text-white">
                                    <div class="glow"></div>{{$zone->zonename}}
                                </div>
                                @forelse($desks as $desk)
                                @if($desk->zoneid == $zone->idzone)
                                <div class="card">
                                    <label class="w-100 text-capitalize">{{$desk->deskname}}</label>
                                    <i class="mdi icon-hg @if($desk->state==0) text-dark mdi-coffee-outline @elseif($desk->state==1) text-success mdi-coffee-outline @else text-success mdi-coffee @endif"></i>
                                    @if($desk->state==0)
                                    <div class="btn-group" role="group">
                                        @can('sell.create')
                                        <a href="{{route('new-call',$desk->iddesk)}}" style="border-bottom-left-radius: 0;" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Gọi Món"><i class="mdi mdi-human-greeting"></i></a>
                                        @else
                                        <button type="button" style="border-bottom-left-radius: 0;" class="btn btn-info" disabled><i class="mdi mdi-human-greeting"></i></button>
                                        @endcan
                                        <button type="button" style="border-bottom-right-radius: 0;" class="btn btn-dark" disabled><i class="mdi mdi-eye"></i></button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" style="border-top-left-radius: 0;" class="btn btn-dark" disabled><i class="mdi mdi-swap-horizontal"></i></button>
                                        <button type="button" style="border-top-right-radius: 0;" class="btn btn-dark" disabled><i class="mdi mdi-bank"></i></button>
                                    </div>
                                    @else
                                    <div class="btn-group" role="group">
                                        @can('sell.create')
                                        <a href="{{route('edit-call',$desk->iddesk)}}" style="border-bottom-left-radius: 0;" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Gọi Món"><i class="mdi mdi-human-greeting"></i></a>
                                        @else
                                        <button type="button" style="border-bottom-left-radius: 0;" class="btn btn-info" disabled><i class="mdi mdi-human-greeting"></i></button>
                                        @endcan
                                        <button type="button" style="border-bottom-right-radius: 0;" class="btn btn-success" data-deskid="{{$desk->iddesk}}" data-toggle="modal" data-target="#view" rel="tooltip" data-placement="top" title="Xem Món Đã Gọi"><i class="mdi mdi-eye"></i></button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" style="border-top-left-radius: 0;" type="button" class="btn btn-danger" @can('sell.merge') data-toggle="modal" data-target="#merge" data-deskname="{{$desk->deskname}}" data-deskid="{{$desk->iddesk}}" rel="tooltip" data-placement="bottom" title="Ghép Bàn" @else disabled @endcan><i class="mdi mdi-swap-horizontal"></i></button>
                                        @can('sell.pay')
                                        <a href="{{route('pay',$desk->iddesk)}}" style="border-top-right-radius: 0;" type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="Thanh Toán"><i class="mdi mdi-bank"></i></a>
                                        @else
                                        <button style="border-top-right-radius: 0;" type="button" class="btn btn-warning"><i class="mdi mdi-bank"></i></button>
                                        @endcan
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @empty
                                <div class="text-center">
                                    <p>Chưa có bàn nào trong cửa hàng được thiết lập.</p>
                                    <p>Vui lòng thêm bàn vào cửa hàng và quay lại sau nhé.</p>
                                    <a class="btn btn-primary text-capitalize" href="{{route('desk')}}">Thêm bàn</a>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">
                        <p>Chưa có khu vực nào trong cửa hàng được thiết lập.</p>
                        <p>Vui lòng thêm khu vực vào cửa hàng và quay lại sau nhé.</p>
                        <a class="btn btn-primary text-capitalize" href="{{route('zone')}}">Thêm khu vực</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!--view menu-->
<div class="modal fade" id="view" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-eye-outline mr-1"></i>danh sách món</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tb-detail">
                        <thead>
                            <tr>
                                <th>Tên món</th>
                                <th>Số lượng</th>
                                <th>Giá bán</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="border-top: none;"></div>
        </div>
    </div>
</div>
<!--end view menu-->

<!--merge-->
<div class="modal fade" id="merge" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-swap-horizontal mr-1"></i>ghép bàn</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('merge')}}" method="post" id="frmmerge">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="olddesk" class="form-control text-capitalize" disabled>
                        <input type="hidden" name="olddesk" id="idolddesk">
                    </div>
                    <div class="text-center text-danger mb-3"><i class="mdi mdi-swap-horizontal mr-1"></i><b>Ghép Với</b><i class="mdi mdi-swap-horizontal ml-1"></i></div>
                    <div class="form-group" id="divdesk"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end merge-->
@endsection