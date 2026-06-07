<script type="text/javascript">
/**
* Semester Attendance Report
*/
function _get_semester_attendance_report() {
	var values = {
		academic_year_id: $('#academic_year_id').val(),
		semester: $('#semester').val(),
		class_group_id: $('#class_group_id').val()
	}
	_H.Loading( true );
	$.post(_BASE_URL + 'academic/semester_attendance_report/get_semester_attendance_report', values, function( response ) {
		var res = _H.StrToObject( response );
		var attendance = res.attendance;
		if (attendance.length) {
			var students = [];
			for (var x in attendance) {
				var row = attendance[ x ];
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
			str += '<th colspan="9">';
			str += 'REKAP PRESENSI PER SEMESTER | ' + $('#academic_year_id option:selected').text() + ' - ' + $('#semester option:selected').text() + ' - ' + $('#class_group_id option:selected').text() + '<button type="button" onclick="save2excel(); return false;" class="btn btn-xs btn-success pull-right"><i class="fa fa-file-excel-o"></i> SIMPAN SEBAGAI FILE EXCEL</button>';
			str += '</th>';
			str += '</tr>';
			str += '<tr>';
			str += '<th width="30px">NO</th>';
			str += '<th>NIS</th>';
			str += '<th>NAMA PESERTA DIDIK</th>';
			str += '<th>L/P</th>';
			str += '<th width="30px">H</th>';
			str += '<th width="30px">S</th>';
			str += '<th width="30px">I</th>';
			str += '<th width="30px">A</th>';
			str += '<th width="30px">NA</th>';
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
				var H = 0, S = 0, I = 0, A = 0, NA = 0;
				var presence = searchAttendance(row.nis, attendance);
				if (presence == 'H') H++;
				if (presence == 'S') S++;
				if (presence == 'I') I++;
				if (presence == 'A') A++;
				if (presence == 'NA') NA++;
				str += '<td>' + H + '</td>';
				str += '<td>' + S + '</td>';
				str += '<td>' + I + '</td>';
				str += '<td>' + A + '</td>';
				str += '<td>' + NA + '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('.semester-attendance-report').empty().html(str);
			$(".select2").select2({ width: '100%' });
		} else {
			$('.semester-attendance-report').empty();
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
function searchAttendance(nis, array) {
	for (var z in array) {
		var arr = array[ z ];
		if (arr.nis === nis) return arr.presence;
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
	var fileName = 'Rekap-Presensi-Per-Semester';
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

							</div>
							<div class="col-md-6 col-sm-12 col-xs-12">
								<div class="form-group">
									<label for="class_group_id" class="col-sm-5 control-label">Kelas</label>
									<div class="col-sm-7">
										<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="form-control select2" id="class_group_id"');?>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-5 col-sm-7">
										<button type="button" onclick="_get_semester_attendance_report(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<div id="data-table-renderer" class="table-responsive">
						<table width="100%" class="table table-striped table-condensed semester-attendance-report"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
