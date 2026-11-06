<script type="text/javascript">
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('.more-videos').remove();
		}
	});

	function get_videos() {
		page++;
		var data = {
			page_number: page
		};
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( _BASE_URL + 'public/gallery_videos/get_videos', data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="col-lg-4 col-md-6 mb-4 videos">';
					str += '<div class="card-video-modern rounded-custom overflow-hidden shadow-sm-custom border bg-white h-100">';
					str += '<div class="embed-responsive embed-responsive-16by9">';
					str += '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' + row.post_content + '" allowfullscreen></iframe>';
					str += '</div>';
					str += '<div class="p-3">';
					str += '<h6 class="font-weight-bold text-dark mb-0 line-clamp-2">' + row.post_title + '</h6>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				}
				var el = $("div.videos:last");
				if (el.length) {
					$( str ).insertAfter(el);
				} else {
					$("#videos-row").append(str);
				}
				if (page == parseInt(total_page)) $('.more-videos').remove();
			});
		}
	}
</script>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="gallery-header mb-4 text-center">
		<span class="section-tagline">Dokumentasi Audio-Visual</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="row" id="videos-row">
		<?php if ($query && $query->getNumRows() > 0) { ?>
			<?php foreach($query->getResult() as $row) { ?>
				<div class="col-lg-4 col-md-6 mb-4 videos">
					<div class="card-video-modern rounded-custom overflow-hidden shadow-sm-custom border bg-white h-100">
						<div class="embed-responsive embed-responsive-16by9">
							<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$row->post_content?>" allowfullscreen></iframe>
						</div>
						<div class="p-3">
							<h6 class="font-weight-bold text-dark mb-0 line-clamp-2"><?=$row->post_title?></h6>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="col-12">
				<div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom my-4">
					<i class="fa fa-video-camera fa-3x text-muted mb-3"></i>
					<h5 class="font-weight-bold text-dark">Belum Ada Video</h5>
					<p class="text-muted small">Saat ini belum ada dokumentasi video yang dipublikasikan.</p>
					<a href="<?=base_url()?>" class="btn btn-outline-primary-custom btn-sm">Kembali ke Beranda</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="text-center my-4 more-videos">
		<button type="button" onclick="get_videos(); return false;" class="btn btn-primary-gradient px-4">
			<i class="fa fa-refresh mr-2"></i> Tampilkan Video Lainnya
		</button>
	</div>
</div>
