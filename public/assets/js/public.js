/* Toastr configurations */
toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": true,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": true,
  "onclick": null,
  "showDuration": 0,
  "hideDuration": 0,
  "timeOut": 0,
  "extendedTimeOut": 0,
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

$(document).ready( function() {
   // Search
   $('a[href="#search_form"]').on('click', function(event) {
		event.preventDefault();
		$('#search_form').addClass('open');
		$('#search_form > form > input[type="search_form"]').focus();
	});

	$( '#search_form' ).on('click keyup', function(event) {
		if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
			$(this).removeClass('open');
		}
	});

   // Satepicker
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

   // initialize Smart Menus
   var $mainMenu = $('#main-menu');
   if ( $mainMenu.length ) {
      $mainMenu.smartmenus({
         mainMenuSubOffsetX: 0,
         mainMenuSubOffsetY: 0,
         subMenusSubOffsetX: 0,
         subMenusSubOffsetY: 0
      });
   }
   var $mainMenuState = $('#main-menu-state');
   if ( $mainMenuState.length ) {
      // animate mobile menu
      $mainMenuState.change(function(e) {
         if (this.checked) {
            $mainMenu.hide().slideDown(250, function() { $mainMenu.css('display', ''); });
         } else {
            $mainMenu.show().slideUp(250, function() { $mainMenu.css('display', ''); });
         }
      });
      // hide mobile menu beforeunload
      $(window).bind('beforeunload unload', function() {
         if ($mainMenuState[0].checked) {
            $mainMenuState[0].click();
         }
      });
   }

   // Custom function which toggles between sticky class (is-sticky)
   var stickyToggle = function (sticky, stickyWrapper, scrollElement) {
      var stickyHeight = sticky.outerHeight();
      var stickyTop = stickyWrapper.offset().top;
      if (scrollElement.scrollTop() >= stickyTop) {
         stickyWrapper.height(stickyHeight);
         sticky.addClass("is-sticky");
      }
      else {
         sticky.removeClass("is-sticky");
         stickyWrapper.height('auto');
      }
   };

   // Find all data-toggle="sticky-menu" elements
   $('[data-toggle="sticky-menu"]').each(function () {
      var sticky = $(this);
      var stickyWrapper = $('<div>').addClass('sticky-wrapper'); // insert hidden element to maintain actual top offset on page
      sticky.before(stickyWrapper);
      sticky.addClass('sticky');

      // Scroll & resize events
      $(window).on('scroll.sticky-menu resize.sticky-menu', function () {
         stickyToggle(sticky, stickyWrapper, $(this));
      });

      // On page load
      stickyToggle(sticky, stickyWrapper, $(window));
   });

   if ($("#quote").length) {
		$("#quote").marquee();
	}

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

/* Biar pilihan 1 dan 2 tidak sama */
function check_options( elementId ) {
	var select = $('#' + (elementId == 1 ? 'first':'second') + '_choice_id');
	if (elementId == 1) {
		var second_choice_id = $('#second_choice_id');
		if (second_choice_id.val() == select.val()) second_choice_id.val('');
	} else {
		var first_choice_id = $('#first_choice_id');
		if (first_choice_id.val() == select.val()) first_choice_id.val('');
	}
}

function change_country_field() {
	var citizenship = $('#citizenship').val();
	var country = $('.country');
	citizenship == 'WNA' ? country.show() : country.hide();
}

function renderRecaptcha() {
	grecaptcha.ready(function() {
		grecaptcha.execute(_RECAPTCHA_SITE_KEY, {action: 'submit'}).then(function(token) {
			document.querySelector('.g-recaptcha-response').value = token;
		});
	});
}

// Send Message
function send_message() {
	var values = {
		comment_author: $('#comment_author').val(),
		comment_email: $('#comment_email').val(),
		comment_url: $('#comment_url').val(),
		comment_content: $('#comment_content').val()
	};
	if (_RECAPTCHA_STATUS) values['g-recaptcha-response'] = $( '.g-recaptcha-response' ).val();
	_H.Loading( true );
	$.post(_BASE_URL + 'hubungi-kami/save', values, function(response) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		_H.Notify(res.status, res.message);
		if (res.status == 'success') $('input[type="text"], input[type="email"], textarea').val('');
		renderRecaptcha();
	});
}

