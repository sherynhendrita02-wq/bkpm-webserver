# Kerangka Presentasi Akhir — Sistem Informasi Akademik (Acara 30, Tugas Mandiri 4)

Durasi disarankan: 10-12 menit + tanya jawab. Rubrik modul menilai demonstrasi aplikasi, penjelasan arsitektur, dan kemampuan menjawab pertanyaan.

| Slide | Isi | Yang ditunjukkan |
|---|---|---|
| 1 | Judul, identitas (NIM, nama, golongan) | |
| 2 | Latar belakang dan tujuan | Masalah: pengelolaan data mahasiswa, kebutuhan API yang aman |
| 3 | Arsitektur MVC | Diagram `app/` (Model, Controller, Core) dan `public/` (Front Controller, View) |
| 4 | Alur autentikasi | Login → JWT (5 menit) → header `Authorization: Bearer` → `Auth::requireAuth()` → RBAC `requireAdmin()` |
| 5 | Demo langsung | Login admin → tambah (foto) → ubah → hapus; lalu login user (tombol aksi tersembunyi, API 403) |
| 6 | Keamanan | Prepared statement (anti SQL injection), `escapeHTML` (anti XSS), validasi foto, HTTPS, `app/` tertutup, kredensial di luar kode |
| 7 | Bukti pengujian | Tabel TC01-TC17 (Pass), perbaikan 409→422, retest produksi |
| 8 | Deployment | cPanel, database, AutoSSL, HTTPS redirect, `display_errors` Off |
| 9 | Pemulihan bencana | Hasil simulasi `DROP TABLE` lalu restore (waktu pemulihan) |
| 10 | Kendala, pelajaran, pengembangan lanjutan | Mis. refresh token, rate limiting, pagination |

## Pertanyaan yang mungkin muncul
- Mengapa JWT berlaku hanya 5 menit? (membatasi dampak token bocor; konsekuensinya perlu login ulang atau refresh token)
- Mengapa validasi dilakukan di View dan Controller? (View untuk kenyamanan; Controller satu-satunya yang dapat dipercaya)
- Apa beda 401 dan 403? (belum terautentikasi vs terautentikasi tetapi tidak berhak)
- Mengapa 422, bukan 409, untuk NIM duplikat? (mengikuti rancangan pengujian: data tidak dapat diproses karena melanggar aturan unik)
- Mengapa `app/` ditaruh di luar `public_html`? (agar kode dan konfigurasi tidak dapat diunduh bila PHP gagal dieksekusi)
