# Simulasi Pemulihan Bencana (Disaster Recovery) — Acara 30, Tugas Mandiri 3

**Tujuan:** membuktikan bahwa tabel yang hilang dapat dipulihkan dari berkas backup Acara 26.
**Aturan:** lakukan HANYA pada database percobaan `db_workshop_dr`, jangan pada `db_workshop` asli.

## Persiapan
1. Pastikan ada backup terbaru: `database/backup/db_workshop_YYYY-MM-DD.sql` (cara membuat: `docs/BACKUP_SCHEDULE.md`).
2. Buat database percobaan dan isi dari backup:
```bash
mysql -u root -e "CREATE DATABASE db_workshop_dr CHARACTER SET utf8mb4"
mysql -u root db_workshop_dr < database/backup/db_workshop_YYYY-MM-DD.sql
```

## Langkah simulasi
| No | Langkah | Perintah | Bukti (SS) |
|---|---|---|---|
| 1 | Catat kondisi awal | `SELECT COUNT(*) FROM mahasiswa;` | Jumlah baris awal |
| 2 | Simulasikan bencana: hapus tabel | `DROP TABLE mahasiswa;` | `SHOW TABLES;` tanpa `mahasiswa` |
| 3 | Buktikan dampaknya | Pada aplikasi yang diarahkan ke `db_workshop_dr`, buka dashboard atau `GET /mahasiswa` | Respons 500 / pesan galat |
| 4 | Pulihkan dari backup (hanya tabel itu) | lihat di bawah | Perintah sukses |
| 5 | Verifikasi | `SELECT COUNT(*) FROM mahasiswa;` | Jumlah sama dengan langkah 1 |
| 6 | Uji aplikasi | `GET /mahasiswa` dan buka dashboard | Data tampil kembali |

### Langkah 4 — memulihkan satu tabel dari backup penuh
Cara paling mudah (phpMyAdmin): pilih database `db_workshop_dr` → tab **Import** → pilih berkas backup. Bila muncul error "table already exists" untuk tabel lain, hapus tabel yang tersisa lebih dulu, atau gunakan cara baris perintah berikut yang hanya mengekstrak tabel `mahasiswa`:

```bash
# Linux/macOS/Git Bash: ambil bagian tabel mahasiswa saja
sed -n '/^-- Table structure for table `mahasiswa`/,/^-- Table structure for table `users`/p' database/backup/db_workshop_YYYY-MM-DD.sql | mysql -u root db_workshop_dr
```
Jika urutan tabel di berkas backup berbeda, cukup buka berkas `.sql` di editor, salin blok `DROP TABLE IF EXISTS mahasiswa; CREATE TABLE ...; INSERT INTO mahasiswa ...;` ke tab **SQL** phpMyAdmin, lalu **Go**.

## Catatan penting
- Foto di `public/uploads/` tidak ada dalam berkas SQL, jadi tidak ikut pulih. Salin folder itu dari cadangan terpisah.
- Catat waktu pemulihan (RTO) dan selisih data terakhir dengan backup (RPO) pada laporan.
- Setelah selesai, hapus database percobaan: `DROP DATABASE db_workshop_dr;`
