ACARA 17 — REST API MVC CRUD Mahasiswa

1. Salin folder acara17 ke C:\laragon\www\bkpm-webserver\
2. Buka Laragon dan Start All.
3. Import database/db_workshop.sql melalui phpMyAdmin (jika database belum ada).
4. Sesuaikan koneksi di app/config/Database.php bila konfigurasi MySQL berbeda.
5. Buka:
   http://localhost/bkpm-webserver/acara17/public/mahasiswa
6. Uji di Postman. PUT dan DELETE menggunakan parameter ?id=1.

Contoh POST JSON:
{"nim":"220100101","nama":"Andi Saputra","prodi":"Teknik Informatika"}

Catatan: Acara 17 menggunakan struktur MVC dan response sederhana sesuai modul.
Acara 18 memperkenalkan class Response terpisah.
