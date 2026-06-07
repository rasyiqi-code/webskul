<script type="text/javascript">
// Save Changes
function save_changes() {
	var values = {
		full_name: $('#full_name').val(),
		email: $('#email').val()
	};
	_H.Loading( true );
	$.post(_BASE_URL + 'profile/save', values, function( response ) {
		_H.Loading( false );
		var res = _H.StrToObject( response );
		_H.Notify(res.status, _H.Message(res.message));
	});
}
</script>
<section class="content">
	<div class="box">
		<div class="box-header">
			<div class="box-title">Ubah Profil</div>
		</div>
		<div class="box-body">
			<form class="form-horizontal">
				<div class="form-group">
					<label for="full_name" class="col-sm-3 control-label">Nama Lengkap</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="full_name" value="<?=$query->full_name ? $query->full_name : '';?>">
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-3 control-label">Email</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="email" value="<?=$query->email ? $query->email : '';?>">
					</div>
				</div>
			</form>
		</div>
		<div class="box-footer">
         <div class="row">
            <div class="col-sm-offset-3 col-sm-9">
               <button type="button" onclick="save_changes(); return false;" class="btn btn-primary submit"><i class="fa fa-save"></i> SIMPAN PERUBAHAN</button>
            </div>
         </div>
      </div>
	</div>
</section>
