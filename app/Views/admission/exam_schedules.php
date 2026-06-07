echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.AdmissionTypes = _H.StrToObject('<?=get_options('admission_type')?>');
DS.AcademicYears = _H.StrToObject('<?=$academic_year_dropdown;?>');
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
var _grid = 'SUBJECT_SEMESTER_REPORT_SETTIGNS', _form = _grid + '_FORM';
var grid_field = [
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
      header: '<i class="fa fa-book"></i>',
      renderer: function( row ) {
         return Ahref(_BASE_URL + 'admission/exam_subject_settings/index/' + row.id, 'Pengaturan Mata Pelajaran', '<i class="fa fa-book"></i>', '_self');
      },
      exclude_excel: true,
      sorting: false
   },
   { header:'Tahun Pelajaran', renderer:'academic_year' },
   { header:'Jenis Pendaftaran', renderer:'admission_type' }
];
// if SMK or PT
if (_MAJOR_COUNT > 0) {
   grid_field.push(
      { header:'Jurusan', renderer:'major_name' }
   );
}
new GridBuilder( _grid , {
   controller:'admission/exam_schedules',
   fields: grid_field,
   resize_column: 4
});

var form_field = [
   { label:'Tahun Pelajaran', name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
   { label:'Jenis Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes }
];
// if SMK or PT
if (_MAJOR_COUNT > 0) {
   form_field.push(
      { label:'Jurusan', name:'major_id', type:'select', datasource:DS.Majors }
   );
}

new FormBuilder( _form , {
   controller:'admission/exam_schedules',
   fields: form_field
});
</script>
