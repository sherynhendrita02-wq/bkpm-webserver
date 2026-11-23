const pesanError = document.querySelector('#pesan-error');
const pesanTersimpan = sessionStorage.getItem('pesanLogin');
if (pesanTersimpan) {
  pesanError.textContent = pesanTersimpan;
  sessionStorage.removeItem('pesanLogin');
}

document.querySelector('#form-login').addEventListener('submit', async (e) => {
  e.preventDefault();
  pesanError.textContent = '';
  try {
    const res = await fetch(`${BASE_URL}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username: username.value, password: password.value }),
    });
    const hasil = await res.json();
    if (hasil.success) {
      Sesi.simpan(hasil.data.token, hasil.data.role, username.value);
      window.location.href = 'dashboard.html';
    } else {
      pesanError.textContent = hasil.message;
    }
  } catch (error) {
    pesanError.textContent = 'Tidak dapat terhubung ke server';
  }
});
