<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
DS.UserGroups = _H.StrToObject('<?=$user_group_dropdown;?>');
var _grid = 'USERS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'users/administrator',
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
      { header:'Nama Lengkap', renderer:'full_name' },
      { header:'Email', renderer:'email' },
      { 
         header:'Grup', 
         renderer: function( row ) {
            return DS.UserGroups[ row.user_group_id ];
         } 
      }
   ]
});

new FormBuilder( _form , {
   controller:'users/administrator',
   fields: [
      { label:'Nama Lengkap', name:'full_name' },
      { label:'Email', name:'email' },
      { label:'Kata Sandi', name:'password', type:'password' },
      { label:'Grup', name:'user_group_id', type:'select', datasource:DS.UserGroups }
   ]
});
</script>
