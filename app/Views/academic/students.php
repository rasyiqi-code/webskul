<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.SpecialNeeds = _H.StrToObject('<?=get_options('special_need')?>');
DS.Religions = _H.StrToObject('<?=get_options('religion')?>');
DS.Residences = _H.StrToObject('<?=get_options('residence')?>');
DS.Transportations = _H.StrToObject('<?=get_options('transportation')?>');
DS.MonthlyIncomes = _H.StrToObject('<?=get_options('monthly_income')?>');
DS.StudentStatus = _H.StrToObject('<?=get_options('student_status')?>');
DS.Employments = _H.StrToObject('<?=get_options('employment')?>');
DS.Educations = _H.StrToObject('<?=get_options('education')?>');
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
var _grid = 'STUDENTS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
	controller:'academic/students',
	fields: [
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
				return row.photo ?
				'<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
			},
			exclude_excel: true,
			sorting: false
		},
		// {
		// 	header: '<i class="fa fa-key"></i>',
		// 	renderer:function( row ) {
		// 		return A('create_account(' + "'" + row.full_name + "'" + ', ' + row.id + ')', 'Aktivasi Akun', '<i class="fa fa-key"></i>');
		// 	},
		// 	exclude_excel: true,
		// 	sorting: false
		// },
		{
			header: '<i class="fa fa-search"></i>',
			renderer: function( row ) {
				return Ahref(_BASE_URL + 'academic/students/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
			},
			exclude_excel: true,
			sorting: false
		},
		{ header:'NIS', renderer:'nis' },
		{ header:'Nama Lengkap', renderer:'full_name' },
		{
			header:'Status',
			renderer: function( row ) {
				return row.student_status;
			},
			sort_field: 'student_status'
		},
		{ header:'Tempat Lahir', renderer:'birth_place' },
		{
			header:'Tanggal Lahir',
			renderer: function( row ) {
				return row.birth_date && row.birth_date !== '0000-00-00' ? _H.ToIndonesianDate(row.birth_date) : '';
			},
			sort_field: 'birth_date'
		},
		{
			header:'L/P',
			renderer: function( row ) {
				return row.gender == 'M' ? 'L' : 'P';
			},
			sort_field: 'gender'
		},
		{ header:'Email', renderer:'email' }
	],
	resize_column: 7,
	to_excel: false,
	extra_buttons: '<button class="btn btn-sm btn-default add" onclick="student_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></button>'
});

var form_fields = [];
form_fields.push(
	{ label:'Pindahan?', name:'is_transfer', type:'select', datasource:DS.TrueFalse },
	{ label:'Tanggal Masuk Sekolah', name:'start_date', type:'date' },
	{ label:'NIS', name:'nis' }
);

// Jika bukan SD atau SMP
if (_MAJOR_COUNT > 0) {
	form_fields.push(
		{ label: 'Jurusan', name:'major_id', type:'select', datasource:DS.Majors }
	);
}

