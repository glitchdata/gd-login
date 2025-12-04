const nameEl = document.getElementById('detail-name');
const emailEl = document.getElementById('detail-email');
const createdEl = document.getElementById('detail-created');
const greetingEl = document.getElementById('user-greeting');
const logoutBtn = document.getElementById('logout-btn');

const formatDate = (isoString) => {
  if (!isoString) return '—';
  return new Date(isoString).toLocaleString();
};

const fetchSession = async () => {
  try {
    const res = await fetch('/api/session');
    const data = await res.json();
    if (!data?.authenticated) {
      window.location.href = '/';
      return null;
    }
    return data.user;
  } catch (error) {
    console.error('Session fetch failed', error);
    window.location.href = '/';
    return null;
  }
};

const renderUser = (user) => {
  if (!user) return;
  nameEl.textContent = user.name;
  emailEl.textContent = user.email;
  createdEl.textContent = formatDate(user.createdAt);
  greetingEl.textContent = `Welcome, ${user.name.split(' ')[0]}!`;
};

const logout = async () => {
  await fetch('/api/logout', { method: 'POST' });
  window.location.href = '/';
};

logoutBtn?.addEventListener('click', logout);

fetchSession().then(renderUser);
