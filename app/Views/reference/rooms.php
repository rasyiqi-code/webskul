echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.Buildings = _H.StrToObject('<?=get_options('building')?>');
var _grid = 'ROOMS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'reference/rooms',
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
      { header:'Lokasi Gedung', renderer:'building_name' },
      { header:'Nama Ruangan', renderer:'room_name' },
      { header:'Kapasitas', renderer:'room_capacity' },
      {
         header:'Ruang Kelas?',
         renderer: function( row ) {
            return row.is_class_room == 'true' ? '<i class="fa fa-check-square-o"></i>' : '';
         },
         sort_field: 'is_class_room'
      }
   ]
});

new FormBuilder( _form , {
   controller:'reference/rooms',
   fields: [
      { label:'Nama Gedung', name:'building_id', type:'select', datasource:DS.Buildings },
      { label:'Nama Ruangan', name:'room_name' },
      { label:'Kapasitas', name:'room_capacity', type:'number' },
      { label:'Ruang Kelas?', name:'is_class_room', type:'select', datasource:DS.TrueFalse },
   ]
});
</script>
