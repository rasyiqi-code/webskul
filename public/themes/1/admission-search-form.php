<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="admission-header mb-4">
		<span class="section-tagline">Penerimaan Peserta Didik Baru</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider"></div>
	</div>

	<div class="admission-card bg-white rounded-custom shadow-sm-custom border p-4 p-md-5 mb-4">
		<h5 class="font-weight-bold text-dark mb-2">Cek Status & Hasil Seleksi</h5>
		<p class="text-muted small mb-4">Masukkan nomor registrasi pendaftaran dan tanggal lahir Anda untuk melihat informasi status dan hasil seleksi penerimaan siswa baru.</p>

		<form>
			<div class="form-group row">
				<label for="registration_number" class="col-sm-4 col-form-label font-weight-bold small">Nomor Pendaftaran <span class="text-danger">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control rounded-custom border" id="registration_number" name="registration_number" placeholder="Contoh: 20260001">
				</div>
			</div>
			<div class="form-group row">
				<label for="birth_date" class="col-sm-4 col-form-label font-weight-bold small">Tanggal Lahir <span class="text-danger">*</span></label>
				<div class="col-sm-8">
					<div class="input-group">
						<input autocomplete="off" readonly type="text" class="form-control rounded-custom-left border date" id="birth_date" placeholder="Format: YYYY-MM-DD">
						<div class="input-group-append">
							<span class="input-group-text rounded-custom-right bg-light border-left-0"><i class="fa fa-calendar text-primary"></i></span>
						</div>
					</div>
				</div>
			</div>
			<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
				<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
			<?php } ?>
			<div class="form-group row mb-0 mt-4">
				<div class="offset-sm-4 col-sm-8">
					<button type="button" onclick="<?=$onclick?>; return false;" class="btn btn-primary-gradient px-4">
						<i class="fa fa-search mr-2"></i> <?=$button?>
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="selection_results"></div>
</div>
<?php echo $this->include('themes/1/sidebar')?>
