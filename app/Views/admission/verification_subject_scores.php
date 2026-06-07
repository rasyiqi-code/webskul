<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'VERIFICATION_SUBJECT_SCORES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'admission/verification_subject_scores',
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
      {
         header:'Tanggal Daftar',
         renderer: function( row ) {
            return _H.ToIndonesianDate(row.created_at);
         }
      },
      { header:'Nama Lengkap', renderer:'full_name' },
      { header:'Mata Pelajaran', renderer:'subject_name' },
      {
         header:'Kategori Nilai',
         renderer: function( row ) {
            if (row.subject_type == 'semester_report') return 'Nilai Rapor Sekolah';
            if (row.subject_type == 'national_exam') return 'Nilai Ujian Nasional';
            if (row.subject_type == 'exam_schedule') return 'Nilai Tes Tulis';
            return '';
         }
      },
      { header:'Skor', renderer:'score' }
   ],
   resize_column: 2,
   can_add: false,
   can_delete: false,
   can_restore: false
});

new FormBuilder( _form , {
   controller:'admission/verification_subject_scores',
   fields: [
      { label:'Skor', name:'score', type:'float' }
   ]
});
</script>
