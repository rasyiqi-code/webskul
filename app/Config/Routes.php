<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Public Routes
$routes->get('hubungi-kami', 'Public\Contact_us::index');
$routes->post('hubungi-kami/save', 'Public\Contact_us::save');
$routes->get('read/(:num)/(:any)', 'Public\Readmore::index/$1/$2');
$routes->get('feed', 'Public\Feed::index');
$routes->get('sambutan-rektor', 'Public\Opening_speech::index');
$routes->get('sambutan-ketua', 'Public\Opening_speech::index');
$routes->get('sambutan-direktur', 'Public\Opening_speech::index');
$routes->get('sambutan-kepala-sekolah', 'Public\Opening_speech::index');
$routes->get('galeri-foto', 'Public\Gallery_photos::index');
$routes->get('galeri-video', 'Public\Gallery_videos::index');
$routes->get('kategori/(:any)', 'Public\Post_categories::index/$1');
$routes->get('arsip/([0-9]{4})/([0-9]{2})', 'Public\Archives::index/$1/$2');
$routes->get('tag/(:any)', 'Public\Post_tags::index/$1');
$routes->get('download/(:any)', 'Public\Download::index/$1');
$routes->get('download/force_download/(:num)', 'Public\Download::force_download/$1');
$routes->get('hasil-jajak-pendapat', 'Public\Pollings::index');
$routes->get('hasil-pencarian', 'Public\Search::index');
$routes->get('under-construction', 'Public\Under_construction::index');
$routes->get('pendaftaran-alumni', 'Public\Alumni::index');
$routes->get('direktori-peserta-didik', 'Public\Student_directory::index');
$routes->get('direktori-dosen-dan-staf', 'Public\Employee_directory::index');
$routes->get('direktori-guru-dan-tenaga-kependidikan', 'Public\Employee_directory::index');
$routes->get('direktori-alumni', 'Public\Alumni_directory::index');
$routes->post('subscribe', 'Public\Subscriber::save');
$routes->post('vote', 'Public\Pollings::save');
$routes->post('student-registration', 'Public\Admission_form::save');
$routes->post('admission-selection-results', 'Public\Admission_selection_results::get_results');
$routes->get('formulir-penerimaan-peserta-didik-baru', 'Public\Admission_form::index');
$routes->get('hasil-seleksi-penerimaan-peserta-didik-baru', 'Public\Admission_selection_results::index');
$routes->get('cetak-formulir-penerimaan-peserta-didik-baru', 'Public\Print_admission_form::index');
$routes->get('pdf-formulir-penerimaan-peserta-didik-baru/[0-9]/([0-9]{4})-([0-9]{2})-([0-9]{2})/([0-9]{9})', 'Public\Admission_form::print_admission_form/$1/$2/$3/$4');
$routes->get('cetak-kartu-peserta-ujian-seleksi-penerimaan-peserta-didik-baru', 'Public\Print_admission_exam_card::index');

// Auth Routes
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::verify');
$routes->get('logout', 'Logout::index');
$routes->get('lost-password', 'Lost_password::index');
$routes->post('lost-password/process', 'Lost_password::process');
$routes->get('reset-password/(:any)', 'Reset_password::index/$1');
$routes->post('reset-password-process/(:any)', 'Reset_password::process/$1');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
| URL menggunakan nama modul Bahasa Inggris agar selaras dengan:
|   1. Sidebar/view yang masih pakai URL CI3 (mis. blog/posts, academic/students)
|   2. Kolom modules.module_url di database (dashboard, blog, academic, dst)
|   3. PrivilegeFilter yang membandingkan segment1 URL dengan user_privileges
|
| Auto-routing (Config\Routing::$autoRoute = true) menangani method-method
| individu di dalam setiap controller. Filter auth & privilege didaftarkan
| di Config\Filters::$filters terhadap pattern URI agar berlaku baik untuk
| route eksplisit maupun route auto.
*/

$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/', 'Dashboard::index');
$routes->post('dashboard/sidebar_collapse', 'Dashboard::sidebarCollapse');
$routes->get('dashboard/print_sessions', 'Dashboard::printSessions');
