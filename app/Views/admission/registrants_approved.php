<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Options = _H.StrToObject('<?=$options;?>');
var _grid = 'REGISTRANTS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
	controller:'admission/registrants_approved',
	fields: [
		{
			header: '<i class="fa fa-edit"></i>',
			renderer: function( row ) {
				return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
			},
			exclude_excel: true,
			sorting: false
		},
		{ header:'No. Daftar', renderer:'registration_number' },
		{ header:'Nama Lengkap', renderer:'full_name' },
		{ header:'Tanggal Daftar', renderer:'created_at' },
		{ header:'Tanggal Lahir', renderer:'birth_date' },
		{
			header:'L/P',
			renderer: function( row ) {
				return row.gender == 'M' ? 'L' : 'P';
			},
			sort_field: 'gender'
		},
		{
			header:'Hasil Seleksi',
			renderer: function( row ) {
				var selection_result = row.selection_result;
				if (selection_result == 'approved') return 'Diterima';
				return selection_result;
			}
		}
	],
	resize_column: 2,
	to_excel: false,
	can_add: false,
	can_delete: false,
	can_restore: false,
	extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="admission_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>'
});

new FormBuilder( _form , {
	controller:'admission/registrants_approved',
	fields: [
		{ label:'Hasil Seleksi', name:'selection_result', type:'select', datasource:DS.Options }
	]
});

// Export All Field to Excel
function admission_reports() {
	var fields = {
		registration_number: 'Nomor Pendaftaran',
		full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
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
		father_birth_place: 'Tempat Lahir Ayah',
		father_birth_date: 'Tanggal Lahir Ayah',
		father_education: 'Pendidikan Ayah',
		father_employment: 'Pekerjaan Ayah',
		father_monthly_income: 'Penghasilan Ayah',
		father_special_need: 'Kebutuhan Khusus Ayah',
		mother_name: 'Nama Ibu',
		mother_birth_place: 'Tempat Lahir Ibu',
		mother_birth_date: 'Tanggal Lahir Ibu',
		mother_education: 'Pendidikan Ibu',
		mother_employment: 'Pekerjaan Ibu',
		mother_monthly_income: 'Penghasilan Ibu',
		mother_special_need: 'Kebutuhan Khusus Ibu',
		guardian_name: 'Nama Wali',
		guardian_birth_place: 'Tempat Lahir Wali',
		guardian_birth_date: 'Tanggal Lahir Wali',
		guardian_education: 'Pendidikan Wali',
		guardian_employment: 'Pekerjaan Wali',
		guardian_monthly_income: 'Penghasilan Wali',
		mileage: 'Jarak Tempat Tinggal',
		traveling_time: 'Waktu Tempuh',
		height: 'Tinggi Badan',
		weight: 'Berat Badan',
		sibling_number: 'Jumlah Saudara Kandung'
	};
	if (_MAJOR_COUNT == 0) {
		delete fields['first_choice'];
		delete fields['second_choice'];
	}
	$.post(_BASE_URL + 'admission/registrants_approved/admission_reports', {}, function(response) {
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
		var fileName = 'DATA-CALON-PESERTA-DIDIK-BARU-YANG-DITERIMA';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
	}).fail(function(xhr) {
		console.log(xhr);
	});
}
</script>
