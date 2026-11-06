<script type="text/javascript">
var page = 1;
var total_page = "<?=$total_page;?>";
$(document).ready(function() {
	if (parseInt(total_page) == page || parseInt(total_page) == 0) {
		$('.more-comments').remove();
	}
});
function get_post_comments() {
	page++;
	var data = {
		page_number: page,
		comment_post_id: '<?=service('uri')->getSegment(2)?>'
	};
	if ( page <= parseInt(total_page) ) {
		$.post( _BASE_URL + 'public/post_comments/get_post_comments', data, function( response ) {
			var res = _H.StrToObject( response );
			var rows = res.comments;
			var str = '';
			for (var z in rows) {
				var row = rows[ z ];
				str += '<div class="comment-item bg-white rounded-custom border p-3 mb-3 shadow-sm-custom post-comments">';
				str += '<div class="d-flex justify-content-between align-items-center mb-2">';
				str += '<div class="d-flex align-items-center">';
				str += '<div class="comment-avatar mr-2"><i class="fa fa-user-circle-o fa-2x text-primary"></i></div>';
				str += '<strong class="text-dark">' + row.comment_author + '</strong>';
				str += '</div>';
				str += '<small class="text-muted"><i class="fa fa-clock-o mr-1"></i>' + row.created_at.substr(8, 2) + '/' + row.created_at.substr(5, 2) + '/' + row.created_at.substr(0, 4) + ' ' + row.created_at.substr(11, 5) + '</small>';
				str += '</div>';
				str += '<div class="comment-body text-muted small pl-4 ml-2 border-left">';
				str += row.comment_content;
				str += '</div>';
				str += '</div>';
			}
			var elementId = $(".post-comments:last");
			$( str ).insertAfter( elementId );
			if ( page == parseInt(total_page) ) $('.more-comments').remove();
		});
	}
}
</script>

