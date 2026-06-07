<?php

if (!function_exists('reject_pip')) {
    function reject_pip($key = '') {
        $data = [
            '1' => 'Dilarang pemda karena menerima bantuan serupa',
            '2' => 'Menolak',
            '3' => 'Sudah mampu',
        ];
        return isset($data[$key]) ? $data[$key] : $data;
    }
}

if (!function_exists('student_status')) {
    function student_status() {
        return [
            'Aktif', 'Lulus', 'Mutasi', 'Dikeluarkan',
            'Mengundurkan Diri', 'Putus Sekolah', 'Meninggal', 'Hilang', 'Lainnya'
        ];
    }
}

if (!function_exists('employments')) {
    function employments() {
        return [
            'Tidak bekerja', 'Nelayan', 'Petani', 'Peternak',
            'PNS/TNI/POLRI', 'Karyawan Swasta', 'Pedagang Kecil',
            'Pedagang Besar', 'Wiraswasta', 'Wirausaha', 'Buruh',
            'Pensiunan', 'Lain-lain'
        ];
    }
}

if (!function_exists('special_needs')) {
    function special_needs() {
        return [
            'Tidak', 'Tuna Netra', 'Tuna Rungu', 'Tuna Grahita ringan',
            'Tuna Grahita Sedang', 'Tuna Daksa Ringan', 'Tuna Daksa Sedang',
            'Tuna Laras', 'Tuna Wicara', 'Tuna ganda', 'Hiper aktif',
            'Cerdas Istimewa', 'Bakat Istimewa', 'Kesulitan Belajar',
            'Narkoba', 'Indigo', 'Down Sindrome', 'Autis', 'Lainnya'
        ];
    }
}

if (!function_exists('educations')) {
    function educations() {
        return [
            'Tidak sekolah', 'Putus SD', 'SD Sederajat', 'SMP Sederajat',
            'SMA Sederajat', 'D1', 'D2', 'D3', 'D4/S1', 'S2', 'S3'
        ];
    }
}

if (!function_exists('achievement_types')) {
    function achievement_types($key = null) {
        $data = ['1' => 'Sains', '2' => 'Seni', '3' => 'Olahraga', '4' => 'Lain-lain'];
        return isset($data[$key]) ? $data[$key] : $data;
    }
}

if (!function_exists('achievement_levels')) {
    function achievement_levels($key = null) {
        $data = ['1' => 'Sekolah', '2' => 'Kecamatan', '3' => 'Kabupaten', '4' => 'Propinsi', '5' => 'Nasional', '6' => 'Internasional'];
        return isset($data[$key]) ? $data[$key] : $data;
    }
}

if (!function_exists('monthly_incomes')) {
    function monthly_incomes() {
        return [
            'Kurang dari Rp 1.000.000',
            'Rp 1.000.000 - Rp 2.000.000',
            'Lebih dari Rp 2.000.000',
            'Kurang dari Rp. 500,000',
            'Rp. 500,000 - Rp. 999,999',
            'Rp. 1,000,000 - Rp. 1,999,999',
            'Rp. 2,000,000 - Rp. 4,999,999',
            'Rp. 5,000,000 - Rp. 20,000,000',
            'Lebih dari Rp. 20,000,000',
            'Tidak Berpenghasilan',
            'Lainnya'
        ];
    }
}

if (!function_exists('residences')) {
    function residences() {
        return ['Bersama orang tua', 'Wali', 'Kos', 'Asrama', 'Panti Asuhan', 'Lainnya'];
    }
}

if (!function_exists('transportations')) {
    function transportations() {
        return [
            'Jalan kaki', 'Kendaraan pribadi', 'Kendaraan Umum / angkot / Pete-pete',
            'Jemputan Sekolah', 'Kereta Api', 'Ojek',
            'Andong / Bendi / Sado / Dokar / Delman / Beca',
            'Perahu penyebrangan / Rakit / Getek', 'Lainnya'
        ];
    }
}

if (!function_exists('religions')) {
    function religions() {
        return ['Islam', 'Kristen / Protestan', 'Katholik', 'Hindu', 'Budha', 'Khong Hu Chu', 'Lainnya'];
    }
}

if (!function_exists('school_levels')) {
    function school_levels() {
        return [
            '1 - Sekolah Dasar (SD) / Sederajat',
            '2 - Sekolah Menengah Pertama (SMP) / Sederajat',
            '3 - Sekolah Menengah Atas (SMA) / Aliyah',
            '4 - Sekolah Menengah Kejuruan (SMK)',
        ];
    }
}

