<script type="text/javascript">
function search() {
	var values = {
		academic_year_id: $('#academic_year_id').val(),
		semester: $('#semester').val(),
		class_group_id: $('#class_group_id').val(),
		start_date: $('#start_date').val(),
		end_date: $('#end_date').val()
	}
	if (values['start_date'] && _H.IsValidDate( values['start_date'] ) && values['end_date'] && _H.IsValidDate( values['end_date'] )) {
		_get_daily_attendance_report(values);
	} else {
		_H.Notify('warning', 'Tanggal harus diisi dengan format YYYY-MM-DD');
	}
}

/**
* Monthly Attendance Report
*/
function _get_daily_attendance_report( values ) {
	_H.Loading( true );
	$.post(_BASE_URL + 'academic/daily_attendance_report/get_daily_attendance_report', values, function( response ) {
		var res = _H.StrToObject( response );
		var dates = response.dates;
		var attendance = response.attendance;
		if (attendance.length) {
			var students = [];
			for (var z in attendance) {
				var row = attendance[ z ];
				if (!inArray('nis', row.nis, students)) {
					var student = {
						'nis': row.nis,
						'full_name': row.full_name,
						'gender': row.gender
					}
					students.push(student);
				}
			}
			var str = '';
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th colspan="' + (8 + dates.length) + '">';
			str += 'REKAP PRESENSI PERHARI | ' + _H.ToIndonesianDate( $('#start_date').val() )  + ' - ' + _H.ToIndonesianDate( $('#end_date').val() )  + '<button type="button" onclick="save2excel(); return false;" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> SIMPAN SEBAGAI FILE EXCEL</button>';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th>NIS</th>';
			str += '<th>NAMA PESERTA DIDIK</th>';
			str += '<th width="60px">L/P</th>';
			for (var z in dates) {
				var date = dates[ z ];
				var is_sunday = '';
				if (_H.DayName( date ) == 'Sunday' ) {
					is_sunday = 'class="text-red"';
				}
				str += '<th ' + is_sunday + ' width="30px" title="' + date + '">' + date.substr(8, 2) + '</th>';
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
				var row = students[ y ];
				str += '<tr>';
				str += '<td>' + no + '</td>';
				str += '<td>' + row.nis + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '<td>' + row.gender + '</td>';
				var H = 0, S = 0, I = 0, A = 0;
				for (var z in dates) {
					var date = dates[ z ];
					var presence = searchAttendance(row.nis, date, attendance);
					if (presence == 'H') H++;
					if (presence == 'S') S++;
					if (presence == 'I') I++;
					if (presence == 'A') A++;
					var is_sunday = '';
					if (_H.DayName( date ) == 'Sunday' ) {
						is_sunday = 'class="text-red"';
					}
					str += '<td ' + is_sunday + '>' + presence + '</td>';
				}
				str += '<td>' + H + '</td>';
				str += '<td>' + S + '</td>';
				str += '<td>' + I + '</td>';
				str += '<td>' + A + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.daily-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.daily-report').empty();
			_H.Notify('info', 'Data tidak ditemukan!');
		}
		_H.Loading( false );
	});
}

/**
* Search in Array
* @return Boolean
*/
function inArray(key, value, array) {
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
function save2excel() {
	var elementId = 'excel-report';
	var div = '<div id="' + elementId + '" style="display: none;"></div>';
	$( div ).appendTo( document.body );
	var table = $( '#data-table-renderer' ).html();
	$( '#' + elementId ).html( table );
	var fileName = 'Rekap-Presensi-Per-Hari';
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
			<div class="callout">
				<h4>Petunjuk Singkat</h4>
				<ul>
					<li>Jika dalam satu hari Peserta Didik masuk di salah satu jam pelajaran, maka sistem akan menganggapnya hadir meskipun di jam berikutnya dinyatakan <b>Izin</b>, <b>Sakit</b>, ataupun <b>Alpa</b>.</li>
					<li>Jika ada data presensi dengan keterangan <b>"NA"</b>, itu disebabkan adanya kesalahan dalam penginputan data. Kesalahan tersebut dikarenakan adanya ketidaksamaan dalam mengisi status presensi Peserta Didik yang tidak hadir. Jika Peserta Didik mulai dari jam pertama sampai jam terakhir tidak hadir dengan keterangan <b>Sakit [ S ]</b>, maka semua presensi pada setiap Mata Pelajaran di hari tersebut harus diisi dengan keterangan <b>Sakit [ S ]</b>, berlaku juga untuk presensi dengan status <b>Izin [ I ]</b>, dan <b>Alpa [ A ]</b>. Untuk memperbaikinya, silahkan klik menu <a href="<?=base_url('academic/student_attendance_report')?>"><b>Rekap Presensi / Tampilkan Semua</b></a>.</li>
				</ul>
			</div>
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Pencarian Data</h3>
				</div>
				<div class="box-body">
					<form class="form-horizontal">
						<div class="row">
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="academic_year_id" class="col-sm-5 control-label">Tahun Pelajaran</label>
									<div class="col-sm-7">
										<?=form_dropdown('academic_year_id', $academic_year_dropdown, __session('current_academic_year_id'), 'class="form-control select2" id="academic_year_id"');?>
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
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="start_date" class="col-sm-5 control-label">Dari Tanggal</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control date" id="start_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-sm-5 control-label">Sampai Tanggal</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" autocomplete="off" class="form-control date" id="end_date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-7">
										<div class="btn-group">
											<button type="button" onclick="search(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
											<button type="reset" class="btn btn-sm btn-inverse"><i class="fa fa-refresh"></i> RESET</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div id="data-table-renderer" class="table-responsive">
						<table width="100%" class="table table-striped table-condensed daily-report"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
