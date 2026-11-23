# Backup & Restore Basis Data — db_workshop

## Prosedur Backup (phpMyAdmin)
1. Buka `http://localhost/phpmyadmin`, pilih database **db_workshop**.
2. Klik tab **Export** → metode **Quick**, format **SQL** → **Export**.
3. Simpan berkas `db_workshop.sql` ke folder `database/backup/` dengan nama bertanggal, misalnya `db_workshop_2026-11-16.sql`.

## Prosedur Restore (uji pemulihan)
1. Buat database percobaan baru, misalnya **db_workshop_restore**.
2. Pilih database tersebut → tab **Import** → pilih berkas `.sql` hasil backup → **Import**.
3. Verifikasi tabel **users** dan **mahasiswa** beserta jumlah barisnya sama dengan database asal.

## Alternatif Baris Perintah (XAMPP)
```bash
C:\xampp\mysql\bin\mysqldump -u root db_workshop > database\backup\db_workshop_2026-11-16.sql
C:\xampp\mysql\bin\mysql -u root db_workshop_restore < database\backup\db_workshop_2026-11-16.sql
```

## Jadwal Backup (Tugas Mandiri 2)
| Waktu | Kegiatan | Penanggung Jawab |
|-------|----------|------------------|
| Setiap akhir sesi praktikum | Backup penuh `db_workshop` via phpMyAdmin Export | Mahasiswa |
| Sebelum perubahan struktur tabel (ALTER) | Backup tambahan sebagai titik pemulihan | Mahasiswa |
| Setiap minggu | Uji restore ke database percobaan (`db_workshop_restore`) | Mahasiswa |

Catatan: berkas foto pada `public/uploads/` **tidak ikut** dalam backup SQL (hanya nama berkasnya), sehingga folder tersebut perlu disalin terpisah.
