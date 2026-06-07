/* Toastr configurations */
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": true,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

$(document).ready(function () {
   // Select2
	$( document ).find(".select2").select2({ width: '100%' });

   // Date Picker
	$( document ).find( 'input.date:enabled' ).datetimepicker({
		format: 'yyyy-mm-dd',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0,
		fontAwesome: true
	});

   // Scroll Top
   var elementId = $('#return-to-top');
   $(window).scroll(function() {
      if ($(this).scrollTop() >= 50) {
           elementId.fadeIn(200);
      } else {
           elementId.fadeOut(200);
      }
   });

   // Return to Top
   elementId.click(function() {
      $('body,html').animate({
           scrollTop : 0
      }, 500);
   });
});