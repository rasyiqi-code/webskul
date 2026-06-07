<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'STUDENT_PRESENCE';
	new GridBuilder( _grid , {
        controller:'student_presence',
        fields: [
    		{ header:'Tanggal', renderer:'date' },
            { 
                header:'Jam', 
                renderer: function( row ) {
                    return row.start_time.substr(0, 5) + ' - ' + row.end_time.substr(0, 5);
                },
                sort_field: 'start_time'
            },
            { header:'Mata Pelajaran', renderer:'subject_name' },
            { header:'Guru Pengajar', renderer:'teacher' },
            { header:'Topik', renderer:'discussion' },
            { header:'Ket.', renderer:'presence' }
    	],
        resize_column: 1,
        can_add: false,
        can_delete: false,
        can_restore: false
    });
</script>