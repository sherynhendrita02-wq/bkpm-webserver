// Proteksi halaman: hanya admin yang boleh menambah data
wajibLogin();
if (Sesi.role() !== 'admin') { setFlash('Akses ditolak, hanya admin yang diizinkan', false); window.location.href = 'dashboard.html'; }
document.querySelector('#info-user').textContent = `${Sesi.username()} (${Sesi.role()})`;

document.querySelector('#form-mhs').addEventListener('submit', async (e) => {
  e.preventDefault();
  const foto = inputFoto.files[0];
  const galat = validasiFormMahasiswa({ nim: nim.value, nama: nama.value, prodi: prodi.value, foto });
  tampilkanGalat(galat);
  if (Object.keys(galat).length) { notif('Periksa kembali isian form', false); return; }

  const formData = new FormData();
  formData.append('nim', nim.value.trim());
  formData.append('nama', nama.value.trim());
  formData.append('prodi', prodi.value.trim());
  if (foto) formData.append('foto', foto);

  // Acara 29 - Langkah 3: try-catch-finally, tombol dinonaktifkan selama pengiriman
  const btn = document.querySelector('#btn-simpan');
  let berhasil = false;
  btn.disabled = true;
  btn.textContent = 'Menyimpan...';
  try {
    // Header Authorization ditambahkan oleh apiFetch(); Content-Type dibiarkan otomatis (multipart)
    const hasil = await apiFetch('/mahasiswa', { method: 'POST', body: formData });
    if (hasil.success) {
      berhasil = true;
      setFlash('Data mahasiswa berhasil ditambahkan', true);
      window.location.href = 'dashboard.html';
    } else {
      tampilkanGalat(hasil.errors && !Array.isArray(hasil.errors) ? hasil.errors : {});
      notif(hasil.message, false);
    }
  } catch (err) {
    notif(`Gagal menyimpan: ${pesanGalat(err)}`, false);
  } finally {
    // setelah sukses halaman berpindah, jadi tombol dibiarkan nonaktif agar tidak terkirim dua kali
    if (!berhasil) { btn.disabled = false; btn.textContent = 'Simpan'; }
  }
});