// Post Comments
function post_comments() {
	var values = {
		comment_author: $('#comment_author').val(),
		comment_email: $('#comment_email').val(),
		comment_url: $('#comment_url').val(),
		comment_content: $('#comment_content').val(),
		comment_post_id: $('#comment_post_id').val()
	};
	if (_RECAPTCHA_STATUS) values['g-recaptcha-response'] = $( '.g-recaptcha-response' ).val();
	_H.Loading( true );
	$.post(_BASE_URL + 'public/post_comments', values, function(response) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		_H.Notify(res.status, res.message);
		if (res.status == 'success') $('input[type="text"], input[type="email"], textarea').val('');
		renderRecaptcha();
	});
}

// Alumni Registered
function alumni_registration() {
	_H.Loading( true );
	var data = new FormData();
	data.append('photo', $('input[name="photo"]')[0].files[ 0 ]);
	data.append('full_name', $('#full_name').val());
	data.append('gender', $('#gender').val());
	data.append('birth_place', $('#birth_place').val());
	data.append('birth_date', $('#birth_date').val());
	data.append('start_date', $('#start_date').val());
	data.append('end_date', $('#end_date').val());
	data.append('nis', $('#nis').val());
	data.append('street_address', $('#street_address').val());
	data.append('phone', $('#phone').val());
	data.append('mobile_phone', $('#mobile_phone').val());
	data.append('email', $('#email').val());
	if (_RECAPTCHA_STATUS) data.append('g-recaptcha-response', $( '.g-recaptcha-response' ).val());
	$.ajax({
		url: _BASE_URL + 'public/alumni/save',
		type: 'POST',
		data: data,
		contentType: false,
		processData: false,
		success : function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			if (res.status == 'success') $( document ).find('input, textarea').val('');
			_H.Notify(res.status, res.message);
			renderRecaptcha();
		},
		error: function() {
			_H.Loading( false );
			renderRecaptcha();
		}
	});
}

// Preview Galeri Foto
function photo_preview(id) {
	_H.Loading( true );
	$.post( _BASE_URL + 'public/gallery_photos/preview', {id: id}, function(response) {
		_H.Loading( false );
		if(response.count > 0) {
			$.magnificPopup.open({
				items: response.items,
				gallery: {
					enabled: true
				},
				type: 'image'
			});
		} else {
			_H.Notify('info', 'Photo tidak ditemukan !');
		}
	});
}

// Subscribe
function subscribe() {
	_H.Loading( true );
	var values = {
		subscriber: $('#subscriber').val(),
		csrf_token: $('meta[name="csrf-token"]').attr('content')
	};
	$.post(_BASE_URL + 'subscribe', values, function(response) {
		var res = _H.StrToObject( response );
		if (res.status == 'token_error') {
			window.location.reload(true);
		} else {
			_H.Loading( false );
			_H.Notify(res.status, res.message);
			$('#subscriber').val('');
			$('meta[name="csrf-token"]').attr('content', res.csrf_token);
		}
	});
}

// Vote
function vote() {
	var values = {
		answer_id: $('input[name="answer_id"]:checked').val(),
		csrf_token: $('meta[name="csrf-token"]').attr('content')
	};
	if (values['answer_id']) {
		_H.Loading( true );
		$.post(_BASE_URL + 'vote', values, function(response) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			if (res.status == 'token_error') {
				window.location.reload(true);
			} else {
				_H.Notify(res.status, res.message);
				$('meta[name="csrf-token"]').attr('content', res.csrf_token);
            $('input[name=answer_id]').prop('checked', false);
			}
		});
	} else {
		_H.Notify('info', 'Anda belum memilih jawaban.');
	}
}

