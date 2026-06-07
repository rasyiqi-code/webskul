<div class="col-lg-8 col-md-8 col-sm-12 ">
	<h5 class="page-title mb-3"><?=strtoupper($page_title);?></h5>
	<div class="card rounded-0 border border-secondary mb-3">
		<div class="card-body">
			<div class="form-group row mb-2">
				<label for="registration_number" class="col-sm-4 control-label">Nomor Pendaftaran <span style="color: red">*</span></label>
				<div class="col-sm-8">
					<input type="text" class="form-control form-control-sm rounded-0 border border-secondary" id="registration_number" name="registration_number">
				</div>
			</div>
			<div class="form-group row mb-2">
				<label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir <span style="color: red">*</span></label>
				<div class="col-sm-8">
					<div class="input-group">
						<input autocomplete="off" readonly type="text" class="form-control form-control-sm rounded-0 border border-secondary date" id="birth_date" placeholder="Masukan tanggal lahir anda dengan format : YYYY-MM-DD">
						<div class="input-group-append">
							<span class="btn btn-sm btn-outline-secondary rounded-0"><i class="fa fa-calendar text-dark"></i></span>
						</div>
					</div>
				</div>
			</div>
			<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
				<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
			<?php } ?>
		</div>
		<div class="card-footer">
			<div class="form-group row mb-0">
				<div class="offset-sm-4 col-sm-8">
					<button type="button" onclick="<?=$onclick?>; return false;" class="btn btn-success rounded-0"><i class="fa fa-send"></i> <?=$button?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="selection_results"></div>
</div>
<?php echo $this->include('themes/1/sidebar')?>
