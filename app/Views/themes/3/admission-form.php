<script type="text/javascript">
$( document ).ready( function() {
	var citizenship = $('#citizenship').val();
	if (citizenship == 'WNI') {
		$('.country').hide();
	}
});
</script>
<!-- CONTENT -->
<div class="col-lg-12 col-md-12 col-sm-12">
	<h5 class="page-title mb-3"><?=strtoupper($page_title);?></h5>
	<div class="card rounded-0 border border-secondary mb-3">
		<div class="card-body">
			<form>
				<h6 class="page-title mb-3 text-uppercase">Registrasi Peserta Didik</h6>
				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="is_transfer" class="col-sm-4 control-label">Jenis Pendaftaran <?=filter_var(__session('form_is_transfer')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
					<div class="col-sm-8">
						<?=form_dropdown('is_transfer', ['' => 'Pilih :', 'false' => 'Baru', 'true' => 'Pindahan'], set_value('is_transfer'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="is_transfer"')?>
					</div>
				</div>

				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="admission_type_id" class="col-sm-4 control-label">Jalur Pendaftaran <?=filter_var(__session('form_admission_type_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
					<div class="col-sm-8">
						<?=form_dropdown('admission_type_id', $admission_types, set_value('admission_type_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="admission_type_id" onchange="get_subject_settings()" onblur="get_subject_settings()" onmouseup="get_subject_settings()"')?>
					</div>
				</div>

				<?php if (filter_var(__session('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="first_choice_id" class="col-sm-4 control-label">Pilihan Ke-1 <?=filter_var(__session('form_first_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('first_choice_id', $majors, set_value('first_choice_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="first_choice_id" onchange="check_options(1); get_subject_settings();" onblur="check_options(1); get_subject_settings();" onmouseup="check_options(1); get_subject_settings();"')?>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="second_choice_id" class="col-sm-4 control-label">Pilihan Ke-2 <?=filter_var(__session('form_second_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('second_choice_id', $majors, set_value('second_choice_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="second_choice_id" onchange="check_options(2); get_subject_settings();" onblur="check_options(2); get_subject_settings();" onmouseup="check_options(2); get_subject_settings();"')?>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="nik" class="col-sm-4 control-label">NIK/ No. KITAS (Untuk WNA) <?=filter_var(__session('form_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('nik')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="nik" name="nik">
							<small class="form-text text-muted">Nomor Induk Kependudukan yang tercantum pada Kartu Keluarga, Kartu Identitas Anak, atau KTP (jika sudah memiliki) bagi WNI. NIK memiliki
format 16 digit angka. Contoh: 6112090906021104.
Pastikan NIK tidak tertukar dengan No. Kartu Keluarga, karena keduanya memiliki format yang sama. Bagi WNA, diisi dengan nomor Kartu Izin
Tinggal Terbatas (KITAS)</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="prev_school_name" class="col-sm-4 control-label">Nama Sekolah Asal <?=filter_var(__session('form_prev_school_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('prev_school_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="prev_school_name" name="prev_school_name">
							<small class="form-text text-muted">Nama sekolah peserta didik sebelumnya. Untuk peserta didik baru, isikan nama sekolah pada jenjang sebelumnya. Sedangkan bagi peserta didik
mutasi/pindahan, diisi dengan nama sekolah sebelum pindah ke sekolah saat ini</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_prev_exam_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="prev_exam_number" class="col-sm-4 control-label">Nomor Peserta UN SMP/MTs <?=filter_var(__session('form_prev_exam_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('prev_exam_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="prev_exam_number" name="prev_exam_number">
							<small class="form-text text-muted">Nomor peserta ujian saat peserta didik masih di jenjang sebelumnya. Formatnya adalah x-xx-xx-xx-xxx-xxx-x (20 digit). Untuk Peserta Didik WNA,
diisi dengan Luar Negeri</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_skhun')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="skhun" class="col-sm-4 control-label">No. SKHUN SMP/MTs <?=filter_var(__session('form_skhun')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('skhun')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="skhun" name="skhun">
							<small class="form-text text-muted">No. SKHUN SMP/MTs/SHUN peserta didik pada jenjang sebelumnya (jika memiliki)</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_prev_diploma_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="prev_diploma_number" class="col-sm-4 control-label">No. Seri Ijazah SMP/MTs <?=filter_var(__session('form_prev_diploma_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('prev_diploma_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="prev_diploma_number" name="prev_diploma_number">
							<small class="form-text text-muted">Nomor seri ijazah peserta didik pada jenjang sebelumnya</small>
						</div>
					</div>
				<?php } ?>
				
				<h6 class="page-title mb-3 mt-3 text-uppercase">Data Pribadi</h6>
				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="full_name" class="col-sm-4 control-label">Nama Lengkap <?=filter_var(__session('form_full_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
					<div class="col-sm-8">
						<input type="text" value="<?php echo set_value('full_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="full_name" name="full_name">
						<small class="form-text text-muted">Nama peserta didik sesuai dokumen resmi yang berlaku (Akta atau Ijazah sebelumnya)</small>
					</div>
				</div>

				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="gender" class="col-sm-4 control-label">Jenis Kelamin <?=filter_var(__session('form_gender')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
					<div class="col-sm-8">
						<?=form_dropdown('gender', ['' => 'Pilih :', 'M' => 'Laki-laki', 'F' => 'Perempuan'], set_value('gender'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="gender"')?>
						<small class="form-text text-muted">Jenis kelamin peserta didik</small>	
					</div>
				</div>

				<?php if (filter_var(__session('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="nisn" class="col-sm-4 control-label">NISN <?=filter_var(__session('form_nisn')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('nisn')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="nisn" name="nisn">
							<small class="form-text text-muted">Nomor Induk Siswa Nasional peserta didik (jika memiliki). Jika belum memiliki, maka wajib dikosongkan. NISN memiliki format 10 digit angka.
Contoh: 0009321234. Untuk memeriksa NISN, dapat mengunjungi laman http://nisn.data.kemdikbud.go.id/page/data</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_family_card_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="family_card_number" class="col-sm-4 control-label">No. Kartu Keluarga <?=filter_var(__session('form_family_card_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('family_card_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="family_card_number" name="family_card_number">
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="birth_place" class="col-sm-4 control-label">Tempat Lahir <?=filter_var(__session('form_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('birth_place')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="birth_place" name="birth_place">
							<small class="form-text text-muted">Tempat lahir peserta didik sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<div class="input-group">
							<input autocomplete="off" readonly type="text" class="form-control form-control-sm rounded-0 border border-secondary date" id="birth_date">
							<div class="input-group-append">
								<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
							</div>
						</div>
						<small class="form-text text-muted">Tanggal lahir peserta didik sesuai dokumen resmi yang berlaku</small>
					</div>
				</div>

				<?php if (filter_var(__session('form_birth_certificate_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="rt" class="col-sm-4 control-label">No. Registasi Akta Lahir <?=filter_var(__session('form_birth_certificate_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('birth_certificate_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="birth_certificate_number" name="birth_certificate_number">
							<small class="form-text text-muted">Nomor registrasi Akta Kelahiran. Nomor registrasi yang dimaksud umumnya tercantum pada bagian tengah atas lembar kutipan akta kelahiran</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_religion_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="religion_id" class="col-sm-4 control-label">Agama dan Kepercayaan <?=filter_var(__session('form_religion_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('religion_id', $religions, set_value('religion_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="religion_id"')?>
							<small class="form-text text-muted">Agama atau kepercayaan yang dianut oleh peserta didik. Apabila peserta didik adalah penghayat kepercayaan (misalnya pada daerah tertentu yang
masih memiliki penganut kepercayaan), dapat memilih opsi Kepercayaan kpd Tuhan YME</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_citizenship')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="citizenship" class="col-sm-4 control-label">Kewarganegaraan <?=filter_var(__session('form_citizenship')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<select name="citizenship" id="citizenship" class="custom-select custom-select-sm rounded-0 border border-secondary" onchange="change_country_field()" onblur="change_country_field()" onmouseup="change_country_field()">
								<option value="">Pilih :</option>
								<option value="WNI">Warga Negara Indonesia (WNI)</option>
								<option value="WNA">Warga Negara Asing (WNA)</option>
							</select>
							<small class="form-text text-muted">Kewarganegaraan peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_country')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2 country">
						<label for="country" class="col-sm-4 control-label">Nama Negara <?=filter_var(__session('form_country')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('country')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="country" name="country">
							<small class="form-text text-muted">Nama negara peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="special_need_id" class="col-sm-4 control-label">Berkebutuhan Khusus <?=filter_var(__session('form_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('special_need_id', $special_needs, set_value('special_need_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="special_need_id"')?>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_street_address')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="street_address" class="col-sm-4 control-label">Alamat Jalan <?=filter_var(__session('form_street_address')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<textarea rows="2" name="street_address" id="street_address" class="form-control form-control-sm rounded-0 border border-secondary"><?php echo set_value('street_address')?></textarea>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_rt')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="rt" class="col-sm-4 control-label">RT <?=filter_var(__session('form_rt')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('rt')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="rt" name="rt">
							<small class="form-text text-muted">Nomor RT tempat tinggal peserta didik saat ini. Dari contoh di atas, misalnya dapat diisi dengan angka 5</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_rw')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="rw" class="col-sm-4 control-label">RW <?=filter_var(__session('form_rw')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('rw')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="rw" name="rw">
							<small class="form-text text-muted">Nomor RW tempat tinggal peserta didik saat ini. Dari contoh di atas, misalnya dapat diisi dengan angka 11</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_sub_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="sub_village" class="col-sm-4 control-label">Nama Dusun <?=filter_var(__session('form_sub_village')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('sub_village')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="sub_village" name="sub_village">
							<small class="form-text text-muted">Nama dusun tempat tinggal peserta didik saat ini. Dari contoh di atas, misalnya dapat diisi dengan Campak</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_village')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="village" class="col-sm-4 control-label">Desa/Kelurahan <?=filter_var(__session('form_village')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('village')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="village" name="village">
							<small class="form-text text-muted">Nama desa atau kelurahan tempat tinggal peserta didik saat ini. Dari contoh di atas, dapat diisi dengan Bayongbong</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_sub_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="sub_district" class="col-sm-4 control-label">Kecamatan <?=filter_var(__session('form_sub_district')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('sub_district')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="sub_district" name="sub_district">
							<small class="form-text text-muted">Kecamatan tempat tinggal peserta didik saat ini</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_district')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="district" class="col-sm-4 control-label">Kabupaten/Kota <?=filter_var(__session('form_district')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('district')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="district" name="district">
							<small class="form-text text-muted">Kabupaten tempat tinggal peserta didik saat ini</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_postal_code')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="postal_code" class="col-sm-4 control-label">Kode Pos <?=filter_var(__session('form_postal_code')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('postal_code')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="postal_code" name="postal_code">
							<small class="form-text text-muted">Kode pos tempat tinggal peserta didik saat ini</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_latitude')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="latitude" class="col-sm-4 control-label">Lintang <?=filter_var(__session('form_latitude')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('latitude')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="latitude" name="latitude">
							<small class="form-text text-muted">Titik koordinat tempat tinggal siswa</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_longitude')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="longitude" class="col-sm-4 control-label">Bujur <?=filter_var(__session('form_longitude')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('longitude')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="longitude" name="longitude">
							<small class="form-text text-muted">Titik koordinat tempat tinggal siswa</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_residence_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="residence_id" class="col-sm-4 control-label">Tempat Tinggal <?=filter_var(__session('form_residence_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('residence_id', $residences, set_value('residence_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="residence_id"')?>
							<small class="form-text text-muted">Kepemilikan tempat tinggal peserta didik saat ini (yang telah diisikan pada kolom-kolom sebelumnya di atas)</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_transportation_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="transportation_id" class="col-sm-4 control-label">Moda Transportasi <?=filter_var(__session('form_transportation_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('transportation_id', $transportations, set_value('transportation_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="transportation_id"')?>
							<small class="form-text text-muted">Jenis transportasi utama atau yang paling sering digunakan peserta didik untuk berangkat ke sekolah</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_child_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="child_number" class="col-sm-4 control-label">Anak Ke Berapa <?=filter_var(__session('form_child_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('child_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="child_number" name="child_number">
							<small class="form-text text-muted">Sesuaikan dengan urutan pada Kartu Keluarga</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="employment_id" class="col-sm-4 control-label">Pekerjaan <?=filter_var(__session('form_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('employment_id', $employments, set_value('employment_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="employment_id"')?>
							<small class="form-text text-muted">Diperuntukan untuk warga belajar</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_have_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="have_kip" class="col-sm-4 control-label">Apakah Punya KIP? <?=filter_var(__session('form_have_kip')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('have_kip', ['' => 'Pilih :', 'false' => 'Tidak', 'true' => 'Ya'], set_value('have_kip'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="have_kip"')?>
							<small class="form-text text-muted">Pilih Ya apabila peserta didik memiliki Kartu Indonesia Pintar (KIP). Pilih Tidak jika tidak memiliki</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_receive_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="receive_kip" class="col-sm-4 control-label">Apakah Tetap Akan Menerima KIP? <?=filter_var(__session('form_receive_kip')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('receive_kip', ['' => 'Pilih :', 'false' => 'Tidak', 'true' => 'Ya'], set_value('receive_kip'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="receive_kip"')?>
							<small class="form-text text-muted">Status bahwa peserta didik sudah menerima atau belum menerima Kartu Indonesia Pintar secara fisik</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_reject_pip')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="reject_pip" class="col-sm-4 control-label">Alasan Menolak PIP? <?=filter_var(__session('form_reject_pip')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('reject_pip', reject_pip(), set_value('reject_pip'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="reject_pip"')?>
							<small class="form-text text-muted">Alasan utama peserta didik jika layak menerima manfaat PIP. Kolom ini akan muncul apabila dipilih Ya
	untuk mengisi kolom Usulan dari Sekolah (Layak PIP)</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_father_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Data Ayah Kandung</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_name" class="col-sm-4 control-label">Nama Ayah <?=filter_var(__session('form_father_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('father_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="father_name" name="father_name">
							<small class="form-text text-muted">Nama ayah kandung peserta didik sesuai dokumen resmi yang berlaku. Hindari penggunaan gelar akademik atau sosial
(seperti Alm., Dr., Drs., S.Pd, dan H.)</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_father_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_nik" class="col-sm-4 control-label">NIK Ayah <?=filter_var(__session('form_father_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('father_nik')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="father_nik" name="father_nik">
							<small class="form-text text-muted">Nomor Induk Kependudukan yang tercantum pada Kartu Keluarga atau KTP ayah kandung peserta didik</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_father_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_birth_place" class="col-sm-4 control-label">Tempat Lahir Ayah <?=filter_var(__session('form_father_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('father_birth_place')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="father_birth_place" name="father_birth_place">
							<small class="form-text text-muted">Tempat lahir ayah sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_birth_date')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_birth_date" class="col-sm-4 control-label">Tanggal Lahir Ayah <span style="color: red">*</span></label>
						<div class="col-sm-8">
							<div class="input-group">
								<input autocomplete="off" readonly type="text" class="form-control form-control-sm rounded-0 border border-secondary date" id="father_birth_date">
								<div class="input-group-append">
									<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
								</div>
							</div>
							<small class="form-text text-muted">Tanggal lahir ayah sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_education_id" class="col-sm-4 control-label">Pendidikan Ayah <?=filter_var(__session('form_father_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('father_education_id', $educations, set_value('father_education_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="father_education_id"')?>
							<small class="form-text text-muted">Pendidikan terakhir ayah kandung peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_employment_id" class="col-sm-4 control-label">Pekerjaan Ayah <?=filter_var(__session('form_father_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('father_employment_id', $employments, set_value('father_employment_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="father_employment_id"')?>
							<small class="form-text text-muted">Pekerjaan utama ayah kandung peserta didik. Pilih Meninggal Dunia apabila ayah kandung peserta didik telah meninggal dunia</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Ayah <?=filter_var(__session('form_father_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('father_monthly_income_id', $monthly_incomes, set_value('father_monthly_income_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="father_monthly_income_id"')?>
							<small class="form-text text-muted">Rentang penghasilan ayah kandung peserta didik. Kosongkan kolom ini apabila ayah kandung tidak bekerja</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="father_special_need_id" class="col-sm-4 control-label">Kebutuhan Khusus Ayah <?=filter_var(__session('form_father_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('father_special_need_id', $special_needs, set_value('father_special_need_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="father_special_need_id"')?>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_mother_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Data Ibu Kandung</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_name" class="col-sm-4 control-label">Nama Ibu <?=filter_var(__session('form_mother_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('mother_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="mother_name" name="mother_name">
							<small class="form-text text-muted">Nama ibu kandung peserta didik sesuai dokumen resmi yang berlaku. Hindari penggunaan gelar akademik atau sosial (seperti Almh. Dr., Dra., S.Pd,
dan Hj.)</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_mother_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_nik" class="col-sm-4 control-label">NIK Ibu <?=filter_var(__session('form_mother_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('mother_nik')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="mother_nik" name="mother_nik">
							<small class="form-text text-muted">Nomor Induk Kependudukan yang tercantum pada Kartu Keluarga atau KTP Ibu kandung peserta didik</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_mother_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_birth_place" class="col-sm-4 control-label">Tempat Lahir Ibu <?=filter_var(__session('form_mother_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('mother_birth_place')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="mother_birth_place" name="mother_birth_place">
							<small class="form-text text-muted">Tempat lahir ibu sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_birth_date')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_birth_date" class="col-sm-4 control-label">Tanggal Lahir Ibu <span style="color: red">*</span></label>
						<div class="col-sm-8">
							<div class="input-group">
								<input autocomplete="off" readonly type="text" class="form-control form-control-sm rounded-0 border border-secondary date" id="mother_birth_date">
								<div class="input-group-append">
									<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
								</div>
							</div>
							<small class="form-text text-muted">Tanggal lahir ibu sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_education_id" class="col-sm-4 control-label">Pendidikan Ibu <?=filter_var(__session('form_mother_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('mother_education_id', $educations, set_value('mother_education_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="mother_education_id"')?>
							<small class="form-text text-muted">Pendidikan terakhir ibu kandung peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_employment_id" class="col-sm-4 control-label">Pekerjaan Ibu <?=filter_var(__session('form_mother_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('mother_employment_id', $employments, set_value('mother_employment_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="mother_employment_id"')?>
							<small class="form-text text-muted">Pekerjaan utama ibu kandung peserta didik. Pilih Meninggal Dunia apabila ibu kandung peserta didik telah meninggal dunia</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Ibu <?=filter_var(__session('form_mother_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('mother_monthly_income_id', $monthly_incomes, set_value('mother_monthly_income_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="mother_monthly_income_id"')?>
							<small class="form-text text-muted">Rentang penghasilan ibu kandung peserta didik. Kosongkan kolom ini apabila ibu kandung tidak bekerja</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mother_special_need_id" class="col-sm-4 control-label">Kebutuhan Khusus Ibu <?=filter_var(__session('form_mother_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('mother_special_need_id', $special_needs, set_value('mother_special_need_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="mother_special_need_id"')?>
							<small class="form-text text-muted">Kebutuhan khusus yang disandang oleh ibu peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Data Wali</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_name" class="col-sm-4 control-label">Nama Wali <?=filter_var(__session('form_guardian_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('guardian_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="guardian_name" name="guardian_name">
							<small class="form-text text-muted">Nama wali peserta didik sesuai dokumen resmi yang berlaku. Hindari penggunaan gelar akademik atau sosial (seperti Dr., Drs., S.Pd, dan H.)</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_guardian_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_nik" class="col-sm-4 control-label">NIK Wali <?=filter_var(__session('form_guardian_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('guardian_nik')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="guardian_nik" name="guardian_nik">
							<small class="form-text text-muted">Nomor Induk Kependudukan yang tercantum pada Kartu Keluarga atau KTP wali peserta didik</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_guardian_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_birth_place" class="col-sm-4 control-label">Tempat Lahir Wali <?=filter_var(__session('form_guardian_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('guardian_birth_place')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="guardian_birth_place" name="guardian_birth_place">
							<small class="form-text text-muted">Tempat lahir wali sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_birth_date')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="form_guardian_birth_date" class="col-sm-4 control-label">Tanggal Lahir Wali <span style="color: red">*</span></label>
						<div class="col-sm-8">
							<div class="input-group">
								<input autocomplete="off" readonly type="text" class="form-control form-control-sm rounded-0 border border-secondary date" id="form_guardian_birth_date">
								<div class="input-group-append">
									<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
								</div>
							</div>
							<small class="form-text text-muted">Tanggal lahir wali sesuai dokumen resmi yang berlaku</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_education_id" class="col-sm-4 control-label">Pendidikan Wali <?=filter_var(__session('form_guardian_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('guardian_education_id', $educations, set_value('guardian_education_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="guardian_education_id"')?>
							<small class="form-text text-muted">Pendidikan terakhir wali peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_employment_id" class="col-sm-4 control-label">Pekerjaan Wali <?=filter_var(__session('form_guardian_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('guardian_employment_id', $employments, set_value('guardian_employment_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="guardian_employment_id"')?>
							<small class="form-text text-muted">Pekerjaan utama wali peserta didik</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="guardian_monthly_income_id" class="col-sm-4 control-label">Penghasilan Bulanan Wali <?=filter_var(__session('form_guardian_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<?=form_dropdown('guardian_monthly_income_id', $monthly_incomes, set_value('guardian_monthly_income_id'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="guardian_monthly_income_id"')?>
							<small class="form-text text-muted">Rentang penghasilan wali peserta didik. Kosongkan kolom ini apabila wali tidak bekerja</small>
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_mobile_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Kontak</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mobile_phone" class="col-sm-4 control-label">Nomor Handphone <?=filter_var(__session('form_mobile_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('mobile_phone')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="mobile_phone" name="mobile_phone">
							<small class="form-text text-muted">Diisi nomor telepon selular (milik pribadi, orang tua, atau wali) tanpa tanda baca</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="phone" class="col-sm-4 control-label">Nomor Telepon Rumah <?=filter_var(__session('form_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('phone')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="phone" name="phone">
							<small class="form-text text-muted">Diisi nomor telepon rumah (milik pribadi, orang tua, atau wali) tanpa tanda baca</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_email')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="email" class="col-sm-4 control-label">Email <?=filter_var(__session('form_email')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('email')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="email" name="email">
							<small class="form-text text-muted">Diisi alamat surat elektronik (surel) peserta didik yang dapat dihubungi (milik pribadi, orang tua, atau wali)</small>
						</div>
					</div>
				<?php } ?>
				
				
				<?php if (filter_var(__session('form_height')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Data Periodik</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="height" class="col-sm-4 control-label">Tinggi Badan (Cm) <?=filter_var(__session('form_height')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="number" value="<?php echo set_value('height')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="height" name="height">
							<small class="form-text text-muted">Tinggi badan peserta didik dalam satuan sentimeter</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_weight')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="weight" class="col-sm-4 control-label">Berat Badan (Kg) <?=filter_var(__session('form_weight')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="number" value="<?php echo set_value('weight')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="weight" name="weight">
							<small class="form-text text-muted">Berat badan peserta didik dalam satuan kilogram</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_head_circumference')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="head_circumference" class="col-sm-4 control-label">Lingkar Kepala (Cm) <?=filter_var(__session('form_head_circumference')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="number" value="<?php echo set_value('head_circumference')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="head_circumference" name="head_circumference">
							<small class="form-text text-muted">Berat badan peserta didik dalam satuan kilogram</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mileage')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="mileage" class="col-sm-4 control-label">Jarak Tempat Tinggal ke Sekolah (Km) <?=filter_var(__session('form_mileage')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('mileage')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="mileage" name="mileage">
							<small class="form-text text-muted">Jarak rumah peserta didik ke sekolah</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_traveling_time')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="traveling_time" class="col-sm-4 control-label">Waktu Tempuh ke Sekolah (Menit) <?=filter_var(__session('form_traveling_time')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="number" value="<?php echo set_value('traveling_time')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="traveling_time" name="traveling_time">
							<small class="form-text text-muted">Lama tempuh peserta didik ke sekolah dalam satuan menit</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_sibling_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="sibling_number" class="col-sm-4 control-label">Jumlah Saudara Kandung <?=filter_var(__session('form_sibling_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="number" value="<?php echo set_value('sibling_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="sibling_number" name="sibling_number">
						</div>
					</div>
				<?php } ?>
				
				<?php if (filter_var(__session('form_welfare_type')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">Kesejahteraan Peserta Didik</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="welfare_type" class="col-sm-4 control-label">Jenis Kesejahteraan <?=filter_var(__session('form_welfare_type')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
						<?=form_dropdown('welfare_type', welfare_types(), set_value('welfare_type'), 'class="custom-select custom-select-sm rounded-0 border border-secondary" id="welfare_type"')?>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_welfare_number')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="welfare_number" class="col-sm-4 control-label">Nomor Kartu <?=filter_var(__session('form_welfare_number')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('welfare_number')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="welfare_number" name="welfare_number">
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_welfare_name')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="welfare_name" class="col-sm-4 control-label">Nama di Kartu <?=filter_var(__session('form_welfare_name')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="text" value="<?php echo set_value('welfare_name')?>" class="form-control form-control-sm rounded-0 border border-secondary" id="welfare_name" name="welfare_name">
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_photo')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<h6 class="page-title mb-3 mt-3 text-uppercase">UNGGAH DOKUMEN</h6>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah Pas Foto <?=filter_var(__session('form_photo')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="photo" name="photo">
							<small class="form-text text-muted">Foto harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_family_card')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah Kartu Keluarga <?=filter_var(__session('form_family_card')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="family_card" name="family_card">
							<small class="form-text text-muted">Kartu Keluarga harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_birth_certificate')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah Akta Lahir <?=filter_var(__session('form_birth_certificate')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="birth_certificate" name="birth_certificate">
							<small class="form-text text-muted">Akta Lahir harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_father_identity_card')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah KTP Ayah <?=filter_var(__session('form_father_identity_card')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="father_identity_card" name="father_identity_card">
							<small class="form-text text-muted">KTP Ayah harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_mother_identity_card')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah KTP Ibu <?=filter_var(__session('form_mother_identity_card')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="mother_identity_card" name="mother_identity_card">
							<small class="form-text text-muted">KTP Ibu harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<?php if (filter_var(__session('form_guardian_identity_card')['admission'], FILTER_VALIDATE_BOOLEAN)) { ?>
					<div class="form-group row mb-1 pt-2 pb-2">
						<label for="file" class="col-sm-4 control-label">Unggah KTP Wali <?=filter_var(__session('form_guardian_identity_card')['admission_required'], FILTER_VALIDATE_BOOLEAN) ? '<span style="color: red">*</span>':''?></label>
						<div class="col-sm-8">
							<input type="file" id="guardian_identity_card" name="guardian_identity_card">
							<small class="form-text text-muted">KTP Wali harus JPG dan ukuran file maksimal 500 KB</small>
						</div>
					</div>
				<?php } ?>

				<div class="subject_scores"></div>

				<h6 class="page-title mb-3 mt-3 text-uppercase">Pernyataan dan Keamanan</h6>
				<div class="form-group row mb-1 pt-2 pb-2">
					<label for="declaration" class="col-sm-4 control-label">Pernyataan <span style="color: red">*</span></label>
					<div class="col-sm-8">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="declaration" id="declaration">
							<label class="form-check-label" for="declaration">
								Saya yang bertandatangan dibawah ini menyatakan bahwa data yang tertera diatas adalah yang sebenarnya.
							</label>
						</div>
					</div>
				</div>
				<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
					<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
				<?php } ?>
			</form>
		</div>
		<div class="card-footer">
			<div class="form-group row mb-0">
				<div class="offset-sm-4 col-sm-8">
					<button type="button" onclick="student_registration(); return false;" class="btn btn-lg btn-success rounded-0 font-weight-bold"><i class="fa fa-send"></i> SIMPAN FORMULIR PENDAFTARAN</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /CONTENT -->
