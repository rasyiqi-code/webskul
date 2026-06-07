<?php
/**
 * Template Halaman Utama (Homepage) - Landing Page Modern
 * Tema 1 - Webskul
 */
?>

<!-- 1. HERO SECTION (CAROUSEL) -->
<?php $sliders = get_image_sliders(); if ($sliders->getNumRows() > 0) { ?>
    <section class="hero-section">
        <div id="landingHeroCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="6000">
            <ol class="carousel-indicators">
                <?php $idx = 0; foreach($sliders->getResult() as $row) { ?>
                    <li data-target="#landingHeroCarousel" data-slide-to="<?=$idx?>" <?=$idx == 0 ? 'class="active"' : ''?>></li>
                <?php $idx++; } ?>
            </ol>
            <div class="carousel-inner">
                <?php $idx = 0; foreach($sliders->getResult() as $row) { ?>
                    <div class="carousel-item <?=$idx == 0 ? 'active' : ''?>">
                        <div class="hero-slide-image" style="background-image: linear-gradient(180deg, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0.85) 100%), url('<?=base_url('media_library/image_sliders/'.$row->image);?>');"></div>
                        <div class="carousel-caption d-flex align-items-center">
                            <div class="container text-left">
                                <div class="row">
                                    <div class="col-lg-8 col-md-10">
                                        <span class="badge badge-accent mb-3 text-uppercase tracking-wider">Selamat Datang</span>
                                        <h1 class="hero-title font-weight-bold text-white mb-3">
                                            <?=__session('school_profile')['school_name']?>
                                        </h1>
                                        <p class="hero-subtitle text-light mb-4">
                                            <?=$row->caption;?>
                                        </p>
                                        <div class="hero-cta-buttons">
                                            <a href="#about-section" class="btn btn-primary-gradient mr-3">
                                                <i class="fa fa-info-circle mr-2"></i> Pelajari Selengkapnya
                                            </a>
                                            <?php if (__session('admission')['admission_status'] == 'open') { ?>
                                                <a href="<?=site_url('formulir-penerimaan-peserta-didik-baru')?>" class="btn btn-outline-light-custom">
                                                    <i class="fa fa-user-plus mr-2"></i> PPDB Online
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php $idx++; } ?>
            </div>
            <a class="carousel-control-prev" href="#landingHeroCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Sebelumnya</span>
            </a>
            <a class="carousel-control-next" href="#landingHeroCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Berikutnya</span>
            </a>
        </div>
    </section>
<?php } ?>

<!-- 2. QUOTE TICKER (JIKA ADA) -->
<?php $quotes = get_quotes(); if ($quotes->getNumRows() > 0) { ?>
    <section class="ticker-quote-bar py-3 bg-white border-bottom">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="ticker-badge px-3 py-1 mr-3 text-white text-uppercase font-weight-bold">
                    <i class="fa fa-quote-left mr-2"></i> Inspirasi
                </div>
                <div class="ticker-wrapper flex-grow-1 overflow-hidden position-relative">
                    <ul id="quote-ticker-list" class="list-unstyled mb-0">
                        <?php $qIdx = 0; foreach($quotes->getResult() as $row) { ?>
                            <li class="ticker-item <?=$qIdx == 0 ? 'active' : ''?>">
                                "<?=$row->quote?>" — <span class="font-weight-bold text-primary"><?=$row->quote_by?></span>
                            </li>
                        <?php $qIdx++; } ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            let items = $('#quote-ticker-list .ticker-item');
            if (items.length > 1) {
                let current = 0;
                setInterval(function() {
                    $(items[current]).removeClass('active');
                    current = (current + 1) % items.length;
                    $(items[current]).addClass('active');
                }, 5000);
            }
        });
    </script>
<?php } ?>

