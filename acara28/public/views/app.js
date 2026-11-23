// Alamat dasar API dihitung otomatis dari lokasi halaman ini (.../public/views/index.html -> .../public)
// BASE_URL didefinisikan pada common.js untuk halaman login/dashboard; halaman publik ini berdiri sendiri
const BASE_URL = larynca.my.id;

// Menulis satu baris pesan (memuat / kosong / error) ke dalam tabel
function tampilkanPesan(tbody, teks) {
  tbody.innerHTML = '';
  const tr = document.createElement('tr');
  const td = document.createElement('td');
  td.colSpan = 3;
  td.className = 'status';
  td.textContent = teks;
  tr.appendChild(td);
  tbody.appendChild(tr);
}

async function muatDataMahasiswa() {
  const tbody = document.querySelector('#tabel-mahasiswa tbody');
  // Tugas mandiri 1: indikator "Memuat data..." sebelum data berhasil diambil
  tampilkanPesan(tbody, 'Memuat data...');
  try {
    const response = await fetch(`${BASE_URL}/mahasiswa`);
    const hasil = await response.json();
    if (!hasil.success) throw new Error(hasil.message);

    // Tugas mandiri 2: penanganan data kosong
    if (hasil.data.length === 0) {
      tampilkanPesan(tbody, 'Belum ada data');
      return;
    }

    tbody.innerHTML = '';
    hasil.data.forEach((mhs) => {
      const tr = document.createElement('tr');
      [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => {
        const td = document.createElement('td');
        td.textContent = nilai; // textContent mencegah XSS dari data yang tersimpan
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  } catch (error) {
    console.error('Gagal memuat data:', error);
    tampilkanPesan(tbody, 'Gagal memuat data dari server');
  }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);
