wajibLogin();
if (Sesi.role() !== 'admin') { setFlash('Akses ditolak, hanya admin yang diizinkan', false); window.location.href = 'dashboard.html'; }
document.querySelector('#info-user').textContent = `${Sesi.username()} (${Sesi.role()})`;

const idMahasiswa = new URLSearchParams(window.location.search).get('id');

async function muatDataUntukEdit(id) {
  let hasil;
  try {
    hasil = await apiFetch(`/mahasiswa?id=${encodeURIComponent(id)}`); // ditangani MahasiswaController::index()
  } catch (err) {
    hasil = { success: false, message: `Gagal memuat data: ${pesanGalat(err)}` };
  }
  if (!hasil.success) {
    setFlash(hasil.message, false);
    window.location.href = 'dashboard.html';
    return;
  }
  nim.value = hasil.data.nim;
  nama.value = hasil.data.nama;
  prodi.value = hasil.data.prodi;
  if (hasil.data.foto) {
    document.querySelector('#img-lama').src = `${BASE_URL}/uploads/${hasil.data.foto}`;
    document.querySelector('#foto-lama').style.display = 'block';
  }
}

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
    // Diteruskan Router ke MahasiswaController::update($id) (POST + _method=PUT, lihat catatan acara 26)
    const hasil = await apiFetch(`/mahasiswa?id=${encodeURIComponent(idMahasiswa)}&_method=PUT`, { method: 'POST', body: formData });
    if (hasil.success) {
      berhasil = true;
      setFlash('Data mahasiswa berhasil diperbarui', true);
      window.location.href = 'dashboard.html';
    } else {
      tampilkanGalat(hasil.errors && !Array.isArray(hasil.errors) ? hasil.errors : {});
      notif(hasil.message, false);
    }
  } catch (err) {
    notif(`Gagal menyimpan: ${pesanGalat(err)}`, false);
  } finally {
    if (!berhasil) { btn.disabled = false; btn.textContent = 'Simpan'; }
  }
});

if (!idMahasiswa) { setFlash('ID mahasiswa tidak ditemukan', false); window.location.href = 'dashboard.html'; }
else muatDataUntukEdit(idMahasiswa);
