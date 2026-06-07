# Webskul — Refactor Plan: Integrasi Platform Pemerintah

> **Tujuan:** Mengurangi duplikasi fitur Webskul dengan platform pendidikan resmi pemerintah Indonesia, dan menjadikannya *website resmi sekolah* yang terintegrasi dengan ekosistem Dapodik, Pusdatin, Verval PD, ARKAS, SIPLah, Sipintar, Rapor Pendidikan, Info GTK, dan Satu Data Kemendikdasmen.

---

## 1. Latar Belakang

### 1.1 Masalah Saat Ini

Webskul Premium v2.5.4 (port dari CI3 ke CI4) saat ini mengelola banyak data yang sebenarnya **sudah menjadi tanggung jawab platform pemerintah**:

- **Tabel kosong** (0 records) di banyak modul inti: `class_group_students`, `course_classes`, `scholarships`, `achievements`, `meeting_attendences`, `comments`
- **Controller mati**: 20+ controller tidak pernah dipakai karena sekolah lebih memilih input data lewat Dapodik
- **Duplikasi input**: operator sekolah harus input data yang sama di 2-3 sistem (Dapodik + CMS + ARKAS)
- **Risiko inkonsistensi**: data di CMS bisa beda dengan data resmi di Dapodik/Rapor Pendidikan

### 1.2 Peluang

Pemerintah menyediakan **9 platform resmi** yang menutupi hampir seluruh data pendidikan. Webskul bisa fokus pada kekuatannya: **website publik sekolah** (blog, media, halaman profil) yang tidak dimiliki oleh satu pun platform pemerintah.

---

## 2. Analisis: 9 Platform Pemerintah vs Fitur CMS

| # | Platform | URL | Fungsi | Status Overlap |
|---|----------|-----|--------|----------------|
| 1 | **Dapodik** | dapodik.kemdikbud.go.id | Pusat data sekolah (PD, GTK, rombel, gedung) | 🔴 Tinggi |
| 2 | **Pusdatin** | referensidata.kemdikbud.go.id | Referensi NPSN & NISN | 🟡 Sedang |
| 3 | **Verval PD** | vervalpd.data.kemdikbud.go.id | Validasi NIK siswa ke Dukcapil | 🟡 Sedang |
| 4 | **ARKAS** | arkas.kemdikbud.go.id | Rencana & laporan dana BOS | ✅ Tidak ada |
| 5 | **SIPLah** | siplah.kemdikbud.go.id | Marketplace barang BOS | ✅ Tidak ada |
| 6 | **Sipintar (PIP)** | pip.kemdikbud.go.id | Cek bantuan PIP siswa | 🟡 Parsial |
| 7 | **Rapor Pendidikan** | raporpendidikan.kemdikbud.go.id | Mutu & evaluasi belajar | 🔴 Tinggi |
| 8 | **Info GTK** | info.gtk.kemdikbud.go.id | Data tunjangan & masa kerja guru | 🔴 Tinggi |
| 9 | **Satu Data** | satudata.kemdikbud.go.id | Statistik pendidikan nasional | 🔴 Tinggi |

---

## 3. Pemetaan Fitur: Hapus / Integrasi / Pertahankan

### 3.1 🔴 HAPUS / DEPRESIASI (fitur duplikat dengan pemerintah)

#### A. Modul Data Pokok Pendidikan (Dapodik)
| Controller/Model | Tabel | Tabel Rows | Pengganti |
|------------------|-------|------------|-----------|
| `Academic/Students` | `users` (filter `is_student`) | 0 | Dapodik API |
| `Academic/Alumni` | `users` (filter `is_alumni`) | 0 | Dapodik API |
| `Academic/Class_groups` | `class_groups` | 0 | Dapodik API |
| `Academic/Class_group_students` | `class_group_students` | 0 | Dapodik API |
| `Academic/Class_group_settings` | `class_group_settings` | 0 | Dapodik API |
| `Academic/Course_classes` | `course_classes` | 0 | Dapodik API |
| `Academic/Subject_teachers` | `subject_teachers` | 0 | Dapodik API |
| `Academic/Subjects` | `subjects` | 3 | Dapodik (referensi) |
| `Academic/Majors` | `majors` | 0 | Dapodik (referensi) |
| `Academic/Academic_years` | `academic_years` | 0 | Dapodik |
| `Academic/Import_students` | — | — | Import CSV Dapodik |
| `Academic/Import_alumni` | — | — | Import CSV Dapodik |
| `Reference/Buildings` | `options` (filter `building`) | 2 | Dapodik |
| `Reference/Rooms` | `rooms` | 3 | Dapodik |
| `Reference/Educations` | `options` (filter `education`) | 11 | Pusdatin (referensi) |
| `Reference/Employments` | `options` (filter `employment`) | 13 | Pusdatin (referensi) |
| `Reference/Special_needs` | `options` (filter `special_need`) | 19 | Dapodik (referensi) |

