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
          required: true,
          min: 0,
          max: 20,
          numner: true
        },
      },
      messages: {
        posname: {
          required: "Vui lòng nhập tên chức vụ"
        },
        coefficient: {
          required: "Vui lòng nhập hệ số lương",
          min: "Hệ số lương phải lớn hơn 0",
          max: "Hệ số lương phải nhỏ hơn 20",
          number: "Hệ số lương phải là số"
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
          required: true,
          min: 0,
          max: 20
        },
      },
      messages: {
        posnameedit: {
          required: "Vui lòng nhập tên chức vụ"
        },
        coefficientedit: {
          required: "Vui lòng nhập hệ số lương",
          min: "Hệ số lương phải lớn hơn 0",
          max: "Hệ số lương phải nhỏ hơn 20"
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

    $("#newZoneForm").validate({
      rules: {
        zonename: {
          required: true
        }
      },
      messages: {
        zonename: {
          required: "Vui lòng nhập tên khu vực"
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

    $("#editZoneForm").validate({
      rules: {
        zonenameedit: {
          required: true
        }
      },
      messages: {
        zonenameedit: {
          required: "Vui lòng nhập tên khu vực"
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

    $("#newDeskForm").validate({
      rules: {
        deskname: {
          required: true
        }
      },
      messages: {
        deskname: {
          required: "Vui lòng nhập tên bàn"
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

    $("#editDeskForm").validate({
      rules: {
        desknameedit: {
          required: true
        }
      },
      messages: {
        desknameedit: {
          required: "Vui lòng nhập tên bàn"
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

    $("#newVoucherForm").validate({
      rules: {
        vouchername: {
          required: true
        },
        sale: {
          required: true,
          min: 0,
          max: 100000000,
        },
        startday: {
          required: true
        },
        endday: {
          required: true
        }
      },
      messages: {
        vouchername: {
          required: "Vui lòng nhập tên khuyến mãi"
        },
        sale: {
          required: "Vui lòng nhập giảm giá",
          min: "Giảm giá phải lớn hơn 0",
          max: "Giảm giá phải nhỏ hơn 100.000.000",
        },
        startday: {
          required: "Vui lòng nhập ngày bắt đầu"
        },
        endday: {
          required: "Vui lòng nhập ngày kết thúc"
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

    $("#editVoucherForm").validate({
      rules: {
        vouchernameedit: {
          required: true
        },
        saleedit: {
          required: true,
          min: 0,
          max: 100000000,
        },
        startdayedit: {
          required: true
        },
        enddayedit: {
          required: true
        }
      },
      messages: {
        vouchernameedit: {
          required: "Vui lòng nhập tên khuyến mãi"
        },
        sale: {
          requirededit: "Vui lòng nhập giảm giá",
          min: "Giảm giá phải lớn hơn 0",
          max: "Giảm giá phải nhỏ hơn 100.000.000",
        },
        startdayedit: {
          required: "Vui lòng nhập ngày bắt đầu"
        },
        enddayedit: {
          required: "Vui lòng nhập ngày kết thúc"
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

    $("#newSupplierForm").validate({
      rules: {
        suppname: {
          required: true
        },
        suppaddress: {
          required: true
        },
        suppphone: {
          required: true
        }
      },
      messages: {
        suppname: {
          required: "Vui lòng nhập tên nhà cung cấp"
        },
        suppaddress: {
          required: "Vui lòng nhập địa chỉ"
        },
        suppphone: {
          required: "Vui lòng nhập số điện thoại"
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

    $("#editSupplierForm").validate({
      rules: {
        suppnameedit: {
          required: true
        },
        suppaddressedit: {
          required: true
        },
        suppphoneedit: {
          required: true
        }
      },
      messages: {
        suppnameedit: {
          required: "Vui lòng nhập tên nhà cung cấp"
        },
        suppaddressedit: {
          required: "Vui lòng nhập địa chỉ"
        },
        suppphoneedit: {
          required: "Vui lòng nhập số điện thoại"
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

    $("#newWorkdayForm").validate({
      rules: {
        hour: {
          required: true
        },
        wddate: {
          required: true
        }
      },
      messages: {
        hour: {
          required: "Vui lòng nhập số giờ làm"
        },
        wddate: {
          required: "Vui lòng nhập ngày chấm công"
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

    $("#editWorkdayForm").validate({
      rules: {
        houredit: {
          required: true
        },
        wddateedit: {
          required: true
        }
      },
      messages: {
        houredit: {
          required: "Vui lòng nhập số giờ làm"
        },
        wddateedit: {
          required: "Vui lòng nhập ngày chấm công"
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

    $("#newCateForm").validate({
      rules: {
        procatename: {
          required: true
        }
      },
      messages: {
        procatename: {
          required: "Vui lòng nhập tên danh mục"
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

    $("#editCateForm").validate({
      rules: {
        procatenameedit: {
          required: true
        }
      },
      messages: {
        procatenameedit: {
          required: "Vui lòng nhập tên danh mục"
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

    $("#newMaterialForm").validate({
      rules: {
        maname: {
          required: true
        },
        maprice: {
          min: 0,
          max: 100000000,
          number: true
        },
        unit: {
          required: true
        }
      },
      messages: {
        maname: {
          required: "Vui lòng nhập tên nguyên liệu"
        },
        maprice: {
          min: "Giá nhập phải lớn hơn 0",
          max: "Giá nhập phải nhỏ hơn 100.000.000",
          number: "Giá nhập phải là số"
        },
        unit: {
          required: "Vui lòng nhập đơn vị tính"
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

    $("#editMaterialForm").validate({
      rules: {
        manameedit: {
          required: true
        },
        mapriceedit: {
          min: 0,
          max: 100000000,
          number: true
        },
        maamountedit: {
          required: true,
          min: 0,
          max: 100000000,
          number: true
        },
        unitedit: {
          required: true
        }
      },
      messages: {
        manameedit: {
          required: "Vui lòng nhập tên nguyên liệu"
        },
        mapriceedit: {
          min: "Giá nhập phải lớn hơn 0",
          max: "Giá nhập phải nhỏ hơn 100.000.000",
          number: "Giá nhập phải là số"
        },
        maamountedit: {
          required: "Vui lòng nhập số lượng tồn",
          min: "Số lượng tồn phải lớn hơn 0",
          max: "Số lượng tồn phải nhỏ hơn 100.000.000",
          number: "Số lượng tồn phải là số"
        },
        unitedit: {
          required: "Vui lòng nhập đơn vị tính"
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