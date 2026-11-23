# Ringkasan Perubahan Acara 29 (dari Acara 28)

| Berkas | Perubahan |
|---|---|
| `public/views/common.js` | + `escapeHTML()`, `fetchRetry()` (retry 1x), `pesanGalat()`; `apiFetch()` memakai retry dan aman terhadap respons non-JSON |
| `public/views/app.js` | Ditulis ulang: loading, `!response.ok`, `renderTabel()` dengan escaping, pesan "Gagal memuat data: ..." |
| `public/views/index.html` | + memuat `common.js` sebelum `app.js` |
| `public/views/dashboard.js` | Tabel dirender dengan `escapeHTML()`, loading + galat + tombol "Coba lagi", hapus dengan try-catch-finally |
| `public/views/form_tambah.js`, `form_edit.js`, `login.js` | try-catch-finally; tombol dinonaktifkan selama proses |
| `docs/TEST_FRONTEND.md` | Baru: UI01-UI11 |
