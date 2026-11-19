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
