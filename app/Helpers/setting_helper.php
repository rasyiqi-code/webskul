<?php

if (!function_exists('general_settings')) {
    function general_settings() {
        return [
            ['setting_group' => 'general', 'setting_variable' => 'site_maintenance', 'setting_default_value' => 'false', 'setting_access_group' => 'public', 'setting_description' => 'Pemeliharaan situs'],
            ['setting_group' => 'general', 'setting_variable' => 'site_maintenance_end_date', 'setting_default_value' => date('Y') . '-01-01', 'setting_access_group' => 'public', 'setting_description' => 'Tanggal Berakhir Pemeliharaan Situs'],
            ['setting_group' => 'general', 'setting_variable' => 'site_cache', 'setting_default_value' => 'false', 'setting_access_group' => 'public', 'setting_description' => 'Cache situs'],
            ['setting_group' => 'general', 'setting_variable' => 'site_cache_time', 'setting_default_value' => 10, 'setting_access_group' => 'public', 'setting_description' => 'Lama Cache Situs'],
            ['setting_group' => 'general', 'setting_variable' => 'meta_description', 'setting_default_value' => 'Webskul adalah Content Management System dan PPDB Online gratis untuk SD SMP/Sederajat SMA/Sederajat', 'setting_access_group' => 'public', 'setting_description' => 'Deskripsi Meta'],
            ['setting_group' => 'general', 'setting_variable' => 'meta_keywords', 'setting_default_value' => 'CMS, Website Sekolah Gratis, Cara Membuat Website Sekolah, membuat web sekolah, contoh website sekolah, fitur website sekolah, Sekolah, Website, Internet,Situs, CMS Sekolah, Web Sekolah, Website Sekolah Gratis, Website Sekolah, Aplikasi Sekolah, PPDB Online, PSB Online, PSB Online Gratis, Penerimaan Siswa Baru Online, Raport Online, Kurikulum 2013, SD, SMP, SMA, Aliyah, MTs, SMK', 'setting_access_group' => 'public', 'setting_description' => 'Kata Kunci Meta'],
            ['setting_group' => 'general', 'setting_variable' => 'map_location', 'setting_default_value' => '', 'setting_access_group' => 'public', 'setting_description' => 'Lokasi di Google Maps'],
            ['setting_group' => 'general', 'setting_variable' => 'favicon', 'setting_default_value' => 'favicon.png', 'setting_access_group' => 'public', 'setting_description' => 'Favicon'],
            ['setting_group' => 'general', 'setting_variable' => 'header', 'setting_default_value' => 'header.png', 'setting_access_group' => 'public', 'setting_description' => 'Gambar Header'],
            ['setting_group' => 'general', 'setting_variable' => 'recaptcha_status', 'setting_default_value' => 'disable', 'setting_access_group' => 'public', 'setting_description' => 'reCAPTCHA Status'],
            ['setting_group' => 'general', 'setting_variable' => 'recaptcha_site_key', 'setting_default_value' => '6Lfo67cjAAAAAHK_c2JcBfzJd8DSMlr8hpeFPy3W', 'setting_access_group' => 'public', 'setting_description' => 'reCAPTCHA Site Key'],
            ['setting_group' => 'general', 'setting_variable' => 'recaptcha_secret_key', 'setting_default_value' => '6Lfo67cjAAAAAIYv3j5yJWhEJtgRATv4xaYTNR0f', 'setting_access_group' => 'public', 'setting_description' => 'reCAPTCHA Secret Key'],
            ['setting_group' => 'general', 'setting_variable' => 'timezone', 'setting_default_value' => 'Asia/Jakarta', 'setting_access_group' => 'public', 'setting_description' => 'Time Zone'],
        ];
    }
}

