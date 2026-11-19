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

function notif(pesan, ok = true) {
  const el = document.createElement('div');
  el.className = 'toast ' + (ok ? 'ok' : 'fail');
  el.textContent = pesan;
  document.querySelector('#toast-area').appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

function sel(teks, kelas) {
  const td = document.createElement('td');
  td.textContent = teks;
  if (kelas) td.className = kelas;
  return td;
}

async function muatDataMahasiswa() {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  const kolom = isAdmin ? 4 : 3;
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
    tr.append(sel(mhs.nim), sel(mhs.nama), sel(mhs.prodi));
    if (isAdmin) {
      const td = document.createElement('td');
      const ubah = document.createElement('button');
      ubah.className = 'btn warn sm';
      ubah.textContent = 'Ubah';
      ubah.onclick = () => ubahData(mhs);
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

// Pada acara 25 form masih sederhana (prompt); form lengkap dibuat pada acara 27
async function tambahData() {
  const nim = prompt('NIM (9 digit):'); if (nim === null) return;
  const nama = prompt('Nama:'); if (nama === null) return;
  const prodi = prompt('Program Studi:'); if (prodi === null) return;
  const hasil = await apiFetch('/mahasiswa', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nim, nama, prodi }),
  });
  notif(hasil.message, hasil.success);
  if (hasil.success) muatDataMahasiswa();
}

async function ubahData(mhs) {
  const nama = prompt('Nama baru:', mhs.nama); if (nama === null) return;
  const prodi = prompt('Program Studi baru:', mhs.prodi); if (prodi === null) return;
  const hasil = await apiFetch(`/mahasiswa?id=${mhs.id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nim: mhs.nim, nama, prodi }),
  });
  notif(hasil.message, hasil.success);
  if (hasil.success) muatDataMahasiswa();
}

async function hapusData(id) {
  if (!confirm('Hapus data ini?')) return;
  const hasil = await apiFetch(`/mahasiswa?id=${id}`, { method: 'DELETE' });
  notif(hasil.message, hasil.success);
  if (hasil.success) muatDataMahasiswa();
}

document.querySelector('#btn-tambah').addEventListener('click', tambahData);
document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
