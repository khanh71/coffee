<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="col-12 mx-auto">
    <div class="text-center mb-3">
        <div class="text-uppercase">
            <h3>BÁO CÁO BÁN HÀNG</h3>
        </div>
        <div>Từ ngày {{date('d/m/Y',strtotime($dayfrom))}} đến ngày {{date('d/m/Y',strtotime($dayto))}}</div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên bàn</th>
                    <th>Tiền hàng</th>
                    <th>Giảm giá</th>
                    <th>Tổng cộng</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $date = '';
                    $half = 0;
                    $total = 0;
                @endphp
                @forelse($sell as $key=>$se)
                @php
                    if($se->billsale<=100)
                        $a=$se->total/(1-($se->billsale/100));
                    else
                        $a = $se->billsale+$se->total;
                    $half += $a;
                    $total += $se->total;
                @endphp
                @if($se->billdate == $date)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$se->deskname}}</td>
                        <td>{{number_format($a).'₫'}}</td>
                        <td>@if($se->billsale<=100){{$se->billsale.'%'}} @else {{number_format($se->billsale).'₫'}}@endif</td>
                        <td>{{number_format($se->total).'₫'}}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="5" class="text-left font-weight-bold">Ngày {{date('d/m/Y',strtotime($se->billdate))}}:</td>
                    </tr>
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$se->deskname}}</td>
                        <td>{{number_format($a).'₫'}}</td>
                        <td>@if($se->billsale<=100){{$se->billsale.'%'}} @else {{number_format($se->billsale).'₫'}}@endif</td>
                        <td>{{number_format($se->total).'₫'}}</td>
                    </tr>
                    @php
                        $date = $se->billdate;
                    @endphp
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Không có dữ liệu</td>
                    </tr>
                    @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="font-weight-bold text-right">Tổng cộng:</td>
                    <td class="font-weight-bold">{{number_format($half).'₫'}}</td>
                    <td></td>
                    <td class="font-weight-bold">{{number_format($total).'₫'}}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="text-right mt-3">
        <p class="mr-2">Ngày {{$now->day}} tháng {{$now->month}} năm {{$now->year}}</p>
    </div><br>
    <div class="container-fluid w-100" style="margin-top: -25px;">
        <div class="float-left ml-5 text-center">
            <label class="mb-0"><b>Người lập</b></label>
            <p><i>(Ký, họ tên)</i></p>
        </div>
        <div class="float-right mr-5 text-center">
            <label class="mb-0"><b>Quản lý</b></label>
            <p><i>(Ký, họ tên)</i></p>
        </div>
    </div>
</div>