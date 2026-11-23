# Pengujian Antarmuka (View) — Acara 29

**Alat uji:** Browser (Chrome/Edge) + DevTools · **Alamat:** `http://localhost/bkpm-webserver/acara29/public/views/`

Kolom **Hasil Aktual** dan **Status** diisi setelah pengujian dijalankan di komputer masing-masing.

| ID | Skenario | Langkah | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| UI01 | Loading state (halaman publik) | DevTools → Network → **Slow 3G**, muat ulang `index.html` | Tabel menampilkan "Memuat data..." sebelum data muncul | | |
| UI02 | Error feedback (server mati) | Matikan Apache, muat ulang `index.html` | Tabel menampilkan "Gagal memuat data: Tidak dapat terhubung ke server..." | | |
| UI03 | Retry otomatis satu kali | Network → **Offline** lalu muat ulang, kembalikan ke **Online** dalam < 1 detik | Console memuat peringatan "Permintaan gagal, mencoba ulang 1x"; data tampil pada percobaan kedua | | |
| UI04 | Escaping nama (tabel publik) | Tambah data dengan nama `<b>Coba</b>` | Tabel `index.html` menampilkan teks `<b>Coba</b>` apa adanya (tidak tercetak tebal) | | |
| UI05 | Escaping nama (dashboard) | Buka `dashboard.html` setelah UI04 | Nama tampil sebagai teks biasa; atribut `alt` foto tidak rusak | | |
| UI06 | Escaping payload skrip | Nama `"><img src=x onerror=alert(1)>` | Tidak ada kotak `alert`; muncul sebagai teks | | |
| UI07 | Tombol nonaktif saat simpan | Slow 3G, klik **Simpan** pada form tambah | Tombol berubah "Menyimpan..." dan tidak bisa diklik dua kali | | |
| UI08 | Tombol nonaktif saat login | Slow 3G, klik **Masuk** | Tombol berubah "Memproses..." | | |
| UI09 | Tombol Hapus nonaktif saat proses | Slow 3G, hapus satu data | Tombol berubah "Menghapus..." lalu tabel dimuat ulang dan toast sukses | | |
| UI10 | Alur lengkap | login → tambah (dengan foto) → ubah → hapus | Setiap tahap menampilkan umpan balik (toast) yang sesuai | | |
| UI11 | Dashboard gagal memuat | Matikan Apache, muat ulang `dashboard.html` | Muncul pesan galat dan tombol **Coba lagi**; setelah Apache hidup, tombol memuat ulang data | | |

## Catatan teknis
- `escapeHTML()` dan `fetchRetry()` berada di `public/views/common.js` dan dipakai oleh seluruh halaman.
- Retry hanya untuk kegagalan jaringan (`fetch` melempar `TypeError`). Respons HTTP 4xx/5xx tidak diulang karena merupakan jawaban sah dari server.
- Tempat data dinamis yang ditampilkan: tabel publik, tabel dashboard (NIM, nama, prodi, `alt` dan `src` foto), nama pengguna di header, toast, pesan galat form, dan pesan login. Yang masuk lewat `innerHTML` dilewatkan `escapeHTML()`; sisanya memakai `textContent` atau `.value` yang tidak menafsirkan HTML.
