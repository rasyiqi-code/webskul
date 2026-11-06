<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="search-header mb-4">
		<span class="section-tagline">Pencarian</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider"></div>
	</div>

	<?php if ($query && $query->getNumRows() > 0) { ?>
		<div class="search-results-list">
			<?php foreach($query->getResult() as $row) { ?>
				<article class="card-archive-item bg-white rounded-custom border shadow-sm-custom overflow-hidden mb-3 p-4">
					<div class="card-meta-inline text-muted small mb-2">
						<span class="mr-3"><i class="fa fa-calendar-o mr-1 text-primary"></i> <?=date('d/m/Y', strtotime($row->created_at))?></span>
						<span class="mr-3"><i class="fa fa-user-o mr-1 text-primary"></i> <?=$row->post_author?></span>
						<span><i class="fa fa-eye mr-1 text-primary"></i> <?=$row->post_counter?> kali</span>
					</div>
					<h5 class="card-title font-weight-bold mb-2">
						<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark hover-primary">
							<?=$row->post_title?>
						</a>
					</h5>
					<p class="card-text text-muted small mb-3">
						<?=substr(strip_tags($row->post_content), 0, 160)?>...
					</p>
					<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="read-more-link font-weight-bold">
						Baca Selengkapnya <i class="fa fa-long-arrow-right ml-1"></i>
					</a>
				</article>
			<?php } ?>
		</div>
	<?php } else { ?>
		<div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom">
			<i class="fa fa-search fa-3x text-muted mb-3"></i>
			<h5 class="font-weight-bold text-dark">Tidak Ditemukan Hasil</h5>
			<p class="text-muted small">Maaf, kami tidak menemukan artikel atau halaman yang sesuai dengan kata kunci Anda. Silakan coba kata kunci lain.</p>
			<a href="<?=base_url()?>" class="btn btn-outline-primary-custom btn-sm">Kembali ke Beranda</a>
		</div>
	<?php } ?>
</div>
<?php echo $this->include('themes/1/sidebar')?>
