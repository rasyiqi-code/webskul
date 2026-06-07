<style type="text/css">
.table > thead > tr {
	background-color: #dae1ea;
	border-bottom: 1px solid #8995a3;
}
.table > thead > tr > th {
	text-align: center;
}
.table > thead > tr > th, .table > tbody > tr > td {
	border: 1px solid #8995a3;
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
	_H.Loading( true );
	page_number++;
	getProspectiveStudents();
};

/*
* button previous
*/
function Prev() {
	_H.Loading( true );
	page_number--;
	getProspectiveStudents();
};

/*
* button first
*/
function First() {
	_H.Loading( true );
	page_number = 0;
	getProspectiveStudents();
};

/*
* button last
*/
function Last() {
	_H.Loading( true );
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
	_H.Loading( true );
	page_number = 0;
	per_page = $('.per-page option:selected').val();
	getProspectiveStudents();
};

/**
* Get Prospective Students
*/
function getProspectiveStudents() {
	_H.Loading( true );
	$('.save-scores, .save-excel').attr('disabled', 'disabled');
	var values = {
		admission_year_id: $('#admission_year_id').val(),
		admission_type_id: $('#admission_type_id').val(),
		major_id: $('#major_id').val() || 0,
		page_number: page_number,
		per_page: per_page
	};
	$.post(_BASE_URL + 'admission/exam_scores/get_prospective_students', values, function( response ) {
		var res = _H.StrToObject( response );
		total_page = res.total_page;
		total_rows = res.total_rows;
		var students = res.students;
		var str = '';
		if (students.length) {
			PaginationButton();
			PaginationInfo();
			if (total_rows <= per_page) $(".next").prop('disabled', true);
			$(".next, .last").prop('disabled', total_page == 0 || (page_number == (total_page - 1)));
			str += '<thead class="header">';
			str += '<tr>';
			str += '<th width="30px">NO.</th>';
			str += '<th>NO. PENDAFTARAN</th>';
			str += '<th>NAMA LENGKAP</th>';
			str += '<th>MATA PELAJARAN</th>';
			str += '<th width="70px">NILAI</th>';
			str += '</tr>';
			str += '</thead>';
			str += '<tbody>';
			var no = (page_number * per_page) + 1;
			for (var z in students) {
				var row = students[ z ];
				str += '<tr>';
				str += '<td>' + (no) + '.</td>';
				str += '<td>' + row.registration_number + '</td>';
				str += '<td>' + row.full_name + '</td>';
				str += '<td>' + row.subject_name + '</td>';
				str += '<td class="score_' + row.id + '">';
				str += '<input class="form-control input-sm number" style="width:65px;" type="text" name="score" id="score_' + row.id + '" value="' + row.score + '" />';
				str += '</td>';
				str += '</tr>';
				no++;
			}
			str += '</tbody>';
			$('table.prospective-students').empty().html(str);
			$('.save-scores, .save-excel').removeAttr('disabled');
		} else {
			$('table.prospective-students').empty();
		}
		_H.Loading( false );
	});
}

// Delete Permanent Data
function saveScores() {
	_H.Loading( true );
	$('.save-scores').attr('disabled', 'disabled');
	var rows = $('input[name="score"]');
	var scores = [];
	rows.each(function() {
		scores.push({
			id:this.id.split('_')[ 1 ],
			score:$(this).val()
		});
	});

	if (scores.length) {
		var values = {
			scores: JSON.stringify(scores)
		};
		$.post(_BASE_URL + 'admission/exam_scores/save', values, function( response ) {
			_H.Notify('info', _H.Message(response.message));
			_H.Loading( false );
			$('.save-scores').removeAttr('disabled');
			getProspectiveStudents();
		});
	} else {
		_H.Notify('info', 'Tidak ada data yang tersimpan');
	}
}

function save2excel() {
	var elementId = 'excel-report';
	var div = '<div id="' + elementId + '" style="display: none;"></div>';
	$( div ).appendTo( document.body );
	var table = $( '#data-table-renderer' ).html();
	$( '#' + elementId ).html( table );
	var inputs = $( '#' + elementId ).find('input[name="score"]');
	inputs.each(function() {
		var score = $('#'+this.id).val();
		$( '#' + elementId ).find('td.' + this.id).text(score);
	});
	var fileName = 'REKAP-NILAI-UJIAN-TES-TULIS-PPDB-TAHUN-' + $('#admission_year_id option:selected').text() + '-JALUR-PENDAFTARAN-' + $('#admission_type_id option:selected').text();
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
			<li>Calon Peserta Didik Baru yang dipanggil dalam proses penginputan nilai ujian Tes Tulis Penerimaan Peserta Didik Baru yaitu Pendaftar yang sudah melakukan <strong>Daftar Ulang</strong>. Pastikan data sudah diatur dengan benar.</li>
		</ul>
	</div>
	<div class="box">
		<div class="box-header">
			<h3 class="box-title">Pencarian Data</h3>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="admission_year_id" class="col-sm-3 control-label">PPDB Tahun</label>
							<div class="col-md-9">
								<?=form_dropdown('admission_year_id', $admission_year_dropdown, __session('admission_semester_id'), 'class="form-control select2" id="admission_year_id"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="admission_type_id" class="col-sm-3 control-label">Jenis Pendaftaran</label>
							<div class="col-md-9">
								<?=form_dropdown('admission_type_id', $admission_type_dropdown, '', 'class="form-control select2" id="admission_type_id"');?>
							</div>
						</div>
						<?php if (__session('major_count') > 0) { ?>
							<div class="form-group">
								<label for="major_id" class="col-sm-3 control-label">Jurusan</label>
								<div class="col-md-9">
									<?=form_dropdown('major_id', $major_dropdown, '', 'class="form-control select2" id="major_id"');?>
								</div>
							</div>
						<?php } ?>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<div class="btn-group">
									<button type="button" onclick="getProspectiveStudents(); return false;" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> CARI DATA</button>
									<button disabled="disabled" type="button" onclick="saveScores(); return false;" class="btn btn-sm btn-success save-scores"><i class="fa fa-save"></i> SIMPAN PERUBAHAN NILAI</button>
									<button disabled="disabled" type="button" onclick="save2excel(); return false;" class="btn btn-sm btn-warning save-excel"><i class="fa fa-file-excel-o"></i> EXPORT KE FILE EXCEL</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div id="data-table-renderer" class="table-responsive">
				<table class="table table-bordered table-striped table-condensed prospective-students"></table>
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
