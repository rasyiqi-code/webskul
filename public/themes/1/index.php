<!DOCTYPE html>
<html lang="en">
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
	<meta name="revised" content="Sunday, July 18th, 2010, 5:15 pm" />
	<meta name="Classification" content="Education">
	<meta name="author" content="Rasyiqi, no-reply@webskul.web.id">
	<meta name="designer" content="Rasyiqi, no-reply@webskul.web.id">
	<meta name="reply-to" content="no-reply@webskul.web.id">
	<meta name="owner" content="Rasyiqi">
	<meta name="url" content="https://www.webskul.web.id">
	<meta name="identifier-URL" content="https://www.webskul.web.id">
	<meta name="category" content="Admission, Education">
	<meta name="coverage" content="Worldwide">
	<meta name="distribution" content="Global">
	<meta name="rating" content="General">
	<meta name="revisit-after" content="7 days">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Copyright" content="<?=__session('school_profile')['school_name'];?>" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="revisit-after" content="7" />
	<meta name="webcrawlers" content="all" />
	<meta name="rating" content="general" />
	<meta name="spiders" content="all" />
	<meta itemprop="name" content="<?=__session('school_profile')['school_name'];?>" />
	<meta itemprop="description" content="<?=__session('general')['meta_description'];?>" />
	<meta itemprop="image" content="<?=base_url('media_library/images/'. __session('school_profile')['logo']);?>" />
	<meta name="csrf-token" content="<?=__session('csrf_token')?>">
	<?php if (isset($post_type) && $post_type == 'post') { ?>
		<meta property="og:url" content="<?=current_url()?>" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?=$query->post_title?>" />
		<meta property="og:description" content="<?=word_limiter(strip_tags($query->post_content), 30)?>" />
		<meta property="og:image" content="<?=base_url('media_library/posts/large/'.$query->post_image)?>" />
	<?php } ?>
	<link rel="icon" href="<?=base_url('media_library/images/'.__session('general')['favicon']);?>">
	<link rel="alternate" type="application/rss+xml" title="<?=__session('school_profile')['school_name'];?> Feed" href="<?=base_url('feed')?>" />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
	<noscript>You need to enable javaScript to run this App!!!</noscript>

	<!-- Top Contact Bar -->
	<div class="top-bar">
		<div class="container d-flex justify-content-between align-items-center">
			<div class="top-bar-info">
				<span class="mr-4"><i class="fa fa-phone"></i> <?=__session('school_profile')['phone']?></span>
				<span class="mr-4 d-none d-md-inline"><i class="fa fa-envelope"></i> <?=__session('school_profile')['email']?></span>
			</div>
			<div class="top-bar-links">
				<a href="<?=site_url('feed')?>" class="mr-3"><i class="fa fa-rss"></i> RSS</a>
				<a href="#search_form"><i class="fa fa-search"></i> Cari</a>
			</div>
		</div>
	</div>

	<!-- Header + Navigation -->
	<header class="main-header" data-toggle="sticky-menu">
		<div class="container">
			<nav class="navbar navbar-expand-lg px-0">
				<a class="navbar-brand d-flex align-items-center py-0" href="<?=base_url()?>">
					<img src="<?=base_url('media_library/images/' . __session('school_profile')['logo'])?>" width="45" height="45" class="mr-3" style="object-fit:contain">
					<div class="brand-text">
						<div class="brand-name"><?=strtoupper(__session('school_profile')['school_name'])?></div>
						<small class="brand-tagline"><?=__session('school_profile')['tagline']?></small>
					</div>
				</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNavDropdown">
					<ul id="main-menu" class="sm sm-clean">
						<li><a href="<?=base_url()?>"><i class="fa fa-home"></i></a></li>
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
	<footer class="site-footer">
		<div class="footer-widgets">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
						<div class="footer-brand">
							<img src="<?=base_url('media_library/images/' . __session('school_profile')['logo'])?>" width="50" height="50" class="mb-3" style="object-fit:contain">
							<h5><?=__session('school_profile')['school_name']?></h5>
							<p class="text-muted"><?=__session('school_profile')['tagline']?></p>
							<p class="mb-0"><i class="fa fa-map-marker mr-2"></i> <?=__session('school_profile')['street_address']?><br>
							<?=__session('school_profile')['village']?>, <?=__session('school_profile')['sub_district']?>, <?=__session('school_profile')['district']?></p>
						</div>
					</div>
					<div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
						<h6 class="footer-title">KONTAK</h6>
						<ul class="footer-contact list-unstyled">
							<li><i class="fa fa-phone"></i> <?=__session('school_profile')['phone']?></li>
							<li><i class="fa fa-fax"></i> <?=__session('school_profile')['fax']?></li>
							<li><i class="fa fa-envelope"></i> <?=__session('school_profile')['email']?></li>
						</ul>
						<h6 class="footer-title mt-4">IKUTI KAMI</h6>
						<div class="social-links">
							<?php if (NULL !== __session('social_account')['facebook'] && __session('social_account')['facebook']) { ?>
								<a href="<?=__session('social_account')['facebook']?>" title="Facebook" class="social-icon facebook"><i class="fa fa-facebook"></i></a>
							<?php } ?>
							<?php if (NULL !== __session('social_account')['twitter'] && __session('social_account')['twitter']) { ?>
								<a href="<?=__session('social_account')['twitter']?>" title="Twitter" class="social-icon twitter"><i class="fa fa-twitter"></i></a>
							<?php } ?>
							<?php if (NULL !== __session('social_account')['instagram'] && __session('social_account')['instagram']) { ?>
								<a href="<?=__session('social_account')['instagram']?>" title="Instagram" class="social-icon instagram"><i class="fa fa-instagram"></i></a>
							<?php } ?>
							<?php if (NULL !== __session('social_account')['youtube'] && __session('social_account')['youtube']) { ?>
								<a href="<?=__session('social_account')['youtube']?>" title="Youtube" class="social-icon youtube"><i class="fa fa-youtube"></i></a>
							<?php } ?>
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<h6 class="footer-title">TAGS</h6>
						<div class="tag-cloud">
							<?php $query = get_tags(15); if ($query->getNumRows() > 0) { ?>
								<?php foreach ($query->getResult() as $row) { ?>
									<a href="<?=site_url('tag/'.$row->slug)?>" class="tag-link"><?=$row->tag?></a>
								<?php } ?>
							<?php } ?>
						</div>
						<?php if (__session('admission')['admission_status'] == 'open') { ?>
							<div class="footer-cta mt-4">
								<a class="btn btn-ppdb" href="<?=site_url('formulir-penerimaan-peserta-didik-baru')?>">
									<i class="fa fa-user-plus mr-2"></i> PPDB <?=__session('admission_year')?> — DAFTAR SEKARANG
								</a>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="container">
				<div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
					<div class="copyright">
						<?=copyright(2017, base_url(), __session('school_profile')['school_name'])?>
					</div>
					<div class="powered-by">
						Powered by <a href="http://webskul.web.id">webskul.web.id</a>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- Search Overlay -->
	<div id="search_form">
		<form action="<?=site_url('hasil-pencarian')?>" method="POST">
			<input type="search_form" name="keyword" autocomplete="off" placeholder="Masukan kata kunci pencarian" />
			<button type="submit" class="btn btn-lg btn-outline-light"><i class="fa fa-search"></i> CARI</button>
		</form>
	</div>

	<a href="javascript:" id="return-to-top"><i class="fa fa-angle-double-up"></i></a>
	<script>
	$(function() {
		var t = $('#main-menu');
		t.length && t.smartmenus({mainMenuSubOffsetX: 0, mainMenuSubOffsetY: 0, subMenusSubOffsetX: 0, subMenusSubOffsetY: 0});
	});
	</script>
</body>
</html>