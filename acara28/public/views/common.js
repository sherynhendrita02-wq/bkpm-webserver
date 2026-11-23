// Berkas bersama untuk seluruh halaman View (login, dashboard, form)
const BASE_URL = window.location.origin + window.location.pathname.replace(/\/views\/.*$/, '');

const Sesi = {
  token: () => localStorage.getItem('token'),
  role: () => localStorage.getItem('role'),
  username: () => localStorage.getItem('username'),
  simpan(token, role, username) {
    localStorage.setItem('token', token);
    localStorage.setItem('role', role);
    localStorage.setItem('username', username || '');
  },
  hapus() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('username');
  },
};

// Memeriksa token di sisi client (hanya untuk kenyamanan tampilan; validasi sebenarnya di server):
// token harus ada, berbentuk JWT, dan klaim exp belum lewat
function tokenMasihBerlaku() {
  try {
    const bagian = Sesi.token().split('.');
    const payload = JSON.parse(atob(bagian[1].replace(/-/g, '+').replace(/_/g, '/')));
    return payload.exp * 1000 > Date.now();
  } catch (e) {
    return false;
  }
}

// Proteksi halaman: tanpa token yang berlaku, kembali ke halaman login
function wajibLogin() {
  if (tokenMasihBerlaku()) return;
  const adaToken = !!Sesi.token();
  Sesi.hapus();
  if (adaToken) sessionStorage.setItem('pesanLogin', 'Sesi berakhir, silakan login kembali');
  window.location.href = 'login.html';
}

// Pembungkus fetch: menambahkan header Authorization dan menangani token kedaluwarsa (401)
async function apiFetch(path, options = {}) {
  const headers = Object.assign({}, options.headers || {});
  if (Sesi.token()) headers['Authorization'] = `Bearer ${Sesi.token()}`;
  const res = await fetch(`${BASE_URL}${path}`, Object.assign({}, options, { headers }));
  const hasil = await res.json();
  if (res.status === 401 && Sesi.token()) {
    Sesi.hapus();
    sessionStorage.setItem('pesanLogin', 'Sesi berakhir, silakan login kembali');
    window.location.href = 'login.html';
  }
  return { status: res.status, ...hasil };
}

// Notifikasi visual (toast) untuk hasil operasi Create/Update/Delete - Tugas mandiri 1 (acara 27)
function notif(pesan, ok = true) {
  let area = document.querySelector('#toast-area');
  if (!area) {
    area = document.createElement('div');
    area.id = 'toast-area';
    document.body.appendChild(area);
  }
  const el = document.createElement('div');
  el.className = 'toast ' + (ok ? 'ok' : 'fail');
  el.textContent = pesan;
  area.appendChild(el);
  setTimeout(() => el.remove(), 3500);
}

// Pesan yang perlu ditampilkan setelah berpindah halaman (misal: setelah simpan data)
function setFlash(pesan, ok = true) {
  sessionStorage.setItem('flash', JSON.stringify({ pesan, ok }));
}
function tampilkanFlash() {
  const f = sessionStorage.getItem('flash');
  if (!f) return;
  sessionStorage.removeItem('flash');
  const { pesan, ok } = JSON.parse(f);
  notif(pesan, ok);
}

// Tugas mandiri 2 (acara 27): validasi sisi View sebelum data dikirim ke Controller.
// Ini lapisan tambahan; validasi yang sebenarnya tetap dilakukan di Controller/Model.
function validasiFormMahasiswa({ nim, nama, prodi, foto }) {
  const galat = {};
  if (!nim.trim()) galat.nim = 'NIM wajib diisi';
  else if (!/^[0-9]{9}$/.test(nim.trim())) galat.nim = 'NIM harus terdiri dari tepat 9 angka';
  if (!nama.trim()) galat.nama = 'Nama wajib diisi';
  if (!prodi.trim()) galat.prodi = 'Program studi wajib diisi';
  if (foto) {
    if (!['image/jpeg', 'image/png'].includes(foto.type)) galat.foto = 'Foto harus berformat JPG atau PNG';
    else if (foto.size > 2 * 1024 * 1024) galat.foto = 'Ukuran foto maksimal 2MB';
  }
  return galat;
}
function tampilkanGalat(galat) {
  ['nim', 'nama', 'prodi', 'foto'].forEach((k) => {
    const el = document.querySelector(`#err-${k}`);
    if (el) el.textContent = galat[k] || '';
  });
}
