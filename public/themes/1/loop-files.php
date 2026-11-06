<script type="text/javascript">
	var page = 1;
	var total_page = "<?=$total_page;?>";
	$(document).ready(function() {
		if (parseInt(total_page) == page || parseInt(total_page) == 0) {
			$('.more-files').remove();
		}
	});
	function get_files() {
		page++;
		var data = {
			page_number: page,
			slug: '<?=service('uri')->getSegment(2)?>'
		};
		if ( page <= parseInt(total_page) ) {
			_H.Loading( true );
			$.post( _BASE_URL + 'public/download/get_files', data, function( response ) {
				_H.Loading( false );
				var res = _H.StrToObject( response );
				var rows = res.rows;
				var str = '';
				var no = parseInt($('.number:last').text()) + 1;
				for (var z in rows) {
					var row = rows[ z ];
					str += '<tr>';
					str += '<td class="number text-center font-weight-bold text-muted">' + no + '</td>';
					str += '<td><i class="fa fa-file-text-o text-primary mr-2"></i>' + row.file_title + '</td>';
					str += '<td><span class="badge badge-light-custom">' + (_H.FormatBytes(row.file_size * 1024)) + '</span></td>';
					str += '<td><span class="badge badge-accent">' + row.file_ext + '</span></td>';
					str += '<td>' + row.file_counter + ' Kali</td>';
					str += '<td class="text-center">';
					str += '<a href="' + _BASE_URL + 'public/download/force_download/' + row.id + '" class="btn btn-sm btn-outline-primary-custom" title="Unduh File"><i class="fa fa-download"></i></a>';
					str += '</td>';
					str += '</tr>';
					no++;
				}
				var elementId = $("tbody > tr:last");
				if (elementId.length) {
					$( str ).insertAfter( elementId );
				} else {
					$("tbody").append(str);
				}
				if ( page == parseInt(total_page) ) $('.more-files').remove();
			});
		}
	}
</script>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="files-header mb-4 text-center">
		<span class="section-tagline">Pusat Unduhan</span>
		<h2 class="section-title font-weight-bold text-dark"><?=strtoupper($page_title);?></h2>
		<div class="section-divider mx-auto"></div>
	</div>

	<div class="table-card bg-white rounded-custom shadow-sm-custom border overflow-hidden mb-4">
		<div class="table-responsive">
			<table class="table table-hover align-middle mb-0">
				<thead class="bg-light-modern text-muted text-uppercase small">
					<tr>
						<th width="60px" class="text-center border-top-0">NO</th>
						<th class="border-top-0">NAMA DOKUMEN</th>
						<th class="border-top-0">UKURAN</th>
						<th class="border-top-0">FORMAT</th>
						<th class="border-top-0">DIUNDUH</th>
						<th width="80px" class="text-center border-top-0">AKSI</th>
					</tr>
				</thead>
				<tbody>
					<?php if ($query && $query->getNumRows() > 0) { ?>
						<?php $no = 1; foreach($query->getResult() as $row) { ?>
							<tr>
								<td class="number text-center font-weight-bold text-muted"><?=$no?></td>
								<td><i class="fa fa-file-text-o text-primary mr-2"></i><strong><?=$row->file_title?></strong></td>
								<td><span class="badge badge-light-custom"><?=filesize_formatted($row->file_size * 1024)?></span></td>
								<td><span class="badge badge-accent"><?=$row->file_ext?></span></td>
								<td><?=$row->file_counter?> Kali</td>
								<td class="text-center">
									<a href="<?=site_url('public/download/force_download/'.$row->id)?>" class="btn btn-sm btn-outline-primary-custom" title="Unduh File">
										<i class="fa fa-download"></i>
									</a>
								</td>
							</tr>
							<?php $no++; } ?>
					<?php } else { ?>
						<tr>
							<td colspan="6" class="text-center py-4 text-muted">
								<i class="fa fa-info-circle mr-1"></i> Belum ada dokumen atau berkas unduhan saat ini.
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="text-center my-4 more-files">
		<button type="button" onclick="get_files()" class="btn btn-primary-gradient px-4">
			<i class="fa fa-refresh mr-2"></i> Tampilkan Dokumen Lainnya
		</button>
	</div>
</div>
