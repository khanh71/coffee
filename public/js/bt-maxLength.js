(function($) {
  'use strict';
  $('#email').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#password').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#repassword').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#name').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#address').maxlength({
    alwaysShow: true,
    threshold: 150,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#shopname').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#shopaddress').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#posname').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#posnameedit').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#nameedit').maxlength({
    alwaysShow: true,
    threshold: 50,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#addressedit').maxlength({
    alwaysShow: true,
    threshold: 150,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });

  $('#maxlength-textarea').maxlength({
    alwaysShow: true,
    warningClass: "badge mt-1 badge-success",
    limitReachedClass: "badge mt-1 badge-danger"
  });
})(jQuery);