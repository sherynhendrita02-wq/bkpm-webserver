// Proteksi halaman: tanpa token yang berlaku, kembali ke halaman login
wajibLogin();

const isAdmin = Sesi.role() === 'admin';
document.querySelector('#info-user').textContent = `${Sesi.username() || 'Pengguna'} (${Sesi.role()})`;

// Tugas mandiri 1: tombol Logout menghapus token dari localStorage lalu kembali ke login
document.querySelector('#btn-logout').addEventListener('click', () => {
  Sesi.hapus();
  window.location.href = 'login.html';
});

// Tugas mandiri 2: sembunyikan tombol Tambah/Ubah/Hapus jika role = user
// (pelengkap tampilan; proteksi sesungguhnya tetap di Auth::requireAdmin() pada backend)
if (!isAdmin) {
  document.querySelector('#btn-tambah').style.display = 'none';
  document.querySelector('.kolom-aksi').style.display = 'none';
}

function sel(teks, kelas) {
  const td = document.createElement('td');
  td.textContent = teks;
  if (kelas) td.className = kelas;
  return td;
}

async function muatDataMahasiswa() {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  const kolom = isAdmin ? 5 : 4;
  tbody.innerHTML = `<tr><td class="status" colspan="${kolom}">Memuat data...</td></tr>`;
  const hasil = await apiFetch('/mahasiswa');
  const data = hasil.success ? hasil.data : [];

  // Ringkasan: jumlah total mahasiswa dari MahasiswaController::index()
  document.querySelector('#total-mahasiswa').textContent = data.length;

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td class="status" colspan="${kolom}">Belum ada data</td></tr>`;
    return;
  }
  tbody.innerHTML = '';
  data.forEach((mhs) => {
    const tr = document.createElement('tr');
    const tdFoto = document.createElement('td');
    const img = document.createElement('img');
    img.className = 'foto';
    img.alt = 'Foto ' + mhs.nama;
    img.src = mhs.foto ? `${BASE_URL}/uploads/${mhs.foto}` : `data:image/svg+xml;utf8,${encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44"><rect width="44" height="44" fill="#e5e7eb"/><circle cx="22" cy="17" r="8" fill="#9ca3af"/><rect x="8" y="29" width="28" height="12" rx="6" fill="#9ca3af"/></svg>')}`;
    tdFoto.appendChild(img);
    tr.append(tdFoto, sel(mhs.nim), sel(mhs.nama), sel(mhs.prodi));
    if (isAdmin) {
      const td = document.createElement('td');
      const ubah = document.createElement('button');
      ubah.className = 'btn warn sm';
      ubah.textContent = 'Ubah';
      ubah.onclick = () => { window.location.href = `form_edit.html?id=${mhs.id}`; };
      const hapus = document.createElement('button');
      hapus.className = 'btn danger sm';
      hapus.textContent = 'Hapus';
      hapus.style.marginLeft = '6px';
      hapus.onclick = () => hapusData(mhs.id);
      td.append(ubah, hapus);
      tr.appendChild(td);
    }
    tbody.appendChild(tr);
  });
}

async function hapusData(id) {
  if (!confirm('Hapus data ini?')) return;
  const hasil = await apiFetch(`/mahasiswa?id=${id}`, { method: 'DELETE' });
  notif(hasil.message, hasil.success);
  if (hasil.success) muatDataMahasiswa();
}

document.addEventListener('DOMContentLoaded', () => {
  tampilkanFlash(); // notifikasi hasil simpan dari halaman form
  muatDataMahasiswa();
});
