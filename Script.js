const tabs = document.querySelectorAll('.tab');
const loginForm = document.getElementById('login-form');
const registerForm = document.getElementById('register-form');
const title = document.getElementById('form-title');
const description = document.getElementById('form-description');

function switchTab(target) {
  if (!tabs.length || !loginForm || !registerForm) return;

  tabs.forEach(tab => tab.classList.toggle('active', tab.dataset.target === target));
  if (target === 'login') {
    loginForm.classList.remove('hidden');
    registerForm.classList.add('hidden');
    title.textContent = 'Log in to your account';
    description.textContent = 'Enter your email address and password to login.';
  } else {
    loginForm.classList.add('hidden');
    registerForm.classList.remove('hidden');
    title.textContent = 'Create your account';
    description.textContent = 'Sign up now to start tracking and trading instantly.';
  }
}

if (tabs.length && loginForm && registerForm) {
  tabs.forEach(tab => {
    tab.addEventListener('click', () => switchTab(tab.dataset.target));
  });

  const signupToggle = document.getElementById('toggle-signup');
  const loginToggle = document.getElementById('toggle-login');

  if (signupToggle) {
    signupToggle.addEventListener('click', (e) => {
      e.preventDefault();
      switchTab('register');
    });
  }

  const forgotToggle = document.getElementById('toggle-forgot');
  const loginToggle2 = document.getElementById('toggle-login-2');

  if (forgotToggle) {
    forgotToggle.addEventListener('click', (e) => {
      e.preventDefault();
      loginForm.classList.add('hidden');
      registerForm.classList.add('hidden');
      document.getElementById('forgot-form').classList.remove('hidden');
      tabs.forEach(tab => tab.classList.toggle('active', false));
      title.textContent = 'Reset your password';
      description.textContent = 'Enter your email and new password.';
    });
  }

  if (loginToggle2) {
    loginToggle2.addEventListener('click', (e) => {
      e.preventDefault();
      switchTab('login');
    });
  }

  if (loginToggle) {
    loginToggle.addEventListener('click', (e) => {
      e.preventDefault();
      switchTab('login');
    });
  }
}
