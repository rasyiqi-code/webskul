<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="alumni-header mb-4 text-center">
		<span class="section-tagline">Jejaring & Komunitas</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="form-modern-card bg-white rounded-custom shadow-sm-custom border p-4 p-md-5 mb-5 mx-auto" style="max-width: 850px;">
		<h5 class="font-weight-bold text-dark mb-2">Formulir Pendaftaran Alumni</h5>
		<p class="text-muted small mb-4">Silakan lengkapi biodata alumni Anda untuk memperbarui direktori dan mempererat silaturahmi keluarga besar sekolah.</p>

		<form id="alumni-form">
			<div class="form-group row">
				<label for="nis" class="col-sm-3 col-form-label font-weight-bold small">NIS</label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="nis" name="nis" placeholder="Nomor Induk Siswa saat aktif...">
				</div>
			</div>
			<div class="form-group row">
				<label for="full_name" class="col-sm-3 col-form-label font-weight-bold small">Nama Lengkap <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="full_name" name="full_name" placeholder="Nama lengkap alumni...">
				</div>
			</div>
			<div class="form-group row">
				<label for="gender" class="col-sm-3 col-form-label font-weight-bold small">Jenis Kelamin <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<?=form_dropdown('gender', ['' => 'Pilih Jenis Kelamin :', 'M' => 'Laki-laki', 'F' => 'Perempuan'], '', 'class="custom-select rounded-custom border" id="gender"')?>
				</div>
			</div>
			<div class="form-group row">
				<label for="birth_place" class="col-sm-3 col-form-label font-weight-bold small">Tempat Lahir <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="birth_place" name="birth_place" placeholder="Kota / tempat kelahiran...">
				</div>
			</div>
			<div class="form-group row">
				<label for="birth_date" class="col-sm-3 col-form-label font-weight-bold small">Tanggal Lahir <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<div class="input-group">
						<input type="text" readonly class="form-control rounded-custom-left border date" id="birth_date" name="birth_date" placeholder="YYYY-MM-DD">
						<div class="input-group-append">
							<span class="input-group-text rounded-custom-right bg-light border-left-0"><i class="fa fa-calendar text-primary"></i></span>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label for="start_date" class="col-sm-3 col-form-label font-weight-bold small">Tahun Masuk <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="number" class="form-control rounded-custom border" id="start_date" name="start_date" placeholder="Contoh: 2018">
				</div>
			</div>
			<div class="form-group row">
				<label for="end_date" class="col-sm-3 col-form-label font-weight-bold small">Tahun Lulus <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="number" class="form-control rounded-custom border" id="end_date" name="end_date" placeholder="Contoh: 2021">
				</div>
			</div>
			<div class="form-group row">
				<label for="street_address" class="col-sm-3 col-form-label font-weight-bold small">Alamat Domisili <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<textarea rows="4" class="form-control rounded-custom border" id="street_address" name="street_address" placeholder="Alamat tempat tinggal saat ini..."></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label for="email" class="col-sm-3 col-form-label font-weight-bold small">Alamat Email <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="email" class="form-control rounded-custom border" id="email" name="email" placeholder="nama@email.com">
				</div>
			</div>
			<div class="form-group row">
				<label for="phone" class="col-sm-3 col-form-label font-weight-bold small">Telepon Rumah</label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="phone" name="phone" placeholder="Nomor telepon rumah jika ada...">
				</div>
			</div>
			<div class="form-group row">
				<label for="mobile_phone" class="col-sm-3 col-form-label font-weight-bold small">No. Handphone / WA <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="mobile_phone" name="mobile_phone" placeholder="08xxxxxxxxxx">
				</div>
			</div>
			<div class="form-group row">
				<label for="photo" class="col-sm-3 col-form-label font-weight-bold small">Pas Foto</label>
				<div class="col-sm-9">
					<input type="file" id="photo" name="photo" class="form-control-file">
					<small class="form-text text-muted">Format JPG/PNG, ukuran file maksimal 1 MB.</small>
				</div>
			</div>
			<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
				<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
			<?php } ?>
			<div class="form-group row mb-0 mt-4 pt-3 border-top">
				<div class="offset-sm-3 col-sm-9">
					<button type="button" onclick="alumni_registration(); return false;" class="btn btn-primary-gradient px-4">
						<i class="fa fa-paper-plane mr-2"></i> Daftarkan Data Alumni
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
