<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.EmploymentTypes = _H.StrToObject('<?=get_options('employment_type')?>');
DS.Religions = _H.StrToObject('<?=get_options('religion')?>');
var _grid = 'EMPLOYEES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'employees/employees',
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
      { header:'NIK', renderer:'nik' },
      { header:'Nama Lengkap', renderer:'full_name' },
      {
         header:'Jenis GTK',
         renderer: function( row ) {
            return row.employment_type ? row.employment_type : '';
         },
         sort_field: 'employment_type'
      },
      { header:'Tempat Lahir', renderer:'birth_place' },
      {
         header:'Tanggal Lahir',
         renderer: function( row ) {
            return row.birth_date && row.birth_date !== '0000-00-00' ? _H.ToIndonesianDate(row.birth_date) : '';
         },
         sort_field: 'birth_date'
      },
      { header:'L/P', renderer: 'gender' },
      { header:'Email', renderer:'email' },
   ],
   resize_column: 7,
   to_excel: false,
   extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="employee_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>'
});

new FormBuilder( _form , {
   controller:'employees/employees',
   fields: [
      { label:'Nama Lengkap (Tanpa Gelar)', name:'full_name', },
      { label:'NIK/ No. KITAS (Untuk WNA)', name:'nik' },
      { label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
      { label:'Tempat Lahir', name:'birth_place' },
      { label:'Tanggal Lahir', name:'birth_date', type:'date' },
      { label:'Alamat Jalan', name:'street_address' },
      { label:'RT', name:'rt' },
      { label:'RW', name:'rw' },
      { label:'Nama Dusun', name:'sub_village' },
      { label:'Desa/Kelurahan', name:'village' },
      { label:'Kecamatan', name:'sub_district' },
      { label:'Kabupaten/Kota', name:'district' },
      { label:'Kode Pos', name:'postal_code' },
      { label:'Agama dan Kepercayaan', name:'religion_id', type:'select', datasource:DS.Religions },
      { label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
      { label:'Nama Negara', name:'country' },
      { label:'Jenis GTK', name:'employment_type_id', type:'select', datasource:DS.EmploymentTypes },
      { label:'No. Telepon', name:'phone' },
      { label:'No. Handphone', name:'mobile_phone' },
      { label:'Email', name:'email' }
   ]
});

function preview( image ) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/users/photo/' + image
      },
      type: 'image'
   });
}

function employee_reports() {
   var fields = {
      nik:'NIK',
      full_name:'Nama Lengkap',
      gender:'L/P',
      birth_place:'Tempat Lahir',
      birth_date:'Tanggal Lahir',
      street_address:'Alamat Jalan',
      rt:'RT',
      rw:'RW',
      sub_village:'Dusun',
      village:'Kelurahan',
      sub_district:'Kecamatan',
      district:'Kabupaten/Kota',
      postal_code:'Kode Pos',
      religion: 'Agama',
      citizenship:'Kewarganegaraan',
      country:'Nama Negara',
      employment_type: 'Jenis Guru dan Tenaga Kependidikan (GTK)',
      special_need: 'Mampu Menangani Kebutuhan Khusus?',
      phone:'Telepon',
      mobile_phone:'Handphone',
      email:'Email'
	};
   $.post(_BASE_URL + 'employees/employees/employee_reports', {}, function(response) {
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
		var fileName = 'DATA-GURU-DAN-TENAGA-KEPENDIDIKAN';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
