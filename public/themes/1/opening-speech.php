<!-- CONTENT -->
<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="speech-header mb-4">
		<span class="section-tagline">Profil & Visi</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title ?? 'SAMBUTAN KEPALA SEKOLAH');?></h2>
		<div class="section-divider"></div>
	</div>

	<article class="speech-article-card bg-white rounded-custom shadow-sm-custom border overflow-hidden p-4 p-md-5 mb-4">
		<div class="headmaster-intro-box d-flex flex-column flex-sm-row align-items-center mb-4 pb-4 border-bottom">
			<img src="<?=base_url('media_library/images/').__session('school_profile')['headmaster_photo'];?>" class="headmaster-speech-photo rounded-circle shadow-sm mr-sm-4 mb-3 mb-sm-0" width="100" height="100" style="object-fit: cover;" alt="<?=__session('school_profile')['headmaster']?>">
			<div>
				<h4 class="font-weight-bold text-dark mb-1"><?=__session('school_profile')['headmaster']?></h4>
				<span class="badge badge-accent text-uppercase mb-2">Kepala <?=__session('school_profile')['school_name']?></span>
				<p class="text-muted small mb-0 font-italic">"<?=__session('school_profile')['tagline']?>"</p>
			</div>
		</div>

		<div class="speech-full-text text-justify text-muted leading-relaxed">
			<?=get_opening_speech()?>
		</div>
	</article>

	<!-- Tulisan Lainnya yang Direkomendasikan -->
	<?php $query = get_random_posts(4); if ($query && $query->getNumRows() > 0) { ?>
		<div class="recommended-posts-section mb-4">
			<h5 class="section-title-modern mb-3 font-weight-bold">
				<i class="fa fa-newspaper-o text-primary mr-2"></i> Baca Juga Informasi Lainnya
			</h5>
			<div class="row">
				<?php foreach($query->getResult() as $row) { ?>
					<div class="col-md-6 mb-3">
						<div class="card-related h-100 bg-white rounded-custom border p-3 shadow-sm-custom">
							<h6 class="font-weight-bold mb-2 line-clamp-2">
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark hover-primary">
									<?=$row->post_title?>
								</a>
							</h6>
							<p class="text-muted small mb-2 line-clamp-2">
								<?=substr(strip_tags($row->post_content), 0, 100)?>...
							</p>
							<div class="d-flex justify-content-between align-items-center text-muted small mt-auto">
								<span><i class="fa fa-calendar-o mr-1"></i> <?=date('d/m/Y', strtotime($row->created_at))?></span>
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-primary font-weight-bold">Baca <i class="fa fa-arrow-right ml-1"></i></a>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<?php echo $this->include('themes/1/sidebar')?>