<!-- CONTENT -->
<div class="col-lg-8 col-md-7 col-sm-12">
	<!-- Detail Artikel Utama -->
	<article class="article-detail-card bg-white rounded-custom shadow-sm-custom border overflow-hidden mb-4">
		<?php if ($post_type == 'post' && file_exists('./media_library/posts/large/'.$query->post_image)) { ?>
			<div class="article-hero-image-wrapper">
				<img src="<?=base_url('media_library/posts/large/'.$query->post_image)?>" class="img-fluid w-100 article-hero-img" alt="<?=$query->post_title?>">
			</div>
		<?php } ?>
		
		<div class="article-detail-body p-4 p-md-5">
			<div class="article-meta-modern d-flex flex-wrap align-items-center text-muted small mb-3">
				<span class="mr-3 mb-2"><i class="fa fa-calendar-check-o text-primary mr-1"></i> <?=indo_date(date('Y-m-d', strtotime($query->created_at)))?></span>
				<span class="mr-3 mb-2"><i class="fa fa-user text-primary mr-1"></i> Oleh: <strong><?=$post_author?></strong></span>
				<span class="mb-2"><i class="fa fa-eye text-primary mr-1"></i> Dilihat <strong><?=$post_counter?></strong> kali</span>
			</div>

			<h1 class="article-main-title font-weight-bold text-dark mb-4">
				<?=$query->post_title?>
			</h1>

			<div class="article-rich-content text-justify">
				<?=$query->post_content?>
			</div>

			<!-- Share Buttons -->
			<div class="article-share-bar mt-5 pt-4 border-top d-flex flex-wrap align-items-center justify-content-between">
				<span class="font-weight-bold text-dark small mb-2 mb-md-0"><i class="fa fa-share-alt mr-2 text-primary"></i> Bagikan Artikel:</span>
				<div class="share-buttons-group">
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?=current_url()?>" target="_blank" class="btn btn-sm btn-outline-primary-custom mr-1"><i class="fa fa-facebook"></i> Facebook</a>
					<a href="https://twitter.com/intent/tweet?url=<?=current_url()?>&text=<?=urlencode($query->post_title)?>" target="_blank" class="btn btn-sm btn-outline-primary-custom mr-1"><i class="fa fa-twitter"></i> Twitter</a>
					<a href="https://api.whatsapp.com/send?text=<?=urlencode($query->post_title.' - '.current_url())?>" target="_blank" class="btn btn-sm btn-outline-primary-custom"><i class="fa fa-whatsapp"></i> WhatsApp</a>
				</div>
			</div>
		</div>
	</article>

	<!-- Bagian Komentar -->
	<?php if ($post_comments->getNumRows() > 0) { ?>
		<div class="article-comments-section mb-4">
			<h5 class="section-title-modern mb-3 font-weight-bold">
				<i class="fa fa-comments text-primary mr-2"></i> Komentar Pengunjung (<?=$post_comments->getNumRows()?>)
			</h5>
			
			<div class="comments-list">
				<?php foreach($post_comments->getResult() as $row) { ?>
					<div class="comment-item bg-white rounded-custom border p-3 mb-3 shadow-sm-custom post-comments">
						<div class="d-flex justify-content-between align-items-center mb-2">
							<div class="d-flex align-items-center">
								<div class="comment-avatar mr-2"><i class="fa fa-user-circle text-primary" style="font-size: 28px;"></i></div>
								<strong class="text-dark"><?=$row->comment_author?></strong>
							</div>
							<small class="text-muted"><i class="fa fa-clock-o mr-1"></i><?=date('d/m/Y H:i', strtotime($row->created_at))?></small>
						</div>
						<div class="comment-body text-muted small pl-4 ml-2 border-left">
							<?=strip_tags($row->comment_content)?>
						</div>
					</div>
					
					<?php if (! empty($row->comment_reply)) { ?>
						<div class="comment-reply-item bg-light-modern rounded-custom border p-3 mb-3 ml-4 ml-md-5 shadow-sm-custom">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<div class="d-flex align-items-center">
									<i class="fa fa-shield text-accent mr-2" style="font-size: 20px;"></i>
									<strong class="text-dark">Administrator</strong>
									<span class="badge badge-accent ml-2">Official</span>
								</div>
							</div>
							<div class="comment-body text-muted small pl-4 ml-2 border-left">
								<?=strip_tags($row->comment_reply)?>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>

			<div class="text-center my-3 more-comments">
				<button type="button" onclick="get_post_comments()" class="btn btn-outline-primary-custom btn-sm px-4">
					<i class="fa fa-refresh mr-1"></i> Tampilkan Komentar Lainnya
				</button>
			</div>
		</div>
	<?php } ?>

	<!-- Form Komentar -->
	<?php if (
		(
			$query->post_comment_status == 'open' &&
			filter_var(__session((string) 'comment_registration'), FILTER_VALIDATE_BOOLEAN) &&
			session('active')
		) ||
		(
			$query->post_comment_status == 'open' &&
			__session('discussion')['comment_registration'] == 'false'
		)
	) { ?>
		<div class="comment-form-section bg-white rounded-custom shadow-sm-custom border p-4 mb-4">
			<h5 class="font-weight-bold text-dark mb-3">
				<i class="fa fa-pencil-square-o text-primary mr-2"></i> Tinggalkan Komentar
			</h5>
			<p class="small text-muted mb-4">Alamat email Anda tidak akan dipublikasikan. Ruas yang wajib ditandai <span class="text-danger">*</span></p>
			
			<form>
				<div class="form-group row">
					<label for="comment_author" class="col-sm-3 col-form-label font-weight-bold small">Nama Lengkap <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<input type="text" class="form-control rounded-custom border" id="comment_author" name="comment_author" placeholder="Nama Anda...">
					</div>
				</div>
				<div class="form-group row">
					<label for="comment_email" class="col-sm-3 col-form-label font-weight-bold small">Alamat Email <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<input type="email" class="form-control rounded-custom border" id="comment_email" name="comment_email" placeholder="email@domain.com">
					</div>
				</div>
				<div class="form-group row">
					<label for="comment_url" class="col-sm-3 col-form-label font-weight-bold small">Website/URL</label>
					<div class="col-sm-9">
						<input type="url" class="form-control rounded-custom border" id="comment_url" name="comment_url" placeholder="https://...">
					</div>
				</div>
				<div class="form-group row">
					<label for="comment_content" class="col-sm-3 col-form-label font-weight-bold small">Komentar <span class="text-danger">*</span></label>
					<div class="col-sm-9">
						<textarea name="comment_content" class="form-control rounded-custom border" id="comment_content" rows="4" placeholder="Tuliskan komentar atau pertanyaan Anda..."></textarea>
					</div>
				</div>
				<?php if (NULL !== __session('general')['recaptcha_status'] && __session('general')['recaptcha_status'] == 'enable') { ?>
					<input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
				<?php } ?>
				<div class="form-group row mb-0">
					<div class="offset-sm-3 col-sm-9">
						<input type="hidden" name="comment_post_id" id="comment_post_id" value="<?=service('uri')->getSegment(2)?>">
						<button type="button" onclick="post_comments(); return false;" class="btn btn-primary-gradient px-4">
							<i class="fa fa-paper-plane mr-2"></i> Kirim Komentar
						</button>
					</div>
				</div>
			</form>
		</div>
	<?php } ?>

	<!-- Artikel / Halaman Terkait -->
	<?php 
	$relatedQuery = $post_type == 'post' ? get_related_posts($query->post_categories, _toInteger(service('uri')->getSegment(2))) : get_another_pages(_toInteger(service('uri')->getSegment(2)), 5); 
	if ($relatedQuery && $relatedQuery->getNumRows() > 0) { 
	?>
		<div class="related-posts-section mb-4">
			<h5 class="section-title-modern mb-3 font-weight-bold">
				<i class="fa fa-bookmark text-primary mr-2"></i> <?=$post_type == 'post' ? 'Tulisan Terkait':'Halaman Lainnya'?>
			</h5>
			<div class="row">
				<?php foreach($relatedQuery->getResult() as $row) { ?>
					<div class="col-md-6 mb-3">
						<div class="card-related h-100 bg-white rounded-custom border p-3 shadow-sm-custom transition-all">
							<h6 class="font-weight-bold mb-2 line-clamp-2">
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark hover-primary">
									<?=$row->post_title?>
								</a>
							</h6>
							<p class="text-muted small mb-2 line-clamp-2">
								<?=substr(strip_tags($row->post_content), 0, 110)?>...
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