if (!function_exists('marriage_status')) {
    function marriage_status() {
        return ['Menikah', 'Belum Menikah', 'Berpisah'];
    }
}

if (!function_exists('institution_lifters')) {
    function institution_lifters() {
        return ['Pemerintah Pusat', 'Pemerintah Provinsi', 'Pemerintah Kab / Kota', 'Ketua Yayasan', 'Kepala Sekolah', 'Komite Sekolah', 'Lainnya'];
    }
}

if (!function_exists('employment_status')) {
    function employment_status() {
        return ['PNS', 'PNS Diperbantukan', 'PNS DEPAG', 'GTY / PTY', 'GTT / PTT Provinsi', 'GTT / PTT Kabupaten / Kota', 'Guru Bantu Pusat', 'Guru Honor Sekolah', 'Tenaga Honor Sekolah', 'CPNS', 'Lainnya'];
    }
}

if (!function_exists('employment_types')) {
    function employment_types() {
        return ['Guru Kelas', 'Guru Mata Pelajaran', 'Guru BK', 'Guru Inklusi', 'Tenaga Administrasi Sekolah', 'Guru Pendamping', 'Guru Magang', 'Guru TIK', 'Laboran', 'Pustakawan', 'Lainnya'];
    }
}

if (!function_exists('ranks')) {
    function ranks() {
        return ['I/A', 'I/B', 'I/C', 'I/D', 'II/A', 'II/B', 'II/C', 'II/D', 'III/A', 'III/B', 'III/C', 'III/D', 'IV/A', 'IV/B', 'IV/C', 'IV/D', 'IV/E'];
    }
}

if (!function_exists('salary_sources')) {
    function salary_sources() {
        return ['APBN', 'APBD Provinsi', 'APBD Kab / Kota', 'Yayasan', 'Sekolah', 'Lembaga Donor', 'Lainnya'];
    }
}

if (!function_exists('laboratory_skills')) {
    function laboratory_skills() {
        return ['Lab IPA', 'Lab Fisika', 'Lab Biologi', 'Lab Kimia', 'Lab Bahasa', 'Lab Komputer', 'Teknik Bangunan', 'Teknik Survei & Pemetaan', 'Teknik Ketenagakerjaan', 'Teknik Pendinginan & Tata Udara', 'Teknik Mesin'];
    }
}

if (!function_exists('buildings')) {
    function buildings() {
        return ['Gedung A', 'Gedung B'];
    }
}

if (!function_exists('scholarships')) {
    function scholarships() {
        return ['Anak berprestasi', 'Anak Miskin', 'Pendidikan', 'Unggulan', 'Lain-lain'];
    }
}

if (!function_exists('scholarship_types')) {
    function scholarship_types($key = '') {
        $data = ['1' => 'Anak Berprestasi', '2' => 'Anak Miskin', '3' => 'Pendidikan', '4' => 'Unggulan', '5' => 'Lain-lain'];
        return isset($data[$key]) ? $data[$key] : $data;
    }
}

if (!function_exists('welfare_types')) {
    function welfare_types($key = '') {
        $data = ['01' => 'PKH', '02' => 'PIP', '03' => 'Kartu Perlindungan Sosial', '04' => 'Kartu Keluarga Sejahtera', '05' => 'Kartu Kesehatan'];
        return isset($data[$key]) ? $data[$key] : $data;
    }
}

if (!function_exists('modules')) {
    function modules($key = '') {
        $modules = [
            'hubungi-kami' => 'Hubungi Kami',
            'galeri-foto' => 'Galeri Foto',
            'galeri-video' => 'Galeri Video',
            'pendaftaran-alumni' => 'Pendaftaran Alumni',
            'direktori-alumni' => 'Direktori Alumni',
            'direktori-peserta-didik' => 'Direktori Peserta Didik',
            'direktori-guru-dan-tenaga-kependidikan' => 'Direktori Guru dan Tenaga Kependidikan',
            'formulir-penerimaan-peserta-didik-baru' => 'Formulir PPDB',
            'hasil-seleksi-penerimaan-peserta-didik-baru' => 'Hasil Seleksi PPDB',
            'cetak-formulir-penerimaan-peserta-didik-baru' => 'Cetak Formulir PPDB',
            'cetak-kartu-peserta-ujian-seleksi-penerimaan-peserta-didik-baru' => 'Cetak Kartu Ujian Seleksi PPDB',
        ];
        return $key == '' ? $modules : $modules[$key];
    }
}