form_fields.push(
	{ label:'Nomor SKHUN Sebelumnya', name:'skhun' },
	{ label:'Nomor Peserta Ujian Sebelumnya', name:'prev_exam_number' },
	{ label:'Nomor Ijazah Sebelumnya', name:'prev_diploma_number' },
	{ label:'Nama Lengkap', name:'full_name' },
	{ label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
	{ label:'NISN', name:'nisn' },
	{ label:'NIK', name:'nik' },
	{ label:'Tempat Lahir', name:'birth_place' },
	{ label:'Tanggal Lahir', name:'birth_date', type:'date' },
	{ label:'Agama', name:'religion_id', type:'select', datasource:DS.Religions },
	{ label:'Berkebutuhan Khusus', name:'special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Alamat Jalan', name:'street_address' },
	{ label:'RT', name:'rt' },
	{ label:'RW', name:'rw' },
	{ label:'Nama Dusun', name:'sub_village' },
	{ label:'Desa/Kelurahan', name:'village' },
	{ label:'Kecamatan', name:'sub_district' },
	{ label:'Kabupaten', name:'district' },
	{ label:'Kode Pos', name:'postal_code' },
	{ label:'Tempat Tinggal', name:'residence_id', type:'select', datasource:DS.Residences },
	{ label:'Moda Transportasi', name:'transportation_id', type:'select', datasource:DS.Transportations },
	{ label:'Nomor Telepon', name:'phone' },
	{ label:'Nomor HP', name:'mobile_phone' },
	{ label:'Email', name:'email' },
	{ label:'Jenis Kesejahteraan', name:'welfare_type', type:'select', datasource:DS.WelfareTypes },
	{ label:'Nomor Kartu Kesejahteraan', name:'welfare_number' },
	{ label:'Nama di Kartu Kesejahteraan', name:'welfare_name' },
	{ label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
	{ label:'Nama Negara', name:'country' },
	{ label:'Nama Ayah Kandung', name:'father_name' },
	{ label:'NIK Ayah Kandung', name:'father_nik' },
	{ label:'Tempat Lahir Ayah', name:'father_birth_place' },
	{ label:'Tanggal Lahir Ayah', name:'father_birth_date', type:'date' },
	{ label:'Pendidikan Ayah', name:'father_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ayah', name:'father_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan  Bulanan Ayah', name:'father_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ayah', name:'father_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Ibu Kandung', name:'mother_name' },
	{ label:'NIK Ibu Kandung', name:'mother_nik' },
	{ label:'Tempat Lahir Ibu', name:'mother_birth_place' },
	{ label:'Tanggal Lahir Ibu', name:'mother_birth_date', type:'date' },
	{ label:'Pendidikan Ibu', name:'mother_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ibu', name:'mother_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan  Bulanan Ibu', name:'mother_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ibu', name:'mother_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Wali', name:'guardian_name' },
	{ label:'NIK Wali', name:'guardian_nik' },
	{ label:'Tempat Lahir Wali', name:'guardian_birth_place' },
	{ label:'Tanggal Lahir Wali', name:'guardian_birth_date', type:'date' },
	{ label:'Pendidikan Wali', name:'guardian_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Wali', name:'guardian_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Bulanan Wali', name:'guardian_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Jarak Tempat Tinggal ke Sekolah (KM)', name:'mileage', type:'number' },
	{ label:'Waktu Tempuh (Menit)', name:'traveling_time', type:'number' },
	{ label:'Tinggi Badan (Cm)', name:'height', type:'number' },
	{ label:'Berat Badan (Kg)', name:'weight', type:'number' },
	{ label:'Lingkar Kepala (Cm)', name:'head_circumference', type:'number' },
	{ label:'Jumlah Saudara Kandung', name:'sibling_number', type:'number' },
	{ label:'Status Peserta Didik', name:'student_status_id', type:'select', datasource:DS.StudentStatus },
	{ label:'Nama Sekolah Sebelumnya', name:'prev_school_name' },
	{ label:'Tanggal Keluar', name:'end_date', type:'date' },
	{ label:'Alasan Keluar', name:'reason', type:'textarea' }
);
new FormBuilder( _form , {
	controller:'academic/students',
	fields: form_fields
});

/**
* Create Student Account - Single Activation
* @param String
* @param Number
*/
function create_account( full_name, id ) {
	eModal.confirm('Apakah anda yakin akan mengaktifkan akun dengan nama ' + full_name + '?', 'Konfirmasi').then(function() {
		$.post(_BASE_URL + 'academic/students/create_account', {'id':id}, function(response) {
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
		});
	});
}

function preview(image) {
	$.magnificPopup.open({
		items: {
			src: _BASE_URL + 'media_library/users/photo/' + image
		},
		type: 'image'
	});
}

// Export All Field to Excel
function student_reports() {
	var fields = {
		full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
		major_name: 'Jurusan',
		is_transfer: 'Pindahan?',
		re_registration: 'Daftar Ulang?',
		nis: 'NIS',
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
		mileage: 'Jarak Tempat Tinggal',
		traveling_time: 'Waktu Tempuh',
		height: 'Tinggi Badan',
		weight: 'Berat Badan',
		head_circumference: 'Lingkar Kepala',
		sibling_number: 'Jumlah Saudara Kandung',
		student_status: 'Status Peserta Didik',
		start_date: 'Tanggal Masuk',
		end_date: 'Tanggal Keluar',
		reason: 'Alasan Keluar'
	};
	$.post(_BASE_URL + 'academic/students/student_reports', {}, function(response) {
		var results = _H.StrToObject( response );
		var table = '<table>';
		table += '<tr>';
		for (var key in fields) {
			if ( _MAJOR_COUNT == 0 && key == 'major_name') continue;
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
		var fileName = 'LAPORAN-DATA-PESERTA-DIDIK';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
	}).fail(function(xhr) {
		console.log(xhr);
	});
}
</script>
