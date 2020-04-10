<link rel="stylesheet" href="{{asset('css/style.css')}}">
<div class="col-6 mx-auto">
    <div class="text-center mb-3">
        <div class="text-uppercase">
            <h4>{{$shop->shopname}}</h4>
        </div>
        <div>Địa chỉ: {{$shop->shopaddress}}</div>
        <div class="mt-2"><i>Ngày: {{date('d/m/Y',strtotime($bill->billdate))}}</i></div>
    </div>
    <div class="table-responsive">
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
        <p class="text-right"><b>Giảm: </b><span id="giam">@if($voucher->sale<=100) {{$voucher->sale.'%'}} @else {{number_format($voucher->sale).'₫'}} @endif</span> </p> <h5 class=" text-right mb-5"><span>Tổng Cộng: </span>
                    <span id="tong">@if($voucher->sale<=100) {{number_format($bill->total*(1-$voucher->sale/100)).'₫'}} @else @if($bill->total-$voucher->sale >= 0) {{number_format($bill->total-$voucher->sale).'₫'}} @else 0₫ @endif @endif</span> </h5> <input type="hidden" name="total" id="total" value="{{$bill->total}}">
    </div>
</div>