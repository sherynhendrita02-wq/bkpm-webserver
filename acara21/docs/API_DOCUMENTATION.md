
# API Documentation - BKPM Web Server

## 1. Deskripsi API

API BKPM Web Server merupakan layanan untuk mengelola data mahasiswa dan akun pengguna. API dibuat menggunakan PHP dan database MySQL.

## 2. Base URL

http://localhost/bkpm-webserver/acara21/public

## 3. Format Request dan Response

API menggunakan format JSON.

Header:
- Content-Type: application/json
- Accept: application/json
- Authorization: Bearer <token>

## 4. Daftar Endpoint

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | /mahasiswa | Menampilkan data mahasiswa |
| POST | /mahasiswa | Menambahkan data mahasiswa |
| PUT | /mahasiswa?id={id} | Mengubah data mahasiswa |
| DELETE | /mahasiswa?id={id} | Menghapus data mahasiswa |
| POST | /login | Login pengguna |
| POST | /register | Registrasi pengguna |

## 5. Autentikasi

Endpoint login digunakan untuk memperoleh token JWT. Token dikirim melalui header Authorization untuk mengakses endpoint yang membutuhkan autentikasi.

## 6. Contoh Request

### Login

Method: POST

Endpoint: /login

Body:

```json
{
  "username": "admin",
  "password": "password123"
}
```

### Menambahkan Mahasiswa

Method: POST

Endpoint: /mahasiswa

Body:

```json
{
  "nim": "E41250001",
  "nama": "Andi",
  "email": "andi@gmail.com",
  "prodi_id": 1,
  "angkatan": 2025
}
```

## 7. Contoh Response

Response berhasil:

```json
{
  "message": "Data berhasil ditambahkan"
}
```

Response gagal:

```json
{
  "message": "Data tidak valid"
}
```

### GET - Menampilkan Data Mahasiswa

- Method: GET
- Endpoint: `/mahasiswa`
- Fungsi: Mengambil seluruh data mahasiswa dari database.
- Response: JSON

Contoh response berhasil:

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

Status HTTP: 200 OK
