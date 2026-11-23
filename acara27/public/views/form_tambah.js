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

  const btn = document.querySelector('#btn-simpan');
  btn.disabled = true;
  try {
    // Header Authorization ditambahkan oleh apiFetch(); Content-Type dibiarkan otomatis (multipart)
    const hasil = await apiFetch('/mahasiswa', { method: 'POST', body: formData });
    if (hasil.success) {
      setFlash('Data mahasiswa berhasil ditambahkan', true);
      window.location.href = 'dashboard.html';
    } else {
      tampilkanGalat(hasil.errors && !Array.isArray(hasil.errors) ? hasil.errors : {});
      notif(hasil.message, false);
    }
  } catch (err) {
    notif('Tidak dapat terhubung ke server', false);
  }
  btn.disabled = false;
});
