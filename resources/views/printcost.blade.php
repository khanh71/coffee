<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="col-12 mx-auto">
    <div class="text-center mb-3">
        <div class="text-uppercase">
            <h3>CHI PHÍ NHẬP NGUYÊN LIỆU</h3>
        </div>
        <div>Từ ngày {{date('d/m/Y',strtotime($dayfrom))}} đến ngày {{date('d/m/Y',strtotime($dayto))}}</div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Nhà cung cấp</th>
                    <th>Tên nguyên liệu</th>
                    <th>Số lượng nhập</th>
                    <th>Đơn giá</th>
                    <th>Tổng cộng</th>
                </tr>
            </thead>
            <tbody>
                @php
                $date = '';
                $total = 0;
                @endphp
                @forelse($cost as $key=>$co)
                @php
                $total += $co->imptotal;
                @endphp
                @if($co->impdate == $date)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$co->suppname}}</td>
                    <td>{{$co->maname}}</td>
                    <td>{{$co->impamount}}</td>
                    <td>{{number_format($co->impprice).'₫'}}</td>
                    <td>{{number_format($co->imptotal).'₫'}}</td>
                </tr>
                @else
                <tr>
                    <td colspan="6" class="text-left font-weight-bold">Ngày {{date('d/m/Y',strtotime($co->impdate))}}:</td>
                </tr>
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$co->suppname}}</td>
                    <td>{{$co->maname}}</td>
                    <td>{{$co->impamount}}</td>
                    <td>{{number_format($co->impprice).'₫'}}</td>
                    <td>{{number_format($co->imptotal).'₫'}}</td>
                </tr>
                @php
                $date = $co->impdate;
                @endphp
                @endif
                @empty
                <tr>
                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-weight-bold">Tổng Cộng:</td>
                    <td>{{number_format($total).'₫'}}</td>
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