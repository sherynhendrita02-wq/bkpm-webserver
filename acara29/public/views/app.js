// Halaman publik (index.html). BASE_URL, escapeHTML(), dan fetchRetry() berasal dari common.js,
// yang dimuat lebih dulu pada index.html.

// Menulis satu baris pesan (memuat / kosong / error) ke dalam tabel.
// Pesan dirangkai dari data/galat sehingga SELALU di-escape sebelum masuk innerHTML.
function tampilkanPesan(tbody, teks) {
  tbody.innerHTML = `<tr><td colspan="3" class="status">${escapeHTML(teks)}</td></tr>`;
}

// Acara 29 - Langkah 2: render tabel dengan escaping pada setiap nilai
function renderTabel(data) {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  if (data.length === 0) {
    tampilkanPesan(tbody, 'Belum ada data');
    return;
  }
  tbody.innerHTML = data.map((mhs) =>
    `<tr><td>${escapeHTML(mhs.nim)}</td><td>${escapeHTML(mhs.nama)}</td><td>${escapeHTML(mhs.prodi)}</td></tr>`
  ).join('');
}

// Acara 29 - Langkah 1: indikator loading + pesan galat yang jelas
async function muatDataMahasiswa() {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  tampilkanPesan(tbody, 'Memuat data...');
  try {
    // fetchRetry: otomatis mencoba ulang 1x bila jaringan gagal (Tugas mandiri 2)
    const response = await fetchRetry(`${BASE_URL}/mahasiswa`);
    if (!response.ok) throw new Error('Server merespons dengan status ' + response.status);
    const hasil = await response.json();
    if (!hasil.success) throw new Error(hasil.message);
    renderTabel(hasil.data);
  } catch (error) {
    console.error('Gagal memuat data:', error);
    tampilkanPesan(tbody, `Gagal memuat data: ${pesanGalat(error)}`);
  }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
