<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
DS.AdmissionPhases = _H.StrToObject('<?=$admission_phase_dropdown;?>');
DS.AdmissionTypes = _H.StrToObject('<?=get_options('admission_type')?>');
DS.SpecialNeeds = _H.StrToObject('<?=get_options('special_need')?>');
DS.Religions = _H.StrToObject('<?=get_options('religion')?>');
DS.Residences = _H.StrToObject('<?=get_options('residence')?>');
DS.Transportations = _H.StrToObject('<?=get_options('transportation')?>');
DS.MonthlyIncomes = _H.StrToObject('<?=get_options('monthly_income')?>');
DS.StudentStatus = _H.StrToObject('<?=get_options('student_status')?>');
DS.Employments = _H.StrToObject('<?=get_options('employment')?>');
DS.Educations = _H.StrToObject('<?=get_options('education')?>');
var _grid = 'REGISTRANTS', _form = _grid + '_FORM', _form2 = _grid + '_FORM2';
var grid_fields = [
	{
		header: '<input type="checkbox" class="check-all">',
		renderer: function( row ) {
			return CHECKBOX(row.id, 'id');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-edit"></i>',
		renderer: function( row ) {
			return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-check-square-o"></i>',
		renderer: function( row ) {
			return A(_form2 + '.OnEdit(' + row.id + ')', 'Daftar Ulang?', '<i class="fa fa-check-square-o"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-print"></i>',
		renderer: function( row ) {
			return Ahref( _BASE_URL + 'admission/registrants/print_admission_form/' + row.id, 'Cetak Formulir Pendaftaran', '<i class="fa fa-print"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-print"></i>',
		renderer: function( row ) {
			if (row.re_registration == 'true') {
				return Ahref( _BASE_URL + 'admission/exam_card/pdf/' + row.id, 'Cetak Kartu Peserta Ujian', '<i class="fa fa-print"></i>');
			}
			return '';
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-file-image-o"></i>',
		renderer: function( row ) {
			return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Photo');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-search-plus"></i>',
		renderer: function( row ) {
			var image = "'" + row.photo + "'";
			return row.photo ? '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-search"></i>',
		renderer: function( row ) {
			return Ahref(_BASE_URL + 'admission/registrants/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{ header:'No. Daftar', renderer:'registration_number' },
	{ header:'Nama Lengkap', renderer:'full_name' },
	{ header:'Tanggal Lahir', renderer:'birth_date' },
	{ header:'Tanggal Daftar', renderer:'created_at' },
	{ header:'Jenis Pendaftaran', renderer:'is_transfer' },
	{ header:'Jalur Pendaftaran', renderer:'admission_type' },
	{ header:'Gelombang Pendaftaran', renderer:'phase_name' },
	{
		header:'Daftar Ulang?',
		renderer: function( row ) {
			var re_registration = row.re_registration;
			return re_registration == 'true' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-warning"></i>';
		},
		sort_field: 're_registration'
	},
];
if (_MAJOR_COUNT > 0) {
	grid_fields.push(
		{ header:'Pilihan Ke-1', renderer:'first_choice' },
		{ header:'Pilihan Ke-2', renderer:'second_choice' }
	);
}
new GridBuilder( _grid , {
	controller:'admission/registrants',
	fields: grid_fields,
	resize_column: 9,
	to_excel: false,
	can_add: false,
	extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="admission_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>'
});

var form_fields = [
	{ label:'Jenis Pendaftaran', name:'is_transfer', type:'select', datasource:{'false':'Baru', 'true':'Pindahan'} },
	{ label:'Jalur Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes },
	{ label:'Gelombang Pendaftaran', name:'admission_phase_id', type:'select', datasource:DS.AdmissionPhases },
	{ label:'Nama Sekolah Asal', name:'prev_school_name' },
	{ label:'Nomor Peserta UN SMP/MTs', name:'prev_exam_number' },
	{ label:'No. SKHUN SMP/MTs', name:'skhun' },
	{ label:'No. Seri Ijazah SMP/MTs', name:'prev_diploma_number' }
];
if (_MAJOR_COUNT > 0) {
	form_fields.push(
		{ label:'Pilihan Ke-1', name:'first_choice_id', type:'select', datasource:DS.Majors },
		{ label:'Pilihan Ke-2', name:'second_choice_id', type:'select', datasource:DS.Majors }
	);
}		
form_fields.push(
	{ label:'Nama Lengkap', name:'full_name' },
	{ label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
	{ label:'NISN', name:'nisn', placeholder:'Nomor Induk Siswa Nasional' },
	{ label:'NIK', name:'nik' },
	{ label:'No. Kartu Keluarga', name:'family_card_number' },
	{ label:'Tempat Lahir', name:'birth_place' },
	{ label:'Tanggal Lahir', name:'birth_date', type:'date' },
	{ label:'No. Registasi Akta Lahir', name:'birth_certificate_number' },
	{ label:'Agama dan Kepercayaan', name:'religion_id', type:'select', datasource:DS.Religions },
	{ label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
	{ label:'Nama Negara', name:'country', placeholder:'Nama Negara. Diisi jika bukan WNI' },
	{ label:'Berkebutuhan Khusus', name:'special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Alamat Jalan', name:'street_address' },
	{ label:'RT', name:'rt' },
	{ label:'RW', name:'rw' },
	{ label:'Nama Dusun', name:'sub_village' },
	{ label:'Desa/Kelurahan', name:'village' },
	{ label:'Kecamatan', name:'sub_district' },
	{ label:'Kabupaten', name:'district' },
	{ label:'Kode Pos', name:'postal_code' },
	{ label:'Lintang', name:'latitude' },
	{ label:'Bujur', name:'longitude' },
	{ label:'Tempat Tinggal', name:'residence_id', type:'select', datasource:DS.Residences },
	{ label:'Moda Transportasi', name:'transportation_id', type:'select', datasource:DS.Transportations },
	{ label:'Anak Keberapa', name:'child_number' },
	{ label:'Pekerjaan (khusus untuk warga belajar)', name:'employment_id', type:'select', datasource:DS.Employments },
	{ label:'Apakah Punya KIP?', name:'have_kip', type:'select', datasource: DS.TrueFalse },
	{ label:'Apakah Tetap Akan Menerima KIP?', name:'receive_kip', type:'select', datasource: DS.TrueFalse },
	{ label:'Alasan Menolak PIP', name:'reject_pip', type:'select', datasource: DS.RejectPIP },
	// DATA AYAH KANDUNG
	{ label:'Nama Ayah', name:'father_name', placeholder:'Nama ayah Kandung' },
	{ label:'NIK Ayah', name:'father_nik', placeholder:'NIK ayah Kandung' },
	{ label:'Tempat Lahir Ayah', name:'father_birth_place' },
	{ label:'Tanggal Lahir Ayah', name:'father_birth_date', type:'date' },
	{ label:'Pendidikan Ayah', name:'father_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ayah', name:'father_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Ayah/Bulan', name:'father_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ayah', name:'father_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	// DATA IBU KANDUNG
	{ label:'Nama Ibu', name:'mother_name', placeholder:'Nama Ibu Kandung' },
	{ label:'NIK Ibu', name:'mother_nik', placeholder:'NIK Ibu Kandung' },
	{ label:'Tempat Lahir Ibu', name:'mother_birth_place' },
	{ label:'Tanggal Lahir Ibu', name:'mother_birth_date', type:'date' },
	{ label:'Pendidikan Ibu', name:'mother_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ibu', name:'mother_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Ibu/Bulan', name:'mother_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ibu', name:'mother_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	// DATA WALI
	{ label:'Nama Wali', name:'guardian_name', placeholder:'Nama Wali' },
	{ label:'NIK Wali', name:'guardian_nik', placeholder:'NIK Wali' },
	{ label:'Tempat Lahir Wali', name:'guardian_birth_place' },
	{ label:'Tanggal Lahir Wali', name:'guardian_birth_date', type:'date' },
	{ label:'Pendidikan Wali', name:'guardian_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Wali', name:'guardian_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Wali/Bulan', name:'guardian_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	// KONTAK
	{ label:'Nomor Telepon', name:'phone', placeholder:'Nomor Telepon' },
	{ label:'Nomor Handphone', name:'mobile_phone', placeholder:'Nomor Handphone' },
	{ label:'Email Pribadi', name:'email', placeholder:'Alamat Email' },
	// DATA PERIODIK
	{ label:'Tinggi Badan (Cm)', name:'height', type:'number' },
	{ label:'Berat Badan (Kg)', name:'weight', type:'number' },
	{ label:'Lingkar Kepala (Cm)', name:'head_circumference', type:'number' },
	{ label:'Jarak Tempat Tinggal ke Sekolah (Km)', name:'mileage', placeholder:'Jarak tempat tinggal ke sekolah', type:'number' },
	{ label:'Waktu Tempuh ke Sekolah (Menit)', name:'traveling_time', placeholder:'Waktu Tempuh', type:'number' },
	{ label:'Jumlah Saudara Kandung', name:'sibling_number', placeholder:'Jumlah Saudara Kandung', type:'number' },
	// DATA KESEJAHTERAAN
	{ label:'Jenis Kesejahteraan', name:'welfare_type', type:'select', datasource:DS.WelfareTypes },
	{ label:'Nomor Kartu Kesejahteraan', name:'welfare_number' },
	{ label:'Nama di Kartu Kesejahteraan', name:'welfare_name' },	
);
new FormBuilder( _form , {
	controller:'admission/registrants',
	fields: form_fields
});

new FormBuilder( _form2 , {
	controller:'admission/registrants',
	fields: [
		{ label:'Daftar Ulang?', name:'re_registration', type:'select', datasource:DS.TrueFalse }
	],
	save_action: 'verified'
});

// Cetak Formulir Pendaftaran
function print_admission_form( id ) {
	$.post(_BASE_URL + 'admission/registrants/print_admission_form', {'id':id}, function(response) {
		var res = _H.StrToObject( response );
		if (res.status == 'success') {
			window.open(_BASE_URL + 'media_library/users/photo/' + res.file_name,'_self');
		}
		_H.Notify('error', 'Format data tidak valid.');
	}).fail(function(xhr) {
		console.log(xhr);
	});
}

// Cetak Kartu Peserta Ujian
function print_exam_card( id ) {
	$.post(_BASE_URL + 'admission/registrants/print_exam_card', {'id':id}, function(response) {
		var res = _H.StrToObject( response );
		if (res.status == 'success') {
			window.open(_BASE_URL + 'media_library/users/photo/' + res.file_name,'_self');
		}
		_H.Notify('error', 'Format data tidak valid.');
	}).fail(function(xhr) {
		console.log(xhr);
	});
}

// Photo Preview
function preview(image) {
	$.magnificPopup.open({
		items: {
			src: _BASE_URL + 'media_library/users/photo/' + image
		},
		type: 'image'
	});
}

// Export All Field to Excel
function admission_reports() {
	var fields = {
		registration_number: 'Nomor Pendaftaran',
		full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
		birth_certificate_number: 'Nomor Akta Lahir',
		family_card_number: 'Nomor Kartu Keluarga',
		first_choice: 'Pilihan 1',
		second_choice: 'Pilihan 2',
		created_at: 'Tanggal Pendaftaran',
		prev_exam_number: 'Nomor Ujian Sebelumnya',
		selection_result: 'Hasil Seleksi',
		phase_name: 'Gelombang Pendaftaran',
		admission_type: 'Jenis Pendaftaran',
		is_transfer: 'Pindahan?',
		re_registration: 'Daftar Ulang?',
		start_date: 'Tanggal Masuk',
		nisn: 'NISN',
		nik: 'NIK',
		prev_diploma_number: 'Nomor Ijazah Sebelumnya',
		skhun: 'SKHUN',
		prev_school_name: 'Nama Sekolah Sebelumnya',
		religion: 'Agama',
		special_need: 'Kebutuhan Khusus',
		street_address: 'Alamat Jalan',
		rt: 'RT',
		rw: 'RW',
		sub_village: 'Dusun',
		village: 'Kelurahan',
		sub_district: 'Kecamatan',
		district: 'Kabupaten',
		postal_code: 'Kode Pos',
		latitude: 'Lintang',
		longitude: 'Bujur',
		residence: 'Tempat Tinggal',
		transportation: 'Alat Transportasi',
		phone: 'Telepon',
		mobile_phone: 'Handphone',
		email: 'Email',
		welfare_type: 'Jenis Kesejahteraan',
		welfare_number: 'Nomor Kartu Kesejahteraan',
		welfare_name: 'Nama di Kartu Kesejahteraan',
		citizenship: 'Kewarganegaraan',
		country: 'Nama Negara',
		father_name: 'Nama Ayah',
		father_nik: 'NIK Ayah',
		father_birth_place: 'Tempat Lahir Ayah',
		father_birth_date: 'Tanggal Lahir Ayah',
		father_education: 'Pendidikan Ayah',
		father_employment: 'Pekerjaan Ayah',
		father_monthly_income: 'Penghasilan Ayah',
		father_special_need: 'Kebutuhan Khusus Ayah',
		mother_name: 'Nama Ibu',
		mother_nik: 'NIK Ibu',
		mother_birth_place: 'Tempat Lahir Ibu',
		mother_birth_date: 'Tanggal Lahir Ibu',
		mother_education: 'Pendidikan Ibu',
		mother_employment: 'Pekerjaan Ibu',
		mother_monthly_income: 'Penghasilan Ibu',
		mother_special_need: 'Kebutuhan Khusus Ibu',
		guardian_name: 'Nama Wali',
		guardian_nik: 'NIK Wali',
		guardian_birth_place: 'Tempat Lahir Wali',
		guardian_birth_date: 'Tanggal Lahir Wali',
		guardian_education: 'Pendidikan Wali',
		guardian_employment: 'Pekerjaan Wali',
		guardian_monthly_income: 'Penghasilan Wali',
		height: 'Tinggi Badan',
		weight: 'Berat Badan',
		head_circumference: 'Lingkar Kepala',
		mileage: 'Jarak Tempat Tinggal',
		traveling_time: 'Waktu Tempuh',
		sibling_number: 'Jumlah Saudara Kandung'
	};
	if (_MAJOR_COUNT == 0) {
		delete fields['first_choice'];
		delete fields['second_choice'];
	}
	$.post(_BASE_URL + 'admission/registrants/admission_reports', {}, function(response) {
		var results = _H.StrToObject( response );
		var table = '<table>';
		table += '<tr>';
		for (var key in fields) {
			table += '<th>' + fields[ key ] + '</th>';
		}
		table += '</tr>'
		for (var x in results) {
			var res = results[ x ];
			table += '<tr>';
			for (var y in fields) {
				table += '<td>' + (res[ y ] ? res[ y ] : '-') + '</td>';
			}
			table += '</tr>';
		}
		table += '</table>';
		var elementId = 'excel-report';
		var div = '<div id="' + elementId + '" style="display: none;"></div>';
		$( div ).appendTo( document.body );
		$( '#' + elementId ).html( table );
		var fileName = 'DATA-CALON-PESERTA-DIDIK-BARU';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
	}).fail(function(xhr) {
		console.log(xhr);
	});
}
</script>
