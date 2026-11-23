# Bahan Laporan Akhir Praktikum — Perjalanan Proyek Acara 17-30

Ringkasan ini dapat dipakai langsung untuk langkah 5 poin 3 Acara 30. Isi kolom "Bukti" dengan nomor gambar pada laporan.

## 1. Perjalanan proyek

| Acara | Topik | Hasil utama pada proyek | Bukti |
|---|---|---|---|
| 17 | Fondasi MVC dan API CRUD | Front Controller `public/index.php`, `Router`, `Response`, Model + Controller mahasiswa | |
| 19 | Registrasi pengguna | `AuthController::register()`, hash `password_hash()` | |
| 20-21 | Autentikasi JWT | `AuthController::login()`, `Auth::generateToken/validateToken`, token 5 menit | |
| 22-23 | Validasi dan pengujian blackbox | Validasi NIM 9 digit, TC01-TC17, perbaikan 409 menjadi 422 | |
| 24 | Dokumentasi API | `API_DOCUMENTATION.md`, `openapi.yaml`, koleksi Postman | |
| 25 | RBAC | `Auth::requireAdmin()` (403 untuk non-admin) | |
| 26 | Unggah berkas dan backup | Unggah foto aman (`exif_imagetype`), `BACKUP_SCHEDULE.md` | |
| 27 | View (frontend) | Halaman login, dashboard, form tambah/ubah, toast, validasi sisi klien | |
| 28 | Deployment ke hosting | Unggah ke cPanel, impor database, `BASE_URL`, pemisahan `app/` | |
| 29 | Integrasi backend-frontend | Loading state, `escapeHTML()`, `fetchRetry()`, try-catch-finally pada form | |
| 30 | Deployment produksi | HTTPS, `app/.htaccess`, `display_errors` Off, kredensial di `config.local.php` | |

> Sesuaikan nomor acara dengan modul bila ada yang bergeser; Acara 18 dan sebagian lain tidak dibahas di sini karena tidak ada di berkas proyek.

## 2. Diagram akhir struktur proyek

```
(di luar public_html bila hosting mengizinkan)
app/                      <- lapisan Model, Controller, Core (tidak boleh diakses browser)
├── .htaccess             Require all denied
├── config/
│   ├── Config.php        pembaca konfigurasi (env, DB, JWT secret)
│   ├── Database.php      koneksi PDO memakai Config
│   ├── config.local.php  KREDENSIAL PRODUKSI (tidak masuk Git)
│   └── config.local.example.php
├── controllers/          MahasiswaController, AuthController, BukuController
├── models/               Mahasiswa, User, Buku
└── core/                 Router, Response, Auth, autoload

public_html/ (= isi public/)  <- Front Controller dan View
├── .htaccess             HTTPS redirect + rewrite ke index.php
├── index.php             Front Controller (pengaturan error produksi, CORS, Router)
├── uploads/              foto mahasiswa (skrip tidak dieksekusi)
└── views/                index, login, dashboard, form_tambah, form_edit (HTML + JS + CSS)

docs/                     dokumentasi (tidak untuk publik)
database/backup/          berkas backup SQL
```

Alur permintaan: Browser → `.htaccess` (HTTPS + rewrite) → `index.php` → `Router` → `Controller` (cek `Auth`) → `Model` (PDO) → `Response::json()` → View menampilkan data (di-escape).

## 3. Ringkasan pengujian
- Blackbox API: TC01-TC17 (`docs/TEST_CASE.md`), retest produksi pada bagian 5.
- Antarmuka: UI01-UI11 (`docs/TEST_FRONTEND.md`).
- Keamanan produksi: SEC01-SEC06 (`docs/TEST_CASE.md`).
