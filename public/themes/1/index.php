<!DOCTYPE html>
<html lang="id">
<head>
	<title><?=isset($page_title) ? $page_title . ' | ' : ''?><?=__session('school_profile')['school_name']?></title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="keywords" content="<?=__session('general')['meta_keywords'];?>"/>
	<meta name="description" content="<?=__session('general')['meta_description'];?>"/>
	<meta name="subject" content="Situs Pendidikan">
	<meta name="copyright" content="<?=__session('school_profile')['school_name']?>">
	<meta name="language" content="Indonesia">
	<meta name="robots" content="index,follow" />
	<meta name="author" content="Webskul">
	<meta name="category" content="Admission, Education">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta itemprop="name" content="<?=__session('school_profile')['school_name'];?>" />
	<meta itemprop="description" content="<?=__session('general')['meta_description'];?>" />
	<meta itemprop="image" content="<?=base_url('media_library/images/'. __session('school_profile')['logo']);?>" />
	<meta name="csrf-token" content="<?=__session('csrf_token')?>">
	<?php if (isset($post_type) && $post_type == 'post' && isset($query)) { ?>
		<meta property="og:url" content="<?=current_url()?>" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?=$query->post_title?>" />
		<meta property="og:description" content="<?=word_limiter(strip_tags($query->post_content), 30)?>" />
		<meta property="og:image" content="<?=base_url('media_library/posts/large/'.$query->post_image)?>" />
	<?php } ?>
	<link rel="icon" href="<?=base_url('media_library/images/'.__session('general')['favicon']);?>">
	<link rel="alternate" type="application/rss+xml" title="<?=__session('school_profile')['school_name'];?> Feed" href="<?=base_url('feed')?>" />
	
	<!-- Google Fonts: Plus Jakarta Sans & Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	
	<?=link_tag('assets/plugins/bootstrap-4/bootstrap.min.css')?>
	<?=link_tag('assets/css/font-awesome.min.css')?>
	<?=link_tag('assets/plugins/toastr/toastr.css')?>
	<?=link_tag('assets/plugins/datetimepicker/datetimepicker.css');?>
	<?=link_tag('assets/plugins/jquery.smartmenus/jquery.smartmenus.bootstrap-4.css')?>
	<?=link_tag('assets/plugins/jquery.smartmenus/sm-core.css')?>
	<?=link_tag('assets/plugins/jquery.smartmenus/sm-clean.css')?>
	<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css')?>
	<?=link_tag('assets/css/loading.css')?>
	<?=link_tag('themes/1/style.css')?>
	
	<script type="text/javascript">
	const _BASE_URL = '<?=base_url();?>';
	const _CURRENT_URL = '<?=current_url();?>';
	const _RECAPTCHA_SITE_KEY = '<?=__session('general')['recaptcha_site_key'];?>';
	const _RECAPTCHA_STATUS = '<?=__captchaActivated() ? 'true': 'false';?>'=='true';
	</script>
	<script src="<?=base_url('assets/js/frontend.min.js')?>"></script>
	<?php if (__captchaActivated()) { ?>
		<style>.grecaptcha-badge{visibility: hidden}</style>
		<script src="https://www.google.com/recaptcha/api.js?render=<?=__session('general')['recaptcha_site_key']; ?>"></script>
		<script type="text/javascript">
			$( document ).ready( function() {
				if ($( 'input[name="g-recaptcha-response"]' ).length) {
					renderRecaptcha();
				}
			});
		</script>
	<?php } ?>
