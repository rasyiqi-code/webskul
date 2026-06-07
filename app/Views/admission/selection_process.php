<style type="text/css">
input[type="checkbox"] {
	width: 20px;
	height: 20px;
}
.table > thead > tr {
	background-color: #f9f9f9;
	border-bottom: 1px solid #d2d6de;
}
.table > thead > tr > th {
	text-align: center;
}
.table > thead > tr > th, .table > tbody > tr > td {
	border: 1px solid #d2d6de;
}
.number {
	text-align: right;
	font-weight: bold;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
	$(".select2").select2({ width: '100%' });
});

// Chek / unchek All Checkbox
function check_all(checked) {
	$('input[name="checkbox"]').prop('checked', checked);
}

/*
* @var Page Number
*/
var page_number = 0;

/*
* @var Per Page
*/
var per_page = 10;

/*
* @var Total Page
*/
var total_page = 0;

/*
* @var Total Rows
*/
var total_rows = 0;

/*
* button next
*/
function Next() {
	page_number++;
	getProspectiveStudents();
};

/*
* button previous
*/
function Prev() {
	page_number--;
	getProspectiveStudents();
};

/*
* button first
*/
function First() {
	page_number = 0;
	getProspectiveStudents();
};

/*
* button last
*/
function Last() {
	page_number = total_page - 1;
	getProspectiveStudents();
};

/*
* render Pagination Info
*/
PaginationInfo = function PaginationInfo() {
	var page_info = 'Page ' + ((total_rows == 0) ? 0 : (page_number + 1));
	page_info += ' of ' + total_page.to_money();
	page_info += ' &sdot; Total : ' + total_rows.to_money() + ' Rows.';
	$('.page-info').html(page_info);
};

/*
* Set pagination button
*/
function PaginationButton() {
	$('.box-footer').show();
	$('.next').attr('onclick', 'Next()');
	$('.previous').attr('onclick', 'Prev()');
	$('.first').attr('onclick', 'First()');
	$('.last').attr('onclick', 'Last()');
	$('.per-page').attr('onchange', 'SetPerPage()');
	$(".previous, .first").prop('disabled', page_number == 0);
	$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
};

/*
* select per-page
*/
function SetPerPage() {
	page_number = 0;
	per_page = $('.per-page option:selected').val();
	getProspectiveStudents();
};

