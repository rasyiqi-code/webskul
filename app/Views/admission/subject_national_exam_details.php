<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Subjects = _H.StrToObject('<?=$subjects_dropdown;?>');
var _grid = 'SCHEDULE_DETAILS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'admission/subject_national_exam_details',
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
      { header:'Mata Pelajaran', renderer:'subject_name' },
      {
         header:'Tampil di Form Pendaftaran',
         renderer: function( row ) {
            return row.visibility == 'public' ? '<i class="fa fa-check-square-o"></i>' : '';
         },
         exclude_excel: true,
         sorting: false
      }
   ],
   extra_buttons: '<a href="' + _BASE_URL + '/admission/subject_national_exam_settings" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Kembali"><i class="fa fa-mail-forward"></i></a>',
   extra_params: { subject_setting_id: <?=service('uri')->getSegment(4)?> }
});

new FormBuilder( _form , {
   controller:'admission/subject_national_exam_details',
   fields: [
      { label:'Mata Pelajaran', name:'subject_id', type:'select', datasource:DS.Subjects },
      { label:'Tampil di Form Pendaftaran?', name:'visibility', type:'select', datasource:DS.AdmissionFormVisibility }
   ],
   extra_params: { subject_setting_id: <?=service('uri')->getSegment(4)?> }
});
</script>
