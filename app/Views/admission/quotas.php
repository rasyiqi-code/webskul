<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
DS.AcademicYears = _H.StrToObject('<?=$academic_year_dropdown;?>');
DS.AdmissionTypes = _H.StrToObject('<?=get_options('admission_type')?>');
var _grid = 'QUOTAS', _form = _grid + '_FORM';
// New Grid Builder
var _grid_fields = [
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
   { header:'Tahun Pelajaran', renderer:'academic_year' },
   { header:'Jenis Pendaftaran', renderer:'admission_type' }
];
if (_MAJOR_COUNT > 0) {
   _grid_fields.push(
      { header:'Jurusan', renderer:'major_name', sorting: false }
   );
}
_grid_fields.push(
   { header:'Kuota', renderer:'quota' }
);
new GridBuilder( _grid , {
   controller:'admission/quotas',
   fields: _grid_fields
});

// New Form Builder
var _form_fields = [
   { label:'Tahun Pelajaran', name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
   { label:'Jenis Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes }
];
if (_MAJOR_COUNT > 0) {
   _form_fields.push(
      { label:'Jurusan', name:'major_id', type:'select', datasource:DS.Majors }
   );
}
_form_fields.push(
   { label:'Kuota', name:'quota', type:'number' }
);
new FormBuilder( _form , {
   controller:'admission/quotas',
   fields: _form_fields
});
</script>