#### B. Modul GTK (Info GTK)
| Controller | Tabel | Pengganti |
|------------|-------|-----------|
| `Employees/Employees` | `users` (filter `is_employee`) | Info GTK API |
| `Employees/Employment_types` | `options` (filter `employment_type`) | Info GTK (referensi) |
| `Employees/Import` | — | Import CSV Info GTK |
| `Employee_profile` (read-only view) | `users` | Info GTK |

#### C. Modul Penilaian & Ujian (Rapor Pendidikan)
| Controller | Tabel | Status DB | Pengganti |
|------------|-------|-----------|-----------|
| `Admission/Exam_scores` | `admission_exam_scores` | ❌ tidak ada | Rapor Pendidikan |
| `Admission/Semester_report_scores` | `semester_report_scores` | ❌ tidak ada | Rapor Pendidikan |
| `Admission/National_exam_scores` | `national_exam_scores` | ❌ tidak ada | Rapor Pendidikan |
| `Admission/Exam_schedules` | `admission_exam_schedules` | — | Rapor Pendidikan |
| `Admission/Exam_attendances` | `admission_exam_attendances` | — | Rapor Pendidikan |
| `Admission/Exam_card` | — | — | Cetak dari Dapodik |
| `Admission/Subject_national_exam_*` | `admission_subject_national_exam_*` | — | Rapor Pendidikan |
| `Admission/Subject_semester_report_*` | `admission_subject_semester_report_*` | — | Rapor Pendidikan |
| `Admission/Verification_subject_scores` | — | — | Rapor Pendidikan |
| `Admission/Selection_process` | `selection_process` | — | PPDB Online (pemerintah) |

#### D. Modul Presensi (Rapor Pendidikan)
| Controller | Tabel | Rows | Pengganti |
|------------|-------|------|-----------|
| `Academic/Daily_attendance_report` | `daily_attendance_report` | 0 | Rapor Pendidikan |
| `Academic/Subject_attendance_report` | `subject_attendance_report` | 0 | Rapor Pendidikan |
| `Academic/Semester_attendance_report` | `semester_attendance_report` | 0 | Rapor Pendidikan |
| `Academic/Student_attendance_report` | `student_attendance_report` | 0 | Rapor Pendidikan |
| `Student_presence` (root) | `meeting_attendences` | 0 | Rapor Pendidikan |

#### E. Modul PPDB (PPDB Online)
| Controller | Tabel | Pengganti |
|------------|-------|-----------|
| `Admission/Registrants` | `users` (filter `is_prospective_student`) | PPDB Online |
| `Admission/Registrants_approved` | view | PPDB Online |
| `Admission/Registrants_unapproved` | view | PPDB Online |
| `Admission/Quotas` | `admission_quotas` | PPDB Online |
| `Admission/Phases` | `admission_phases` | PPDB Online |
| `Admission/Types` | `options` (filter `admission_type`) | PPDB Online |
| `Admission/Settings` | `options` (admission) | PPDB Online |
| `Admission/Form_settings` | `form_settings` | ⚠️ Pertahankan (custom field) |
| `Admission/Subject_setting_*` | `admission_subject_settings` | PPDB Online |

#### F. Modul Statistik (Satu Data)
| Controller | Tabel | Pengganti |
|------------|-------|-----------|
| `Academic/By_class_groups` | — | Embed widget Satu Data |
| `Academic/By_student_status` | — | Embed widget Satu Data |
| `Academic/By_end_date` | — | Embed widget Satu Data |
| `Achievements` (root) | `achievements` (kosong) | Satu Data |
| Dashboard widget counters (13 cards) | `M_dashboard::widgetBox()` | Satu Data + read-only sync dari Dapodik |

