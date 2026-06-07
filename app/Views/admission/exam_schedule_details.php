<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Rooms = _H.StrToObject('<?=$rooms_dropdown;?>');
var _grid = 'SCHEDULE_DETAILS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'admission/exam_schedule_details',
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
         header: '<i class="fa fa-user"></i>',
         renderer: function( row ) {
            return Ahref(_BASE_URL + 'admission/exam_attendances/index/' + row.id, 'Pengaturan Peserta Ujian', '<i class="fa fa-user"></i>', '_self');
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-print"></i>',
         renderer: function( row ) {
            return Ahref( _BASE_URL + 'admission/exam_schedule_details/print_exam_attendances/' + row.id, 'Cetak Daftar Hadir Ujian', '<i class="fa fa-print"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Lokasi Gedung', renderer:'building_name' },
      { header:'Lokasi Ruangan Ujian', renderer:'room_name' },
      { header:'Kapasitas', renderer:'room_capacity' },
      { header:'Tanggal Pelaksanaan', renderer:'exam_date' },
      { header:'Jam Mulai', renderer:'exam_start_time' },
      { header:'Jam Selesai', renderer:'exam_end_time' }
   ],
   extra_params: { subject_setting_detail_id: <?=service('uri')->getSegment(4)?> },
   resize_column: 5
});

new FormBuilder( _form , {
   controller:'admission/exam_schedule_details',
   fields: [
      { label:'Ruangan Ujian', name:'room_id', type:'select', datasource:DS.Rooms },
      { label:'Tanggal Pelaksanaan', name:'exam_date', type:'date' },
      { label:'Jam Mulai', name:'exam_start_time', type:'time' },
      { label:'Jam Selesai', name:'exam_end_time', type:'time' }
   ],
   extra_params: { subject_setting_detail_id: <?=service('uri')->getSegment(4)?> }
});

function print_exam_attendances( id ) {
   $.post(_BASE_URL + 'admission/exam_schedule_details/print_exam_attendances', {'id':id}, function(response) {
      var res = _H.StrToObject( response );
      if (res.status == 'success') {
         window.open(_BASE_URL + 'media_library/users/photo/' + res.file_name,'_self');
      }
      _H.Notify('error', 'Format data tidak valid.');
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
