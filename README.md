# Webskul

> **Content Management System & PPDB Online** untuk SD, SMP/Sederajat, SMA/Sederajat.
> Versi 3.0 — rebranded dari **CMS Sekolahku Premium** ke **Webskul**, dengan upgrade
> dari CodeIgniter 3 ke CodeIgniter 4.

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.7-EF4223?logo=codeigniter&logoColor=white)](https://codeigniter.com)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

---

## Tentang Webskul

Webskul adalah platform **website sekolah + PPDB online** all-in-one yang menyediakan
konten publik (artikel, pengumuman, galeri, video, alumni) dan area admin untuk
mengelola data master sekolah (guru, siswa, kelas, mata pelajaran, nilai,考勤, dll).

Project ini dimulai dari **CMS Sekolahku Premium v2.5.4** (CI3) dan telah di-port
ke **CodeIgniter 4.7** dengan:

- Arsitektur MVC yang dirapikan (filter, entity, BaseModel, namespace konsisten)
- Auto-route aktif (controller method otomatis menjadi URL endpoint)
- Session pakai database handler (tabel `_sessions`) — aman untuk multi-server
- `BaseController` memuat `general`, `school_profile`, `mail_server`, `academic`
  ke dalam session untuk akses cepat dari seluruh view
- Privilege filter yang proteksi area admin berdasarkan `user_privileges`
- UI dimodernkan (AdminLTE 2 di-override dengan CSS custom — compact & rounded)
- 4 tema publik (1, 2, 3, 4) bisa diganti dari `/appearance/themes`

## Fitur Utama

### Area Publik
- Homepage, blog (post + kategori + tag + arsip), halaman statis
- Galeri foto & video, direktori alumni, direktori siswa
- PPDB online: formulir pendaftaran, seleksi, cetak formulir & kartu ujian
- Pengumuman (opening speech kepala sekolah), polling, pencarian
- RSS feed, sitemap, contact us, lost-password, reset-password

### Area Admin (`/dashboard`)
- 10 modul: Dashboard, Profil, Akademik, PPDB, Tampilan, Blog, Media, Plugins,
  Data Induk (Reference), Pengaturan, Users, Maintenance
- 86 URL sidebar (CRUD masing-masing modul)
- Grid builder + Form builder JS untuk konsistensi tabel/form
- Backup & restore database (manual, bukan pakai `BaseUtils::backup()`)
- Backup aplikasi (.zip) dari `/maintenance/backup_apps`
- Manajemen tema, menu, widget, image slider, album, file
- Pengaturan: profil sekolah, mail server, social account, form field settings
- Impor massal: siswa (`/academic/import_students`), alumni
- Modul Dapodik: phases, quotas, exam schedules, exam scores, subject settings
- 5 controller Data Induk: buildings, rooms, educations, employments, special_needs

## Persyaratan Sistem

- **PHP 8.2+** (tested on 8.3 dan 8.4)
- **MySQL 5.7+** atau MariaDB 10.3+ (utf8mb4)
- Extension PHP: `intl`, `mbstring`, `json`, `mysqlnd`, `libcurl`
- Composer (untuk dependency management)
- Web server: Apache/Nginx (atau `php -S` untuk development)

## Instalasi

```bash
# 1. Clone / extract project
cd /path/to/webskul

# 2. Install dependency
composer install

# 3. Setup environment
cp env .env
# Edit .env: baseURL, database.default.*, session.* (lihat bagian "Konfigurasi")

# 4. Import database
mysql -u root -p < db_premium_cms_sekolahku.sql

# 5. Pastikan folder writable bisa ditulis
chmod -R 775 writable/

# 6. (Opsional) Set custom .so extensions
#    Edit php-ext/ext.ini jika perlu custom MySQL driver
```

## Konfigurasi (`.env`)

```ini
CI_ENVIRONMENT = production

app.baseURL = 'http://localhost:8080/'      # sesuaikan dengan domain Anda

database.default.hostname = 127.0.0.1
database.default.database = db_premium_cms_sekolahku
database.default.username = root
database.default.password = root
database.default.DBDriver = MySQLi
database.default.port     = 3306
database.default.charset  = utf8mb4
database.default.DBCollat = utf8mb4_unicode_ci
database.default.DBDebug = true              # false di production
```

## Menjalankan (Development)

```bash
# PHP built-in server (paling cepat untuk dev)
php -S 127.0.0.1:8080 -t public

# atau指定 port lain:
php -S 127.0.0.1:18080 -t public
```

Akses:
- **Publik**: http://127.0.0.1:8080/
- **Login admin**: http://127.0.0.1:8080/login

**Default login (ganti setelah login pertama!):**

| Field    | Value                |
|----------|----------------------|
| Email    | `super@admin.com`    |
| Password | `admin123`           |

## Struktur Folder

```
webskul/
├── app/                    # Kode aplikasi
│   ├── Config/             # Routes, Filters, Database, Session, dll
│   ├── Controllers/        # 60+ controller (Admin, Public, API)
│   ├── Models/             # 40+ model (M_users, M_posts, M_settings, dll)
│   ├── Views/              # Backend layout + form/grid views
│   │   └── themes -> ../../public/themes   # symlink ke tema publik
│   ├── Filters/            # AuthFilter, PrivilegeFilter, MaintenanceFilter
│   ├── Helpers/            # core, datasource, recaptcha, setting, blog
│   └── Libraries/          # Auth, Token
├── public/                 # Document root (yang di-serve ke user)
│   ├── assets/             # CSS, JS, plugin (adminLTE, select2, toastr)
│   ├── themes/             # 4 tema publik (1, 2, 3, 4)
│   ├── media_library/      # Upload (posts, albums, banners, files)
│   └── index.php           # Front controller
├── writable/               # logs, cache, session (db), backup, uploads
│   ├── backup/             # Output backup DB (.sql) & app (.zip)
│   ├── logs/               # Log harian (log-YYYY-MM-DD.log)
│   └── session/            # (kosong — session pakai DB)
├── docs/
│   └── REFACTOR_PLAN.md    # Roadmap integrasi 9 platform pemerintah
├── vendor/                 # Composer dependencies
├── composer.json           # Codeigniter4/framework, tecnickcom/tcpdf
├── php-ext/ext.ini         # Custom MySQL driver (.so)
├── .env                    # Environment-specific config
└── README.md               # ← Anda di sini
```

## URL Conventions

| Pola                        | Contoh                                      |
|-----------------------------|---------------------------------------------|
| `/`                         | Homepage (theme aktif)                      |
| `/read/{id}/{slug}`         | Detail post                                 |
| `/kategori/{slug}`          | Post per kategori                           |
| `/tag/{slug}`               | Post per tag                                |
| `/arsip/{YYYY}/{MM}`        | Post per bulan                              |
| `/hubungi-kami`             | Form kontak                                 |
| `/login`                    | Halaman login (publik + AJAX)               |
| `/lost-password`            | Form lupa password                          |
| `/dashboard`                | Admin area (butuh session)                  |
| `/blog/posts`               | CRUD posts di admin                         |
| `/reference/buildings`      | CRUD gedung                                 |
| `/maintenance/backup_database` | Trigger backup DB                        |

## Rebrand: CMS Sekolahku → Webskul

Project ini dulunya bernama **CMS Sekolahku** oleh Anton Sofyan (4ntonsofyan@gmail.com).
Dirawat dan di-rebrand ke **Webskul** oleh **Rasyiqi** (no-reply@webskul.web.id)
sejak Juni 2026, dengan ringkasan perubahan:

| Aspek                    | Sebelum                          | Sesudah                          |
|--------------------------|----------------------------------|----------------------------------|
| Brand name               | CMS Sekolahku                    | **Webskul**                      |
| Domain placeholder       | `sekolahku.web.id`               | `webskul.web.id`                 |
| Email sender             | `4ntonsofyan@gmail.com`          | `no-reply@webskul.web.id`        |
| Folder project           | `cms-sekolahku`                  | `webskul`                        |
| Session cookie           | `_sessions`                      | `webskul_session` (prefix `webskul_`) |
| Framework                | CodeIgniter 3                    | **CodeIgniter 4.7**              |
| Database name            | `db_premium_cms_sekolahku`       | (tetap — tidak di-rename)        |

Lihat juga: [docs/REFACTOR_PLAN.md](docs/REFACTOR_PLAN.md) untuk roadmap integrasi
9 platform pemerintah (Dapodik, Pusdatin, Verval PD, ARKAS, SIPLah, PIP, Rapor
Pendidikan, Info GTK, Satu Data).

## Changelog Singkat

### 3.0.0 (Juni 2026) — Rebrand & CI4 Migration
- **BREAKING:** Migrasi dari CodeIgniter 3 ke CodeIgniter 4.7
- **BREAKING:** Folder project di-rename `cms-sekolahku` → `webskul`
- Brand "CMS Sekolahku" diganti "Webskul" di semua UI, config, dan seed DB
- Session cookie prefix di-rebrand (`webskul_`)
- Modern UI: AdminLTE 2 di-override dengan CSS custom (compact, rounded, gradient)
- 5 controller Reference baru: Educations, Employments, Special_needs
- Model M_rooms dibuat
- 38 controller di-fix (`$this->vars['rows']` BaseResult → array)
- Lost_password & Reset_password di-fix (extends BaseController, load settings)
- Bug fix: alpha_numeric_spaces → alpha_numeric_space (CI3→CI4 rule)
- Bug fix: textarea `name="name"` → `name="comment_content"` di 4 theme
- Bug fix: Subscriber route panggil `save()` (sebelumnya `index()`)
- Bug fix: contact form `comment_url` rule ditambahkan `permit_empty`

### 2.5.4 (Mei 2022) — Last CI3 version
- Original CMS Sekolahku Premium oleh Anton Sofyan (forked dan dimodifikasi
  oleh Rasyiqi untuk versi Webskul)

## Kontribusi

Project ini adalah fork privat. Untuk kontribusi internal:
1. Buat branch feature dari `main`
2. Commit dengan pesan jelas (conventional commits lebih disukai)
3. Pastikan test lokal lulus sebelum push
4. Buka Pull Request dengan deskripsi perubahan

## Security

- **CSRF**: Token di-generate via `App\Libraries\Token`.Beberapa endpoint publik
  (lost-password, contact-us) **belum** memvalidasi token — perlu ditambah
  `is_valid_token()` check di production deployment.
- **Session**: Disimpan di DB (`_sessions` table) dengan cookie ber-prefix
  `webskul_`. Expiration 7200 detik (2 jam).
- **Privilege**: `App\Filters\PrivilegeFilter` cek segment URL terhadap
  `user_privileges` — `*` bypass semua.
- **Password**: hashed dengan `password_hash(PASSWORD_DEFAULT)` (bcrypt/argon2).

## Lisensi

MIT License — lihat file [LICENSE](LICENSE) untuk detail.

Copyright (c) 2026 Webskul Contributors
Originally Copyright (c) 2010-2022 Anton Sofyan
