<script type="text/javascript">
$( document ).ready(function() {
	var values = get_values();
	if (values['academic_year_id'] && values['class_group_id']) {
		get_students();
	}
});

function get_values() {
	var values = {
		academic_year_id: $('#academic_year_id').val(),
		class_group_id: $('#class_group_id').val()
	};
	return values;
}

function get_students() {
	var values = get_values();
	if (values['academic_year_id'] && values['class_group_id']) {
		_H.Loading( true );
		$.post( _BASE_URL + 'public/student_directory/get_students', values, function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			var rows = res.rows;
			var str = '';
			if (rows.length === 0) {
				str = '<div class="col-12"><div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom my-3"><i class="fa fa-info-circle fa-2x text-muted mb-2"></i><h6 class="font-weight-bold text-dark">Tidak ada data peserta didik pada kelas yang dipilih.</h6></div></div>';
			} else {
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="col-lg-6 col-md-6 mb-4">';
					str += '<div class="card-profile-modern bg-white rounded-custom border shadow-sm-custom p-3 h-100">';
					str += '<div class="d-flex align-items-center">';
					str += '<div class="profile-photo-wrapper mr-3 flex-shrink-0">';
					str += '<img src="' + row.photo + '" class="rounded-circle profile-photo shadow-sm" width="80" height="80" style="object-fit: cover;" alt="' + row.full_name + '">';
					str += '</div>';
					str += '<div class="profile-info-wrapper flex-grow-1">';
					str += '<h5 class="font-weight-bold text-dark mb-1">' + row.full_name + '</h5>';
					str += '<div class="profile-details text-muted small">';
					str += '<div><i class="fa fa-id-badge mr-2 text-primary"></i> NIS: ' + (row.nis).substring(0, 4) + '*******</div>';
					str += '<div><i class="fa fa-user mr-2 text-primary"></i> Gender: ' + (row.gender == 'M' ? 'Laki-laki' : 'Perempuan') + '</div>';
					str += '<div><i class="fa fa-map-marker mr-2 text-primary"></i> ' + row.birth_place + ', ' + row.birth_date + '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				}
			}
			var elementId = $("div.student-directory");
			elementId.html( str );
		});
	}
}
</script>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="student-header mb-4 text-center">
		<span class="section-tagline">Data Akademik</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="filter-card bg-white rounded-custom shadow-sm-custom border p-4 mb-4">
		<form onsubmit="return false;">
			<div class="form-row align-items-center justify-content-center">
				<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
					<label class="font-weight-bold small text-muted d-block" for="academic_year_id">Tahun Pelajaran</label>
					<?=form_dropdown('academic_year_id', $academic_year_dropdown, __session('current_academic_year_id'), 'class="custom-select rounded-custom border" id="academic_year_id"');?>
				</div>
				<div class="col-md-4 col-sm-12 mb-2 mb-md-0">
					<label class="font-weight-bold small text-muted d-block" for="class_group_id">Pilih Rombongan Belajar (Kelas)</label>
					<?=form_dropdown('class_group_id', $class_group_dropdown, '', 'class="custom-select rounded-custom border" id="class_group_id"');?>
				</div>
				<div class="col-md-3 col-sm-12 align-self-end">
					<button type="button" onclick="get_students()" class="btn btn-primary-gradient btn-block">
						<i class="fa fa-search mr-2"></i> Tampilkan Siswa
					</button>
				</div>
			</div>
		</form>
	</div>

	<div class="row student-directory"></div>
</div>