// Get Subject Settings
function get_subject_settings() {
	var values = {
		admission_type_id: $('#admission_type_id').val(),
		major_id: $('#first_choice_id').val() || 0
	};
	if (values['admission_type_id']) {
		$.post(_BASE_URL + 'public/admission_form/get_subject_settings', values, function(response) {
			var res = _H.StrToObject( response );
			var semester_report_subjects = res.semester_report_subjects;
			var national_exam_subjects = res.national_exam_subjects;
			var str = '';
			if (semester_report_subjects.length) {
				str += '<h6 class="page-title mb-3">Nilai Rapor Sekolah</h6>';
				for (var y in semester_report_subjects) {
					var subject = semester_report_subjects[ y ];
					str += '<div class="form-group row mb-1 pt-2 pb-2">';
					str += '<label for="subject_' + subject.subject_setting_detail_id + '" class="col-sm-4 control-label">' + subject.subject_name + ' <span style="color: red">*</span></label>';
					str += '<div class="col-sm-8">';
					str += '<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="subject_' + subject.subject_setting_detail_id + '" name="subjects" placeholder="Nilai Rapor diisi dalam format desimal dengan tanda pemisah titik. Contoh: 80.89">';
					str += '</div>';
					str += '</div>';
				}
			}
			if (national_exam_subjects.length) {
				str += '<h6 class="page-title mb-3">Nilai Ujian Nasional</h6>';
				for (var y in national_exam_subjects) {
					var subject = national_exam_subjects[ y ];
					str += '<div class="form-group row mb-1 pt-2 pb-2">';
					str += '<label for="subject_' + subject.subject_setting_detail_id + '" class="col-sm-4 control-label">' + subject.subject_name + ' <span style="color: red">*</span></label>';
					str += '<div class="col-sm-8">';
					str += '<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="subject_' + subject.subject_setting_detail_id + '" name="subjects" placeholder="Nilai Ujian Nasional diisi dalam format desimal dengan tanda pemisah titik. Contoh: 80.89">';
					str += '</div>';
					str += '</div>';
				}
			}
			$('.subject_scores').empty().append(str);
		});
	} else {
		$('.subject_scores').empty();
	}
}