</head>
<body>
	<noscript>You need to enable JavaScript to run this App!</noscript>

	<!-- Top Contact Bar -->
	<div class="top-bar">
		<div class="container d-flex justify-content-between align-items-center">
			<div class="top-bar-info d-flex align-items-center">
				<span class="mr-4"><i class="fa fa-phone"></i> <?=__session('school_profile')['phone']?></span>
				<span class="mr-4 d-none d-md-inline"><i class="fa fa-envelope-o"></i> <?=__session('school_profile')['email']?></span>
			</div>
			<div class="top-bar-links d-flex align-items-center">
				<a href="<?=site_url('feed')?>" class="mr-3 top-bar-link"><i class="fa fa-rss"></i> <span class="d-none d-sm-inline">RSS</span></a>
				<a href="#search_form" class="top-bar-link"><i class="fa fa-search"></i> <span class="d-none d-sm-inline">Cari</span></a>
			</div>
		</div>
	</div>

	<!-- Header + Navigation -->
	<header class="main-header" data-toggle="sticky-menu">
		<div class="container">
			<nav class="navbar navbar-expand-lg px-0">
				<a class="navbar-brand d-flex align-items-center py-2" href="<?=base_url()?>">
					<div class="brand-logo-box mr-3">
						<img src="<?=base_url('media_library/images/' . __session('school_profile')['logo'])?>" width="46" height="46" alt="Logo" class="brand-logo-img">
					</div>
					<div class="brand-text">
						<div class="brand-name"><?=strtoupper(__session('school_profile')['school_name'])?></div>
						<small class="brand-tagline"><?=__session('school_profile')['tagline']?></small>
					</div>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul id="main-menu" class="sm sm-clean">
						<li><a href="<?=base_url()?>" title="Beranda"><i class="fa fa-home"></i></a></li>
						<?php
						$menus = get_menus();
						foreach ($menus as $menu) {
							echo '<li>';
							$url = $menu['menu_url'] == '#' ? $menu['menu_url'] : base_url() . $menu['menu_url'];
							if ($menu['menu_type'] == 'links') $url = $menu['menu_url'];
							echo '<a href="'. $url .'" target="'. $menu['menu_target'] .'">' . strtoupper($menu['menu_title']) . '</a>';
							$sub_nav = recursive_list($menu['children']);
							if ($sub_nav) echo '<ul>' . $sub_nav . '</ul>';
							echo '</li>';
						}?>
					</ul>
				</div>
			</nav>
		</div>
	</header>

	<!-- Main Content -->
	<?php if ( ! service('uri')->getSegment(1)) { ?>
		<main class="main-content-home">
			<?php echo $this->include($content)?>
		</main>
	<?php } else { ?>
		<main class="main-content">
			<div class="container">
				<div class="row">
					<?php echo $this->include($content)?>
				</div>
			</div>
		</main>
	<?php } ?>

	<!-- Footer -->
	<footer class="site-footer-modern">
		<div class="container pt-5 pb-4 text-center">
			<!-- Logo & Identitas Sekolah -->
			<div class="footer-brand-centered mb-4">
				<img src="<?=base_url('media_library/images/' . __session('school_profile')['logo'])?>" width="58" height="58" class="mb-3 footer-logo-img" alt="Logo Sekolah">
				<h4 class="footer-school-name font-weight-bold text-white mb-1"><?=__session('school_profile')['school_name']?></h4>
				<p class="footer-school-tagline text-muted small mb-0"><?=__session('school_profile')['tagline']?></p>
			</div>

			<!-- Navigasi Horizontal -->
			<nav class="footer-nav-wrapper mb-4">
				<ul class="footer-nav-horizontal list-unstyled d-flex flex-wrap justify-content-center mb-0">
					<li><a href="<?=base_url()?>">BERANDA</a></li>
					<li><a href="<?=site_url(opening_speech_route());?>">SAMBUTAN</a></li>
					<li><a href="<?=site_url('kategori/berita')?>">BERITA</a></li>
					<li><a href="<?=site_url('galeri-foto')?>">GALERI FOTO</a></li>
					<li><a href="<?=site_url('galeri-video')?>">GALERI VIDEO</a></li>
					<li><a href="<?=site_url('hubungi-kami')?>">HUBUNGI KAMI</a></li>
					<?php if (__session('admission')['admission_status'] == 'open') { ?>
						<li><a href="<?=site_url('formulir-penerimaan-peserta-didik-baru')?>" class="nav-ppdb-link">PPDB ONLINE</a></li>
					<?php } ?>
				</ul>
			</nav>

			<!-- Alamat Ringkas -->
			<p class="footer-address-text text-muted mb-4">
				<i class="fa fa-map-marker text-accent mr-2"></i> <?=__session('school_profile')['street_address']?>, <?=__session('school_profile')['village']?>, <?=__session('school_profile')['sub_district']?>, <?=__session('school_profile')['district']?>
			</p>

			<!-- Sosial Media Monokrom Minimalis -->
			<div class="footer-social-mono d-flex justify-content-center mb-4">
				<?php if (NULL !== __session('social_account')['facebook'] && __session('social_account')['facebook']) { ?>
					<a href="<?=__session('social_account')['facebook']?>" title="Facebook" class="social-icon-mono" target="_blank"><i class="fa fa-facebook"></i></a>
				<?php } ?>
				<?php if (NULL !== __session('social_account')['twitter'] && __session('social_account')['twitter']) { ?>
					<a href="<?=__session('social_account')['twitter']?>" title="Twitter" class="social-icon-mono" target="_blank"><i class="fa fa-twitter"></i></a>
				<?php } ?>
				<?php if (NULL !== __session('social_account')['instagram'] && __session('social_account')['instagram']) { ?>
					<a href="<?=__session('social_account')['instagram']?>" title="Instagram" class="social-icon-mono" target="_blank"><i class="fa fa-instagram"></i></a>
				<?php } ?>
				<?php if (NULL !== __session('social_account')['youtube'] && __session('social_account')['youtube']) { ?>
					<a href="<?=__session('social_account')['youtube']?>" title="Youtube" class="social-icon-mono" target="_blank"><i class="fa fa-youtube-play"></i></a>
				<?php } ?>
			</div>

			<!-- Garis Pemisah & Copyright -->
			<div class="footer-divider-modern mb-4"></div>
			
			<div class="footer-bottom-modern d-flex flex-column flex-md-row justify-content-between align-items-center text-muted small">
				<div class="copyright-text mb-2 mb-md-0">
					<?=copyright(2017, base_url(), __session('school_profile')['school_name'])?>
				</div>
				<div class="powered-by-text">
					Portal Manajemen Sekolah Modern &bull; <a href="http://webskul.web.id" target="_blank" class="text-muted hover-white">Webskul</a>
				</div>
			</div>
		</div>
	</footer>

	<!-- Search Overlay -->
	<div id="search_form">
		<button type="button" class="close text-white search-overlay-close-btn" aria-label="Close" onclick="$('#search_form').removeClass('open');">&times;</button>
		<form action="<?=site_url('hasil-pencarian')?>" method="POST">
			<div class="search-input-wrapper position-relative">
				<input type="search_form" name="keyword" autocomplete="off" placeholder="Ketik kata kunci pencarian..." />
				<button type="submit" class="btn btn-search-overlay-submit"><i class="fa fa-search mr-2"></i> CARI</button>
			</div>
		</form>
	</div>

	<!-- Return to Top Floating Button -->
	<a href="javascript:" id="return-to-top" title="Kembali ke atas"><i class="fa fa-chevron-up"></i></a>

	<script>
	$(function() {
		var t = $('#main-menu');
		t.length && t.smartmenus({mainMenuSubOffsetX: 0, mainMenuSubOffsetY: 0, subMenusSubOffsetX: 0, subMenusSubOffsetY: 0});
	});
	</script>
</body>
</html>