### 3.2 🟡 DEPRESIASI BERTahap (transisi 1-2 semester)

| Fitur | Alasan tetap | Timeline |
|-------|--------------|----------|
| `Scholarships` (5 jenis internal) | Sekolah punya beasiswa non-PIP (Anak Berprestasi, dll) | Tetap, tambah badge "Lihat di Sipintar untuk PIP" |
| `Employee_profile` (read-only) | Riwayat kepegawaian di sekolah | Ganti jadi link ke Info GTK |
| `Student_profile` (read-only) | Catatan khusus sekolah (riwayat pelanggaran, dll) | Tambah info "Validasi NIK di Verval PD" |

### 3.3 ✅ PERTAHANKAN (tidak ada di platform pemerintah)

#### A. Konten Website Publik
- `Blog/Posts`, `Pages`, `Post_categories`, `Tags`, `Post_comments`
- `Blog/Messages` (pesan dari form kontak)
- `Blog/Image_sliders`, `Quotes`, `Opening_speech`, `Subscribers`, `Links`
- `Media/Files`, `Albums`, `Videos` (media library website)
- `Appearance/Menus`, `Themes` (kustomisasi tampilan)
- `Plugins/Questions` (polling), `Answers`, `Banners`

#### B. Pengaturan Website
- `Settings/General`, `School_profile` (frontend), `Media`, `Writing`, `Reading`, `Discussion`, `Social_account`, `Mail_server`

#### C. Keamanan & User Management
- `Users/Administrator`, `Employees`, `Students`, `Modules`, `User_groups`, `User_privileges`
- `Login`, `Logout`, `Profile`, `Change_password`, `Lost_password`, `Reset_password`

#### D. Pendukung PPDB Custom
- `Admission/Form_settings` — sekolah butuh field khusus di atas PPDB Online standar

---

## 4. Arsitektur Target

### 4.1 Struktur Menu Sidebar Baru

```
📊 Dashboard
   └─ Widget Dapodik (sync status, GTK aktif, PD aktif)
   └─ Quick links ke 9 platform pemerintah
   └─ Statistik ringkas (embed Satu Data)

📝 Konten Website
   ├─ Blog (Posts, Pages, Categories, Tags, Comments)
   ├─ Media (Files, Albums, Videos)
   ├─ Pesan (Messages, Subscribers)
   ├─ Tampilan (Quotes, Opening_speech, Image_sliders, Links, Menus, Themes)
   └─ Plugin (Questions/Answers, Banners)

🎓 PPDB Online
   ├─ Formulir Pendaftaran (custom field)
   └─ Pengaturan Gelombang (override PPDB Online)

👥 Pengguna CMS
   ├─ Administrator
   ├─ Grup Pengguna
   ├─ Hak Akses
   └─ Modul

⚙️ Pengaturan
   ├─ Umum, Profil Sekolah (frontend), Media, Penulisan, Pembacaan
   ├─ Diskusi, SMTP, Jejaring Sosial
   └─ Tema

🔗 Integrasi Pemerintah  ← MENU BARU
   ├─ Dapodik        (status sinkronisasi, sync now)
   ├─ Pusdatin       (validasi NPSN, NISN)
   ├─ Verval PD      (validasi NIK siswa)
   ├─ ARKAS          (link ke arkas.kemdikbud.go.id)
   ├─ SIPLah         (link ke siplah.kemdikbud.go.id)
   ├─ Sipintar/PIP   (link + embed status PIP siswa)
   ├─ Rapor Pendidikan (link raporpendidikan.kemdikbud.go.id)
   ├─ Info GTK       (link info.gtk.kemdikbud.go.id)
   └─ Satu Data      (embed widget statistik)

🛠️ Maintenance
   ├─ Backup Apps
   └─ Backup Database
```

### 4.2 Modul Baru: Integrasi Pemerintah

