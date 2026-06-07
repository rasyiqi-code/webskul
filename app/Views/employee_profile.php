<style type="text/css">
legend {
	margin-bottom: 30px;
	margin-top: 30px;
}
</style>
<script type="text/javascript">
$( document ).ready( function() {
	// Select2
	$('.select2').select2();

	// Date Picker
	$( document ).find( 'input.date' ).datetimepicker({
		format: 'yyyy-mm-dd',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
});

function save_changes() {
	var values = {
		full_name: $('#full_name').val(),
		gender: $('#gender').val(),
		nik: $('#nik').val(),
		birth_place: $('#birth_place').val(),
		birth_date: $('#birth_date').val(),
		street_address: $('#street_address').val(),
		rt: $('#rt').val(),
		rw: $('#rw').val(),
		sub_village: $('#sub_village').val(),
		village: $('#village').val(),
		sub_district: $('#sub_district').val(),
		district: $('#district').val(),
		postal_code: $('#postal_code').val(),
		religion_id: $('#religion_id').val(),
		citizenship: $('#citizenship').val(),
		country: $('#country').val(),
		employment_type_id: $('#employment_type_id').val(),
		phone: $('#phone').val(),
		mobile_phone: $('#mobile_phone').val(),
		email: $('#email').val()
	};
	$.post(_BASE_URL + 'employee_profile/save', values, function(response) {
		var res = _H.StrToObject( response );
		_H.Notify(res.status, _H.Message(res.message));
	});
}
</script>
<section class="content">
	<div class="nav-tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_1" data-toggle="tab">PROFIL</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab_1">
				<form class="form-horizontal">
					<div class="box-body">
						<div class="form-group">
							<label for="full_name" class="col-sm-4 control-label">Nama Lengkap</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="full_name" value="<?=$query->full_name ? $query->full_name : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="gender" class="col-sm-4 control-label">Jenis Kelamin</label>
							<div class="col-sm-8">
								<?=form_dropdown('gender', ['M' => 'Laki-laki', 'F' => 'Perempuan'], $query->gender, 'id="gender" class="form-control select2" style="width:100%;"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="nik" class="col-sm-4 control-label">NIK</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="nik" value="<?=$query->nik ? $query->nik : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="birth_place" class="col-sm-4 control-label">Tempat Lahir</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="birth_place" value="<?=$query->birth_place ? $query->birth_place : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir</label>
							<div class="col-sm-8">
								<div class="input-group">
									<input type="text" class="form-control date" id="birth_date" value="<?=$query->birth_date ? $query->birth_date : '';?>">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="street_address" class="col-sm-4 control-label">Alamat Jalan</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="street_address" value="<?=$query->street_address ? $query->street_address : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="rt" class="col-sm-4 control-label">RT</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="rt" value="<?=$query->rt ? $query->rt : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="rw" class="col-sm-4 control-label">RW</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="rw" value="<?=$query->rw ? $query->rw : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="sub_village" class="col-sm-4 control-label">Nama Dusun</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="sub_village" value="<?=$query->sub_village ? $query->sub_village : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="village" class="col-sm-4 control-label">Desa/Kelurahan</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="village" value="<?=$query->village ? $query->village : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="sub_district" class="col-sm-4 control-label">Kecamatan</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="sub_district" value="<?=$query->sub_district ? $query->sub_district : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="district" class="col-sm-4 control-label">Kabupaten</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="district" value="<?=$query->district ? $query->district : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="postal_code" class="col-sm-4 control-label">Kode Pos</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="postal_code" value="<?=$query->postal_code ? $query->postal_code : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="religion_id" class="col-sm-4 control-label">Agama</label>
							<div class="col-sm-8">
								<?=form_dropdown('religion_id', $religions, $query->religion_id, 'id="religion_id" class="form-control select2" style="width:100%;"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="citizenship" class="col-sm-4 control-label">Kewarganegaraan</label>
							<div class="col-sm-8">
								<?=form_dropdown('citizenship', ['WNI' => 'Warga Negara Indonesia', 'WNA' => 'Warga Negara Asing'], $query->citizenship, 'id="citizenship" class="form-control select2" style="width:100%;"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="country" class="col-sm-4 control-label">Nama Negara</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="country" value="<?=$query->country ? $query->country : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="employment_type_id" class="col-sm-4 control-label">Jenis GTK</label>
							<div class="col-sm-8">
								<?=form_dropdown('employment_type_id', $employment_types, $query->employment_type_id, 'id="employment_type_id" class="form-control select2" style="width:100%;"');?>
							</div>
						</div>
						<div class="form-group">
							<label for="phone" class="col-sm-4 control-label">Nomor Telepon Rumah</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="phone" value="<?=$query->phone ? $query->phone : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="mobile_phone" class="col-sm-4 control-label">Nomor HP</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="mobile_phone" value="<?=$query->mobile_phone ? $query->mobile_phone : '';?>">
							</div>
						</div>
						<div class="form-group">
							<label for="email" class="col-sm-4 control-label">Email</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" id="email" value="<?=$query->email ? $query->email : '';?>">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="col-sm-offset-4 col-sm-8">
							<button type="submit" onclick="save_changes(); return false;" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN PERUBAHAN</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
