// Practical 7 JavaScript - Interactive Controls & Validation
document.addEventListener('DOMContentLoaded', function() {
  
  // Mobile Navigation Toggle
  const navToggle = document.getElementById('nav-toggle');
  const primaryNav = document.getElementById('primary-nav');
  if (navToggle && primaryNav) {
    navToggle.addEventListener('click', function() {
      primaryNav.classList.toggle('nav-open');
    });
  }

  // Dark Mode Toggle
  const themeToggle = document.getElementById('theme-toggle');
  if (themeToggle) {
    const isDarkMode = localStorage.getItem('theme') === 'dark';
    if (isDarkMode) {
      document.body.classList.add('dark-mode');
      themeToggle.textContent = '☀️ Light Mode';
    }

    themeToggle.addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
      const isDark = document.body.classList.contains('dark-mode');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      themeToggle.textContent = isDark ? '☀️ Light Mode' : '🌙 Dark Mode';
    });
  }

  // Auto-dismiss success alert after 6 seconds
  const successAlert = document.getElementById('success-alert');
  if (successAlert) {
    setTimeout(function() {
      successAlert.style.transition = 'opacity 0.5s ease';
      successAlert.style.opacity = '0';
      setTimeout(() => successAlert.remove(), 500);
    }, 6000);
  }

});
