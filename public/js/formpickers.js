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

  if ($("#datepicker-popup-dayfrom").length) {
    $('#datepicker-popup-dayfrom').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date.valueOf());
      $('#dayto').datepicker('setStartDate', minDate);
    });
  }

  if ($("#datepicker-popup-dayto").length) {
    $('#datepicker-popup-dayto').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#dayfrom').datepicker('setEndDate', maxDate);
    });
  }

  if ($("#datepicker-popup-dayto").length) {
    $('#datepicker-popup-dayto').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
    }).on('changeDate', function (selected) {
      var maxDate = new Date(selected.date.valueOf());
      $('#dayfrom').datepicker('setEndDate', maxDate);
    });
  }

  if ($("#datepicker-popup-daywork").length) {
    $('#datepicker-popup-daywork').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      defaultDate: new Date(),
    });
  }

  if ($("#datepicker-popup-daywork-edit").length) {
    $('#datepicker-popup-daywork-edit').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
      autoclose: true,
      defaultDate: new Date(),
    });
  }




})(jQuery);