/**
* Get Origin Data From Class Group Settings
*/
function getProspectiveStudents() {
	_H.Loading( true );
	$('.save-excel').attr('disabled', 'disabled');
	var values = {
		admission_year_id: $('#admission_year_id').val(),
		admission_type_id: $('#admission_type_id').val(),
		major_id: $('#major_id').val() || 0,
		page_number: page_number,
		per_page: per_page
	};
	$.post(_BASE_URL + 'admission/selection_process/get_prospective_students', values, function( response ) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		total_page = res.total_page;
		total_rows = res.total_rows;
		var students = res.students;
		var subjects = res.subjects;
		var admission_exam_subjects = [];
		var semester_report_subjects = [];
		var national_exam_subjects = [];
		var admission_exam_scores = res.admission_exam_scores;
		var semester_report_scores = res.semester_report_scores;
		var national_exam_scores = res.national_exam_scores;
		var results = [];
		for (var x in students) {
			var row = {};
			var student = students[ x ];
			row['student_id'] = student.id;
			row['registration_number'] = student.registration_number;
			row['full_name'] = student.full_name;
			row['first_choice'] = student.first_choice;
			row['second_choice'] = student.second_choice;
			// Mapel Ujian Tes Tulis
			var sum_score = 0;
			for (var y in admission_exam_scores) {
				var exam = admission_exam_scores[ y ];
				if (exam.student_id === student.id) {
					row['admission_exam_subject_' + exam.subject_id] = exam.score;
					sum_score += parseFloat(exam.score);
				}
				if ($.inArray(exam.subject_id, admission_exam_subjects) < 0) {
					admission_exam_subjects.push(exam.subject_id);
				}
			}
			// Mapel Rapor Sekolah
			for (var z in semester_report_scores) {
				var semester_report = semester_report_scores[ z ];
				if (semester_report.student_id === student.id) {
					row['semester_report_subject_' + semester_report.subject_id] = semester_report.score;
					sum_score += parseFloat(semester_report.score);
				}
				if ($.inArray(semester_report.subject_id, semester_report_subjects) < 0) {
					semester_report_subjects.push(semester_report.subject_id);
				}
			}
			// Mapel Ujian Nasional
			for (var z in national_exam_scores) {
				var national_exam = national_exam_scores[ z ];
				if (national_exam.student_id === student.id) {
					row['national_exam_subject_' + national_exam.subject_id] = national_exam.score;
					sum_score += parseFloat(national_exam.score);
				}
				if ($.inArray(national_exam.subject_id, national_exam_subjects) < 0) {
					national_exam_subjects.push(national_exam.subject_id);
				}
			}
			row['total'] = (sum_score / (admission_exam_subjects.length + semester_report_subjects.length + national_exam_subjects.length)).toFixed(2);
			results.push(row);
		}
		var str = '';
		if (results.length) {
			PaginationButton();
			PaginationInfo();
			if (total_rows <= per_page) $(".next").prop('disabled', true);
			$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
			// Sort by Total
			results.sort(function(a,b) {
				if (a.total < b.total) return 1;
				if (a.total > b.total) return -1;
				return 0;
			});
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px" rowspan="2" class="exclude_excel"><input type="checkbox" onclick="check_all(this.checked)" /></th>';
			str += '<th width="30px" rowspan="2">NO.</th>';
			str += '<th rowspan="2">NO. DAFTAR</th>';
			str += '<th rowspan="2">NAMA LENGKAP</th>';
			if (_MAJOR_COUNT > 0) {
				str += '<th rowspan="2">Pilihan Ke-1</th>';
				str += '<th rowspan="2">Pilihan Ke-2</th>';
			}
			if (admission_exam_subjects.length) {
				str += '<th colspan="' + admission_exam_subjects.length + '">NILAI UJIAN TES TULIS</th>';
			}
			if (semester_report_subjects.length) {
				str += '<th align="center" colspan="' + semester_report_subjects.length + '">NILAI RAPOR</th>';
			}
			if (national_exam_subjects.length) {
				str += '<th align="center" colspan="' + national_exam_subjects.length + '">NILAI UJIAN NASIONAL</th>';
			}
			if (admission_exam_subjects.length || semester_report_subjects.length || national_exam_subjects.length) {
				str += '<th rowspan="2">NILAI AKHIR</th>';
			}
			str += '</tr>';
			str += '<tr>';
			if (admission_exam_subjects.length) {
				for (var x in admission_exam_subjects) {
					str += '<th>' + subjects[ admission_exam_subjects[ x ] ] + '</th>';
				}
			}
			if (semester_report_subjects.length) {
				for (var y in semester_report_subjects) {
					str += '<th>' + subjects[ semester_report_subjects[ y ] ] + '</th>';
				}
			}
			if (national_exam_subjects.length) {
				for (var y in national_exam_subjects) {
					str += '<th>' + subjects[ national_exam_subjects[ y ] ] + '</th>';
				}
			}
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = (page_number * per_page) + 1;
			for (var z in results) {
				var res = results[ z ];
				str += '<tr>';
				str += '<td class="exclude_excel"><input type="checkbox" name="checkbox" value="' + res.student_id + '" /></td>';
				str += '<td>' + no + '.</td>';
				str += '<td>' + res.registration_number + '</td>';
				str += '<td>' + res.full_name + '</td>';
				if (_MAJOR_COUNT > 0) {
					str += '<td>' + res.first_choice + '</td>';
					str += '<td>' + res.second_choice + '</td>';
				}
				if (admission_exam_subjects.length) {
					for (var x in admission_exam_subjects) {
						var key = 'admission_exam_subject_' + admission_exam_subjects[ x ];
						str += '<td width="10%" class="number">' + res[ key ] + '</td>';
					}
				}
				if (semester_report_subjects.length) {
					for (var x in semester_report_subjects) {
						var key = 'semester_report_subject_' + semester_report_subjects[ x ];
						str += '<td width="10%" class="number">' + res[ key ] + '</td>';
					}
				}
				if (national_exam_subjects.length) {
					for (var x in national_exam_subjects) {
						var key = 'national_exam_subject_' + national_exam_subjects[ x ];
						str += '<td width="10%" class="number">' + res[ key ] + '</td>';
					}
				}
				if (admission_exam_subjects.length || semester_report_subjects.length || national_exam_subjects.length) {
					str += '<td width="10%" class="number">' + res.total + '</td>';
				}
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('table.source_list').empty().html(str);
			$('.save-excel').removeAttr('disabled');
		} else {
			$('table.source_list').empty();
		}
	});
}

