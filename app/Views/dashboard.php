<?php
$agent = \Config\Services::request()->getUserAgent();
$stats_primary = [
    ['label' => 'Peserta Didik',   'value' => $widget_box->students,    'icon' => 'fa-user-graduate',    'color' => 'primary',   'url' => 'academic/students'],
    ['label' => 'GTK',             'value' => $widget_box->employees,   'icon' => 'fa-id-badge',         'color' => 'success',   'url' => 'employees/employees'],
    ['label' => 'Alumni',          'value' => $widget_box->alumni,      'icon' => 'fa-user-tie',         'color' => 'info',      'url' => 'academic/alumni'],
    ['label' => 'Tulisan',         'value' => $widget_box->posts,       'icon' => 'fa-newspaper',        'color' => 'warning',   'url' => 'blog/posts'],
];
$stats_secondary = [
    ['label' => 'Halaman',         'value' => $widget_box->pages,       'icon' => 'fa-file-alt',         'color' => 'danger',    'url' => 'blog/pages'],
    ['label' => 'Komentar',        'value' => $widget_box->comments,    'icon' => 'fa-comments',         'color' => 'primary',   'url' => 'blog/post_comments'],
    ['label' => 'Pesan Masuk',     'value' => $widget_box->messages,    'icon' => 'fa-envelope',         'color' => 'success',   'url' => 'blog/messages'],
    ['label' => 'Pendaftar Baru',  'value' => $widget_box->registrants, 'icon' => 'fa-user-plus',        'color' => 'info',      'url' => 'admission/registrants'],
];
$stats_tertiary = [
    ['label' => 'Subscriber',      'value' => $widget_box->subscribers, 'icon' => 'fa-bell',            'color' => 'warning',   'url' => 'blog/subscribers'],
    ['label' => 'Kategori',        'value' => $widget_box->categories,  'icon' => 'fa-list',            'color' => 'danger',    'url' => 'blog/post_categories'],
    ['label' => 'Tags',            'value' => $widget_box->tags,        'icon' => 'fa-tags',            'color' => 'primary',   'url' => 'blog/tags'],
    ['label' => 'Tautan',          'value' => $widget_box->links,       'icon' => 'fa-link',            'color' => 'success',   'url' => 'blog/links'],
    ['label' => 'Kutipan',         'value' => $widget_box->quotes,      'icon' => 'fa-quote-right',     'color' => 'info',      'url' => 'blog/quotes'],
];
?>
<style>
.modern-dashboard { font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, sans-serif; }
.modern-dashboard .stat-card {
    position: relative; border-radius: 10px; padding: 18px 20px; color: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,.08); overflow: hidden; transition: transform .2s, box-shadow .2s;
    display: flex; align-items: center; justify-content: space-between; min-height: 92px;
}
.modern-dashboard .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.12); }
.modern-dashboard .stat-card .stat-label { font-size: 13px; opacity: .9; text-transform: uppercase; letter-spacing: .5px; font-weight: 500; }
.modern-dashboard .stat-card .stat-value { font-size: 28px; font-weight: 700; line-height: 1.2; margin-top: 4px; }
.modern-dashboard .stat-card .stat-icon {
    width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    background: rgba(255,255,255,.18); font-size: 22px; flex-shrink: 0;
}
.modern-dashboard .stat-card a { color: #fff; text-decoration: none; display: block; }
.modern-dashboard .stat-card.bg-primary  { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
.modern-dashboard .stat-card.bg-success  { background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); }
.modern-dashboard .stat-card.bg-info     { background: linear-gradient(135deg, #36b9cc 0%, #258391 100%); }
.modern-dashboard .stat-card.bg-warning  { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); }
.modern-dashboard .stat-card.bg-danger   { background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); }
.modern-dashboard .panel-modern {
    background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.05);
    border: 1px solid #e3e6f0; margin-bottom: 20px; overflow: hidden;
}
.modern-dashboard .panel-modern .panel-head {
    padding: 14px 20px; border-bottom: 1px solid #e3e6f0;
    display: flex; align-items: center; justify-content: space-between;
    background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
}
.modern-dashboard .panel-modern .panel-head h3 { margin: 0; font-size: 15px; font-weight: 600; color: #2c3e50; }
.modern-dashboard .panel-modern .panel-head h3 i { color: #4e73df; margin-right: 8px; }
.modern-dashboard .panel-modern .panel-body { padding: 18px 20px; }
.modern-dashboard .panel-modern .panel-body.p-0 { padding: 0; }
.modern-dashboard .info-grid { display: grid; grid-template-columns: 130px 1fr; row-gap: 10px; column-gap: 12px; }
.modern-dashboard .info-grid label { color: #6c757d; font-weight: 500; font-size: 13px; padding-top: 2px; }
.modern-dashboard .info-grid p { margin: 0; color: #2c3e50; font-weight: 500; font-size: 14px; }
.modern-dashboard .activity-item {
    display: flex; align-items: flex-start; padding: 12px 20px; border-bottom: 1px solid #f0f0f3;
    transition: background .15s;
}
.modern-dashboard .activity-item:hover { background: #f8f9fc; }
.modern-dashboard .activity-item:last-child { border-bottom: 0; }
.modern-dashboard .activity-item .ai-icon {
    width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 14px; flex-shrink: 0; margin-right: 12px;
}
.modern-dashboard .activity-item .ai-body { flex: 1; min-width: 0; }
.modern-dashboard .activity-item .ai-title { font-weight: 600; color: #2c3e50; font-size: 14px; margin-bottom: 2px; }
.modern-dashboard .activity-item .ai-meta { color: #858796; font-size: 12px; }
.modern-dashboard .activity-item .ai-content { color: #4a4a4a; font-size: 13px; margin-top: 4px; line-height: 1.4; }
.modern-dashboard .modern-table { width: 100%; border-collapse: collapse; }
.modern-dashboard .modern-table thead th {
    background: #f8f9fc; color: #6c757d; font-size: 12px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px; padding: 12px 20px; text-align: left; border-bottom: 1px solid #e3e6f0;
}
.modern-dashboard .modern-table tbody td { padding: 12px 20px; font-size: 14px; color: #2c3e50; border-bottom: 1px solid #f0f0f3; }
.modern-dashboard .modern-table tbody tr:hover { background: #f8f9fc; }
.modern-dashboard .modern-table tbody tr:last-child td { border-bottom: 0; }
.modern-dashboard .badge-status {
    display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase;
}
.modern-dashboard .badge-open   { background: #d1f3e0; color: #13855c; }
.modern-dashboard .badge-closed { background: #fde2e1; color: #be2617; }
.modern-dashboard .section-title {
    font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    color: #858796; margin: 18px 0 12px 0; display: flex; align-items: center;
}
.modern-dashboard .section-title::after {
    content: ''; flex: 1; height: 1px; background: #e3e6f0; margin-left: 12px;
}
.modern-dashboard .alert-dev {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border-left: 4px solid #f6c23e; border-radius: 8px; padding: 14px 18px; margin-bottom: 18px;
    color: #856404; font-size: 13px; line-height: 1.5;
}
.modern-dashboard .alert-dev h4 { margin: 0 0 6px 0; font-size: 14px; font-weight: 700; color: #6c5400; }
.modern-dashboard .server-stat {
    display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f3;
}
.modern-dashboard .server-stat:last-child { border-bottom: 0; }
.modern-dashboard .server-stat i { width: 32px; color: #4e73df; font-size: 14px; }
.modern-dashboard .server-stat .ss-label { color: #6c757d; font-size: 13px; flex: 1; }
.modern-dashboard .server-stat .ss-value { color: #2c3e50; font-weight: 600; font-size: 13px; }
@media (max-width: 767px) {
    .modern-dashboard .info-grid { grid-template-columns: 1fr; }
}
</style>

<section class="content modern-dashboard">
	<?php if ( filter_var(__session('is_super_admin'), FILTER_VALIDATE_BOOLEAN) || filter_var(__session('is_admin'), FILTER_VALIDATE_BOOLEAN)) { ?>
		<?php if (ENVIRONMENT == 'development') { ?>
			<div class="alert-dev">
				<h4><i class="fa fa-exclamation-triangle"></i> INFORMASI !</h4>
				Website masih dalam mode <b>"DEVELOPMENT"</b>. Jika website sudah online <b>SANGAT DISARANKAN</b> untuk mengubahnya menjadi mode <b>"PRODUCTION"</b>. Mode development hanya diperbolehkan jika masih dijalankan pada server offline.<br>
				Untuk mengubah menjadi mode <b>"PRODUCTION"</b>, buka file <b>INDEX.PHP</b> di root direktori Webskul. Pada baris 56, ubah tulisan <b>development</b> menjadi <b>production</b>.
			</div>
		<?php } ?>

		<div class="section-title">Data Utama</div>
		<div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
			<?php foreach ($stats_primary as $s) { ?>
				<div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0 10px; margin-bottom: 20px; flex: 0 0 25%; max-width: 25%; box-sizing: border-box;">
					<a href="<?=site_url($s['url']);?>">
						<div class="stat-card bg-<?=$s['color']?>">
							<div>
								<div class="stat-label"><?=$s['label']?></div>
								<div class="stat-value"><?=number_format($s['value']);?></div>
							</div>
							<div class="stat-icon"><i class="fa <?=$s['icon']?>"></i></div>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
		<div style="clear: both;"></div>

		<div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
			<?php foreach ($stats_secondary as $s) { ?>
				<div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0 10px; margin-bottom: 20px; flex: 0 0 25%; max-width: 25%; box-sizing: border-box;">
					<a href="<?=site_url($s['url']);?>">
						<div class="stat-card bg-<?=$s['color']?>">
							<div>
								<div class="stat-label"><?=$s['label']?></div>
								<div class="stat-value"><?=number_format($s['value']);?></div>
							</div>
							<div class="stat-icon"><i class="fa <?=$s['icon']?>"></i></div>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
		<div style="clear: both;"></div>

		<div class="section-title">Konten &amp; Engagement</div>
		<div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
			<?php foreach ($stats_tertiary as $s) { ?>
				<div class="col-lg-3 col-md-6 col-sm-6" style="padding: 0 10px; margin-bottom: 20px; flex: 0 0 25%; max-width: 25%; box-sizing: border-box;">
					<a href="<?=site_url($s['url']);?>">
						<div class="stat-card bg-<?=$s['color']?>">
							<div>
								<div class="stat-label"><?=$s['label']?></div>
								<div class="stat-value"><?=number_format($s['value']);?></div>
							</div>
							<div class="stat-icon"><i class="fa <?=$s['icon']?>"></i></div>
						</div>
					</a>
				</div>
			<?php } ?>
		</div>
		<div style="clear: both;"></div>

		<div class="panel-modern">
			<div class="panel-head">
				<h3><i class="fa fa-bullhorn"></i>Informasi Penerimaan Peserta Didik Baru</h3>
			</div>
			<div class="panel-body p-0">
				<table class="modern-table">
					<tbody>
						<tr>
							<td style="width: 25%;"><i class="fa fa-calendar text-warning"></i> &nbsp;<b>Tahun Pelajaran Aktif</b></td>
							<td style="width: 25%;"><?=__session('current_academic_year')?> / <?=__session('current_academic_semester')=='odd' ? 'Ganjil':'Genap'?></td>
							<td style="width: 25%;"><i class="fa fa-calendar-plus-o text-info"></i> &nbsp;<b>Tahun PPDB</b></td>
							<td style="width: 25%;"><?=__session('admission_year')?></td>
						</tr>
						<tr>
							<td><i class="fa fa-flag text-primary"></i> &nbsp;<b>Gelombang Pendaftaran</b></td>
							<td><?=__session('admission_phase')?></td>
							<td><i class="fa fa-power-off text-danger"></i> &nbsp;<b>Status PPDB</b></td>
							<td>
								<?php $isOpen = __session('admission')['admission_status'] == 'open'; ?>
								<span class="badge-status <?=$isOpen ? 'badge-open':'badge-closed'?>">
									<?=$isOpen ? 'Dibuka':'Ditutup'?>
								</span>
							</td>
						</tr>
						<tr>
							<td><i class="fa fa-play text-success"></i> &nbsp;<b>Tanggal Mulai</b></td>
							<td><?=indo_date(__session('admission_start_date'))?></td>
							<td><i class="fa fa-stop text-danger"></i> &nbsp;<b>Tanggal Selesai</b></td>
							<td><?=indo_date(__session('admission_end_date'))?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>

	<div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
		<div class="col-md-6" style="padding: 0 10px; margin-bottom: 20px; flex: 0 0 50%; max-width: 50%; box-sizing: border-box;">
			<div class="panel-modern">
				<div class="panel-head">
					<h3><i class="fa fa-school"></i>Profil Sekolah</h3>
				</div>
				<div class="panel-body">
					<div class="info-grid">
						<label>Nama Sekolah</label><p><?=__session('school_profile')['school_name']?></p>
						<label>NPSN</label><p><?=__session('school_profile')['npsn']?></p>
						<label>Kepala Sekolah</label><p><?=__session('school_profile')['headmaster']?></p>
						<label>Alamat</label><p><?=__session('school_profile')['street_address']?></p>
						<label>Dusun</label><p><?=__session('school_profile')['sub_village']?></p>
						<label>Kelurahan/Desa</label><p><?=__session('school_profile')['village']?></p>
						<label>Kecamatan</label><p><?=__session('school_profile')['sub_district']?></p>
						<label>Kabupaten</label><p><?=__session('school_profile')['district']?></p>
						<label>Kode Pos</label><p><?=__session('school_profile')['postal_code']?></p>
						<label>Telepon</label><p><?=__session('school_profile')['phone']?></p>
						<label>Fax</label><p><?=__session('school_profile')['fax']?></p>
						<label>Email</label><p><?=__session('school_profile')['email']?></p>
						<label>Website</label><p><?=__session('school_profile')['website']?></p>
					</div>
				</div>
			</div>

			<div class="panel-modern">
				<div class="panel-head">
					<h3><i class="fa fa-server"></i>Informasi Server</h3>
				</div>
				<div class="panel-body">
					<div class="server-stat">
						<i class="fa fa-desktop"></i>
						<div class="ss-label">Sistem Operasi</div>
						<div class="ss-value"><?=$agent->getPlatform();?></div>
					</div>
					<div class="server-stat">
						<i class="fa fa-globe"></i>
						<div class="ss-label">Browser</div>
						<div class="ss-value"><?=$agent->getBrowser();?></div>
					</div>
					<div class="server-stat">
						<i class="fa fa-code"></i>
						<div class="ss-label">Versi PHP</div>
						<div class="ss-value"><?=phpversion();?></div>
					</div>
					<div class="server-stat">
						<i class="fa fa-database"></i>
						<div class="ss-label">Database</div>
						<div class="ss-value"><?=ucwords(\Config\Database::connect()->getPlatform()).' '.\Config\Database::connect()->getVersion();?></div>
					</div>
					<div class="server-stat">
						<i class="fa fa-calendar"></i>
						<div class="ss-label">Tanggal Server</div>
						<div class="ss-value"><?=indo_date(date('Y-m-d'));?></div>
					</div>
					<div class="server-stat">
						<i class="fa fa-clock-o"></i>
						<div class="ss-label">Jam Server</div>
						<div class="ss-value" id="server-clock"><?=date('H:i:s');?></div>
					</div>
				</div>
			</div>

			<div class="panel-modern">
				<div class="panel-head">
					<h3><i class="fa fa-users"></i>Login Pengguna</h3>
				</div>
				<div class="panel-body p-0">
					<?php if (!empty($last_active)) { ?>
						<?php foreach($last_active as $idx => $row) { ?>
							<div class="activity-item">
								<div class="ai-icon bg-info"><i class="fa fa-user"></i></div>
								<div class="ai-body">
									<div class="ai-title"><?=$row->full_name;?></div>
									<div class="ai-meta"><i class="fa fa-clock-o"></i> <?=$row->last_active;?></div>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div style="padding: 30px; text-align: center; color: #858796;">Belum ada aktivitas login.</div>
					<?php } ?>
				</div>
			</div>
		</div>

		<div class="col-md-6" style="padding: 0 10px; margin-bottom: 20px; flex: 0 0 50%; max-width: 50%; box-sizing: border-box;">
			<div class="panel-modern">
				<div class="panel-head">
					<h3><i class="fa fa-newspaper-o"></i>Tulisan Terbaru</h3>
				</div>
				<div class="panel-body p-0">
					<?php
						$posts = get_latest_posts(10);
						$postRows = $posts ? $posts->getResult() : [];
					?>
					<?php if (!empty($postRows)) { ?>
						<?php foreach($postRows as $idx => $row) { ?>
							<div class="activity-item">
								<div class="ai-icon bg-primary"><i class="fa fa-file-text-o"></i></div>
								<div class="ai-body">
									<a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" target="_blank" class="ai-title" style="color:#2c3e50; text-decoration:none;"><?=$row->post_title;?></a>
									<div class="ai-meta">
										<i class="fa fa-user"></i> <?=$row->post_author?> &nbsp;&middot;&nbsp;
										<i class="fa fa-calendar"></i> <?=date('d/m/Y H:i', strtotime($row->created_at))?> &nbsp;&middot;&nbsp;
										<i class="fa fa-eye"></i> <?=$row->post_counter?> kali
									</div>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div style="padding: 30px; text-align: center; color: #858796;">Belum ada tulisan.</div>
					<?php } ?>
				</div>
			</div>

			<div class="panel-modern">
				<div class="panel-head">
					<h3><i class="fa fa-comments-o"></i>Komentar Terbaru</h3>
				</div>
				<div class="panel-body p-0">
					<?php if (!empty($recent_comments)) { ?>
						<?php foreach($recent_comments as $row) { ?>
							<div class="activity-item">
								<div class="ai-icon bg-success"><i class="fa fa-comment"></i></div>
								<div class="ai-body">
									<div class="ai-title">
										<a href="<?=$row->comment_url;?>" target="_blank" style="color:#2c3e50; text-decoration:none;"><?=$row->comment_author;?></a>
										<span style="color:#858796; font-weight:400;"> pada </span>
										<a href="<?=site_url('read/'.$row->comment_post_id.'/'.$row->post_slug);?>" target="_blank" style="color:#4e73df; text-decoration:none;"><?=$row->post_title;?></a>
									</div>
									<div class="ai-content"><?=strip_tags($row->comment_content);?></div>
								</div>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div style="padding: 30px; text-align: center; color: #858796;">Belum ada komentar.</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div style="clear: both;"></div>
</section>

<script type="text/javascript">
(function(){
	var el = document.getElementById('server-clock');
	if (el) {
		setInterval(function(){
			var d = new Date();
			var pad = function(n){ return n < 10 ? '0' + n : n; };
			el.textContent = pad(d.getHours()) + ':' + pad(d.getMinutes()) + ':' + pad(d.getSeconds());
		}, 1000);
	}
})();
</script>
