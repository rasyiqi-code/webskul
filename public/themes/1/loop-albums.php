<script type="text/javascript">
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('.more-albums').remove();
		}
	});
	function get_albums() {
		page++;
		var data = {
			page_number: page
		};
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( _BASE_URL + 'public/gallery_photos/get_albums', data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				for (var z in rows) {
					var row = rows[ z ];
					str += '<div class="col-lg-4 col-md-6 mb-4 albums">';
					str += '<div class="card-gallery-modern bg-white rounded-custom border shadow-sm-custom overflow-hidden h-100 transition-all">';
					str += '<div class="card-gallery-img-box position-relative">';
					str += '<img src="' + _BASE_URL + 'media_library/albums/' + row.album_cover + '" class="card-gallery-img" alt="' + row.album_title + '">';
					str += '<button type="button" onclick="photo_preview(' + row.id + ')" class="btn-gallery-zoom-overlay"><i class="fa fa-search-plus"></i></button>';
					str += '</div>';
					str += '<div class="card-body p-4">';
					str += '<h5 class="card-title font-weight-bold mb-2">' + row.album_title + '</h5>';
					str += '<p class="card-text text-muted small mb-3">' + row.album_description + '</p>';
					str += '<button type="button" onclick="photo_preview(' + row.id + ')" class="btn btn-outline-primary-custom btn-sm btn-block"><i class="fa fa-camera mr-2"></i> Buka Album</button>';
					str += '</div>';
					str += '</div>';
					str += '</div>';
				}
				var elementId = $("div.albums:last");
				if (elementId.length) {
					$( str ).insertAfter( elementId );
				} else {
					$("#albums-row").append(str);
				}
				if (page == parseInt(total_page)) $('.more-albums').remove();
			});
		}
	}
</script>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="gallery-header mb-4 text-center">
		<span class="section-tagline">Dokumentasi & Galeri</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="row" id="albums-row">
		<?php if ($query && $query->getNumRows() > 0) { ?>
			<?php foreach($query->getResult() as $row) { ?>
				<div class="col-lg-4 col-md-6 mb-4 albums">
					<div class="card-gallery-modern bg-white rounded-custom border shadow-sm-custom overflow-hidden h-100 transition-all">
						<div class="card-gallery-img-box position-relative">
							<img src="<?=base_url('media_library/albums/'.$row->album_cover)?>" class="card-gallery-img" alt="<?=$row->album_title?>">
							<button type="button" onclick="photo_preview(<?=$row->id?>)" class="btn-gallery-zoom-overlay" title="Lihat Foto">
								<i class="fa fa-search-plus"></i>
							</button>
						</div>
						<div class="card-body p-4">
							<h5 class="card-title font-weight-bold mb-2"><?=$row->album_title?></h5>
							<p class="card-text text-muted small mb-3"><?=$row->album_description?></p>
							<button type="button" onclick="photo_preview(<?=$row->id?>)" class="btn btn-outline-primary-custom btn-sm btn-block">
								<i class="fa fa-camera mr-2"></i> Buka Album Foto
							</button>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="col-12">
				<div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom my-4">
					<i class="fa fa-picture-o fa-3x text-muted mb-3"></i>
					<h5 class="font-weight-bold text-dark">Belum Ada Album Foto</h5>
					<p class="text-muted small">Saat ini belum ada dokumentasi foto kegiatan yang dipublikasikan.</p>
					<a href="<?=base_url()?>" class="btn btn-outline-primary-custom btn-sm">Kembali ke Beranda</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="text-center my-4 more-albums">
		<button type="button" onclick="get_albums(); return false;" class="btn btn-primary-gradient px-4">
			<i class="fa fa-refresh mr-2"></i> Tampilkan Album Lainnya
		</button>
	</div>
</div>