// Delete Permanent Data
function SelectionProcess() {
	var rows = $('input[name="checkbox"]:checked');
	var student_ids = [];
	rows.each(function() {
		student_ids.push($(this).val());
	});
	if (student_ids.length) {
		var selection_result = $('#selection_result option:selected').text();
		eModal.confirm('Apakah anda yakin ' + student_ids.length + ' data calon Peserta Didik baru akan diproses dengan hasil seleksi ' + selection_result + '?', 'Konfirmasi').then(function() {
			_H.Loading( true );
			var values = {
				admission_year_id: $('#admission_year_id').val(),
				admission_type_id: $('#admission_type_id').val(),
				selection_result: $('#selection_result').val(),
				student_ids: student_ids.join(',')
			};
			$.post(_BASE_URL + 'admission/selection_process/save', values, function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, res.message);
				if (res.status == 'success') {
					getProspectiveStudents();
				}
				_H.Loading( false );
			});
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang terpilih');
	}
}

function save2excel() {
	var elementId = 'excel-report';
	var div = '<div id="' + elementId + '" style="display: none;"></div>';
	$( div ).appendTo( document.body );
	var table = $( '#data-table-renderer' ).html();
	$( '#' + elementId ).html( table );
	var fileName = 'REKAP-DATA-PPDB-TAHUN-' + $('#admission_year_id option:selected').text() + '-JALUR-PENDAFTARAN-' + $('#admission_type_id option:selected').text();
	fileName += ($('#major_id').length ? '-PROGRAM-KEAHLIAN-' + $('#major_id option:selected').text() : '');
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
	<div class="callout">
		<h4>Petunjuk Singkat</h4>
		<ul>
			<li><strong>Nilai Akhir</strong> merupakan jumlah keseluruhan dari skor mata pelajaran dibagi jumlah mata pelajaran.</li>
			<?php if (__session('major_count') > 0) { ?>
				<li><strong>Jurusan</strong> yang dipilih akan merujuk pada <strong>Pilihan Ke-1</strong> dari data <strong>Calon Peserta Didik Baru</strong>.</li>
			<?php } ?>
		</ul>
	</div>
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Pencarian Data</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-6 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="admission_year_id" class="col-sm-4 control-label">PPDB Tahun</label>
							<div class="col-md-8">
								<?=form_dropdown('admission_year_id', $admission_year_dropdown, __session('admission_semester_id'), 'class="form-control select2" id="admission_year_id"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="admission_type_id" class="col-sm-4 control-label">Jenis Pendaftaran</label>
							<div class="col-md-8">
								<?=form_dropdown('admission_type_id', $admission_type_dropdown, '', 'class="form-control select2" id="admission_type_id"');?>
							</div>
						</div>
						<?php if (__session('major_count') > 0) { ?>
							<div class="form-group">
								<label for="major_id" class="col-sm-4 control-label">Jurusan</label>
								<div class="col-md-8">
									<?=form_dropdown('major_id', $major_dropdown, '', 'class="form-control select2" id="major_id"');?>
								</div>
							</div>
						<?php } ?>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="button" onclick="getProspectiveStudents(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
								<button disabled="disabled" type="button" onclick="save2excel(); return false;" class="btn btn-sm btn-warning save-excel"><i class="fa fa-file-excel-o"></i> EXPORT KE FILE EXCEL</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="selection_result" class="col-sm-4 control-label">Hasil Seleksi</label>
							<div class="col-md-8 col-sm-12 col-xs-12">
								<?=form_dropdown('selection_result', $options, '', 'class="form-control select2" id="selection_result"');?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								<button type="button" onclick="SelectionProcess(); return false;" class="btn btn-sm btn-success"><i class="fa fa-save"></i> SIMPAN HASIL SELEKSI</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div id="data-table-renderer" class="table-responsive">
				<table class="table table-bordered table-striped table-condensed source_list"></table>
			</div>
		</div>
		<div class="box-footer" style="display: none;">
			<div class="row">
				<div class="col-md-9">
					<em class="page-info"></em>
				</div>
				<div class="col-md-3">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-default btn-sm first" title="First"><i class="fa fa-angle-double-left"></i></button>
						<button type="button" class="btn btn-default btn-sm previous" title="Prev"><i class="fa fa-angle-left"></i></button>
						<button type="button" class="btn btn-default btn-sm next" title="Next"><i class="fa fa-angle-right"></i></button>
						<button type="button" class="btn btn-default btn-sm last" title="Last"><i class="fa fa-angle-double-right"></i></button>
						<div class="btn-group">
							<select class="btn btn-default input-sm per-page" style="padding: 5px 5px" onchange="SetPerPage()">
								<option value="10">10</option>
								<option value="20">20</option>
								<option value="50">50</option>
								<option value="100">100</option>
								<option value="0">All</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
