<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng Ký</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
</head>

<body>
    <div class="container-scroller d-flex">
        <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
            <div class="content-wrapper-login d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{asset('images/logo-dark.svg')}}" alt="logo">
                            </div>
                            <h4>ĐĂNG KÝ</h4>
                            <h6 class="font-weight-light">Bắt đầu quản lý cửa hàng của bạn</h6>
                            <form class="pt-3" id="signupForm" action="{{route('register')}}" method="POST">
                                @if(count($errors)>0)
                                @foreach($errors->all() as $err)
                                <div class="alert alert-fill-danger" role="alert">
                                    <i class="mdi mdi-information-outline"></i>
                                    {{$err}}
                                </div>
                                @endforeach
                                @endif
                                {{csrf_field()}}
                                <fieldset>
                                    <legend>Thông tin đăng nhập</legend>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="Tên đăng nhập" maxLength='50' required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mật khẩu" maxLength='50' required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-lg" id="repassword" name="repassword" placeholder="Xác nhận mật khẩu" maxLength='50' required>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Thông tin cá nhân</legend>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg text-capitalize" id="name" name="name" placeholder="Họ tên" maxLength='50' required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg text-capitalize" id="address" name="address" placeholder="Địa chỉ" maxLength='150' required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg" id="birthday" name="birthday" placeholder="Ngày sinh" data-inputmask="'alias': 'date','placeholder': '_'" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg" id="phone" name="phone" placeholder="Số điện thoại" data-inputmask="'alias': 'phonevn'" required>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Thông tin cửa hàng</legend>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg text-capitalize" id="shopname" name="shopname" placeholder="Tên cửa hàng" maxLength='100' required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-lg text-capitalize" id="shopaddress" name="shopaddress" placeholder="Địa chỉ" maxLength='150' required>
                                    </div>
                                </fieldset>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">ĐĂNG KÝ</button>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Đã có tài khoản? <a href="{{route('login')}}" class="text-primary">Đăng nhập</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script src="{{asset('vendors/inputmask/jquery.inputmask.bundle.js')}}"></script>
    <script src="{{asset('vendors/inputmask/inputmask.binding.js')}}"></script>
    <script src="{{asset('vendors/inputmask/phone-vn.js')}}"></script>
    <script src="{{asset('js/form-validation.js')}}"></script>
    <script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/form-validation.js')}}"></script>
    <script src="{{asset('vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('js/bt-maxLength.js')}}"></script>
    <!-- endinject -->
</body>

</html>