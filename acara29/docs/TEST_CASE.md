# Laporan Pengujian — Manual Testing (Blackbox, Equivalence Partitioning)

**Alat uji:** Postman · **Base URL:** `http://localhost/bkpm-webserver/acara29/public`

## 1. Ruang Lingkup

| Endpoint | Method | Controller::method |
|---|---|---|
| `/register` | POST | AuthController::register() |
| `/login` | POST | AuthController::login() |
| `/mahasiswa` | GET | MahasiswaController::index() |
| `/mahasiswa` | POST | MahasiswaController::store() |
| `/mahasiswa?id={id}` | PUT | MahasiswaController::update() |
| `/mahasiswa?id={id}` | DELETE | MahasiswaController::destroy() |

## 2. Hasil Eksekusi Pertama (sebelum perbaikan)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC01 | NIM valid dan lengkap (store) | nim=220100123, nama, prodi | 201 Created | 201 - Data berhasil ditambahkan | Pass |
| TC02 | NIM kosong (store) | nim="" | 400/422 | 400 - Validasi gagal | Pass |
| TC03 | NIM duplikat (store) | nim=220100123 (sudah ada) | 422 Unprocessable | 409 - NIM sudah terdaftar | Fail |
| TC04 | Login kredensial benar | admin / rahasia123 | 200 OK, token diterima | 200 - Login berhasil | Pass |
| TC05 | Login password salah | admin / salah123 | 401 Unauthorized | 401 - Username atau password salah | Pass |
| TC06 | Store tanpa token | tanpa header Authorization | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC07 | Update data valid | id=1, nama diubah | 200 OK | 200 - Data berhasil diperbarui | Pass |
| TC08 | Update ID tidak ada | id=999 | 404 Not Found | 404 - Data tidak ditemukan | Pass |
| TC09 | Update NIM 8 digit (tidak valid) | id=1, nim=23010001 | 422 Unprocessable | 422 - Format NIM tidak valid, harus 9 digit | Pass |
| TC10 | Update nama kosong | id=1, nama="" | 400 Bad Request | 400 - Validasi gagal | Pass |
| TC11 | Update NIM milik data lain | id=1, nim=230100002 | 422 Unprocessable | 409 - NIM sudah digunakan | Fail |
| TC12 | Update tanpa token | id=1, tanpa header | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC13 | Destroy data valid | id=2 | 200 OK | 200 - Data berhasil dihapus | Pass |
| TC14 | Destroy ID tidak ada | id=999 | 404 Not Found | 404 - Data tidak ditemukan | Pass |
| TC15 | Destroy ID non-numerik | id=abc | 400 Bad Request | 400 - ID tidak valid | Pass |
| TC16 | Destroy tanpa token | id=1, tanpa header | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC17 | Destroy token palsu | id=1, token diubah | 401 Unauthorized | 401 - Token tidak valid atau kedaluwarsa | Pass |

Ditemukan **2 test case Fail** (TC03 dan TC11): NIM duplikat dijawab `409 Conflict`, sedangkan rancangan pengujian mengharapkan `422 Unprocessable Entity` (data tidak dapat diproses karena melanggar aturan unik).

## 3. Perbaikan

Perbaikan dilakukan pada lapisan Controller (`app/controllers/MahasiswaController.php`):

- `store()`: pada blok catch (`PDOException` kode 23000 dan `Exception` dari Model `nimExists()`), status diubah dari 409 menjadi 422 dan rincian kesalahan dikirim pada field `errors.nim`.
- `update()`: pada blok catch `PDOException` kode 23000, status diubah dari 409 menjadi 422.

## 4. Hasil Retest (setelah perbaikan)

| ID | Skenario | Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| TC01 | NIM valid dan lengkap (store) | nim=220100123, nama, prodi | 201 Created | 201 - Data berhasil ditambahkan | Pass |
| TC02 | NIM kosong (store) | nim="" | 400/422 | 400 - Validasi gagal | Pass |
| TC03 | NIM duplikat (store) | nim=220100123 (sudah ada) | 422 Unprocessable | 422 - NIM sudah terdaftar | Pass |
| TC04 | Login kredensial benar | admin / rahasia123 | 200 OK, token diterima | 200 - Login berhasil | Pass |
| TC05 | Login password salah | admin / salah123 | 401 Unauthorized | 401 - Username atau password salah | Pass |
| TC06 | Store tanpa token | tanpa header Authorization | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC07 | Update data valid | id=1, nama diubah | 200 OK | 200 - Data berhasil diperbarui | Pass |
| TC08 | Update ID tidak ada | id=999 | 404 Not Found | 404 - Data tidak ditemukan | Pass |
| TC09 | Update NIM 8 digit (tidak valid) | id=1, nim=23010001 | 422 Unprocessable | 422 - Format NIM tidak valid, harus 9 digit | Pass |
| TC10 | Update nama kosong | id=1, nama="" | 400 Bad Request | 400 - Validasi gagal | Pass |
| TC11 | Update NIM milik data lain | id=1, nim=230100002 | 422 Unprocessable | 422 - NIM sudah digunakan | Pass |
| TC12 | Update tanpa token | id=1, tanpa header | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC13 | Destroy data valid | id=2 | 200 OK | 200 - Data berhasil dihapus | Pass |
| TC14 | Destroy ID tidak ada | id=999 | 404 Not Found | 404 - Data tidak ditemukan | Pass |
| TC15 | Destroy ID non-numerik | id=abc | 400 Bad Request | 400 - ID tidak valid | Pass |
| TC16 | Destroy tanpa token | id=1, tanpa header | 401 Unauthorized | 401 - Token tidak ditemukan | Pass |
| TC17 | Destroy token palsu | id=1, token diubah | 401 Unauthorized | 401 - Token tidak valid atau kedaluwarsa | Pass |

**Ringkasan:** 17 test case, 17 Pass, 0 Fail.

Test case TC01-TC06 berasal dari modul; TC07-TC17 adalah tambahan untuk `update()` dan `destroy()` (tugas mandiri) dengan pembagian partisi:

| Method | Partisi Valid | Partisi Tidak Valid |
|---|---|---|
| update | data lengkap & NIM 9 digit (TC07) | ID tidak ada (TC08), NIM 8 digit (TC09), nama kosong (TC10), NIM milik data lain (TC11), tanpa token (TC12) |
| destroy | ID ada (TC13) | ID tidak ada (TC14), ID non-numerik (TC15), tanpa token (TC16), token palsu (TC17) |