<!-- 3. KEUNGGULAN SEKOLAH (FEATURES SECTION) -->
<section id="features-section" class="py-5 bg-light-modern">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-tagline">Mengapa Memilih Kami?</span>
            <h2 class="section-title font-weight-bold">Keunggulan Utama Sekolah</h2>
            <div class="section-divider mx-auto"></div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card-feature text-center p-4">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <h4 class="font-weight-bold mb-2">Kurikulum Unggulan</h4>
                    <p class="text-muted">Kurikulum adaptif berbasis kompetensi global untuk membekali siswa dengan ilmu pengetahuan dan karakter mulia.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card-feature text-center p-4">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="fa fa-flask"></i>
                    </div>
                    <h4 class="font-weight-bold mb-2">Fasilitas Modern</h4>
                    <p class="text-muted">Laboratorium teknologi, perpustakaan multimedia, serta sarana olahraga yang representatif mendukung kenyamanan belajar.</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card-feature text-center p-4">
                    <div class="feature-icon-wrapper mb-3 mx-auto">
                        <i class="fa fa-trophy"></i>
                    </div>
                    <h4 class="font-weight-bold mb-2">Prestasi & Ekstrakurikuler</h4>
                    <p class="text-muted">Beragam kegiatan non-akademik aktif guna mengasah minat, bakat, kepemimpinan, dan meraih prestasi tingkat nasional.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. SAMBUTAN KEPALA SEKOLAH (ABOUT SECTION) -->
<section id="about-section" class="py-5 bg-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 text-center mb-4 mb-lg-0">
                <div class="about-image-frame position-relative d-inline-block">
                    <img src="<?=base_url('media_library/images/').__session('school_profile')['headmaster_photo'];?>" class="img-fluid rounded-custom shadow-lg-custom" alt="<?=__session('school_profile')['headmaster']?>">
                    <div class="about-image-badge text-left">
                        <h5 class="m-0 text-white font-weight-bold"><?=__session('school_profile')['headmaster']?></h5>
                        <small class="text-accent">- Kepala Sekolah -</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 pl-lg-5">
                <span class="section-tagline">Sambutan Hangat</span>
                <h2 class="section-title font-weight-bold mb-3">Sambutan Kepala Sekolah</h2>
                <div class="section-divider mb-4"></div>
                <div class="speech-content text-justify text-muted lead-custom">
                    <?=word_limiter(strip_tags(get_opening_speech()), 120);?>
                </div>
                <div class="mt-4">
                    <a href="<?=site_url(opening_speech_route());?>" class="btn btn-primary-gradient">
                        Baca Selengkapnya <i class="fa fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 5. STATISTIK SEKOLAH -->
<section class="py-5 stats-section text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-box">
                    <div class="stat-icon mb-2"><i class="fa fa-users"></i></div>
                    <h2 class="font-weight-bold counter-value">1,200+</h2>
                    <p class="text-light-50 m-0">Siswa Aktif</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4 mb-md-0">
                <div class="stat-box">
                    <div class="stat-icon mb-2"><i class="fa fa-certificate"></i></div>
                    <h2 class="font-weight-bold counter-value">A</h2>
                    <p class="text-light-50 m-0">Akreditasi</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-icon mb-2"><i class="fa fa-briefcase"></i></div>
                    <h2 class="font-weight-bold counter-value">80+</h2>
                    <p class="text-light-50 m-0">Tenaga Pendidik</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-icon mb-2"><i class="fa fa-smile-o"></i></div>
                    <h2 class="font-weight-bold counter-value">25+</h2>
                    <p class="text-light-50 m-0">Ekstrakurikuler</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 6. TULISAN/BERITA TERBARU -->
