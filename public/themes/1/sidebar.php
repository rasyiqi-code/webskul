<div class="col-lg-4 col-md-5 col-sm-12 sidebar">
	<!-- Sambutan Kepala Sekolah -->
	<?php if ( ! in_array(service('uri')->getSegment(1), ['sambutan-kepala-sekolah', 'sambutan-rektor', 'sambutan-ketua', 'sambutan-direktur'])) { ?>
		<div class="sidebar-widget widget-headmaster mb-4">
			<div class="widget-headmaster-card shadow-sm-custom rounded-custom overflow-hidden bg-white border">
				<div class="widget-headmaster-img-wrapper position-relative text-center pt-4 pb-2 bg-light-modern">
					<img src="<?=base_url('media_library/images/').__session('school_profile')['headmaster_photo'];?>" class="widget-headmaster-photo rounded-circle shadow-sm" alt="<?=__session('school_profile')['headmaster']?>">
					<div class="widget-headmaster-info mt-3 px-3">
						<h6 class="font-weight-bold text-dark mb-1"><?=__session('school_profile')['headmaster']?></h6>
						<span class="badge badge-accent text-uppercase">Kepala Sekolah</span>
					</div>
				</div>
				<div class="card-body p-3 text-center">
					<p class="card-text text-muted small speech-excerpt mb-3"><?=word_limiter(strip_tags(get_opening_speech()), 22);?></p>
					<a href="<?=site_url(opening_speech_route());?>" class="btn btn-outline-primary-custom btn-sm btn-block">
						Baca Sambutan Lengkap <i class="fa fa-arrow-right ml-1"></i>
					</a>
				</div>
			</div>
		</div>
	<?php } ?>

	<!-- Tautan Terkait -->
	<?php $links = get_links(); if ($links->getNumRows() > 0) { ?>
		<div class="sidebar-widget widget-links mb-4">
			<h6 class="widget-title"><span><i class="fa fa-external-link text-primary mr-2"></i> Tautan Penting</span></h6>
			<div class="widget-card bg-white rounded-custom shadow-sm-custom border p-2">
				<div class="list-group list-group-flush">
					<?php foreach($links->getResult() as $row) { ?>
						<a href="<?=$row->link_url?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 border-0 rounded-sm" target="<?=$row->link_target?>">
							<span><i class="fa fa-angle-right text-muted mr-2"></i> <?=$row->link_title?></span>
							<i class="fa fa-share text-light-muted small"></i>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<!-- Arsip Tulisan -->
	<?php $query = get_archives(date('Y')); if ($query->getNumRows() > 0) { ?>
		<div class="sidebar-widget widget-archives mb-4">
			<h6 class="widget-title"><span><i class="fa fa-archive text-primary mr-2"></i> Arsip <?=date('Y')?></span></h6>
			<div class="widget-card bg-white rounded-custom shadow-sm-custom border p-2">
				<div class="list-group list-group-flush">
					<?php foreach($query->getResult() as $row) { ?>
						<a href="<?=site_url('arsip/'.date('Y').'/'.$row->code)?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 border-0 rounded-sm">
							<span><i class="fa fa-calendar-o text-muted mr-2"></i> <?=bulan($row->code)?></span>
							<span class="badge badge-pill badge-light-custom font-weight-bold"><?=$row->count?></span>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>

	<!-- Paling Dikomentari -->
	<?php $query = get_most_commented(5); if ($query->getNumRows() > 0) { ?>
		<div class="sidebar-widget widget-popular mb-4">
			<h6 class="widget-title"><span><i class="fa fa-comments-o text-primary mr-2"></i> Paling Populer</span></h6>
			<div class="widget-card bg-white rounded-custom shadow-sm-custom border p-3">
				<?php foreach($query->getResult() as $row) { ?>
					<div class="popular-post-item mb-3 pb-3 border-bottom-light">
						<h6 class="font-weight-bold mb-1 line-clamp-2">
							<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark hover-primary">
								<?=$row->post_title?>
							</a>
						</h6>
						<div class="popular-meta text-muted small d-flex align-items-center">
							<span class="mr-3"><i class="fa fa-clock-o mr-1"></i> <?=date('d M Y', strtotime($row->created_at))?></span>
							<span><i class="fa fa-eye mr-1"></i> <?=$row->post_counter?> tayang</span>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>

	<!-- Jajak Pendapat (Polling) -->
	<?php $query = get_active_question(); if ($query) { ?>
		<div class="sidebar-widget widget-polling mb-4">
			<h6 class="widget-title"><span><i class="fa fa-bar-chart text-primary mr-2"></i> Jajak Pendapat</span></h6>
			<div class="widget-card bg-white rounded-custom shadow-sm-custom border p-3">
				<p class="font-weight-bold text-dark mb-3 small"><?=$query->question?></p>
				<form id="sidebar-poll-form" class="mb-3">
					<?php $options = get_answers($query->id); foreach($options->getResult() as $option) { ?>
						<div class="custom-control custom-radio mb-2">
							<input class="custom-control-input" type="radio" name="answer_id" id="sb_answer_id_<?=$option->id?>" value="<?=$option->id?>">
							<label class="custom-control-label small text-muted" for="sb_answer_id_<?=$option->id?>"><?=$option->answer?></label>
						</div>
					<?php } ?>
				</form>
				<div class="d-flex justify-content-between align-items-center pt-2 border-top">
					<button type="button" onclick="vote(); return false;" class="btn btn-primary-gradient btn-sm px-3">
						<i class="fa fa-paper-plane mr-1"></i> Pilih
					</button>
					<a href="<?=site_url('hasil-jajak-pendapat')?>" class="btn btn-link btn-sm text-muted">
						<i class="fa fa-pie-chart mr-1"></i> Hasil
					</a>
				</div>
			</div>
		</div>
	<?php } ?>

	<!-- Berlangganan (Newsletter) -->
	<div class="sidebar-widget widget-newsletter mb-4">
		<h6 class="widget-title"><span><i class="fa fa-envelope-o text-primary mr-2"></i> Buletin Berita</span></h6>
		<div class="widget-card bg-white rounded-custom shadow-sm-custom border p-3">
			<p class="small text-muted mb-3">Dapatkan kabar dan agenda pendidikan terbaru langsung ke email Anda.</p>
			<div class="input-group">
				<input type="email" id="subscriber" onkeydown="if (event.keyCode == 13) { subscribe(); return false; }" class="form-control form-control-sm rounded-custom-left border" placeholder="Email Anda...">
				<div class="input-group-append">
					<button type="button" onclick="subscribe();" class="btn btn-primary-gradient btn-sm px-3 rounded-custom-right">
						<i class="fa fa-send"></i>
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Banner Iklan / Info Grafis -->
	<?php $query = get_banners(); if ($query->getNumRows() > 0) { ?>
		<div class="sidebar-widget widget-banners mb-4">
			<h6 class="widget-title"><span><i class="fa fa-bullhorn text-primary mr-2"></i> Informasi Penting</span></h6>
			<div class="banner-list">
				<?php foreach($query->getResult() as $row) { ?>
					<div class="banner-card rounded-custom overflow-hidden shadow-sm-custom mb-3 border">
						<a href="<?=$row->link_url?>" title="<?=$row->link_title?>" target="_blank">
							<img src="<?=base_url('media_library/banners/'.$row->link_image)?>" class="img-fluid w-100 banner-img" alt="<?=$row->link_title?>">
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<!-- /CONTENT -->