// Admission Form
function student_registration() {
	// Get Subject Values
	var subject_scores = {};
	var subjects = $(".subject_scores").find('input[name="subjects"]');
	var next = true;
	if (subjects.length) {
		subjects.each(function() {
			var id = this.id; // subject_1, subject_2, dst
			var value = this.value;
			if (!$('#' + id).val()) {
				var subject_name = $('label[for="' + id + '"').text().replace('*', '');
				_H.Notify('error', 'Nilai ' + subject_name + ' belum diisi');
				next = false;
			}
			var subject_setting_detail_id = id.split('_');
			subject_scores[subject_setting_detail_id[ 1 ]] = value;
		});
	}
	if ( ! next ) return;
	var data = new FormData();
	data.append('is_transfer', $('#is_transfer').val());
	data.append('admission_type_id', $('#admission_type_id').val());
	data.append('subject_scores', JSON.stringify(subject_scores));
	if ($('#first_choice_id').length) {
		data.append('first_choice_id', $('#first_choice_id').val());
	}
	if ($('#second_choice_id').length) {
		data.append('second_choice_id', $('#second_choice_id').val());
	}
	if ($('#prev_exam_number').length) {
		data.append('prev_exam_number', $('#prev_exam_number').val());
	}
	if ($('#prev_school_name').length) {
		data.append('prev_school_name', $('#prev_school_name').val());
	}
	if ($('#skhun').length) {
		data.append('skhun', $('#skhun').val());
	}
	if ($('#prev_diploma_number').length) {
		data.append('prev_diploma_number', $('#prev_diploma_number').val());
	}

	// BIODATA PESESRTA DIDIK
	data.append('full_name', $('#full_name').val());
	data.append('gender', $('#gender').val());
	data.append('birth_date', $('#birth_date').val());
	if ($('#nisn').length) {
		data.append('nisn', $('#nisn').val());
	}
	if ($('#nik').length) {
		data.append('nik', $('#nik').val());
	}
	if ($('#family_card_number').length) {
		data.append('family_card_number', $('#family_card_number').val());
	}
	if ($('#birth_place').length) {
		data.append('birth_place', $('#birth_place').val());
	}
	if ($('#birth_certificate_number').length) {
		data.append('birth_certificate_number', $('#birth_certificate_number').val());
	}
	if ($('#religion_id').length) {
		data.append('religion_id', $('#religion_id').val());
	}
	if ($('#citizenship').length) {
		data.append('citizenship', $('#citizenship').val());
	}
	if ($('#country').length) {
		data.append('country', $('#country').val());
	}
	if ($('#special_need_id').length) {
		data.append('special_need_id', $('#special_need_id').val());
	}
	if ($('#street_address').length) {
		data.append('street_address', $('#street_address').val());
	}
	if ($('#rt').length) {
		data.append('rt', $('#rt').val());
	}
	if ($('#rw').length) {
		data.append('rw', $('#rw').val());
	}
	if ($('#sub_village').length) {
		data.append('sub_village', $('#sub_village').val());
	}
	if ($('#village').length) {
		data.append('village', $('#village').val());
	}
	if ($('#sub_district').length) {
		data.append('sub_district', $('#sub_district').val());
	}
	if ($('#district').length) {
		data.append('district', $('#district').val());
	}
	if ($('#postal_code').length) {
		data.append('postal_code', $('#postal_code').val());
	}
	if ($('#latitude').length) {
		data.append('latitude', $('#latitude').val());
	}
	if ($('#longitude').length) {
		data.append('longitude', $('#longitude').val());
	}
	if ($('#residence_id').length) {
		data.append('residence_id', $('#residence_id').val());
	}
	if ($('#transportation_id').length) {
		data.append('transportation_id', $('#transportation_id').val());
	}
	if ($('#child_number').length) {
		data.append('child_number', $('#child_number').val());
	}
	if ($('#employment_id').length) {
		data.append('employment_id', $('#employment_id').val());
	}
	if ($('#have_kip').length) {
		data.append('have_kip', $('#have_kip').val());
	}
	if ($('#receive_kip').length) {
		data.append('receive_kip', $('#receive_kip').val());
	}
	if ($('#reject_pip').length) {
		data.append('reject_pip', $('#reject_pip').val());
	}
	// DATA AYAH KANDUNG
	if ($('#father_name').length) {
		data.append('father_name', $('#father_name').val());
	}
	if ($('#father_nik').length) {
		data.append('father_nik', $('#father_nik').val());
	}
	if ($('#father_birth_place').length) {
		data.append('father_birth_place', $('#father_birth_place').val());
	}
	if ($('#father_birth_date').length) {
		data.append('father_birth_date', $('#father_birth_date').val());
	}
	if ($('#father_education_id').length) {
		data.append('father_education_id', $('#father_education_id').val());
	}
	if ($('#father_employment_id').length) {
		data.append('father_employment_id', $('#father_employment_id').val());
	}
	if ($('#father_monthly_income_id').length) {
		data.append('father_monthly_income_id', $('#father_monthly_income_id').val());
	}
	if ($('#father_special_need_id').length) {
		data.append('father_special_need_id', $('#father_special_need_id').val());
	}

	// DATA IBU KANDUNG
	if ($('#mother_name').length) {
		data.append('mother_name', $('#mother_name').val());
	}
	if ($('#mother_nik').length) {
		data.append('mother_nik', $('#mother_nik').val());
	}
	if ($('#mother_birth_place').length) {
		data.append('mother_birth_place', $('#mother_birth_place').val());
	}
	if ($('#mother_birth_date').length) {
		data.append('mother_birth_date', $('#mother_birth_date').val());
	}
	if ($('#mother_education_id').length) {
		data.append('mother_education_id', $('#mother_education_id').val());
	}
	if ($('#mother_employment_id').length) {
		data.append('mother_employment_id', $('#mother_employment_id').val());
	}
	if ($('#mother_monthly_income_id').length) {
		data.append('mother_monthly_income_id', $('#mother_monthly_income_id').val());
	}
	if ($('#mother_special_need_id').length) {
		data.append('mother_special_need_id', $('#mother_special_need_id').val());
	}
	// DATA WALI
	if ($('#guardian_name').length) {
		data.append('guardian_name', $('#guardian_name').val());
	}
	if ($('#guardian_nik').length) {
		data.append('guardian_nik', $('#guardian_nik').val());
	}
	if ($('#guardian_birth_place').length) {
		data.append('guardian_birth_place', $('#guardian_birth_place').val());
	}
	if ($('#guardian_birth_date').length) {
		data.append('guardian_birth_date', $('#guardian_birth_date').val());
	}
	if ($('#guardian_education_id').length) {
		data.append('guardian_education_id', $('#guardian_education_id').val());
	}
	if ($('#guardian_employment_id').length) {
		data.append('guardian_employment_id', $('#guardian_employment_id').val());
	}
	if ($('#guardian_monthly_income_id').length) {
		data.append('guardian_monthly_income_id', $('#guardian_monthly_income_id').val());
	}
	// KONTAK
	if ($('#phone').length) {
		data.append('phone', $('#phone').val());
	}
	if ($('#mobile_phone').length) {
		data.append('mobile_phone', $('#mobile_phone').val());
	}
	if ($('#email').length) {
		data.append('email', $('#email').val());
	}
	// DATA PERIODIK
	if ($('#height').length) {
		data.append('height', $('#height').val());
	}
	if ($('#weight').length) {
		data.append('weight', $('#weight').val());
	}
	if ($('#head_circumference').length) {
		data.append('head_circumference', $('#head_circumference').val());
	}
	if ($('#mileage').length) {
		data.append('mileage', $('#mileage').val());
	}
	if ($('#traveling_time').length) {
		data.append('traveling_time', $('#traveling_time').val());
	}
	if ($('#sibling_number').length) {
		data.append('sibling_number', $('#sibling_number').val());
	}
	// KESEJAHTERAAN PESERTA DIDIK
	if ($('#welfare_type').length) {
		data.append('welfare_type', $('#welfare_type').val());
	}
	if ($('#welfare_number').length) {
		data.append('welfare_number', $('#welfare_number').val());
	}
	if ($('#welfare_name').length) {
		data.append('welfare_name', $('#welfare_name').val());
	}
	// DOCUMENTS
	if ($('#photo').length) {
		data.append('photo', $('input[name="photo"]')[0].files[ 0 ]);
	}
	if ($('#family_card').length) {
		data.append('family_card', $('input[name="family_card"]')[0].files[ 0 ]);
	}
	if ($('#birth_certificate').length) {
		data.append('birth_certificate', $('input[name="birth_certificate"]')[0].files[ 0 ]);
	}
	if ($('#father_identity_card').length) {
		data.append('father_identity_card', $('input[name="father_identity_card"]')[0].files[ 0 ]);
	}
	if ($('#mother_identity_card').length) {
		data.append('mother_identity_card', $('input[name="mother_identity_card"]')[0].files[ 0 ]);
	}
	if ($('#guardian_identity_card').length) {
		data.append('guardian_identity_card', $('input[name="guardian_identity_card"]')[0].files[ 0 ]);
	}
	data.append('declaration', $('#declaration').prop('checked'));
	if (_RECAPTCHA_STATUS) data.append('g-recaptcha-response', $( '.g-recaptcha-response' ).val());
	_H.Loading( true );
	$.ajax({
		url: _BASE_URL + 'public/admission_form/save',
		type: 'POST',
		data: data,
		contentType: false,
		processData: false,
		success : function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			if (res.status == 'success') {
				window.location.href = _BASE_URL + 'public/print_admission_form/pdf/' + res.id + '/' + res.registration_number + '/' + res.birth_date; 
			} else {
				_H.Notify(res.status, res.message);
				renderRecaptcha();
			}
		}
	});
}

