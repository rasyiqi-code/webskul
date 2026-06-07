<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
	DS.Presence = {
  		'present': 'Hadir',
  		'sick': 'Sakit',
  		'permit': 'Izin',
  		'absent': 'Alpa / Tanpa Keterangan'
  	};
	var _grid = 'ATTENDANCE_REPORT', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
		controller:'academic/student_attendance_report',
		fields: [
			{ 
				header: '<i class="fa fa-edit"></i>', 
				renderer: function( row ) {
					return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
				},
				exclude_excel: true,
				sorting: false
			},
			{ 
				header:'Tahun Pelajaran', 
				renderer: function( row ) {
					return row.academic_year + ' / ' + row.semester;
				},
				sort_field: 'academic_year'
			},
			{ header:'NIS', renderer:'nis' },
			{ header:'Nama Peserta Didik', renderer:'full_name' },
			{ header:'Kelas', renderer:'class_name' },
			{ header:'Mata Pelajaran', renderer:'subject_name' },
			{ header:'Tanggal', renderer:'date' },
			{ header:'Mulai', renderer:'start_time' },
			{ header:'Selesai', renderer:'end_time' },
			{ header:'Ket.', renderer:'presence' }
    	],
		resize_column: 2,
		can_add: false,
		can_delete: false,
		can_restore: false
	});

	new FormBuilder( _form , {
	    controller:'academic/student_attendance_report',
	    fields: [
            { label:'Presensi', name:'presence', type:'select', datasource: DS.Presence }
	    ]
  	});
</script>