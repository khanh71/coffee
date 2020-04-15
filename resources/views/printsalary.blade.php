<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="col-12 mx-auto">
    <div class="text-center mb-3">
        <div class="text-uppercase">
            <h3>BÁO CÁO LƯƠNG NHÂN VIÊN</h3>
        </div>
        <div>Từ ngày {{date('d/m/Y',strtotime($dayfrom))}} đến ngày {{date('d/m/Y',strtotime($dayto))}}</div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên nhân viên</th>
                    <th>Chức vụ</th>
                    <th>Số giờ làm</th>
                    <th>Hệ số lương</th>
                    <th>Lương cơ bản</th>
                    <th>Tổng lương</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salary as $key=>$sa)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$sa->name}}</td>
                    <td>{{$sa->posname}}</td>
                    <td>{{$sa->hour}}</td>
                    <td>{{$sa->coefficient}}</td>
                    <td>{{number_format($sa->basesalary).'₫'}}</td>
                    <td>{{number_format($sa->basesalary*$sa->coefficient*$sa->hour).'₫'}}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Không có dữ liệu</td>
                </tr>
                @endforelse
            </tbody>
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