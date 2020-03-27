(function ($) {
  'use strict';
  $(function () {
    $("#signupForm").validate({
      rules: {
        password: {
          required: true,
          minlength: 8
        },
        repassword: {
          required: true,
          minlength: 8,
          equalTo: "#password"
        },
        email: {
          required: true
        },
        name: {
          required: true,
          minlength: 10
        },
        address: {
          required: true
        },
        birthday: {
          required: true
        },
        phone: {
          required: true
        },
        shopname: {
          required: true
        },
        shopaddress: {
          required: true
        }
      },
      messages: {
        password: {
          required: "Vui lòng nhập mật khẩu",
          minlength: "Mật khẩu tối thiểu 8 ký tự"
        },
        repassword: {
          required: "Vui lòng nhập lại mật khẩu",
          minlength: "Mật khẩu tối thiểu 8 ký tự",
          equalTo: "Mật khẩu xác nhận không khớp"
        },
        email: {
          required: "Vui lòng nhập tên đăng nhập"
        },
        name: {
          required: "Vui lòng nhập họ tên",
          minlength: "Họ tên tối thiểu 10 ký tự"
        },
        address: {
          required: "Vui lòng nhập địa chỉ"
        },
        birthday: {
          required: "Vui lòng nhập ngày sinh",
        },
        phone: {
          required: "Vui lòng nhập số điện thoại"
        },
        shopname: {
          required: "Vui lòng nhập tên cửa hàng"
        },
        shopaddress: {
          required: "Vui lòng nhập địa chỉ cửa hàng"
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    $("#loginForm").validate({
      rules: {
        username: {
          required: true
        },
        password: {
          required: true,
          minlength: 8
        },
      },
      messages: {
        password: {
          required: "Vui lòng nhập mật khẩu",
          minlength: "Mật khẩu tối thiểu 8 ký tự"
        },
        username: {
          required: "Vui lòng nhập tên đăng nhập",
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    $("#newPositionForm").validate({
      rules: {
        posname: {
          required: true
        },
        coefficient: {
          required: true
        },
      },
      messages: {
        posname: {
          required: "Vui lòng nhập tên chức vụ"
        },
        coefficient: {
          required: "Vui lòng nhập hệ số lương",
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    $("#editPositionForm").validate({
      rules: {
        posnameedit: {
          required: true
        },
        coefficientedit: {
          required: true
        },
      },
      messages: {
        posnameedit: {
          required: "Vui lòng nhập tên chức vụ"
        },
        coefficientedit: {
          required: "Vui lòng nhập hệ số lương",
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    $("#newEmployeeForm").validate({
      rules: {
        password: {
          required: true,
          minlength: 8
        },
        email: {
          required: true
        },
        name: {
          required: true,
          minlength: 10
        },
        address: {
          required: true
        },
        birthday: {
          required: true
        },
        phone: {
          required: true
        },
        startday: {
          required: true
        },
        basesalary: {
          required: true
        }
      },
      messages: {
        password: {
          required: "Vui lòng nhập mật khẩu",
          minlength: "Mật khẩu tối thiểu 8 ký tự"
        },
        email: {
          required: "Vui lòng nhập tên đăng nhập"
        },
        name: {
          required: "Vui lòng nhập họ tên",
          minlength: "Họ tên tối thiểu 10 ký tự"
        },
        address: {
          required: "Vui lòng nhập địa chỉ"
        },
        birthday: {
          required: "Vui lòng nhập ngày sinh",
        },
        phone: {
          required: "Vui lòng nhập số điện thoại"
        },
        startday: {
          required: "Vui lòng nhập ngày vào làm"
        },
        basesalary: {
          required: "Vui lòng nhập lương cơ bản"
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });

    $("#editEmployeeForm").validate({
      rules: {
        nameedit: {
          required: true,
          minlength: 10
        },
        addressedit: {
          required: true
        },
        birthdayedit: {
          required: true
        },
        phoneedit: {
          required: true
        },
        startdayedit: {
          required: true
        },
        basesalaryedit: {
          required: true
        }
      },
      messages: {
        nameedit: {
          required: "Vui lòng nhập họ tên",
          minlength: "Họ tên tối thiểu 10 ký tự"
        },
        addressedit: {
          required: "Vui lòng nhập địa chỉ"
        },
        birthdayedit: {
          required: "Vui lòng nhập ngày sinh",
        },
        phoneedit: {
          required: "Vui lòng nhập số điện thoại"
        },
        startdayedit: {
          required: "Vui lòng nhập ngày vào làm"
        },
        basesalaryedit: {
          required: "Vui lòng nhập lương cơ bản"
        }
      },
      errorPlacement: function (label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      success: function (label, element) {
        label.parent().removeClass('has-danger');
        label.remove();
      },
      highlight: function (element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });









  });
})(jQuery);