<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'ALUMNI', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/alumni',
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
      {
         header: '<i class="fa fa-search"></i>',
         renderer: function( row ) {
            return Ahref(_BASE_URL + 'academic/alumni/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header: 'NIS', renderer:'nis' },
      { header:'Nama Lengkap', renderer:'full_name' },
      {
         header:'L/P',
         renderer: function( row ) {
            return row.gender == 'M' ? 'L' : 'P';
         },
         sort_field: 'gender'
      },
      { header:'Tempat Lahir', renderer:'birth_place' },
      { header:'Tanggal Lahir', renderer:'birth_date' },
      { header:'Tahun Masuk', renderer:'year_in' },
      { header:'Tahun Keluar', renderer:'year_out' },
      { header:'Alamat', renderer:'street_address' },
      {
         header:'Alumni?',
         renderer: function( row ) {
            var is_alumni = row.is_alumni;
            if (is_alumni == 'true') return 'Ya';
            if (is_alumni == 'false') return 'Tidak';
            if (is_alumni == 'unverified') return 'Belum Diverifikasi';
            return '';
         },
         sort_field: 'is_alumni'
      },
      { header:'Email', renderer:'email' },
   ],
   resize_column: 7,
   to_excel: false,
   can_add: false,
   extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="alumni_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>'
});

new FormBuilder( _form , {
   controller:'academic/alumni',
   fields: [
      { label:'Alumni?', name:'is_alumni', type:'select', datasource:DS.IsAlumni },
      { label: 'NIS', name:'nis' },
      { label:'Nama Lengkap', name:'full_name' },
      { label:'Tempat Lahir', name:'birth_place' },
      { label:'Tanggal Lahir', name:'birth_date', type:'date' },
      { label:'Alamat Jalan', name:'street_address', },
      { label:'RT', name:'rt' },
      { label:'RW', name:'rw' },
      { label:'Nama Dusun', name:'sub_village' },
      { label:'Desa/Kelurahan', name:'village' },
      { label:'Kecamatan', name:'sub_district' },
      { label:'Kabupaten/Kota', name:'district' },
      { label:'Kode Pos', name:'postal_code' },
      { label:'No. Telepon', name:'phone' },
      { label:'No. Handphone', name:'mobile_phone' },
      { label:'Email', name:'email' },
      { label:'Tahun Masuk', name:'start_date', type:'date' },
      { label:'Tahun Keluar', name:'end_date', type:'date' },
      { label:'Alasan Keluar', name:'reason', type:'textarea' }
   ]
});

function preview(image) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/users/photo/' + image
      },
      type: 'image'
   });
}

function alumni_reports() {
   var fields = {
      full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
		major_name: 'Jurusan',
		is_transfer: 'Pindahan?',
		start_date: 'Tanggal Masuk',
      end_date: 'Tanggal Keluar',
		nis: "NIS",
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
		district: 'Kabupaten/Kota',
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
		sibling_number: 'Jumlah Saudara Kandung',
		student_status: 'Status Peserta Didik',
		reason: 'Alasan Keluar'
	};
   $.post(_BASE_URL + 'academic/alumni/alumni_reports', {}, function(response) {
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
		var fileName = 'DATA-ALUMNI';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