// Cetak Formulir PPDB
function print_admission_form() {
	var values = {
		birth_date: $('#birth_date').val(),
		registration_number: $('#registration_number').val()
	};
	if (_RECAPTCHA_STATUS) values['g-recaptcha-response'] = $( '.g-recaptcha-response' ).val();
	_H.Loading( true );
	$.post(_BASE_URL + 'public/print_admission_form/process', values, function(response) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		if (res.status == 'success') {
			window.location.href = _BASE_URL + 'public/print_admission_form/pdf/' + res.id + '/' + res.registration_number + '/' + res.birth_date;
		} else {
			_H.Notify(res.status, res.message);
			renderRecaptcha();
		}
	});
}

// Cetak Kartu Peserta Ujian PPDB
function print_admission_exam_card() {
	var values = {
		birth_date: $('#birth_date').val(),
		registration_number: $('#registration_number').val()
	};
	if (_RECAPTCHA_STATUS) values['g-recaptcha-response'] = $( '.g-recaptcha-response' ).val();
	_H.Loading( true );
	$.post(_BASE_URL + 'public/print_admission_exam_card/process', values, function(response) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		if (res.status == 'success') {
			window.location.href = _BASE_URL + 'public/print_admission_exam_card/pdf/' + res.id + '/' + res.registration_number + '/' + res.birth_date;
		} else {
			_H.Notify(res.status, res.message);
			renderRecaptcha();
		}
	});
}

