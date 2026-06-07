echo $this->include('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'OPTIONS',
_form1 = _grid + '_FORM1', // admission_status
_form2 = _grid + '_FORM2', // announcement_start_date
_form3 = _grid + '_FORM3', // announcement_end_date
_form4 = _grid + '_FORM4', // print_exam_card_start_date
_form5 = _grid + '_FORM5', // print_exam_card_end_date
_form6 = _grid + '_FORM6', // Min Birth Date
_form7 = _grid + '_FORM7'; // Max Birth Date
new GridBuilder( _grid , {
	controller:'admission/settings',
	fields: [
		{
			header: '<i class="fa fa-edit"></i>',
			renderer: function( row ) {
				if (row.setting_variable == 'admission_status') {
					return A(_form1 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'announcement_start_date') {
					return A(_form2 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'announcement_end_date') {
					return A(_form3 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'print_exam_card_start_date') {
					return A(_form4 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'print_exam_card_end_date') {
					return A(_form5 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'min_birth_date') {
					return A(_form6 + '.OnEdit(' + row.id + ')');
				}
				if (row.setting_variable == 'max_birth_date') {
					return A(_form7 + '.OnEdit(' + row.id + ')');
				}
			},
			exclude_excel: true,
			sorting: false
		},
		{ header:'Setting Name', renderer: 'setting_description' },
		{
			header:'Setting Value',
			renderer: function(row){
				if (row.setting_variable == 'admission_status') {
					return DS.OpenClose[ row.setting_value ];
				}
				return row.setting_value ? row.setting_value : '';
			},
			sort_field: 'setting_value'
		}
	],
	can_add: false,
	can_delete: false,
	can_restore: false,
	resize_column: 2,
	per_page: 50,
	per_page_options: [50, 100]
});

/**
* Admission Status
*/
new FormBuilder( _form1 , {
	controller:'admission/settings',
	fields: [
		{ label:'Status Penerimaan Peserta Didik Baru', name:'setting_value', type:'select', datasource:DS.OpenClose }
	]
});

/**
* Announcement Start Date
*/
new FormBuilder( _form2 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Mulai Pengumuman PPDB', name:'setting_value', type:'date' }
	]
});

/**
* Announcement End Date
*/
new FormBuilder( _form3 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Selesai Pengumuman PPDB', name:'setting_value', type:'date' }
	]
});

/**
 * Tanggal Mulai Pencetakan Kartu Peserta Ujian Tes Tulis
 */
new FormBuilder( _form4 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Mulai Cetak Kartu Tes', name:'setting_value', type:'date' }
	]
});

/**
 * Tanggal Selesai Pencetakan Kartu Peserta Ujian Tes Tulis
 */
new FormBuilder( _form5 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Selesai Cetak Kartu Tes', name:'setting_value', type:'date' }
	]
});

/**
 * Tanggal Lahir Minimal Calon Peserta Didik Baru
 */
new FormBuilder( _form6 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Lahir Minimal', name:'setting_value', type:'date' }
	]
});

/**
* Tanggal Lahir Maksimal Calon Peserta Didik Baru
*/
new FormBuilder( _form7 , {
	controller:'admission/settings',
	fields: [
		{ label:'Tanggal Lahir Maksimal', name:'setting_value', type:'date' }
	]
});
</script>
