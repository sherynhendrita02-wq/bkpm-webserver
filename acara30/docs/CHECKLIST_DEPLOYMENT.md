# Checklist Akhir dan Serah Terima Proyek — Acara 30

Centang (`[x]`) setelah diperiksa di server produksi.

## A. Keamanan dan konfigurasi
- [ ] Domain dapat diakses lewat `https://` dan sertifikat AutoSSL aktif (cPanel → SSL/TLS Status → Run AutoSSL)
- [ ] Akses `http://` otomatis dialihkan ke `https://` (aturan di `public/.htaccess` atau menu Force HTTPS Redirect)
- [ ] `display_errors = Off`, `log_errors = On`, `error_log` mengarah ke `/home/namacpanel/logs/php_errors.log` (MultiPHP INI Editor)
- [ ] `app/.htaccess` berisi `Require all denied`; `app/` berada di luar `public_html` bila memungkinkan
- [ ] `app/config/config.local.php` dibuat di server (dari `config.local.example.php`) dan **tidak** ada di GitHub
- [ ] `jwt_secret` produksi diganti dengan nilai acak, bukan nilai bawaan
- [ ] `public/uploads/` tidak mengeksekusi skrip (`.htaccess` di dalamnya aktif)
- [ ] Berkas dokumentasi (`docs/`) tidak dapat dibuka publik

## B. Fungsional
- [ ] Seluruh endpoint dapat diakses melalui HTTPS
- [ ] TC01-TC17 diuji ulang pada produksi (Environment Postman "Production") — hasil di `docs/TEST_CASE.md` bagian 5
- [ ] Alur View: login → dashboard → tambah (foto) → ubah → hapus berjalan
- [ ] Uji `<b>Coba</b>` pada field nama: tampil sebagai teks (lihat `docs/TEST_FRONTEND.md`)

## C. Dokumentasi
- [ ] `docs/API_DOCUMENTATION.md` dan `docs/openapi.yaml` memuat base URL produksi `https://...`
- [ ] `docs/postman_collection.json` dan `docs/postman_environment_production.json` terbaru
- [ ] `docs/BACKUP_SCHEDULE.md` dan `docs/DISASTER_RECOVERY.md` terbaru
- [ ] Laporan akhir praktikum (Acara 17-30) disusun — bahan di `docs/LAPORAN_AKHIR_BAHAN.md`

## D. Serah terima
- [ ] Backup terbaru `db_workshop_YYYY-MM-DD.sql` tersimpan di luar server
- [ ] Folder `public/uploads/` ikut dicadangkan (tidak ada di berkas SQL)
- [ ] Akun admin awal diberikan ke pemilik; kata sandi bawaan diganti
