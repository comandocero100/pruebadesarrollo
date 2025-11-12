function setHidden(el, hidden){ if(hidden) el.setAttribute('hidden',''); else el.removeAttribute('hidden'); }

async function handleLogin(ev){
  ev.preventDefault();
  const btn = document.getElementById('btnLogin');
  const err = document.getElementById('err');
  setHidden(err, true);
  btn.disabled = true;
  try {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Accept':'application/json','Content-Type':'application/json' },
      body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if(!res.ok){ throw new Error(data?.message || 'Error de autenticación'); }
    localStorage.setItem('token', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
    location.href = '/app';
  } catch(e){
    err.textContent = e.message;
    setHidden(err, false);
  } finally {
    btn.disabled = false;
  }
}

window.addEventListener('DOMContentLoaded', () => {
  const token = localStorage.getItem('token');
  if(token){ location.replace('/app'); return; }
  const form = document.getElementById('loginForm');
  form?.addEventListener('submit', handleLogin);
});

