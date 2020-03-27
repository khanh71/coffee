<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng Nhập</title>
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
                            <h4>ĐĂNG NHẬP</h4>
                            <form class="pt-3" id="loginForm" action="{{route('login')}}" method="POST">
                                @if(Session::has('message'))
                                <div class="alert alert-fill-danger" role="alert">
                                    <i class="mdi mdi-information-outline"></i>
                                    {{Session::get('message')}}
                                </div>
                                @endif
                                @if(isset($message))
                                <div class="alert alert-fill-success" role="alert">
                                    <i class="mdi mdi-information-outline"></i>
                                    {{$message}}
                                </div>
                                @endif
                                {{csrf_field()}}
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" id="username" name="username" value="{{old('username')}}" placeholder="Tên đăng nhập" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mật khẩu" required>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">ĐĂNG NHẬP</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                            Lưu mật khẩu
                                        </label>
                                    </div>
                                    <a href="#" class="auth-link text-black">Quên mật khẩu</a>
                                </div>
                                <div class="text-center mt-4 font-weight-light">
                                    Chưa có tài khoản? <a href="{{route('register')}}" class="text-primary">Đăng ký ngay</a>
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
    <script src="{{asset('js/settings.js')}}"></script>
    <script src="{{asset('js/todolist.js')}}"></script>
    <script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/form-validation.js')}}"></script>
    <!-- endinject -->
</body>

</html>