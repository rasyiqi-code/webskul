<style type="text/css">
input[type="checkbox"] {
	width: 20px;
	height: 20px;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	getProspectiveStudent();
	getAttendancesList();
});

var exam_schedule_id = '<?=service('uri')->getSegment(4)?>';

// Chek / unchek All Checkbox
function check_all(checked, el) {
	$('input[name="' + el + '"]').prop('checked', checked);
}

function getProspectiveStudent() {
	_H.Loading( true );
	$.post(_BASE_URL + '/admission/exam_attendances/get_prospective_students', {'exam_schedule_id':exam_schedule_id}, function(response) {
		var res = _H.StrToObject( response );
		var students = res.students;
		var str = '';
		if (students.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-prospective-students\')" /></th>';
			str += '<th>NO. DAFTAR</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			for (var z in students) {
				var row = students[ z ];
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-prospective-students" value="' + row.student_id + '" /></td>';
				str += '<td>' + row.registration_number + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '</tr>';
			}
			str += '</tbody>';
		}
		$('table.prospective-students').empty().html(str);
		_H.Loading( false );
	});
}

function getAttendancesList() {
	_H.Loading( true );
	var exam_schedule_id = '<?=service('uri')->getSegment(4)?>';
	$.post(_BASE_URL + '/admission/exam_attendances/get_attendance_lists', {'exam_schedule_id':exam_schedule_id}, function(response) {
		var res = _H.StrToObject( response );
		var students = res.students;
		var str = '';
		if (students.length) {
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px"><input type="checkbox" onclick="check_all(this.checked, \'checkbox-attendance-list\')" /></th>';
			str += '<th>NO. DAFTAR</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '<th>PRESENSI</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			for (var z in students) {
				var row = students[ z ];
				str += '<tr>';
				str += '<td><input type="checkbox" name="checkbox-attendance-list" value="' + row.id + '" /></td>';
				str += '<td>' + row.registration_number + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '<td>';
				str += '<select class="form-control input-sm" name="presence" id="presence_' + row.id + '">';
				str += '<option value="H" ' + (row.presence == 'H' ? 'selected="selected"' : '') + '>H</option>';
				str += '<option value="S" ' + (row.presence == 'S' ? 'selected="selected"' : '') + '>S</option>';
				str += '<option value="I" ' + (row.presence == 'I' ? 'selected="selected"' : '') + '>I</option>';
				str += '<option value="A" ' + (row.presence == 'A' ? 'selected="selected"' : '') + '>A</option>';
				str += '</select>';
				str += '</td>';
				str += '</tr>';
			}
			str += '</tbody>';
		}
		$('table.attendance-list').empty().html(str);
		_H.Loading( false );
	});
}

function saveAttendanceList() {
	var rows = $('input[name="checkbox-prospective-students"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data Peserta Didik yang terceklis akan dimasukan ke dalam daftar Peserta Ujian Tes Tulis?', 'Konfirmasi').then(function() {
			_H.Loading( true );
			var values = {
				student_ids: student_ids.join(','),
				exam_schedule_id: exam_schedule_id
			};
			$.post(_BASE_URL + 'admission/exam_attendances/save_attendance_lists', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				getProspectiveStudent();
				getAttendancesList();
				_H.Loading( false );
			});
		});
	} else {
		_H.Loading( false );
	}
}

// Delete Permanent Data
function deleteFromAttendanceList() {
	var rows = $('input[name="checkbox-attendance-list"]:checked');
	var ids = [];
	rows.each(function() {
		ids.push($(this).val());
	});
	if (ids.length) {
		eModal.confirm('Apakah anda yakin ' + ids.length + ' data Peserta Didik yang terceklis akan dihapus?', 'Konfirmasi').then(function() {
			_H.Loading( true );
			var values = {
				ids: ids.join(',')
			};
			$.post(_BASE_URL + 'admission/exam_attendances/delete_attendance_lists', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				getAttendancesList();
				getProspectiveStudent();
				_H.Loading( false );
			});
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang terpilih');
	}
}

function savePresences() {
	$('.save-attendances').attr('disabled', 'disabled');
	var rows = $('select[name="presence"]');
	var presences = [];
	rows.each(function() {
		presences.push({
			id:this.id.split('_')[ 1 ],
			presence:$(this).val()
		});
	});
	if (presences.length) {
		_H.Loading( true );
		var values = {
			presences: JSON.stringify(presences)
		};
		$.post(_BASE_URL + 'admission/exam_attendances/save_attendance', values, function( response ) {
			_H.Notify('info', _H.Message(response.message));
			_H.Loading( false );
			$('.save-attendances').removeAttr('disabled');
			getProspectiveStudents();
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang tersimpan');
	}
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
		<div class="col-md-5 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<i class="fa fa-sign-out"></i>
					<h3 class="box-title">CALON PESERTA DIDIK BARU</h3>
					<div class="box-tools">
						<button class="btn btn-sm btn-success" onclick="saveAttendanceList()"><i class="fa fa-save"></i> SIMPAN</button>
					</div>
				</div>
				<div class="box-body">
					<dl class="dl-horizontal">
						<dt>Tahun Pelajaran</dt>
						<dd><?=$query->academic_year?></dd>
						<dt>Jenis Pendaftaran</dt>
						<dd><?=$query->admission_type?></dd>
						<?php if (__session('major_count') > 0) { ?>
							<dt>Jurusan</dt>
							<dd><?=$query->major_name?></dd>
						<?php } ?>
						<dt>Mata Pelajaran</dt>
						<dd><?=$query->subject_name?></dd>
						<dt>Tanggal</dt>
						<dd><?=indo_date($query->exam_date)?></dd>
						<dt>Waktu</dt>
						<dd><?=$query->exam_start_time?> s.d <?=$query->exam_end_time?></dd>
						<dt>Lokasi</dt>
						<dd>Gedung <?=str_replace(['gedung', 'Gedung'], '', $query->building_name)?> Ruang <?=$query->room_name?></dd>
						<dt>Kapasitas</dt>
						<dd><?=$query->room_capacity?> Orang</dd>
					</dl>
					<table class="table table-striped table-condensed prospective-students"></table>
				</div>
			</div>
		</div>
		<div class="col-md-7 col-sm-12 col-xs-12">
			<div class="box">
				<div class="box-header">
					<i class="fa fa-sign-out"></i>
					<h3 class="box-title">PESERTA UJIAN</h3>
					<div class="box-tools">
						<div class="btn-group">
							<button class="btn btn-sm btn-success save-attendances" onclick="savePresences()"><i class="fa fa-save"></i> SIMPAN PRESENSI</button>
							<button class="btn btn-sm btn-danger" onclick="deleteFromAttendanceList()"><i class="fa fa-trash"></i> HAPUS</button>
						</div>
					</div>
				</div>
				<div class="box-body">
					<div class="table-responsive">
						<table class="table table-striped table-condensed attendance-list"></table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>