if (!function_exists('media_settings')) {
    function media_settings() {
        return [
            ['setting_group' => 'media', 'setting_variable' => 'file_allowed_types', 'setting_default_value' => 'jpg, jpeg, png, gif', 'setting_access_group' => 'public', 'setting_description' => 'Tipe file yang diizinkan'],
            ['setting_group' => 'media', 'setting_variable' => 'upload_max_filesize', 'setting_default_value' => 0, 'setting_access_group' => 'public', 'setting_description' => 'Maksimal Ukuran File yang Diupload'],
            ['setting_group' => 'media', 'setting_variable' => 'thumbnail_size_height', 'setting_default_value' => 100, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Thumbnail'],
            ['setting_group' => 'media', 'setting_variable' => 'thumbnail_size_width', 'setting_default_value' => 150, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Thumbnail'],
            ['setting_group' => 'media', 'setting_variable' => 'medium_size_height', 'setting_default_value' => 308, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Sedang'],
            ['setting_group' => 'media', 'setting_variable' => 'medium_size_width', 'setting_default_value' => 460, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Sedang'],
            ['setting_group' => 'media', 'setting_variable' => 'large_size_height', 'setting_default_value' => 600, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Besar'],
            ['setting_group' => 'media', 'setting_variable' => 'large_size_width', 'setting_default_value' => 800, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Besar'],
            ['setting_group' => 'media', 'setting_variable' => 'album_cover_height', 'setting_default_value' => 250, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Cover Album Foto'],
            ['setting_group' => 'media', 'setting_variable' => 'album_cover_width', 'setting_default_value' => 400, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Cover Album Foto'],
            ['setting_group' => 'media', 'setting_variable' => 'banner_height', 'setting_default_value' => 81, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Iklan'],
            ['setting_group' => 'media', 'setting_variable' => 'banner_width', 'setting_default_value' => 245, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Iklan'],
            ['setting_group' => 'media', 'setting_variable' => 'image_slider_height', 'setting_default_value' => 400, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Slide'],
            ['setting_group' => 'media', 'setting_variable' => 'image_slider_width', 'setting_default_value' => 900, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Slide'],
            ['setting_group' => 'media', 'setting_variable' => 'user_photo_height', 'setting_default_value' => 600, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Foto'],
            ['setting_group' => 'media', 'setting_variable' => 'user_photo_width', 'setting_default_value' => 400, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Foto'],
            ['setting_group' => 'media', 'setting_variable' => 'logo_height', 'setting_default_value' => 120, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Logo Sekolah'],
            ['setting_group' => 'media', 'setting_variable' => 'logo_width', 'setting_default_value' => 120, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Logo Sekolah'],
        ];
    }
}

if (!function_exists('writing_settings')) {
    function writing_settings() {
        return [
            ['setting_group' => 'writing', 'setting_variable' => 'default_post_category', 'setting_default_value' => 1, 'setting_access_group' => 'private', 'setting_description' => 'Default Kategori Tulisan'],
            ['setting_group' => 'writing', 'setting_variable' => 'default_post_status', 'setting_default_value' => 'publish', 'setting_access_group' => 'private', 'setting_description' => 'Default Status Tulisan'],
            ['setting_group' => 'writing', 'setting_variable' => 'default_post_visibility', 'setting_default_value' => 'public', 'setting_access_group' => 'private', 'setting_description' => 'Default Akses Tulisan'],
            ['setting_group' => 'writing', 'setting_variable' => 'default_post_discussion', 'setting_default_value' => 'open', 'setting_access_group' => 'private', 'setting_description' => 'Default Komentar Tulisan'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_thumbnail_height', 'setting_default_value' => 100, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Kecil'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_thumbnail_width', 'setting_default_value' => 150, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Kecil'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_medium_height', 'setting_default_value' => 250, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Sedang'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_medium_width', 'setting_default_value' => 400, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Sedang'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_large_height', 'setting_default_value' => 450, 'setting_access_group' => 'private', 'setting_description' => 'Tinggi Gambar Besar'],
            ['setting_group' => 'writing', 'setting_variable' => 'post_image_large_width', 'setting_default_value' => 840, 'setting_access_group' => 'private', 'setting_description' => 'Lebar Gambar Besar'],
        ];
    }
}

if (!function_exists('reading_settings')) {
    function reading_settings() {
        return [
            ['setting_group' => 'reading', 'setting_variable' => 'post_per_page', 'setting_default_value' => 5, 'setting_access_group' => 'public', 'setting_description' => 'Tulisan per halaman'],
            ['setting_group' => 'reading', 'setting_variable' => 'post_rss_count', 'setting_default_value' => 10, 'setting_access_group' => 'public', 'setting_description' => 'Jumlah RSS'],
            ['setting_group' => 'reading', 'setting_variable' => 'post_related_count', 'setting_default_value' => 5, 'setting_access_group' => 'public', 'setting_description' => 'Jumlah Tulisan Terkait'],
            ['setting_group' => 'reading', 'setting_variable' => 'comment_per_page', 'setting_default_value' => 5, 'setting_access_group' => 'public', 'setting_description' => 'Komentar per halaman'],
        ];
    }
}

if (!function_exists('discussion_settings')) {
    function discussion_settings() {
        return [
            ['setting_group' => 'discussion', 'setting_variable' => 'comment_moderation', 'setting_default_value' => 'false', 'setting_access_group' => 'public', 'setting_description' => 'Komentar harus disetujui secara manual'],
            ['setting_group' => 'discussion', 'setting_variable' => 'comment_registration', 'setting_default_value' => 'false', 'setting_access_group' => 'public', 'setting_description' => 'Pengguna harus terdaftar dan login untuk komentar'],
            ['setting_group' => 'discussion', 'setting_variable' => 'comment_blacklist', 'setting_default_value' => 'kampret', 'setting_access_group' => 'public', 'setting_description' => 'Komentar disaring'],
            ['setting_group' => 'discussion', 'setting_variable' => 'comment_order', 'setting_default_value' => 'asc', 'setting_access_group' => 'public', 'setting_description' => 'Urutan Komentar'],
        ];
    }
}

if (!function_exists('social_account_settings')) {
    function social_account_settings() {
        return [
            ['setting_group' => 'social_account', 'setting_variable' => 'facebook', 'setting_default_value' => 'https://www.facebook.com/webskul/', 'setting_access_group' => 'public', 'setting_description' => 'Facebook'],
            ['setting_group' => 'social_account', 'setting_variable' => 'twitter', 'setting_default_value' => 'https://twitter.com/rasyiqi', 'setting_access_group' => 'public', 'setting_description' => 'Twitter'],
            ['setting_group' => 'social_account', 'setting_variable' => 'linked_in', 'setting_default_value' => 'https://www.linkedin.com/in/rasyiqi/', 'setting_access_group' => 'public', 'setting_description' => 'Linked In'],
            ['setting_group' => 'social_account', 'setting_variable' => 'youtube', 'setting_default_value' => '-', 'setting_access_group' => 'public', 'setting_description' => 'Youtube'],
            ['setting_group' => 'social_account', 'setting_variable' => 'instagram', 'setting_default_value' => 'https://www.instagram.com/rasyiqi/', 'setting_access_group' => 'public', 'setting_description' => 'Instagram'],
        ];
    }
}

if (!function_exists('mail_server_settings')) {
    function mail_server_settings() {
        return [
            ['setting_group' => 'mail_server', 'setting_variable' => 'smtp_host', 'setting_default_value' => '', 'setting_access_group' => 'private', 'setting_description' => 'SMTP Server Address'],
            ['setting_group' => 'mail_server', 'setting_variable' => 'smtp_user', 'setting_default_value' => '', 'setting_access_group' => 'private', 'setting_description' => 'SMTP Username'],
            ['setting_group' => 'mail_server', 'setting_variable' => 'smtp_pass', 'setting_default_value' => '', 'setting_access_group' => 'private', 'setting_description' => 'SMTP Password'],
            ['setting_group' => 'mail_server', 'setting_variable' => 'smtp_port', 'setting_default_value' => '', 'setting_access_group' => 'public', 'setting_description' => 'SMTP Port'],
        ];
    }
}

if (!function_exists('admission_settings')) {
    function admission_settings() {
        return [
            ['setting_group' => 'admission', 'setting_variable' => 'admission_status', 'setting_default_value' => 'open', 'setting_access_group' => 'public', 'setting_description' => 'Status Penerimaan Peserta Didik Baru'],
            ['setting_group' => 'admission', 'setting_variable' => 'announcement_start_date', 'setting_default_value' => date('Y') . '-01-01', 'setting_access_group' => 'public', 'setting_description' => 'Tanggal mulai pengumuman'],
            ['setting_group' => 'admission', 'setting_variable' => 'announcement_end_date', 'setting_default_value' => (date('Y') + 1) . '-12-31', 'setting_access_group' => 'public', 'setting_description' => 'Tanggal selesai pengumuman'],
            ['setting_group' => 'admission', 'setting_variable' => 'print_exam_card_start_date', 'setting_default_value' => date('Y') . '-01-01', 'setting_access_group' => 'public', 'setting_description' => 'Tanggal mulai cetak kartu ujian'],
            ['setting_group' => 'admission', 'setting_variable' => 'print_exam_card_end_date', 'setting_default_value' => (date('Y') + 1) . '-12-31', 'setting_access_group' => 'public', 'setting_description' => 'Tanggal selesai cetak kartu ujian'],
            ['setting_group' => 'admission', 'setting_variable' => 'min_birth_date', 'setting_default_value' => null, 'setting_access_group' => 'private', 'setting_description' => 'Tanggal lahir minimal'],
            ['setting_group' => 'admission', 'setting_variable' => 'max_birth_date', 'setting_default_value' => null, 'setting_access_group' => 'private', 'setting_description' => 'Tanggal lahir maksimal'],
        ];
    }
}

if (!function_exists('school_profile_settings')) {
    function school_profile_settings() {
        return [
            ['setting_group' => 'school_profile', 'setting_variable' => 'npsn', 'setting_default_value' => 123, 'setting_access_group' => 'public', 'setting_description' => 'NPSN'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'school_name', 'setting_default_value' => 'SMK Negeri 10 Kuningan', 'setting_access_group' => 'public', 'setting_description' => 'Nama Sekolah'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'headmaster', 'setting_default_value' => 'Rasyiqi', 'setting_access_group' => 'public', 'setting_description' => 'Kepala Sekolah'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'headmaster_photo', 'setting_default_value' => 'headmaster_photo.png', 'setting_access_group' => 'public', 'setting_description' => 'Foto Kepala Sekolah'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'school_level', 'setting_default_value' => 3, 'setting_access_group' => 'public', 'setting_description' => 'Bentuk Pendidikan'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'school_status', 'setting_default_value' => 1, 'setting_access_group' => 'public', 'setting_description' => 'Status Sekolah'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'ownership_status', 'setting_default_value' => 1, 'setting_access_group' => 'public', 'setting_description' => 'Status Kepemilikan'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'tagline', 'setting_default_value' => 'Where Tomorrow\'s Leaders Come Together', 'setting_access_group' => 'public', 'setting_description' => 'Slogan'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'rt', 'setting_default_value' => 12, 'setting_access_group' => 'public', 'setting_description' => 'RT'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'rw', 'setting_default_value' => '06', 'setting_access_group' => 'public', 'setting_description' => 'RW'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'sub_village', 'setting_default_value' => 'Wage', 'setting_access_group' => 'public', 'setting_description' => 'Dusun'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'village', 'setting_default_value' => 'Kadugede', 'setting_access_group' => 'public', 'setting_description' => 'Kelurahan / Desa'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'sub_district', 'setting_default_value' => 'Kadugede', 'setting_access_group' => 'public', 'setting_description' => 'Kecamatan'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'district', 'setting_default_value' => 'Kuningan', 'setting_access_group' => 'public', 'setting_description' => 'Kabupaten/Kota'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'postal_code', 'setting_default_value' => 45561, 'setting_access_group' => 'public', 'setting_description' => 'Kode Pos'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'street_address', 'setting_default_value' => 'Jalan Raya Kadugede No. 11', 'setting_access_group' => 'public', 'setting_description' => 'Alamat Jalan'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'phone', 'setting_default_value' => '0232123456', 'setting_access_group' => 'public', 'setting_description' => 'Telepon'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'fax', 'setting_default_value' => '0232123456', 'setting_access_group' => 'public', 'setting_description' => 'Fax'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'email', 'setting_default_value' => 'info@sman9kuningan.sch.id', 'setting_access_group' => 'public', 'setting_description' => 'Email'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'website', 'setting_default_value' => 'http://www.sman9kuningan.sch.id', 'setting_access_group' => 'public', 'setting_description' => 'Website'],
            ['setting_group' => 'school_profile', 'setting_variable' => 'logo', 'setting_default_value' => 'logo.png', 'setting_access_group' => 'public', 'setting_description' => 'Logo'],
        ];
    }
}

if (!function_exists('field_settings')) {
    function field_settings() {
        return [
            ['field_name' => 'is_transfer', 'field_description' => 'Jenis Pendaftaran', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'admission_type_id', 'field_description' => 'Jalur Pendaftaran', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'first_choice_id', 'field_description' => 'Pilihan Ke-1', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'second_choice_id', 'field_description' => 'Pilihan Ke-2', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'nik', 'field_description' => 'NIK/ No. KITAS (Untuk WNA)', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'prev_school_name', 'field_description' => 'Sekolah Asal', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'prev_exam_number', 'field_description' => 'Nomor Peserta UN SMP/MTs', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'skhun', 'field_description' => 'Nomor SKHUN SMP/MTs', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'prev_diploma_number', 'field_description' => 'Nomor Seri Ijazah SMP/MTs', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'full_name', 'field_description' => 'Nama Lengkap', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'gender', 'field_description' => 'Jenis Kelamin', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'nisn', 'field_description' => 'NISN', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'family_card_number', 'field_description' => 'Nomor Kartu Keluarga', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'birth_place', 'field_description' => 'Tempat Lahir', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'birth_date', 'field_description' => 'Tanggal Lahir', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'birth_certificate_number', 'field_description' => 'Nomor Registasi Akta Lahir', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'religion_id', 'field_description' => 'Agama dan Kepercayaan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'citizenship', 'field_description' => 'Kewarganegaraan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'country', 'field_description' => 'Nama Negara', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'special_need_id', 'field_description' => 'Berkebutuhan Khusus', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'street_address', 'field_description' => 'Alamat Jalan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'rt', 'field_description' => 'RT', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'rw', 'field_description' => 'RW', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'sub_village', 'field_description' => 'Nama Dusun', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'village', 'field_description' => 'Nama Kelurahan/Desa', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'sub_district', 'field_description' => 'Kecamatan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'district', 'field_description' => 'Kabupaten', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'postal_code', 'field_description' => 'Kode POS', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'latitude', 'field_description' => 'Lintang', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'longitude', 'field_description' => 'Bujur', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'residence_id', 'field_description' => 'Tempat Tinggal', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'transportation_id', 'field_description' => 'Moda Transportasi', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'child_number', 'field_description' => 'Anak Keberapa', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'employment_id', 'field_description' => 'Pekerjaan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'have_kip', 'field_description' => 'Apakah Punya KIP', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'receive_kip', 'field_description' => 'Apakah Peserta Didik Tersebut Tetap Akan Menerima KIP', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'reject_pip', 'field_description' => 'Alasan Menolak PIP', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_name', 'field_description' => 'Nama Ayah Kandung', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_nik', 'field_description' => 'NIK Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_birth_place', 'field_description' => 'Tempat Lahir Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_birth_date', 'field_description' => 'Tanggal Lahir Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_education_id', 'field_description' => 'Pendidikan Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_employment_id', 'field_description' => 'Pekerjaan Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_monthly_income_id', 'field_description' => 'Penghasilan Bulanan Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_special_need_id', 'field_description' => 'Kebutuhan Khusus Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'father_identity_card', 'field_description' => 'Upload KTP Ayah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_name', 'field_description' => 'Nama Ibu Kandung', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_nik', 'field_description' => 'NIK Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_birth_place', 'field_description' => 'Tempat Lahir Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_birth_date', 'field_description' => 'Tanggal Lahir Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_education_id', 'field_description' => 'Pendidikan Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_employment_id', 'field_description' => 'Pekerjaan Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_monthly_income_id', 'field_description' => 'Penghasilan Bulanan Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_special_need_id', 'field_description' => 'Kebutuhan Khusus Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mother_identity_card', 'field_description' => 'Upload KTP Ibu', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_name', 'field_description' => 'Nama Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_nik', 'field_description' => 'NIK Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_birth_date', 'field_description' => 'Tanggal Lahir Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_birth_place', 'field_description' => 'Tempat Lahir Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_education_id', 'field_description' => 'Pendidikan Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_employment_id', 'field_description' => 'Pekerjaan Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_monthly_income_id', 'field_description' => 'Penghasilan Bulanan Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'guardian_identity_card', 'field_description' => 'Upload KTP Wali', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'phone', 'field_description' => 'Nomor Telepon Rumah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mobile_phone', 'field_description' => 'Nomor HP', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'email', 'field_description' => 'Email', 'field_setting' => '{"admission":"true", "admission_required":"true"}'],
            ['field_name' => 'height', 'field_description' => 'Tinggi Badan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'weight', 'field_description' => 'Berat Badan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'head_circumference', 'field_description' => 'Lingkar Kepala', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'mileage', 'field_description' => 'Jarak Tempat Tinggal ke Sekolah', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'traveling_time', 'field_description' => 'Waktu Tempuh ke Sekolah (Menit)', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'sibling_number', 'field_description' => 'Jumlah Saudara Kandung', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'welfare_type', 'field_description' => 'Jenis Kesejahteraan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'welfare_number', 'field_description' => 'Nomor Kartu Kesejahteraan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'welfare_name', 'field_description' => 'Nama di Kartu Kesejahteraan', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'photo', 'field_description' => 'Unggah Pas Foto', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'family_card', 'field_description' => 'Unggah Kartu Keluarga', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
            ['field_name' => 'birth_certificate', 'field_description' => 'Unggah Akta Lahir', 'field_setting' => '{"admission":"false", "admission_required":"false"}'],
        ];
    }
}
