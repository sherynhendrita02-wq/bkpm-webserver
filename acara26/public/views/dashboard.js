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

// Acara 26: form tambah/ubah memakai FormData agar dapat mengirim berkas foto
let idDiedit = null;
const dlg = document.querySelector('#dlg-form');

function bukaForm(mhs = null) {
  idDiedit = mhs ? mhs.id : null;
  document.querySelector('#judul-form').textContent = mhs ? 'Ubah Mahasiswa' : 'Tambah Mahasiswa';
  nim.value = mhs ? mhs.nim : '';
  nama.value = mhs ? mhs.nama : '';
  prodi.value = mhs ? mhs.prodi : '';
  inputFoto.value = '';
  document.querySelector('#err-form').textContent = '';
  dlg.showModal();
}

document.querySelector('#btn-batal').addEventListener('click', () => dlg.close());

document.querySelector('#form-mhs').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData();
  formData.append('nim', nim.value);
  formData.append('nama', nama.value);
  formData.append('prodi', prodi.value);
  if (inputFoto.files[0]) formData.append('foto', inputFoto.files[0]);

  // Content-Type tidak diatur manual agar browser menambahkan boundary multipart secara otomatis.
  // Update dikirim sebagai POST + _method=PUT karena PHP tidak membaca multipart pada method PUT.
  const url = idDiedit ? `/mahasiswa?id=${idDiedit}&_method=PUT` : '/mahasiswa';
  const hasil = await apiFetch(url, { method: 'POST', body: formData });
  if (hasil.success) {
    dlg.close();
    notif(hasil.message, true);
    muatDataMahasiswa();
  } else {
    document.querySelector('#err-form').textContent = hasil.message;
  }
});

function tambahData() { bukaForm(); }
function ubahData(mhs) { bukaForm(mhs); }

async function hapusData(id) {
  if (!confirm('Hapus data ini?')) return;
  const hasil = await apiFetch(`/mahasiswa?id=${id}`, { method: 'DELETE' });
  notif(hasil.message, hasil.success);
  if (hasil.success) muatDataMahasiswa();
}

document.querySelector('#btn-tambah').addEventListener('click', tambahData);
document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
