<style type="text/css">
.checkbox-bg {
	width: 18px;
	height: 18px;
}
</style>
<script type="text/javascript">
function check_addmission_form( el ) {
	var is_checked = el.checked;
	$( document ).find( 'input.admission_form:enabled' ).prop('checked', el.checked);
}

function check_required_form( el ) {
	var is_checked = el.checked;
	$( document ).find( 'input.required_form:enabled' ).prop('checked', el.checked);
}

function save() {
	_H.Loading( true );
	$('#save').attr('disabled', 'disabled');
	var values = [];
	$(".table").find("tr").each(function() {
		if (this.id) {
			var admission = $('input[name="admission_' + this.id + '"]').is(':checked') ? 'true' : 'false';
			var admission_required = admission == 'true' ? ($('input[name="admission_required_' + this.id + '"]').is(':checked') ? 'true' : 'false') : 'false';
			var value = {
				id: this.id,
				admission : admission,
				admission_required : admission_required
			};
			values.push(value);
		}
	});
	$.post(_BASE_URL + 'admission/form_settings/save', {'field_setting':JSON.stringify(values)}, function( response ) {
		_H.Notify('info', response.message);
		_H.Loading( false );
		$('#save').removeAttr('disabled');
		setTimeout(function() {
			window.location.reload();
		}, 2000);
	});
}
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header">Pengaturan Formulir Penerimaan Peserta Didik Baru <?=__session('admission_year')?></p>
   </div>
</section>
<section class="content">
	<div class="callout">
		<h4>Petunjuk Singkat</h4>
		<ul>
			<li>Form isian <strong>Nama Lengkap</strong>, <strong>Jenis Kelamin</strong>, <strong>Jenis Pendaftaran</strong>, <strong>Jalur Pendaftaran</strong>, dan <strong>Email</strong> tidak dapat diubah statusnya karena menjadi identitas utama Calon Peserta Didik Baru.</li>
			<li>Form isian <strong>Tanggal Lahir</strong> tidak dapat diubah statusnya, tetap tampil dan wajib diisi karena digunakan untuk pencarian data ketika cetak formulir PPDB maupun pengecekan hasil seleksi PPDB.</li>
			<li>Form isian <strong>Kabupaten</strong> tidak dapat diubah statusnya, tetap tampil dan wajib diisi karena digunakan pada footer formulir PPDB.</li>
		</ul>
	</div>
	<div class="box">
		<div class="box-header">
			<button id="save" onclick="save(); return false;" class="btn btn-default pull-right"><i class="fa fa-save"></i> SIMPAN PENGATURAN</button>
		</div>
		<div class="box-body">
			<div class="table-responsive">
				<table class="table table-hover table-striped table-condensed">
					<thead>
						<tr>
							<th>NO</th>
							<th>NAMA ISIAN FORMULIR</th>
							<th style="text-align: center;">TAMPIL DI FORMULIR?
								<br>
								<input type="checkbox" class="checkbox-bg check_addmission_form" onclick="check_addmission_form(this)">
							</th>
							<th style="text-align: center;">FORMULIR HARUS DIISI?
								<br>
								<input type="checkbox" class="checkbox-bg check_required_form" onclick="check_required_form(this)">
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$required_fields = ['is_transfer', 'admission_type_id', 'full_name', 'birth_date', 'gender', 'district', 'email'];
						foreach($query->getResult() as $row) { ?>
							<?php $setting = json_decode($row->field_setting);?>
							<tr id="<?=$row->id?>">
								<td><?=$no?></td>
								<td><?=$row->field_description?></td>
								<td style="text-align: center;"><input <?=in_array($row->field_name, $required_fields) ? 'disabled="disabled"':'';?> <?=filter_var($setting->admission, FILTER_VALIDATE_BOOLEAN) ? 'checked="checked"':"";?> type="checkbox" class="checkbox-bg admission_form" name="admission_<?=$row->id?>"></td>
								<td style="text-align: center;"><input <?=in_array($row->field_name, $required_fields) ? 'disabled="disabled"':'';?> <?=filter_var($setting->admission_required, FILTER_VALIDATE_BOOLEAN) ? 'checked="checked"':"";?> type="checkbox" class="checkbox-bg required_form" name="admission_required_<?=$row->id?>"></td>
							</tr>
							<?php $no++; } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="overlay" style="display: none;">
				<i class="fa fa-refresh fa-spin"></i>
			</div>
		</div>
	</section>
