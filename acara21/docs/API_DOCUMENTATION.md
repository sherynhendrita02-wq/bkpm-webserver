# API Documentation - BKPM Web Server (Acara 21)

API BKPM Web Server adalah layanan REST untuk mengelola data mahasiswa dan akun pengguna. API dibuat dengan PHP Native (arsitektur MVC) dan MySQL, memakai format JSON dan autentikasi Bearer Token (JWT).

**Base URL:** `http://localhost/bkpm-webserver/acara21/public`

---

## 1. Ringkasan Seluruh Endpoint

| No | Method | Endpoint | Controller::method | Autentikasi | Keterangan |
|----|--------|----------|--------------------|-------------|------------|
| 1 | POST | `/register` | `AuthController::register()` | Publik | Pendaftaran pengguna baru |
| 2 | POST | `/login` | `AuthController::login()` | Publik | Autentikasi pengguna, menghasilkan token JWT |
| 3 | GET | `/mahasiswa` | `MahasiswaController::index()` | Publik | Mengambil seluruh data mahasiswa |
| 4 | GET | `/mahasiswa?id={id}` | `MahasiswaController::index()` | Publik | Mengambil satu data mahasiswa |
| 5 | POST | `/mahasiswa` | `MahasiswaController::store()` | Bearer Token | Menambahkan data mahasiswa |
| 6 | PUT | `/mahasiswa?id={id}` | `MahasiswaController::update($id)` | Bearer Token | Memperbarui data mahasiswa |
| 7 | DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy($id)` | Bearer Token | Menghapus data mahasiswa |

## 2. Peta Router

Router mengambil **segmen terakhir URL** sebagai nama resource, lalu memetakannya ke Controller.

| Resource (URL) | Controller | Keterangan |
|----------------|------------|------------|
| `/mahasiswa` | `MahasiswaController` | CRUD data mahasiswa (index/store/update/destroy) |
| `/buku` | `BukuController` | CRUD data buku (peninggalan acara 17-18, tidak dibahas pada dokumen ini) |
| `/login` | `AuthController::login` | Autentikasi pengguna, menghasilkan token |
| `/register` | `AuthController::register` | Pendaftaran pengguna baru |

Pemetaan method HTTP pada resource `/mahasiswa`:

| Method HTTP | Fungsi yang dipanggil |
|-------------|-----------------------|
| GET | `index()` |
| POST | `store()` |
| PUT | `update($_GET['id'])` |
| DELETE | `destroy($_GET['id'])` |

Method lain dijawab `405 Method tidak diizinkan`. Resource yang tidak ada dijawab `404 Endpoint tidak ditemukan`.

## 3. Format Request dan Response

### 3.1 Header

| Header | Nilai | Keterangan |
|--------|-------|------------|
| `Content-Type` | `application/json` | Wajib pada request yang memiliki body (POST, PUT) |
| `Accept` | `application/json` | Opsional |
| `Authorization` | `Bearer <token>` | Wajib pada endpoint yang terproteksi (nomor 5-7) |

### 3.2 Format Response Standar

Seluruh response (sukses maupun gagal) memakai struktur yang sama dari `Response::json()`:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

| Field | Tipe | Keterangan |
|-------|------|------------|
| `success` | boolean | `true` jika berhasil, `false` jika gagal |
| `message` | string | Pesan hasil operasi |
| `data` | object / array / null | Data hasil (null jika tidak ada) |
| `errors` | object / array | Rincian kesalahan per field (kosong jika tidak ada) |
| `timestamp` | string | Waktu response (format ISO 8601) |

## 4. Autentikasi

Endpoint `/login` menghasilkan token JWT yang berlaku **1 jam**. Token dikirim lewat header `Authorization: Bearer <token>` untuk mengakses endpoint terproteksi. Endpoint terproteksi memanggil `Auth::requireAuth()`.

### 4.1 POST /register

- **Controller:** `AuthController::register()`
- **Akses:** Publik
- **Header:** `Content-Type: application/json`

**Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `username` | string | Ya | Nama pengguna (harus unik) |
| `email` | string | Ya | Alamat email yang valid (harus unik) |
| `password` | string | Ya | Kata sandi (disimpan dalam bentuk hash) |

```json
{
  "username": "budi",
  "email": "budi@example.com",
  "password": "rahasia123"
}
```

**Response sukses - 201 Created**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": { "id": "5" },
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

**Response gagal**

| Kode | Penyebab | `message` |
|------|----------|-----------|
| 400 | Body bukan JSON | `Body JSON tidak valid` |
| 400 | Field kosong / email tidak valid | `Validasi gagal` (rincian di `errors`) |
| 409 | Username atau email sudah dipakai | `Username atau Email sudah terdaftar` |
| 500 | Kesalahan database | `Registrasi gagal` |

Contoh 400:

```json
{
  "success": false,
  "message": "Validasi gagal",
  "data": null,
  "errors": { "email": "Format email tidak valid" },
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

### 4.2 POST /login

- **Controller:** `AuthController::login()`
- **Akses:** Publik
- **Header:** `Content-Type: application/json`

**Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `username` | string | Ya | Nama pengguna terdaftar |
| `password` | string | Ya | Kata sandi |

```json
{
  "username": "admin",
  "password": "rahasia123"
}
```

**Response sukses - 200 OK**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwicm9sZSI6ImFkbWluIiwiaWF0IjoxNzYzNDgzMDAwLCJleHAiOjE3NjM0ODY2MDB9.xxxxxxxx",
    "role": "admin"
  },
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

**Response gagal - 401 Unauthorized**

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

