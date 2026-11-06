<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="contact-header mb-4">
		<span class="section-tagline">Layanan & Informasi</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider"></div>
	</div>

	<!-- Peta Lokasi Interaktif -->
	<div class="contact-map-wrapper rounded-custom overflow-hidden shadow-sm-custom border mb-4">
		<div class="gmap_canvas embed-responsive embed-responsive-21by9" style="min-height: 280px;">
			<?=__session('general')['map_location'] ?>
		</div>
	</div>

	<!-- Info Kontak Cepat -->
	<div class="row mb-4">
		<div class="col-md-6 mb-3">
			<div class="contact-info-card bg-white rounded-custom border p-3 shadow-sm-custom d-flex align-items-center">
				<div class="contact-info-icon bg-light-primary rounded-circle mr-3 d-flex align-items-center justify-content-center">
					<i class="fa fa-phone text-primary font-weight-bold"></i>
				</div>
				<div>
					<small class="text-muted text-uppercase d-block font-weight-bold">Telepon</small>
					<span class="font-weight-bold text-dark"><?=__session('school_profile')['phone']?></span>
				</div>
			</div>
		</div>
		<div class="col-md-6 mb-3">
			<div class="contact-info-card bg-white rounded-custom border p-3 shadow-sm-custom d-flex align-items-center">
				<div class="contact-info-icon bg-light-primary rounded-circle mr-3 d-flex align-items-center justify-content-center">
					<i class="fa fa-envelope text-primary font-weight-bold"></i>
				</div>
				<div>
					<small class="text-muted text-uppercase d-block font-weight-bold">Email Resmi</small>
					<span class="font-weight-bold text-dark"><?=__session('school_profile')['email']?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- Form Kirim Pesan -->
	<div class="contact-form-card bg-white rounded-custom shadow-sm-custom border p-4 p-md-5 mb-4">
		<h5 class="font-weight-bold text-dark mb-2">Kirimkan Pesan Anda</h5>
		<p class="text-muted small mb-4">Silakan tinggalkan pesan, kritik, saran, atau pertanyaan seputar sekolah. Tim kami akan segera merespons.</p>

		<form id="contact-form">
			<div class="form-group row">
				<label for="comment_author" class="col-sm-3 col-form-label font-weight-bold small">Nama Lengkap <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control rounded-custom border" id="comment_author" name="comment_author" placeholder="Nama lengkap Anda...">
				</div>
			</div>
			<div class="form-group row">
				<label for="comment_email" class="col-sm-3 col-form-label font-weight-bold small">Alamat Email <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="email" class="form-control rounded-custom border" id="comment_email" name="comment_email" placeholder="nama@email.com">
				</div>
			</div>
			<div class="form-group row">
				<label for="comment_url" class="col-sm-3 col-form-label font-weight-bold small">Website/URL</label>
				<div class="col-sm-9">
					<input type="url" class="form-control rounded-custom border" id="comment_url" name="comment_url" placeholder="https://...">
				</div>
			</div>
			<div class="form-group row">
				<label for="comment_content" class="col-sm-3 col-form-label font-weight-bold small">Pesan Anda <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<textarea name="comment_content" class="form-control rounded-custom border" id="comment_content" rows="4" placeholder="Tuliskan pesan Anda secara rinci..."></textarea>
				</div>
			</div>
			<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
				<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
			<?php } ?>
			<div class="form-group row mb-0">
				<div class="offset-sm-3 col-sm-9">
					<button type="button" onclick="send_message(); return false;" class="btn btn-primary-gradient px-4">
						<i class="fa fa-paper-plane mr-2"></i> Kirimkan Pesan
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php echo $this->include('themes/1/sidebar')?>