<?php $latestPosts = get_latest_posts(3); if ($latestPosts->getNumRows() > 0) { ?>
    <section class="py-5 bg-light-modern">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <span class="section-tagline">Kabar Terbaru</span>
                    <h2 class="section-title font-weight-bold mb-0">Tulisan & Informasi Terkini</h2>
                </div>
                <a href="<?=site_url('kategori/berita')?>" class="btn btn-outline-primary-custom d-none d-md-inline-block">
                    Lihat Semua Berita <i class="fa fa-chevron-right ml-1"></i>
                </a>
            </div>
            <div class="section-divider mb-5"></div>
            <div class="row">
                <?php foreach($latestPosts->getResult() as $row) { ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card-news h-100 shadow-sm-custom border-0 bg-white">
                            <div class="card-news-img-wrapper position-relative">
                                <img src="<?=base_url('media_library/posts/medium/'.$row->post_image)?>" class="card-img-top-news" alt="<?=$row->post_title?>">
                                <div class="card-news-date">
                                    <span class="day"><?=date('d', strtotime($row->created_at))?></span>
                                    <span class="month"><?=bulan(date('m', strtotime($row->created_at)))?></span>
                                </div>
                            </div>
                            <div class="card-news-body p-4">
                                <div class="card-news-meta mb-2">
                                    <span class="mr-3"><i class="fa fa-user text-primary mr-1"></i> <?=$row->post_author?></span>
                                    <span><i class="fa fa-eye text-primary mr-1"></i> <?=$row->post_counter?> kali</span>
                                </div>
                                <h5 class="card-news-title font-weight-bold mb-3">
                                    <a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="text-dark-blue">
                                        <?=word_limiter($row->post_title, 8)?>
                                    </a>
                                </h5>
                                <p class="card-news-excerpt text-muted mb-4">
                                    <?=substr(strip_tags($row->post_content), 0, 100)?>...
                                </p>
                                <a href="<?=site_url('read/'.$row->id.'/'.$row->post_slug)?>" class="read-more-link font-weight-bold">
                                    Selengkapnya <i class="fa fa-long-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="text-center mt-3 d-md-none">
                <a href="<?=site_url('kategori/berita')?>" class="btn btn-outline-primary-custom btn-block">
                    Lihat Semua Berita <i class="fa fa-chevron-right ml-1"></i>
                </a>
            </div>
        </div>
    </section>
<?php } ?>

<!-- 7. GALERI FOTO & VIDEO TERBARU -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <!-- Grid Album Foto -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="mb-4">
                    <span class="section-tagline">Galeri Dokumentasi</span>
                    <h3 class="section-title font-weight-bold">Album Foto Kegiatan</h3>
                    <div class="section-divider"></div>
                </div>
                <?php $albums = get_albums(2); if ($albums->getNumRows() > 0) { ?>
                    <div class="row">
                        <?php foreach($albums->getResult() as $row) { ?>
                            <div class="col-md-6 mb-4">
                                <div class="card-gallery position-relative overflow-hidden rounded-custom shadow-sm-custom">
                                    <img src="<?=base_url('media_library/albums/'.$row->album_cover)?>" class="img-fluid gallery-img w-100" alt="<?=$row->album_title?>">
                                    <div class="gallery-overlay d-flex flex-column justify-content-end p-3">
                                        <h6 class="text-white font-weight-bold m-0"><?=$row->album_title?></h6>
                                        <p class="text-accent small m-0"><?=$row->album_description?></p>
                                        <button type="button" onclick="photo_preview(<?=$row->id?>)" class="btn-gallery-zoom mt-2">
                                            <i class="fa fa-search-plus"></i> Lihat Foto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mt-2 text-right">
                        <a href="<?=site_url('galeri-foto')?>" class="text-primary font-weight-bold font-size-sm">
                            Semua Galeri Foto <i class="fa fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                <?php } else { ?>
                    <p class="text-muted">Belum ada dokumentasi album foto.</p>
                <?php } ?>
            </div>

            <!-- Grid Video Youtube -->
            <div class="col-lg-6">
                <div class="mb-4">
                    <span class="section-tagline">Dokumentasi Video</span>
                    <h3 class="section-title font-weight-bold">Video Terbaru</h3>
                    <div class="section-divider"></div>
                </div>
                <?php $videos = get_videos(2); if ($videos->getNumRows() > 0) { ?>
                    <div class="row">
                        <?php foreach($videos->getResult() as $row) { ?>
                            <div class="col-md-6 mb-4">
                                <div class="video-card rounded-custom overflow-hidden shadow-sm-custom">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?=$row->post_content?>" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="mt-2 text-right">
                        <a href="<?=site_url('galeri-video')?>" class="text-primary font-weight-bold font-size-sm">
                            Semua Galeri Video <i class="fa fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                <?php } else { ?>
                    <p class="text-muted">Belum ada dokumentasi video.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- 8. PANEL INTERAKSI PUBLIK (POLLING & NEWSLETTER) -->
<section class="py-5 bg-dark-blue text-white interaction-section position-relative">
    <div class="container">
        <div class="row">
            <!-- Jajak Pendapat / Polling -->
            <div class="col-lg-6 mb-5 mb-lg-0 pr-lg-5 border-right-custom">
                <h4 class="font-weight-bold text-white mb-3">
                    <i class="fa fa-bar-chart text-accent mr-2"></i> Jajak Pendapat
                </h4>
                <div class="section-divider bg-accent mb-4"></div>
                <?php $question = get_active_question(); if ($question) { ?>
                    <div class="polling-box p-4 bg-glassmorphism rounded-custom">
                        <h6 class="font-weight-bold text-white mb-3"><?=$question->question?></h6>
                        <form id="poll-form" class="mb-3">
                            <?php $options = get_answers($question->id); foreach($options->getResult() as $option) { ?>
                                <div class="custom-control custom-radio mb-2">
                                    <input type="radio" id="answer_id_<?=$option->id?>" name="answer_id" value="<?=$option->id?>" class="custom-control-input">
                                    <label class="custom-control-label text-light-50" for="answer_id_<?=$option->id?>"><?=$option->answer?></label>
                                </div>
                            <?php } ?>
                        </form>
                        <div class="btn-group">
                            <button type="button" onclick="vote(); return false;" class="btn btn-accent px-4 font-weight-bold">
                                <i class="fa fa-send mr-1"></i> Kirim Pilihan
                            </button>
                            <a href="<?=site_url('hasil-jajak-pendapat')?>" class="btn btn-outline-light-custom-sm ml-2">
                                <i class="fa fa-bar-chart mr-1"></i> Lihat Hasil
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <p class="text-light-50">Saat ini tidak ada jajak pendapat aktif.</p>
                <?php } ?>
            </div>

            <!-- Langganan Informasi / Newsletter -->
            <div class="col-lg-6 pl-lg-5">
                <h4 class="font-weight-bold text-white mb-3">
                    <i class="fa fa-envelope text-accent mr-2"></i> Berlangganan Berita
                </h4>
                <div class="section-divider bg-accent mb-4"></div>
                <p class="text-light-50 mb-4">Dapatkan update informasi penting, kegiatan sekolah, dan rilis berita terbaru langsung ke kotak masuk email Anda secara gratis.</p>
                <div class="newsletter-box p-4 bg-glassmorphism rounded-custom">
                    <div class="input-group">
                        <input type="email" id="subscriber" onkeydown="if (event.keyCode == 13) { subscribe(); return false; }" class="form-control bg-white-10 text-white border-0 px-4 py-4 rounded-custom-left" placeholder="Masukkan alamat email Anda...">
                        <div class="input-group-append">
                            <button type="button" onclick="subscribe();" class="btn btn-accent px-4 rounded-custom-right">
                                <i class="fa fa-paper-plane mr-1"></i> Daftar
                            </button>
                        </div>
                    </div>
                    <small class="text-light-50 d-block mt-2 font-italic"><i class="fa fa-lock mr-1"></i> Kami menjamin privasi email Anda dan bebas spam.</small>
                </div>
            </div>
        </div>
    </div>
</section>
