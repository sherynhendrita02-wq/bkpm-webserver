const pesanError = document.querySelector('#pesan-error');
const pesanTersimpan = sessionStorage.getItem('pesanLogin');
if (pesanTersimpan) {
  pesanError.textContent = pesanTersimpan;
  sessionStorage.removeItem('pesanLogin');
}

document.querySelector('#form-login').addEventListener('submit', async (e) => {
  e.preventDefault();
  pesanError.textContent = '';
  // Acara 29 - Langkah 3: try-catch-finally, tombol dinonaktifkan selama proses login
  const btn = document.querySelector('#form-login button[type=submit]');
  let berhasil = false;
  btn.disabled = true;
  btn.textContent = 'Memproses...';
  try {
    const res = await fetchRetry(`${BASE_URL}/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username: username.value, password: password.value }),
    });
    const hasil = await res.json();
    if (hasil.success) {
      berhasil = true;
      Sesi.simpan(hasil.data.token, hasil.data.role, username.value);
      window.location.href = 'dashboard.html';
    } else {
      pesanError.textContent = hasil.message; // textContent: pesan server tidak ditafsirkan sebagai HTML
    }
  } catch (error) {
    pesanError.textContent = pesanGalat(error);
  } finally {
    if (!berhasil) { btn.disabled = false; btn.textContent = 'Masuk'; }
  }
});
