<script type="text/javascript">
	function strip_tags(input) {
		return input.replace(/(<([^>]+)>)/ig,"");
	}
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('div.more-posts').remove();
		}
	});

	function get_posts() {
		page++;
		var segment_1 = '<?=service('uri')->getSegment(1)?>';
		var segment_2 = '<?=service('uri')->getSegment(2)?>';
		var segment_3 = '<?=service('uri')->getSegment(3)?>';
		var url = '';
		var data = {
			page_number: page
		};
		if (segment_1 == 'kategori') {
			data['category_slug'] = segment_2;
			url = _BASE_URL + 'public/post_categories/get_posts';
		} else if (segment_1 == 'tag') {
			data['tag'] = segment_2;
			url = _BASE_URL + 'public/post_tags/get_posts';
		} else if (segment_1 == 'arsip') {
			data['year'] = segment_2;
			data['month'] = segment_3;
			url = _BASE_URL + 'public/archives/get_posts';
		}
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( url, data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<article class="card-archive-item bg-white rounded-custom border shadow-sm-custom overflow-hidden mb-4 posts">';
					str += '<div class="row no-gutters align-items-center">';
					str += '<div class="col-md-5">';
					str += '<div class="card-archive-img-box">';
					str += '<img src="' + _BASE_URL + 'media_library/posts/medium/' + row.post_image + '" class="card-archive-img" alt="' + row.post_title + '">';
					str += '</div>';
					str += '</div>';
					str += '<div class="col-md-7">';
					str += '<div class="card-body p-4">';
					str += '<div class="card-meta-inline text-muted small mb-2">';
					str += '<span class="mr-3"><i class="fa fa-calendar-o mr-1 text-primary"></i> ' + row.created_at.substr(8, 2) + '/' + row.created_at.substr(5, 2) + '/' + row.created_at.substr(0, 4) + '</span>';
					str += '<span><i class="fa fa-eye mr-1 text-primary"></i> ' + row.post_counter + ' kali</span>';
					str += '</div>';
					str += '<h5 class="card-title font-weight-bold mb-2"><a href="' + _BASE_URL + 'read/' + row.id + '/' + row.post_slug + '" class="text-dark hover-primary">' + row.post_title + '</a></h5>';
					str += '<p class="card-text text-muted small mb-3">' + strip_tags(row.post_content, '').substr(0, 140) + '...</p>';
					str += '<a href="' + _BASE_URL + 'read/' + row.id + '/' + row.post_slug + '" class="read-more-link font-weight-bold">Baca Selengkapnya <i class="fa fa-long-arrow-right ml-1"></i></a>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
					str += '</article>';
				}
				var elementId = $("article.posts:last");
				if (elementId.length) {
					$( str ).insertAfter( elementId );
				} else {
					$("div.posts-container").append(str);
				}
				if (page == parseInt(total_page)) $('div.more-posts').remove();
			});
		}
	}
</script>

<div class="col-lg-8 col-md-7 col-sm-12">
	<div class="archive-header mb-4">
		<span class="section-tagline">Arsip & Kategori</span>
		<h2 class="archive-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider"></div>
	</div>

	<div class="posts-container">
		<?php if ($query && $query->getNumRows() > 0) { ?>
			<?php foreach($query->getResult() as $row) { ?>
				<article class="card-archive-item bg-white rounded-custom border shadow-sm-custom overflow-hidden mb-4 posts">
					<div class="row no-gutters align-items-center">
						<div class="col-md-5">
							<div class="card-archive-img-box position-relative">
								<img src="<?=base_url('media_library/posts/medium/'.$row->post_image)?>" class="card-archive-img" alt="<?=$row->post_title?>">
								<div class="card-archive-date-badge">
									<span class="day"><?=date('d', strtotime($row->created_at))?></span>
									<span class="month"><?=bulan(date('m', strtotime($row->created_at)))?></span>
								</div>
							</div>
						</div>
						<div class="col-md-7">
							<div class="card-body p-4">
								<div class="card-meta-inline text-muted small mb-2">
									<span class="mr-3"><i class="fa fa-user-o mr-1 text-primary"></i> <?=$row->post_author?></span>
									<span><i class="fa fa-eye mr-1 text-primary"></i> <?=$row->post_counter?> kali</span>
								</div>
								<h5 class="card-title font-weight-bold mb-2">
									<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark hover-primary">
										<?=$row->post_title?>
									</a>
								</h5>
								<p class="card-text text-muted small mb-3">
									<?=substr(strip_tags($row->post_content), 0, 140)?>...
								</p>
								<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="read-more-link font-weight-bold">
									Baca Selengkapnya <i class="fa fa-long-arrow-right ml-1"></i>
								</a>
							</div>
						</div>
					</div>
				</article>
			<?php } ?>
		<?php } else { ?>
			<div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom">
				<i class="fa fa-folder-open-o fa-3x text-muted mb-3"></i>
				<h5 class="font-weight-bold text-dark">Belum Ada Informasi</h5>
				<p class="text-muted small">Saat ini belum ada artikel atau tulisan yang diterbitkan dalam kategori ini.</p>
				<a href="<?=base_url()?>" class="btn btn-outline-primary-custom btn-sm">Kembali ke Beranda</a>
			</div>
		<?php } ?>
	</div>

	<div class="text-center my-4 more-posts">
		<button type="button" onclick="get_posts()" class="btn btn-primary-gradient px-4">
			<i class="fa fa-refresh mr-2"></i> Tampilkan Lebih Banyak
		</button>
	</div>
</div>
<?php echo $this->include('themes/1/sidebar')?>
