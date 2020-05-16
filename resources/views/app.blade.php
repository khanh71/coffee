<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{asset('vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/vendor.bundle.base.css')}}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    @yield('css')
    <!-- endinject -->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />
</head>

<body class="sidebar-icon-only navbar-fixed-top">
    <div class="container-scroller d-flex">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item sidebar-category">
                    <p>Bán Hàng</p>
                    <span></span>
                </li>
                @can('sell.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('sell')}}">
                        <i class="mdi mdi-square-inc-cash menu-icon"></i>
                        <span class="menu-title">Bán Hàng</span>
                    </a>
                </li>
                @endcan
                <li class="nav-item sidebar-category">
                    <p>Quản Lý</p>
                    <span></span>
                </li>
                @can('position.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('position')}}">
                        <i class="mdi mdi-briefcase menu-icon"></i>
                        <span class="menu-title">Quản Lý Chức Vụ</span>
                    </a>
                </li>
                @endcan
                @can('employee.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('employee')}}">
                        <i class="mdi mdi-account menu-icon"></i>
                        <span class="menu-title">Quản Lý Nhân Viên</span>
                    </a>
                </li>
                @endcan
                @can('zone.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('zone')}}">
                        <i class="mdi mdi-nature-people menu-icon"></i>
                        <span class="menu-title">Quản Lý Khu Vực</span>
                    </a>
                </li>
                @endcan
                @can('desk.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('desk')}}">
                        <i class="mdi mdi-sofa menu-icon"></i>
                        <span class="menu-title">Quản Lý Bàn</span>
                    </a>
                </li>
                @endcan
                @can('supplier.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('supplier')}}">
                        <i class="mdi mdi-truck menu-icon"></i>
                        <span class="menu-title">Quản Lý Nhà Cung Cấp</span>
                    </a>
                </li>
                @endcan
                @can('material.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('material')}}">
                        <i class="mdi mdi-shape-plus menu-icon"></i>
                        <span class="menu-title">Quản Lý Nguyên Liệu</span>
                    </a>
                </li>
                @endcan
                @can('import.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('import')}}">
                        <i class="mdi mdi-import menu-icon"></i>
                        <span class="menu-title">Quản Lý Nhập Kho</span>
                    </a>
                </li>
                @endcan
                @can('productcate.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('category')}}">
                        <i class="mdi mdi-buffer menu-icon"></i>
                        <span class="menu-title">Quản Lý Thực Đơn</span>
                    </a>
                </li>
                @endcan
                @can('product.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('product')}}">
                        <i class="mdi mdi-food menu-icon"></i>
                        <span class="menu-title">Quản Lý Món</span>
                    </a>
                </li>
                @endcan
                @can('voucher.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('voucher')}}">
                        <i class="mdi mdi-sale menu-icon"></i>
                        <span class="menu-title">Quản Lý Khuyến Mãi</span>
                    </a>
                </li>
                @endcan
                @can('workday.view')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('workday')}}">
                        <i class="mdi mdi-calendar-clock menu-icon"></i>
                        <span class="menu-title">Quản Lý Chấm Công</span>
                    </a>
                </li>
                @endcan
                <li class="nav-item sidebar-category">
                    <p>Báo Cáo</p>
                    <span></span>
                </li>
                @can('report.salary')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report-salary')}}">
                        <i class="mdi mdi-calendar-text menu-icon"></i>
                        <span class="menu-title">Tính Lương</span>
                    </a>
                </li>
                @endcan
                @can('report.sell')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report-sell')}}">
                        <i class="mdi mdi-chart-areaspline menu-icon"></i>
                        <span class="menu-title">Báo Cáo Bán Hàng</span>
                    </a>
                </li>
                @endcan
                @can('report.profit')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report-profit')}}">
                        <i class="mdi mdi-chart-gantt menu-icon"></i>
                        <span class="menu-title">Báo Cáo Lợi Nhuận</span>
                    </a>
                </li>
                @endcan
                @can('report.cost')
                <li class="nav-item">
                    <a class="nav-link" href="{{route('report-cost')}}">
                        <i class="mdi mdi-wallet menu-icon"></i>
                        <span class="menu-title">Chi Phí Nhập Nguyên Liệu</span>
                    </a>
                </li>
                @endcan
            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 px-0 py-4 d-flex flex-row navbar-mini fixed-top">
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <div class="navbar-brand-wrapper">
                        <a class="navbar-brand brand-logo" href="{{route('/')}}"><img src="{{asset('images/logo.svg')}}" alt="logo" /></a>
                        <a class="navbar-brand brand-logo-mini" href="{{route('/')}}"><img src="{{asset('images/logo-mini.svg')}}" alt="logo" /></a>
                    </div>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item m-1">
                            <a id="top"></a>
                        </li>
                        <li class="nav-item m-1">
                            <a id="down"></a>
                        </li>
                        <li class="nav-item nav-profile dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" id="profileDropdown">
                                <i class="mdi mdi-account-circle icon-lg"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" data-toggle="modal" data-target="#editeee">
                                    <i class="mdi mdi-account-check text-primary"></i>
                                    Sửa thông tin cá nhân
                                </a>
                                <a class="dropdown-item" data-toggle="modal" data-target="#editlogin">
                                    <i class="mdi mdi-key-variant text-primary"></i>
                                    Sửa thông tin đăng nhập
                                </a>
                                @can('shop.update')
                                <a class="dropdown-item" data-toggle="modal" data-target="#editshop">
                                    <i class="mdi mdi-settings text-primary"></i>
                                    Sửa thông tin cửa hàng
                                </a>
                                @endcan
                                <a class="dropdown-item" href="{{route('logout')}}">
                                    <i class="mdi mdi-logout text-primary"></i>
                                    Đăng xuất
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-sm-flex justify-content-center justify-content-sm-between py-2">
                                <p class="text-center text-sm-left d-block d-sm-inline-block mb-0">Phần mềm quản lý quán cà phê</p>
                                <p class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center mb-0">Trần Hồng Khánh <i class="mdi mdi-heart-outline text-muted icon-sm"></i></p>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <div class="modal fade" id="editshop" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa thông tin cửa hàng</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" id="formShop" method="post" action="{{route('edit-shop')}}">
                        @csrf
                        <div class="form-group">
                            <label>Tên cửa hàng</label>
                            <input type="text" class="form-control text-capitalize" id="shopnameedit" name="shopname" required maxlength="50" value="{{$shop->shopname}}">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <input type="text" class="form-control text-capitalize" id="shopaddressedit" name="shopaddress" required maxlength="255" value="{{$shop->shopaddress}}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            <button class="btn btn-info btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editeee" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa thông cá nhân</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" id="formEee" method="post" action="{{route('edit-user')}}">
                        @csrf
                        <div class="form-group">
                            <label>Họ tên</label>
                            <input type="text" class="form-control form-control-lg text-capitalize" id="nameeee" name="nameeee" maxLength='50' required value="{{$eee->name}}">
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <input type="text" class="form-control form-control-lg text-capitalize" id="addresseee" name="addresseee" maxLength='150' required value="{{$eee->address}}">
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="text" class="form-control form-control-lg" id="birthdayeee" name="birthdayeee" data-inputmask="'alias': 'date','placeholder': '_'" required value="{{$eee->birthday}}">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="text" class="form-control form-control-lg" id="phone" name="phoneeee" data-inputmask="'alias': 'phonevn'" required value="{{$eee->phone}}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            <button class="btn btn-info btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editlogin" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-uppercase" id="ModalLabel"><i class="mdi mdi-pencil-box-outline mr-1"></i>Sửa thông tin đăng nhập</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample" id="formEditLogin" method="post" action="{{route('edit-login')}}">
                        @csrf
                        <div class="form-group">
                            <label>Tên đăng nhập mới</label>
                            <input type="text" class="form-control form-control-lg" id="emailedit" name="emailedit" maxLength='50' required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <input type="password" class="form-control form-control-lg" id="passwordedit" name="passwordedit" maxLength='50' required>
                        </div>
                        <div class="form-group">
                            <label>Nhập lại mật khẩu mới</label>
                            <input type="password" class="form-control form-control-lg" id="repasswordedit" maxLength='50' required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Hủy</button>
                            <button class="btn btn-info btn-icon-text" type="submit"><i class="mdi mdi-content-save btn-icon-prepend"></i>Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script src="{{asset('vendors/js/vendor.bundle.base.js')}}"></script>
    <script>
        var toparr = $('#top');
        var downarr = $('#down');
        $(window).scroll(function() {
            if ($(window).scrollTop() > 100) {
                toparr.addClass('show');
                downarr.addClass('show');
            } else {
                toparr.removeClass('show');
                downarr.removeClass('show');
            }
        });

        toparr.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, '500');
        });

        downarr.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: $(document).height()
            }, '500');
        });
    </script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    @yield('javascript')
    <script>
        $('#emailedit').on('keyup', function() {
            var email = $(this).val();
            $('#formEditLogin').find('#usernamedupp').remove();
            var dataId = {
                'email': email
            };
            $.ajax({
                type: 'GET',
                url: '{{route("find-username")}}',
                dataType: 'json',
                data: dataId,
                success: function(data) {
                    if (data.state == 1) {
                        $('#emailedit').val('');
                        $('#formEditLogin').prepend('<div class="alert alert-fill-danger" id="usernamedupp" role="alert">' +
                            '<i class="mdi mdi-information-outline mr-1"></i>Tên đăng nhập "' + data.email + '" đã được đăng ký</div>');
                    } else
                        $('#formEditLogin').find('#usernamedupp').remove();
                }
            });
        });
    </script>
    <script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/form-validation.js')}}"></script>
    <script src="{{asset('vendors/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
    <script src="{{asset('js/bt-maxLength.js')}}"></script>
    <script src="{{asset('vendors/inputmask/jquery.inputmask.bundle.js')}}"></script>
    <script src="{{asset('vendors/inputmask/inputmask.binding.js')}}"></script>
    <script src="{{asset('vendors/inputmask/phone-vn.js')}}"></script>
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{asset('js/off-canvas.js')}}"></script>
    <script src="{{asset('js/hoverable-collapse.js')}}"></script>
    <script src="{{asset('js/template.js')}}"></script>
    <script>
        $('.dropdown').hover(function() {
            $('.dropdown-toggle', this).trigger('click');
        });
    </script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
</body>

</html>