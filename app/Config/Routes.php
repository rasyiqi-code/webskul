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
$routes->get('views/themes/(:any)/(:any)', 'Public\Assets::theme/$1/$2');

// Auth Routes
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::verify');
$routes->get('logout', 'Logout::index');
$routes->get('lost-password', 'Lost_password::index');
$routes->post('lost-password/process', 'Lost_password::process');
$routes->get('reset-password/(:any)', 'Reset_password::index/$1');
$routes->post('reset-password-process/(:any)', 'Reset_password::process/$1');

// Admin Routes
$routes->group('dashboard', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->post('sidebar-collapse', 'Dashboard::sidebar_collapse');
    $routes->get('print-sessions', 'Dashboard::print_sessions');
});

// Academic Routes
$routes->group('akademik', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('tahun-akademik', 'Academic\Academic_years::index');
    $routes->get('jurusan', 'Academic\Majors::index');
    $routes->get('kelas', 'Academic\Class_groups::index');
    $routes->get('pengaturan-kelas', 'Academic\Class_group_settings::index');
    $routes->get('siswa-per-kelas', 'Academic\Class_group_students::index');
    $routes->get('siswa', 'Academic\Students::index');
    $routes->get('alumni', 'Academic\Alumni::index');
    $routes->get('mata-pelajaran', 'Academic\Subjects::index');
    $routes->get('guru-mata-pelajaran', 'Academic\Subject_teachers::index');
    $routes->get('kelas-mata-pelajaran', 'Academic\Course_classes::index');
    $routes->get('rombongan-belajar', 'Academic\Student_groups::index');
    $routes->get('status-siswa', 'Academic\Student_status::index');
    $routes->get('transportasi', 'Academic\Transportations::index');
    $routes->get('penghasilan', 'Academic\Monthly_incomes::index');
    $routes->get('tempat-tinggal', 'Academic\Residences::index');
    $routes->get('impor-siswa', 'Academic\Import_students::index');
    $routes->get('impor-alumni', 'Academic\Import_alumni::index');
});

// PPDB / Admission Routes
$routes->group('ppdb', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('pengaturan', 'Admission\Settings::index');
    $routes->get('gelombang', 'Admission\Phases::index');
    $routes->get('jenis-pendaftaran', 'Admission\Types::index');
    $routes->get('kuota', 'Admission\Quotas::index');
    $routes->get('pengaturan-formulir', 'Admission\Form_settings::index');
    $routes->get('pendaftar', 'Admission\Registrants::index');
});

// Blog Routes
$routes->group('blog', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('tulisan', 'Blog\Posts::index');
    $routes->get('halaman', 'Blog\Pages::index');
    $routes->get('kategori', 'Blog\Post_categories::index');
    $routes->get('komentar', 'Blog\Post_comments::index');
    $routes->get('tag', 'Blog\Tags::index');
    $routes->get('slider', 'Blog\Image_sliders::index');
    $routes->get('tautan', 'Blog\Links::index');
    $routes->get('kutipan', 'Blog\Quotes::index');
    $routes->get('sambutan', 'Blog\Opening_speech::index');
    $routes->get('pesan', 'Blog\Messages::index');
    $routes->get('pelanggan', 'Blog\Subscribers::index');
});

// Setting Routes
$routes->group('pengaturan', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('umum', 'Settings\General::index');
    $routes->get('profil-sekolah', 'Settings\School_profile::index');
    $routes->get('menulis', 'Settings\Writing::index');
    $routes->get('membaca', 'Settings\Reading::index');
    $routes->get('diskusi', 'Settings\Discussion::index');
    $routes->get('media', 'Settings\Media::index');
    $routes->get('server-email', 'Settings\Mail_server::index');
    $routes->get('akun-sosial', 'Settings\Social_account::index');
});

// Employee Routes
$routes->group('pegawai', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('/', 'Employees\Employees::index');
    $routes->get('jenis-gtk', 'Employees\Employment_types::index');
    $routes->get('impor', 'Employees\Import::index');
});

// User Management Routes
$routes->group('pengguna', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('administrator', 'Users\Administrator::index');
    $routes->get('siswa', 'Users\Students::index');
    $routes->get('pegawai', 'Users\Employees::index');
    $routes->get('modul', 'Users\Modules::index');
    $routes->get('grup', 'Users\User_groups::index');
    $routes->get('hak-akses', 'Users\User_privileges::index');
});

// Media Routes
$routes->group('media', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('berkas', 'Media\Files::index');
    $routes->get('album', 'Media\Albums::index');
    $routes->get('foto', 'Media\Photos::index');
    $routes->get('video', 'Media\Videos::index');
});

// Appearance Routes
$routes->group('tampilan', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('tema', 'Appearance\Themes::index');
    $routes->get('menu', 'Appearance\Menus::index');
});

// Maintenance Routes
$routes->group('pemeliharaan', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('cadangkan-aplikasi', 'Maintenance\Backup_apps::index');
    $routes->get('cadangkan-database', 'Maintenance\Backup_database::index');
});

// Plugin Routes
$routes->group('plugin', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('banner', 'Plugins\Banners::index');
    $routes->get('pertanyaan', 'Plugins\Questions::index');
    $routes->get('jawaban', 'Plugins\Answers::index');
});

// Reference Routes
$routes->group('referensi', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('gedung', 'Reference\Buildings::index');
    $routes->get('ruangan', 'Reference\Rooms::index');
});

// Teacher Routes (akses khusus guru — difilter privilege & cek employment_type di controller)
$routes->group('guru', ['filter' => 'auth|privilege'], static function ($routes) {
    $routes->get('jadwal-mengajar', 'Teacher\Academic_schedules::index');
    $routes->post('jadwal-mengajar/pagination', 'Teacher\Academic_schedules::pagination');

    $routes->get('masuk-kelas/(:num)/(:any)', 'Teacher\Class_meetings::index/$1/$2');
    $routes->post('masuk-kelas/is-exists', 'Teacher\Class_meetings::is_exists');
    $routes->post('masuk-kelas/insert', 'Teacher\Class_meetings::insert');
    $routes->post('masuk-kelas/update', 'Teacher\Class_meetings::update');
    $routes->post('masuk-kelas/get-pertemuan', 'Teacher\Class_meetings::get_class_meetings');
    $routes->get('masuk-kelas/cetak-absen/(:num)/(:any)', 'Teacher\Class_meetings::print_meeting_attendance/$1/$2');

    $routes->post('absensi-pertemuan', 'Teacher\Meeting_attendences::index');

    $routes->get('laporan-kehadiran', 'Teacher\Meeting_attendance_report::index');
    $routes->post('laporan-kehadiran/pagination', 'Teacher\Meeting_attendance_report::pagination');
    $routes->post('laporan-kehadiran/generate', 'Teacher\Meeting_attendance_report::generate');
});