// Hasil Seleksi PPDB
function selection_results() {
	var subject_types = {
		'exam_schedule':'Nilai Ujian Tes Tulis',
		'semester_report':'Nilai Rapor',
		'national_exam':'Nilai Ujian Nasional'
	};
	var values = {
		birth_date: $('#birth_date').val(),
		registration_number: $('#registration_number').val(),
	};
	if (_RECAPTCHA_STATUS) values['g-recaptcha-response'] = $( '.g-recaptcha-response' ).val();
	_H.Loading( true );
	$.post(_BASE_URL + 'public/admission_selection_results/selection_results', values, function(response) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		if (res.status == 'recaptcha_error' || res.status == 'validation_errors' || res.status == 'warning' || res.status == 'info') {
			renderRecaptcha();
			_H.Notify(res.status, res.message);
		} else {
			var str = '<div class="table-responsive bg-light p-0 border border-secondary rounded-0">';
			var subject_scores = res.subject_scores;
			if (subject_scores.length) {
				str += '<table class="table table-bordered table-hover table-striped table-condensed mb-2">';
				str += '<thead>';
				str += '<tr>';
				str += '<th width="10px">NO</th>';
				str += '<th>MATA PELAJARAN</th>';
				str += '<th>KATEGORI NILAI</th>';
				str += '<th>NILAI</th>';
				str += '</tr>';
				str += '</thead>';
				str += '<tbody>';
				var no = 1;
				var total_score = 0;
				for (var z in subject_scores) {
					var subjects = subject_scores[ z ];
					str += '<tr>';
					str += '<td>' + no + '.</td>';
					str += '<td>' + subjects.subject_name + '</td>';
					str += '<td>' + subject_types[ subjects.subject_type ] + '</td>';
					str += '<td align="right">' + subjects.score + '</td>';
					str += '</tr>';
					no++;
					total_score += parseFloat(subjects.score);
				}
				str += '<tr>';
				str += '<th style="text-align:right;" colspan="3">NILAI RATA-RATA</th>';
				str += '<th style="text-align:right;">' + (total_score / subject_scores.length).toFixed(2) + '</th>';
				str += '</tr>';
				str += '</tbody>';
				str += '</table>';
			}
			str += '<p class="p-3">' + res.message + '</p>';
			str += '</div>';
			$('.selection_results').empty().html(str);
			$('.form-search').remove();
		}
	});
}
