<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="col-12 mx-auto">
    <div class="text-center mb-3">
        <div class="text-uppercase">
            <h3>BÁO CÁO LỢI NHUẬN</h3>
        </div>
        <div>Từ ngày {{date('d/m/Y',strtotime($dayfrom))}} đến ngày {{date('d/m/Y',strtotime($dayto))}}</div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên món</th>
                    <th>Đơn giá</th>
                    <th>Giá nhập</th>
                    <th>Số lượng</th>
                    <th>Doanh thu</th>
                    <th>Lợi nhuận</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $cate = '';
                    $total = 0;
                    $prf = 0;
                @endphp
                @forelse($profit as $key=>$pro)
                @php
                    $a = $pro->proprice*$pro->billamount;
                    $b = $pro->price*$pro->billamount;
                    $total += $a;
                    $prf += $a - $b;
                @endphp
                @if($pro->procatename == $cate)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$pro->proname}}</td>
                    <td>{{number_format($pro->proprice).'₫'}}</td>
                    <td>{{number_format($pro->price).'₫'}}</td>
                    <td>{{$pro->billamount}}</td>
                    <td>{{number_format($a).'₫'}}</td>
                    <td>{{number_format($a - $b).'₫'}}</td>
                </tr>
                @else
                <tr>
                    <td colspan="7" class="text-left font-weight-bold">Thực đơn: {{$pro->procatename}}</td>
                </tr>
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$pro->proname}}</td>
                    <td>{{number_format($pro->proprice).'₫'}}</td>
                    <td>{{number_format($pro->price).'₫'}}</td>
                    <td>{{$pro->billamount}}</td>
                    <td>{{number_format($a).'₫'}}</td>
                    <td>{{number_format($a - $b).'₫'}}</td>
                </tr>
                @php
                $cate = $pro->procatename;
                @endphp
                @endif
                @empty
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right font-weight-bold">Tổng Cộng:</td>
                    <td class="font-weight-bold">{{number_format($total).'₫'}}</td>
                    <td class="font-weight-bold">{{number_format($prf).'₫'}}</td>
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