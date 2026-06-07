<script type="text/javascript">
$(document).ready(function() {
	// Select2
	$(".select2").select2({ width: '100%' });
	// Datepicker
	// Date Picker
	$( document ).find( 'input.date' ).datetimepicker({
		format: 'yyyy-mm-dd',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
});

function search() {
	var values = {
		academic_year_id: $('#academic_year_id').val(),
		semester: $('#semester').val(),
		class_group_id: $('#class_group_id').val(),
		subject_id: $('#subject_id').val(),
		start_date: $('#start_date').val(),
		end_date: $('#end_date').val()
	}
	if (values['start_date'] && _H.IsValidDate( values['start_date'] ) && values['end_date'] && _H.IsValidDate(values['end_date'])) {
		_get_summary_report(values);
		_get_detail_report(values);
	} else {
		_H.Notify('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
	}
}
/**
* Daily Report
*/
function _get_summary_report( values ) {
	_H.Loading( true );
	$.post(_BASE_URL + 'teacher/meeting_attendance_report/summary_report', values, function( response ) {
		var res = _H.StrToObject( response );
		var rows = res.rows;
		var str = '';
		if (rows.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="10">';
			str += '<button type="button" onclick="_save2excel(\'summary\'); return false;" class="btn btn-sm btn-inverse"><i class="fa fa-file-excel-o"></i></button> ATTENDANCE SUMMARY REPORT';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th>TANGGAL</th>';
			str += '<th>JAM MULAI</th>';
			str += '<th>JAM SELESAI</th>';
			str += '<th>TOPIK</th>';
			str += '<th>H</th>';
			str += '<th>S</th>';
			str += '<th>I</th>';
			str += '<th>A</th>';
			str += '<th>TOTAL</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for (var z in rows) {
				var row = rows[ z ];
				str += '<tr>';
				str += '<td>' + no + '.</td>';
				str += '<td>' + row.date + '</td>';
				str += '<td>' + row.start_time + '</td>';
				str += '<td>' + row.end_time + '</td>';
				str += '<td>' + row.discussion + '</td>';
				str += '<td>' + row.H + '</td>';
				str += '<td>' + row.S + '</td>';
				str += '<td>' + row.I + '</td>';
				str += '<td>' + row.A + '</td>';
				str += '<td>' + row.total + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.summary-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.summary-report').empty();
		}
		_H.Loading( false );
	});
}

/**
* Monthly Report
*/
function _get_detail_report( values ) {
	_H.Loading( true );
	$.post(_BASE_URL + 'teacher/meeting_attendance_report/detail_report', values, function( response ) {
		var res = _H.StrToObject( response );
		var dates = res.dates;
		var rows = res.rows;
		if (rows.length) {
			var students = [];
			for (var x in rows) {
				var row = rows[ x ];
				if (!in_array('nis', row.nis, students)) {
					var student = {
						'nis': row.nis,
						'full_name': row.full_name
					}
					students.push(student);
				}
			}
			var str = '';
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="' + (7 + dates.length) + '">';
			str += '<button type="button" onclick="_save2excel(\'detail\'); return false;" class="btn btn-sm btn-inverse"><i class="fa fa-file-excel-o"></i></button> ATTENDANCE DETAIL REPORT';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th width="150px">NIS</th>';
			str += '<th width="250px">NAMA LENGKAP</th>';
			for (var x in dates) {
				str += '<th width="30px" title="' + dates[ x ] + '">' + dates[ x ].substr(8, 2) + '</th>';
			}
			str += '<th width="30px">H</th>';
			str += '<th width="30px">S</th>';
			str += '<th width="30px">I</th>';
			str += '<th width="30px">A</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = 1;
			for( var y in students) {
				var student = students[ y ];
				str += '<tr>';
				str += '<td width="30px">' + no + '</td>';
				str += '<td>' + student.nis + '</td>';
				str += '<td>' + student.full_name + '</td>';
				var H = 0, S = 0, I = 0, A = 0;
				for (var z in dates) {
					var date = dates[ z ];
					var presence = searchAttendance(student.nis, date, rows);
					if (presence == 'H') H++;
					if (presence == 'S') S++;
					if (presence == 'I') I++;
					if (presence == 'A') A++;
					str += '<td>' + presence + '</td>';
				}
				str += '<td>' + H + '</td>';
				str += '<td>' + S + '</td>';
				str += '<td>' + I + '</td>';
				str += '<td>' + A + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.detail-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.detail-report').empty();
		}
		_H.Loading( false );
	});
}

/**
* Search in Array
* @return Boolean
*/
function in_array(key, value, array) {
	for (var z in array) {
		if (array[ z ][ key ] === value) return true;
	}
	return false;
}

/**
* Search Attendance
* @return Boolean
*/
function searchAttendance(nis, date, array) {
	for (var z in array) {
		var arr = array[ z ];
		if (arr.nis === nis && arr.date == date) return arr.presence;
	}
	return '-';
}

/**
* Save to Excel
*/
function _save2excel( type ) {
	var elementId = 'excel-report';
	var div = '<div id="' + elementId + '" style="display: none;"></div>';
	$( div ).appendTo( document.body );
	var table = $( '.' + (type == 'summary' ? 'table-summary-report' : 'table-detail-report') ).html();
	$( '#' + elementId ).html( table );
	var fileName = 'Rekap-Presensi' + (type == 'summary' ? '' : '-Detail') + '-Per-Mata-Pelajaran | ' + $('#employee_id option:selected').text().toUpperCase();
	_H.ExportToExcel( elementId, fileName ); // Export to Excel
}
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<small>'.$sub_title.'</small>' : ''?>
   </div>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-md-4 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="academic_year_id" class="col-sm-5 control-label">Tahun Pelajaran</label>
									<div class="col-sm-7">
										<?=form_dropdown('academic_year_id', $academic_year_dropdown, '', 'class="form-control select2" id="academic_year_id"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="semester" class="col-sm-5 control-label">Semester</label>
									<div class="col-sm-7">
										<?=form_dropdown('semester', ['odd' => 'Ganjil', 'even' => 'Genap'], '', 'class="form-control select2" id="semester"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="class_group_id" class="col-sm-5 control-label">Kelas</label>
									<div class="col-sm-7">
										<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="class_group_id"');?>
									</div>
								</div>
							</div>
							<div class="col-md-8 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="subject_id" class="col-sm-4 control-label">Mata Pelajaran</label>
									<div class="col-sm-8">
										<?=form_dropdown('subject_id', $subject_dropdown, '', 'class="form-control select2" id="subject_id"');?>
									</div>
								</div>
								<div class="form-group">
									<label for="start_date" class="col-sm-4 control-label">Dari Tanggal</label>
									<div class="col-sm-8">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control date" id="start_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-sm-4 control-label">Sampai Tanggal</label>
									<div class="col-sm-8">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control date" id="end_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<div class="btn-group">
											<button type="button" onclick="search(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
											<button type="reset" class="btn btn-sm btn-inverse"><i class="fa fa-refresh"></i> RESET</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div class="table-summary-report table-responsive">
						<table width="100%" class="table table-striped table-condensed summary-report"></table>
					</div>
					<div class="table-detail-report table-responsive">
						<table width="100%" class="table table-striped table-condensed detail-report"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
