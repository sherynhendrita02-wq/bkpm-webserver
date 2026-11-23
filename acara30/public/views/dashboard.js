// Proteksi halaman: tanpa token yang berlaku, kembali ke halaman login
wajibLogin();

const isAdmin = Sesi.role() === 'admin';
// textContent dipakai untuk teks biasa: browser tidak menafsirkan tag HTML di dalamnya
document.querySelector('#info-user').textContent = `${Sesi.username() || 'Pengguna'} (${Sesi.role()})`;

// Tugas mandiri 1 (acara 27): tombol Logout menghapus token dari localStorage lalu kembali ke login
document.querySelector('#btn-logout').addEventListener('click', () => {
  Sesi.hapus();
  window.location.href = 'login.html';
});

// Tugas mandiri 2 (acara 27): sembunyikan tombol Tambah/Ubah/Hapus jika role = user
// (pelengkap tampilan; proteksi sesungguhnya tetap di Auth::requireAdmin() pada backend)
if (!isAdmin) {
  document.querySelector('#btn-tambah').style.display = 'none';
  document.querySelector('.kolom-aksi').style.display = 'none';
}

const FOTO_KOSONG = `data:image/svg+xml;utf8,${encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="44" height="44"><rect width="44" height="44" fill="#e5e7eb"/><circle cx="22" cy="17" r="8" fill="#9ca3af"/><rect x="8" y="29" width="28" height="12" rx="6" fill="#9ca3af"/></svg>')}`;
const KOLOM = isAdmin ? 5 : 4;

function tampilkanPesan(teks, denganUlangi = false) {
  const tombol = denganUlangi ? ' <button class="btn sec sm" id="btn-ulangi" style="margin-left:8px">Coba lagi</button>' : '';
  document.querySelector('#tabel-mahasiswa tbody').innerHTML =
    `<tr><td class="status" colspan="${KOLOM}">${escapeHTML(teks)}${tombol}</td></tr>`;
  if (denganUlangi) document.querySelector('#btn-ulangi').addEventListener('click', muatDataMahasiswa);
}

// Acara 29 - Tugas mandiri 1: SEMUA data dari Controller di-escape sebelum masuk innerHTML
// (nim, nama, prodi, teks alt, nama berkas foto, dan id pada atribut tombol)
function renderTabel(data) {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  tbody.innerHTML = data.map((mhs) => {
    const src = mhs.foto ? `${BASE_URL}/uploads/${encodeURIComponent(mhs.foto)}` : FOTO_KOSONG;
    const aksi = isAdmin
      ? `<td><button class="btn warn sm" data-aksi="ubah" data-id="${Number(mhs.id)}">Ubah</button>
             <button class="btn danger sm" data-aksi="hapus" data-id="${Number(mhs.id)}" style="margin-left:6px">Hapus</button></td>`
      : '';
    return `<tr>
      <td><img class="foto" src="${escapeHTML(src)}" alt="${escapeHTML('Foto ' + mhs.nama)}"></td>
      <td>${escapeHTML(mhs.nim)}</td><td>${escapeHTML(mhs.nama)}</td><td>${escapeHTML(mhs.prodi)}</td>${aksi}
    </tr>`;
  }).join('');
}

// Acara 29 - Langkah 1: loading state + pesan galat (try/catch)
async function muatDataMahasiswa() {
  tampilkanPesan('Memuat data...');
  try {
    const hasil = await apiFetch('/mahasiswa'); // apiFetch sudah memakai retry 1x untuk gangguan jaringan
    if (!hasil.success) throw new Error(hasil.message);
    const data = hasil.data;

    // Ringkasan: jumlah total mahasiswa dari MahasiswaController::index()
    document.querySelector('#total-mahasiswa').textContent = data.length;

    if (data.length === 0) {
      tampilkanPesan('Belum ada data');
      return;
    }
    renderTabel(data);
  } catch (error) {
    console.error('Gagal memuat data:', error);
    tampilkanPesan(`Gagal memuat data: ${pesanGalat(error)}`, true);
  }
}

// Acara 29 - Langkah 3: Delete dengan try-catch-finally; tombol dinonaktifkan selama proses
async function hapusData(id, tombol) {
  if (!confirm('Hapus data ini?')) return;
  tombol.disabled = true;
  tombol.textContent = 'Menghapus...';
  try {
    const hasil = await apiFetch(`/mahasiswa?id=${id}`, { method: 'DELETE' });
    notif(hasil.message, hasil.success);
    if (hasil.success) await muatDataMahasiswa();
  } catch (error) {
    notif(`Gagal menghapus: ${pesanGalat(error)}`, false);
  } finally {
    // tombol lama sudah diganti saat tabel dimuat ulang; pada kegagalan, kembalikan ke keadaan semula
    if (tombol.isConnected) { tombol.disabled = false; tombol.textContent = 'Hapus'; }
  }
}

// Event delegation: satu pendengar untuk semua tombol Ubah/Hapus (tombol dibuat dari string HTML)
document.querySelector('#tabel-mahasiswa tbody').addEventListener('click', (e) => {
  const tombol = e.target.closest('button[data-aksi]');
  if (!tombol) return;
  const id = tombol.dataset.id;
  if (tombol.dataset.aksi === 'ubah') window.location.href = `form_edit.html?id=${encodeURIComponent(id)}`;
  else hapusData(id, tombol);
});

document.addEventListener('DOMContentLoaded', () => {
  tampilkanFlash(); // notifikasi hasil simpan dari halaman form
  muatDataMahasiswa();
});
