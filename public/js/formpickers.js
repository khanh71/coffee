(function ($) {
  'use strict';
  if ($("#datepicker-popup-startday").length) {
    $('#datepicker-popup-startday').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endday').datepicker('setStartDate', minDate);
    });
  }

  if ($("#datepicker-popup-endday").length) {
    $('#datepicker-popup-endday').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#startday').datepicker('setEndDate', maxDate);
        });
  }

  if ($("#datepicker-popup-startday-edit").length) {
    $('#datepicker-popup-startday-edit').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#enddayedit').datepicker('setStartDate', minDate);
    });
  }

  if ($("#datepicker-popup-endday-edit").length) {
    $('#datepicker-popup-endday-edit').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#startdayedit').datepicker('setEndDate', maxDate);
    });
  }




})(jQuery);