```
app/
├── Controllers/
│   └── Integration/
│       ├── Dapodik.php           (sinkronisasi & status)
│       ├── Pusdatin.php          (validasi NPSN/NISN)
│       ├── VervalPD.php          (validasi NIK)
│       ├── Arkas.php             (link/info)
│       ├── Siplah.php            (link/info)
│       ├── Sipintar.php          (link/info)
│       ├── RaporPendidikan.php   (link/info)
│       ├── InfoGtk.php           (link/info)
│       └── SatuData.php          (embed widget)
├── Services/
│   └── GovernmentApiService.php  (HTTP client untuk API pemerintah)
├── Models/
│   ├── M_dapodik_sync.php        (log sinkronisasi)
│   └── M_integration_settings.php (token, konfigurasi API)
└── Views/
    └── integration/
        ├── dapodik.php
        ├── pusdatin.php
        ├── verval_pd.php
        └── ... (9 views)
```

### 4.3 API Service Layer

```php
// app/Services/GovernmentApiService.php
class GovernmentApiService
{
    protected array $endpoints = [
        'dapodik'           => 'https://api.dapodik.kemdikbud.go.id/v1/',
        'pusdatin'          => 'https://referensidata.kemdikbud.go.id/api/',
        'verval_pd'         => 'https://vervalpd.data.kemdikbud.go.id/api/',
        'pip'               => 'https://pip.kemdikbud.go.id/api/',
        'info_gtk'          => 'https://info.gtk.kemdikbud.go.id/api/',
        'satu_data'         => 'https://satudata.kemdikbud.go.id/api/',
    ];

    public function validateNisn(string $nisn): array;
    public function validateNik(string $nik): array;
    public function validateNpsn(string $npsn): array;
    public function getStudentByNisn(string $nisn): array;
    public function getEmployeeByNuptk(string $nuptk): array;
    public function getSchoolStatistics(string $npsn): array;
}
```

---

## 5. Roadmap Implementasi

### Phase 1: Foundation (Minggu 1-2)
**Tujuan:** Siapkan infrastruktur integrasi tanpa mengubah menu apapun.

- [ ] Buat `app/Services/GovernmentApiService.php` dengan HTTP client (Guzzle/CURL)
- [ ] Buat `app/Models/M_dapodik_sync.php` (log sinkronisasi)
- [ ] Buat `app/Models/M_integration_settings.php` (token API per platform)
- [ ] Tambah tabel `integration_settings` (token, last_sync, status)
- [ ] Tambah tabel `dapodik_sync_log` (riwayat sync)
- [ ] Tambah menu config di `app/Config/Services.php`
- [ ] Update `app/Config/Routes.php` untuk grup `integration/*`
- [ ] Update `app/Config/Filters.php` untuk filter `integration` di `PrivilegeFilter`

### Phase 2: Sidebar Modernization (Minggu 2)
**Tujuan:** Restrukturisasi sidebar menu, kelompokkan berdasarkan fungsi.

