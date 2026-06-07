echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.AcademicYears = _H.StrToObject('<?=$academic_year_dropdown;?>');
var _grid = 'PHASES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'admission/phases',
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
      { header:'Tahun Pelajaran', renderer:'academic_year' },
      { header:'Gelombang Pendaftaran', renderer:'phase_name' },
      {
         header:'Tanggal Mulai',
         renderer: function( row ) {
            return row.phase_start_date ? _H.ToIndonesianDate(row.phase_start_date) : '';
         },
         sort_field: 'phase_start_date'
      },
      {
         header:'Tanggal Selesai',
         renderer: function( row ) {
            return row.phase_end_date ? _H.ToIndonesianDate(row.phase_end_date) : '';
         },
         sort_field: 'phase_end_date'
      }
   ]
});

new FormBuilder( _form , {
   controller:'admission/phases',
   fields: [
      { label:'Tahun Pelajaran', name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
      { label:'Gelombang Pendaftaran', name:'phase_name' },
      { label:'Tanggal Mulai', name:'phase_start_date', type:'date' },
      { label:'Tanggal Selesai', name:'phase_end_date', type:'date' }
   ]
});
</script>
