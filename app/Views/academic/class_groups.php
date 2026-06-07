<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
var _grid = 'CLASS_GROUPS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/class_groups',
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
      { header:'Kelas', renderer:'class_name' }
   ]
});

var fields = [
   { label:'Kelas', name:'class_group' },
   { label:'Sub Kelas', name:'sub_class_group' }
];
if (_MAJOR_COUNT > 0) {
   fields.push(
      { label:'Jurusan', name:'major_id', type:'select', datasource:DS.Majors }
   );
}

new FormBuilder( _form , {
   controller:'academic/class_groups',
   fields: fields
});
</script>