- [ ] Update `app/Views/backend/sidebar.php` dengan struktur baru
- [ ] Tambah grup menu "Integrasi Pemerintah" (placeholder, link ke #)
- [ ] Deprecate menu `academic/*` (kecuali yang dipakai khusus)
- [ ] Deprecate menu `admission/*` (kecuali `form_settings`)
- [ ] Deprecate menu `employees/*`, `reference/*` (kecuali beberapa)
- [ ] Tambah badge "Deprecated" di menu yang akan dihapus

### Phase 3: Modul Dapodik (Minggu 3-4) — Prioritas Tertinggi
**Tujuan:** Modul paling kritikal karena Dapodik = sumber data utama.

- [ ] `app/Controllers/Integration/Dapodik.php`
  - Method `index()` — status sinkronisasi terakhir
  - Method `sync()` — panggil API Dapodik, simpan ke `M_dapodik_sync`
  - Method `schoolInfo()` — info sekolah dari Dapodik (read-only)
- [ ] `app/Models/M_dapodik_sync.php`
  - Method `getLastSync()`
  - Method `logSync($status, $message)`
- [ ] `app/Views/integration/dapodik.php` — status page dengan indicator (🟢 synced, 🟡 stale, 🔴 error)
- [ ] Cron job setup: `spark dapodik:sync` (jalankan tiap 6 jam)

### Phase 4: Modul Pusdatin & Verval PD (Minggu 4-5)
**Tujuan:** Validasi referensi & data diri siswa.

- [ ] `app/Controllers/Integration/Pusdatin.php`
  - `validateNpsn($npsn)` — API call ke Pusdatin
  - `validateNisn($nisn)` — API call ke Pusdatin
  - `bulkValidate()` — validasi semua siswa sekaligus
- [ ] `app/Controllers/Integration/VervalPD.php`
  - `validateNik($nik)` — API call ke Verval PD
  - `bulkValidate()` — validasi semua siswa
- [ ] `app/Views/integration/pusdatin.php` — form validasi
- [ ] `app/Views/integration/verval_pd.php` — form validasi

### Phase 5: Modul Pip/InfoGTK/Etc (Minggu 5-6)
**Tujuan:** Quick-win link-only integration.

- [ ] `app/Controllers/Integration/Arkas.php` — link langsung ke ARKAS
- [ ] `app/Controllers/Integration/Siplah.php` — link ke SIPLah
- [ ] `app/Controllers/Integration/Sipintar.php` — link + embed status PIP siswa
- [ ] `app/Controllers/Integration/RaporPendidikan.php` — link
- [ ] `app/Controllers/Integration/InfoGtk.php` — link
- [ ] `app/Controllers/Integration/SatuData.php` — embed widget iframe
- [ ] Tambah filter `after` di sidebar untuk user_role tertentu (operator hanya lihat menu relevan)

### Phase 6: Data Migration & Cleanup (Minggu 6-8)
**Tujuan:** Migrasi data dari CMS ke Dapodik, archival tabel yang sudah tidak dipakai.

- [ ] Export data `users` (filter `is_student`) → CSV format Dapodik
- [ ] Export data `users` (filter `is_employee`) → CSV format Dapodik GTK
- [ ] Arsipkan tabel ke tabel `_archive_*` (jangan hapus, untuk audit)
- [ ] Hapus controller yang sudah di-deprecate
- [ ] Hapus model yang sudah tidak dipakai (atau tandai `@deprecated`)
- [ ] Update dokumentasi di setiap file yang di-deprecate

### Phase 7: Testing & Documentation (Minggu 8)
- [ ] Unit test untuk `GovernmentApiService` (mock API response)
- [ ] Integration test untuk sinkronisasi Dapodik
- [ ] E2E test untuk full flow login → sync → display
- [ ] Update `README.md` dengan list integrasi
- [ ] Buat `docs/INTEGRATION.md` (panduan developer)
- [ ] Buat `docs/OPERATIONS.md` (panduan operator sekolah)

---

## 6. Strategi Transisi (Tanpa Disrupsi)

### 6.1 Prinsip "Read-Only First"
- **JANGAN langsung hapus** modul lama
- Ganti **input form** dengan **read-only view** + link "Kelola di Dapodik"
- Tetap izinkan export data dari CMS untuk migrasi awal

### 6.2 Coexistence Pattern
```
[Deprecated Menu]                 [New Integration Menu]
academic/students        →   integration/dapodik (status)
                              (link "Kelola di Dapodik" → eksternal)
academic/employees       →   integration/info_gtk
                              (link "Kelola di Info GTK" → eksternal)
academic/by_class_groups →   integration/satu_data
                              (embed iframe widget Satu Data)
```

### 6.3 Pesan Transisi ke User
- Banner di top dashboard: *"Mulai semester ini, data Pokok Pendidikan dikelola langsung di Dapodik. [Baca panduan migrasi]"*
- Email blast ke operator sekolah dengan checklist migrasi
- Video tutorial YouTube (3 menit): "Migrasi dari CMS ke Dapodik dalam 5 langkah"

---

## 7. Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|--------|--------|----------|
| API pemerintah down/rate limit | Sinkronisasi gagal | Retry dengan exponential backoff, cache 6 jam |
| Data CMS tidak match Dapodik | Error validasi | Tampilkan diff di dashboard, izinkan operator reconcile |
| Koneksi internet sekolah terbatas | API call gagal | Mode offline dengan antrian sync, sync saat online |
| Token API bocor | Akses tidak sah | Simpan di `.env`, encrypt di DB, rotate tiap 90 hari |
| Sekolah tidak mau pindah | Adopsi rendah | Coexistence 1-2 semester, training, incentive |
| Schema Dapodik berubah | API break | Versioning di URL, fallback ke versi sebelumnya |
| Dapodik tutup/tidak ada API publik | Tidak bisa sync | Mode manual: export CSV, upload ke CMS, sinkronisasi via file |

---

## 8. Acceptance Criteria

### 8.1 Phase 1-2 selesai jika:
- [ ] `GovernmentApiService` bisa melakukan HTTP GET/POST ke 6 endpoint
- [ ] Tabel `integration_settings` dan `dapodik_sync_log` terbuat dan ter-migrate
- [ ] Sidebar baru ter-render dengan grup menu "Integrasi Pemerintah"
- [ ] Semua 96 URL existing masih HTTP 200 (zero regression)
- [ ] 0 error di `writable/logs/`

### 8.2 Phase 3 (Dapodik) selesai jika:
- [ ] Halaman `/integration/dapodik` menampilkan status terakhir sync
- [ ] Tombol "Sync Sekarang" memicu API call + simpan log
- [ ] Cron `spark dapodik:sync` jalan tiap 6 jam via supervisor/cron
- [ ] Badge sync: 🟢 (kurang 6 jam) | 🟡 (6-24 jam) | 🔴 (lebih 24 jam)
- [ ] Read-only view info sekolah dari Dapodik tersedia

### 8.3 Phase 4-5 selesai jika:
- [ ] Validasi NPSN real-time berfungsi (test dengan NPSN valid/invalid)
- [ ] Validasi NISN real-time berfungsi
- [ ] Validasi NIK real-time berfungsi
- [ ] Link eksternal ke 6 platform (ARKAS, SIPLah, dll) terbuka di tab baru
- [ ] Embed widget Satu Data render di halaman `/integration/satu_data`

### 8.4 Phase 6-7 selesai jika:
- [ ] Semua controller di-deprecate memiliki banner "@deprecated" di PHP doc
- [ ] Dokumentasi `docs/INTEGRATION.md` dan `docs/OPERATIONS.md` selesai
- [ ] Test coverage untuk `GovernmentApiService` minimal 80%
- [ ] E2E test pass untuk flow: login → dashboard → integration → external link

---

## 9. Metrik Kesuksesan (Post-Launch)

| Metrik | Target 3 bulan | Target 6 bulan |
|--------|----------------|----------------|
| Adopsi menu Integrasi Pemerintah | 50% sekolah aktif | 90% sekolah aktif |
| Frekuensi sync Dapodik otomatis | Rata-rata 4x/hari | Rata-rata 6x/hari |
| Error rate API pemerintah | < 5% | < 2% |
| Pengurangan tabel/modul CMS aktif | -20% controller | -40% controller |
| Traffic ke platform pemerintah dari CMS | +30% referral | +60% referral |
| User satisfaction (skala 1-5) | ≥ 3.5 | ≥ 4.0 |

---

## 10. Referensi

### 10.1 Dokumentasi Resmi Platform Pemerintah
- Dapodik: https://dapo.dikdasmen.kemdikbud.go.id
- Pusdatin: https://referensi.data.kemdikbud.go.id
- Verval PD: https://vervalpd.data.kemdikbud.go.id
- ARKAS: https://arkas.kemdikbud.go.id
- SIPLah: https://siplah.kemdikbud.go.id
- PIP: https://pip.kemdikbud.go.id
- Rapor Pendidikan: https://raporpendidikan.kemdikbud.go.id
- Info GTK: https://info.gtk.kemdikbud.go.id
- Satu Data Kemendikdasmen: https://satudata.kemdikbud.go.id

### 10.2 Dokumen Internal
- `README.md` — setup project
- `docs/INTEGRATION.md` (akan dibuat) — panduan developer
- `docs/OPERATIONS.md` (akan dibuat) — panduan operator sekolah
- `writable/logs/log-*.log` — application logs

### 10.3 Code Reference Penting
- Sidebar menu: `app/Views/backend/sidebar.php`
- Filter privilege: `app/Filters/PrivilegeFilter.php`
- Session management: `app/Config/Session.php`
- Base model: `app/Models/BaseModel.php`
- Dashboard controller: `app/Controllers/Dashboard.php`
- Widget dashboard: `app/Models/M_dashboard.php::widgetBox()`

---

## 11. Changelog

| Versi | Tanggal | Perubahan |
|-------|---------|-----------|
| 1.0 | 2026-06-07 | Dokumen awal — analisis & roadmap |

---

> **Catatan:** Dokumen ini akan di-update setiap akhir sprint. Owner: Tim Pengembang Webskul.
