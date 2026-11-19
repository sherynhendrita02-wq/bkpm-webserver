# Dokumentasi API — Sistem Informasi Akademik (PHP Native MVC)

**Base URL:** `http://localhost/bkpm-webserver/acara23/public`
**Format data:** JSON (`Content-Type: application/json`)
**Autentikasi:** JWT (Bearer Token), masa berlaku 5 menit

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

Pemetaan method HTTP pada resource `/mahasiswa`: `GET → index()`, `POST → store()`, `PUT → update($_GET['id'])`, `DELETE → destroy($_GET['id'])`. Method lain dijawab `405 Method tidak diizinkan`.

## 3. Format Response Standar

Seluruh response dibentuk oleh `Response::json()` dengan struktur yang sama:

```json
{
  "success": true,
  "message": "Pesan hasil proses",
  "data": null,
  "errors": [],
  "timestamp": "2026-11-16T21:00:00+07:00"
}
```

| Field | Tipe | Keterangan |
|-------|------|------------|
| `success` | boolean | `true` jika proses berhasil |
| `message` | string | Pesan singkat hasil proses |
| `data` | object / array / null | Data hasil (jika ada) |
| `errors` | object / array | Rincian kesalahan validasi per field |
| `timestamp` | string | Waktu response (ISO 8601) |

## 4. Autentikasi

### POST /register
**Controller:** `AuthController::register()` · **Akses:** Publik

**Headers:** `Content-Type: application/json`

**Request Body:**

| Field | Tipe | Wajib | Keterangan |
|-------|------|-------|------------|
| `username` | string | Ya | Harus unik |
| `email` | string | Ya | Format email valid, harus unik |
| `password` | string | Ya | Disimpan dalam bentuk hash (`password_hash`) |

```json
{ "username": "budi", "email": "budi@example.com", "password": "rahasia123" }
```

**Response 201 Created**
```json
{ "success": true, "message": "Registrasi berhasil", "data": { "id": "3" }, "errors": [] }
```
**Response 400 Bad Request** (field kosong / email tidak valid)
```json
{ "success": false, "message": "Validasi gagal", "data": null, "errors": { "username": "Field username wajib diisi" } }
```
**Response 409 Conflict** (username/email sudah terdaftar)
```json
{ "success": false, "message": "Username atau Email sudah terdaftar", "data": null, "errors": [] }
```

### POST /login
**Controller:** `AuthController::login()` · **Akses:** Publik

**Headers:** `Content-Type: application/json`

**Request Body:**
```json
{ "username": "admin", "password": "rahasia123" }
```
**Response 200 OK**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": { "token": "eyJhbGciOiJIUzI1NiIs...", "role": "admin" },
  "errors": []
}
```
**Response 401 Unauthorized**
```json
{ "success": false, "message": "Username atau password salah", "data": null, "errors": [] }
```

## 5. Mahasiswa

### GET /mahasiswa
**Controller:** `MahasiswaController::index()` · **Akses:** Publik

**Response 200 OK**
```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    { "id": "1", "nim": "230100105", "nama": "Test", "prodi": "TI", "created_at": "2026-11-16 20:00:00" }
  ],
  "errors": []
}
```

### GET /mahasiswa?id={id}
**Controller:** `MahasiswaController::index()` · **Akses:** Publik

| Parameter | Lokasi | Tipe | Keterangan |
|-----------|--------|------|------------|
| `id` | query | integer | ID mahasiswa |

**Response 200 OK** → `data` berisi satu objek mahasiswa.
**Response 404 Not Found**
```json
{ "success": false, "message": "Data tidak ditemukan", "data": null, "errors": [] }
```

### POST /mahasiswa
**Controller:** `MahasiswaController::store()` · **Akses:** Terproteksi (`Auth::requireAuth()`)

**Headers:**
- `Content-Type: application/json`
- `Authorization: Bearer <token>`

**Request Body:**

| Field | Tipe | Wajib | Aturan |
|-------|------|-------|--------|
| `nim` | string | Ya | Tepat 9 digit angka, unik |
| `nama` | string | Ya | Tidak boleh kosong |
| `prodi` | string | Ya | Tidak boleh kosong |

```json
{ "nim": "230100777", "nama": "Test Token", "prodi": "TI" }
```
| Status | Kondisi | Message |
|--------|---------|---------|
| 201 Created | Berhasil | Data berhasil ditambahkan |
| 400 Bad Request | Body bukan JSON / field kosong | Body JSON tidak valid / Validasi gagal |
| 401 Unauthorized | Token tidak ada | Token tidak ditemukan |
| 401 Unauthorized | Token salah / kedaluwarsa | Token tidak valid atau kedaluwarsa |
| 422 Unprocessable Entity | NIM bukan 9 digit | Format NIM tidak valid, harus 9 digit |
| 422 Unprocessable Entity | NIM sudah terdaftar (duplikat) | NIM sudah terdaftar |

**Response 201 Created**
```json
{ "success": true, "message": "Data berhasil ditambahkan", "data": { "id": "5" }, "errors": [] }
```

### PUT /mahasiswa?id={id}
**Controller:** `MahasiswaController::update($id)` · **Akses:** Terproteksi (`Auth::requireAuth()`)

**Headers:** `Content-Type: application/json`, `Authorization: Bearer <token>`
**Query:** `id` (integer, wajib) · **Body:** sama dengan `POST /mahasiswa`

| Status | Kondisi | Message |
|--------|---------|---------|
| 200 OK | Berhasil | Data berhasil diperbarui |
| 400 Bad Request | `id` tidak valid / field kosong | ID tidak valid / Validasi gagal |
| 401 Unauthorized | Token tidak ada / tidak valid | Token tidak ditemukan / Token tidak valid atau kedaluwarsa |
| 404 Not Found | Data tidak ada | Data tidak ditemukan |
| 422 Unprocessable Entity | NIM bukan 9 digit | Format NIM tidak valid, harus 9 digit |
| 422 Unprocessable Entity | NIM dipakai data lain (duplikat) | NIM sudah digunakan |

### DELETE /mahasiswa?id={id}
**Controller:** `MahasiswaController::destroy($id)` · **Akses:** Terproteksi (`Auth::requireAuth()`)

**Headers:** `Authorization: Bearer <token>` · **Query:** `id` (integer, wajib)

| Status | Kondisi | Message |
|--------|---------|---------|
| 200 OK | Berhasil | Data berhasil dihapus |
| 400 Bad Request | `id` tidak valid | ID tidak valid |
| 401 Unauthorized | Token tidak ada / tidak valid | Token tidak ditemukan / Token tidak valid atau kedaluwarsa |
| 404 Not Found | Data tidak ada | Data tidak ditemukan |

## 6. Kode Status yang Digunakan

| Kode | Arti | Digunakan pada |
|------|------|----------------|
| 200 | OK | Pengambilan, pembaruan, penghapusan, login berhasil |
| 201 | Created | Registrasi dan penambahan data berhasil |
| 400 | Bad Request | Body tidak valid, field kosong, ID tidak valid |
| 401 | Unauthorized | Token tidak ada / tidak valid / kedaluwarsa, login salah |
| 404 | Not Found | Endpoint atau data tidak ditemukan |
| 405 | Method Not Allowed | Method HTTP tidak didukung |
| 409 | Conflict | Username/email duplikat saat registrasi |
| 422 | Unprocessable Entity | Format data tidak valid / NIM duplikat |
| 500 | Internal Server Error | Kesalahan server / database |
