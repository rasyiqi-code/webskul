<?php echo $this->include('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'USERS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'users/employees',
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
            return A(_form + '.OnEdit(' + row.id + ')', 'Ubah Kata Sandi', '<i class="fa fa-edit"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'NIK', renderer:'nik' },
      { header:'Nama Lengkap', renderer:'full_name', sorting: false },
      { header:'Telp', renderer:'phone' },
      { header:'Handphone', renderer:'mobile_phone' },
      { header:'Email', renderer:'email', sorting: false }
   ],
   can_add: false
});

new FormBuilder( _form , {
   controller:'users/employees',
   fields: [
      { label:'Kata Sandi', name:'password', type:'password' },
      { label:'Ulangi Kata Sandi', name:'password_confirm', type:'password' }
   ]
});
</script>
