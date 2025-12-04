const statusBanner = document.querySelector('[data-status]');
const statusText = statusBanner?.querySelector('p');

const showStatus = (message, intent = 'success') => {
  if (!statusBanner || !statusText) return;
  statusText.textContent = message;
  statusBanner.classList.remove('success', 'error');
  statusBanner.classList.add(intent === 'error' ? 'error' : 'success');
  statusBanner.hidden = false;
};

const request = async (endpoint, payload) => {
  const res = await fetch(endpoint, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });

  const data = await res.json().catch(() => ({}));
  if (!res.ok) {
    const errorMessage = data?.message || 'Something went wrong';
    throw new Error(errorMessage);
  }
  return data;
};

const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');

loginForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(loginForm);
  const payload = Object.fromEntries(formData.entries());
  try {
    await request('/api/login', payload);
    showStatus('Login successful. Redirecting...', 'success');
    setTimeout(() => {
      window.location.href = '/dashboard';
    }, 400);
  } catch (error) {
    showStatus(error.message, 'error');
  }
});

registerForm?.addEventListener('submit', async (event) => {
  event.preventDefault();
  const formData = new FormData(registerForm);
  const payload = Object.fromEntries(formData.entries());
  try {
    await request('/api/register', payload);
    showStatus('Account created. Redirecting...', 'success');
    setTimeout(() => {
      window.location.href = '/dashboard';
    }, 400);
  } catch (error) {
    showStatus(error.message, 'error');
  }
});

const hydrateFromSession = async () => {
  try {
    const res = await fetch('/api/session');
    const data = await res.json();
    if (data?.authenticated) {
      window.location.href = '/dashboard';
    }
  } catch (error) {
    console.warn('Unable to fetch session', error);
  }
};

hydrateFromSession();
