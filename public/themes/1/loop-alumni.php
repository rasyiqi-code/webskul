<script type="text/javascript">
var page = 1;
var total_page = "<?=$total_page;?>";
$(document).ready(function() {
	if (parseInt(total_page) == page || parseInt(total_page) == 0) {
		$('.more-alumni').remove();
	}
});

function get_alumni() {
	page++;
	var data = {
		page_number: page
	};
	if ( page <= parseInt(total_page) ) {
		_H.Loading( true );
		$.post( _BASE_URL + 'public/alumni_directory/get_alumni', data, function( response ) {
			_H.Loading( false );
			var res = _H.StrToObject( response );
			var rows = res.rows;
			var str = '';
			for (var z in rows) {
				var row = rows[ z ];
				str += '<div class="col-lg-6 col-md-6 mb-4 profile-alumni">';
				str += '<div class="card-profile-modern bg-white rounded-custom border shadow-sm-custom p-3 h-100">';
				str += '<div class="d-flex align-items-center">';
				str += '<div class="profile-photo-wrapper mr-3 flex-shrink-0">';
				str += '<img src="' + row.photo + '" class="rounded-circle profile-photo shadow-sm" width="85" height="85" style="object-fit: cover;" alt="' + row.full_name + '">';
				str += '</div>';
				str += '<div class="profile-info-wrapper flex-grow-1">';
				str += '<h5 class="font-weight-bold text-dark mb-1">' + row.full_name + '</h5>';
				str += '<span class="badge badge-accent mb-2">Angkatan ' + row.year_out + '</span>';
				str += '<div class="profile-details text-muted small">';
				str += '<div><i class="fa fa-id-badge mr-2 text-primary"></i> NIS: ' + (row.nis).substring(0, 4) + '*******</div>';
				str += '<div><i class="fa fa-user mr-2 text-primary"></i> Gender: ' + (row.gender == 'M' ? 'Laki-laki' : 'Perempuan') + '</div>';
				str += '<div><i class="fa fa-calendar mr-2 text-primary"></i> Periode: ' + row.year_in + ' - ' + row.year_out + '</div>';
				str += '</div>';
				str += '</div>';
				str += '</div>';
				str += '</div>';
				str += '</div>';
			}
			var elementId = $("div.profile-alumni:last");
			if (elementId.length) {
				$( str ).insertAfter( elementId );
			} else {
				$("#alumni-row").append(str);
			}
			if ( page == parseInt(total_page) ) $('.more-alumni').remove();
		});
	}
}
</script>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="alumni-header mb-4 text-center">
		<span class="section-tagline">Jejaring Alumni</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="row" id="alumni-row">
		<?php if ($query && $query->getNumRows() > 0) { ?>
			<?php foreach($query->getResult() as $row) { ?>
				<div class="col-lg-6 col-md-6 mb-4 profile-alumni">
					<div class="card-profile-modern bg-white rounded-custom border shadow-sm-custom p-3 h-100">
						<div class="d-flex align-items-center">
							<div class="profile-photo-wrapper mr-3 flex-shrink-0">
								<?php
								$photo = 'no-image.png';
								if ($row->photo && file_exists('./media_library/users/photo/'.$row->photo)) {
									$photo = $row->photo;
								}
								?>
								<img src="<?=base_url('media_library/users/photo/' . $photo)?>" class="rounded-circle profile-photo shadow-sm" width="85" height="85" style="object-fit: cover;" alt="<?=$row->full_name?>">
							</div>
							<div class="profile-info-wrapper flex-grow-1">
								<h5 class="font-weight-bold text-dark mb-1"><?=$row->full_name?></h5>
								<span class="badge badge-accent mb-2">Angkatan <?=$row->year_out?></span>
								<div class="profile-details text-muted small">
									<div><i class="fa fa-id-badge mr-2 text-primary"></i> NIS: <?=substr($row->nis, 0, 4)?>*******</div>
									<div><i class="fa fa-user mr-2 text-primary"></i> Gender: <?=$row->gender == 'M' ? 'Laki-laki' : 'Perempuan'?></div>
									<div><i class="fa fa-calendar mr-2 text-primary"></i> Periode: <?=$row->year_in?> - <?=$row->year_out?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<div class="col-12">
				<div class="empty-state-card bg-white rounded-custom border p-5 text-center shadow-sm-custom my-4">
					<i class="fa fa-graduation-cap fa-3x text-muted mb-3"></i>
					<h5 class="font-weight-bold text-dark">Data Alumni Belum Tersedia</h5>
					<p class="text-muted small">Saat ini belum ada data alumni yang dipublikasikan.</p>
					<a href="<?=base_url()?>" class="btn btn-outline-primary-custom btn-sm">Kembali ke Beranda</a>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="text-center my-4 more-alumni">
		<button type="button" onclick="get_alumni()" class="btn btn-primary-gradient px-4">
			<i class="fa fa-refresh mr-2"></i> Tampilkan Lebih Banyak
		</button>
	</div>
</div>