## 5. Mahasiswa

Endpoint ini ditangani oleh `MahasiswaController`.

**Struktur data mahasiswa**

| Field | Tipe | Keterangan |
|-------|------|------------|
| `id` | integer | ID data (dibuat otomatis) |
| `nim` | string | NIM, **tepat 9 digit angka**, harus unik |
| `nama` | string | Nama mahasiswa |
| `prodi` | string | Program studi |

**Tabel status endpoint**

| Endpoint | Method | Fungsi | Terproteksi |
|----------|--------|--------|-------------|
| `/mahasiswa` | GET | `index()` | Tidak |
| `/mahasiswa?id={id}` | GET | `index()` | Tidak |
| `/mahasiswa` | POST | `store()` | **Ya** (`Auth::requireAuth()`) |
| `/mahasiswa?id={id}` | PUT | `update($id)` | **Ya** (`Auth::requireAuth()`) |
| `/mahasiswa?id={id}` | DELETE | `destroy($id)` | **Ya** (`Auth::requireAuth()`) |

Endpoint terproteksi wajib menyertakan header `Authorization: Bearer <token>`. Tanpa token dijawab `401 Token tidak ditemukan`, dan dengan token palsu atau kedaluwarsa dijawab `401 Token tidak valid atau kedaluwarsa`.

### 5.1 GET /mahasiswa

Mengambil seluruh data mahasiswa (diurutkan dari ID terbaru).

**Response sukses - 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 15,
      "nim": "230100777",
      "nama": "Test Token",
      "prodi": "TI",
      "created_at": "2026-11-03 23:22:40"
    }
  ],
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

### 5.2 GET /mahasiswa?id={id}

Mengambil satu data berdasarkan ID.

**Response sukses - 200 OK:** `data` berisi satu objek mahasiswa.

**Response gagal - 404 Not Found**

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

### 5.3 POST /mahasiswa (terproteksi)

**Header:** `Content-Type: application/json`, `Authorization: Bearer <token>`

**Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `nim` | string | Ya | 9 digit angka |
| `nama` | string | Ya | Nama mahasiswa |
| `prodi` | string | Ya | Program studi |

```json
{
  "nim": "230100777",
  "nama": "Test Token",
  "prodi": "TI"
}
```

**Response sukses - 201 Created**

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": { "id": "15" },
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

**Response gagal**

| Kode | Penyebab | `message` |
|------|----------|-----------|
| 401 | Token tidak ada | `Token tidak ditemukan` |
| 401 | Token palsu / kedaluwarsa | `Token tidak valid atau kedaluwarsa` |
| 400 | Body bukan JSON | `Body JSON tidak valid` |
| 400 | Ada field kosong | `Validasi gagal` (rincian di `errors`) |
| 422 | NIM bukan 9 digit angka | `Format NIM tidak valid, harus 9 digit` |
| 409 | NIM sudah terdaftar | `NIM sudah terdaftar` |
| 500 | Kesalahan database | `Gagal menambahkan data` |

Contoh 422:

```json
{
  "success": false,
  "message": "Format NIM tidak valid, harus 9 digit",
  "data": null,
  "errors": { "nim": "NIM harus terdiri dari tepat 9 angka" },
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

### 5.4 PUT /mahasiswa?id={id} (terproteksi)

Mengubah data mahasiswa. Body sama dengan POST dan seluruh field wajib dikirim.

```json
{
  "nim": "230100777",
  "nama": "Test Token Revisi",
  "prodi": "TI"
}
```

**Response sukses - 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

**Response gagal**

| Kode | Penyebab | `message` |
|------|----------|-----------|
| 401 | Token tidak ada / tidak valid | `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa` |
| 400 | ID bukan angka positif | `ID tidak valid` |
| 400 | Body bukan JSON / field kosong | `Body JSON tidak valid` / `Validasi gagal` |
| 422 | NIM bukan 9 digit angka | `Format NIM tidak valid, harus 9 digit` |
| 404 | ID tidak ditemukan | `Data tidak ditemukan` |
| 409 | NIM dipakai data lain | `NIM sudah digunakan` |
| 500 | Kesalahan database | `Gagal memperbarui data` |

### 5.5 DELETE /mahasiswa?id={id} (terproteksi)

Menghapus data mahasiswa. Tidak memerlukan body.

**Response sukses - 200 OK**

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-18T16:03:20+00:00"
}
```

**Response gagal**

| Kode | Penyebab | `message` |
|------|----------|-----------|
| 401 | Token tidak ada / tidak valid | `Token tidak ditemukan` / `Token tidak valid atau kedaluwarsa` |
| 400 | ID bukan angka positif | `ID tidak valid` |
| 404 | ID tidak ditemukan | `Data tidak ditemukan` |
| 500 | Kesalahan database | `Gagal menghapus data` |

## 6. Kode Status yang Digunakan

| Kode | Arti | Dipakai pada |
|------|------|--------------|
| 200 | OK | GET, PUT, DELETE, login berhasil |
| 201 | Created | POST berhasil (register, tambah mahasiswa) |
| 400 | Bad Request | Body bukan JSON, field kosong, ID tidak valid |
| 401 | Unauthorized | Login gagal, token tidak ada, palsu, atau kedaluwarsa |
| 404 | Not Found | Data atau endpoint tidak ditemukan |
| 405 | Method Not Allowed | Method HTTP selain GET/POST/PUT/DELETE |
| 409 | Conflict | NIM, username, atau email sudah dipakai |
| 422 | Unprocessable Entity | Format NIM tidak valid |
| 500 | Internal Server Error | Kesalahan database |