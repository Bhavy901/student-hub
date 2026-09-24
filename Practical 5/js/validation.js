(function () {
  'use strict';

  var form = document.getElementById('registration-form');
  var successPanel = document.getElementById('success-panel');
  var patterns = {
    name: /^[A-Za-z][A-Za-z ]{1,49}$/,
    email: /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,
    mobile: /^[6-9][0-9]{9}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/
  };

  function setError(field, message) {
    var error = document.getElementById(field.id + '-error');
    field.setAttribute('aria-invalid', 'true');
    error.textContent = message;
    return false;
  }

  function clearError(field) {
    var error = document.getElementById(field.id + '-error');
    field.removeAttribute('aria-invalid');
    if (error) error.textContent = '';
  }

  function validateForm() {
    var valid = true;
    var fullName = document.getElementById('full-name');
    var email = document.getElementById('email');
    var mobile = document.getElementById('mobile');
    var password = document.getElementById('password');
    var confirmPassword = document.getElementById('confirm-password');
    var course = document.getElementById('course');
    var year = document.getElementById('year');
    var terms = document.getElementById('terms');
    var gender = document.querySelector('input[name="gender"]:checked');
    var fields = [fullName, email, mobile, password, confirmPassword, course, year];

    fields.forEach(clearError);
    document.getElementById('gender-error').textContent = '';
    document.getElementById('terms-error').textContent = '';

    if (!patterns.name.test(fullName.value.trim())) {
      valid = setError(fullName, 'Enter a name with 2-50 letters and spaces.') && valid;
    }
    if (!patterns.email.test(email.value.trim())) {
      valid = setError(email, 'Enter a valid email address.') && valid;
    }
    if (!patterns.mobile.test(mobile.value.trim())) {
      valid = setError(mobile, 'Enter a 10-digit number starting with 6, 7, 8 or 9.') && valid;
    }
    if (!patterns.password.test(password.value)) {
      valid = setError(password, 'Use 8+ characters with uppercase, lowercase, number and symbol.') && valid;
    }
    if (confirmPassword.value !== password.value || confirmPassword.value === '') {
      valid = setError(confirmPassword, 'Passwords must match.') && valid;
    }
    if (course.value === '') {
      valid = setError(course, 'Select a course.') && valid;
    }
    if (year.value === '') {
      valid = setError(year, 'Select your year of study.') && valid;
    }
    if (!gender) {
      document.getElementById('gender-error').textContent = 'Select a gender option.';
      valid = false;
    }
    if (!terms.checked) {
      document.getElementById('terms-error').textContent = 'Accept the terms to continue.';
      valid = false;
    }

    return valid;
  }

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var status = document.getElementById('form-status');

    if (!validateForm()) {
      status.textContent = 'Please correct the highlighted fields.';
      status.className = 'form-status error';
      var firstInvalid = form.querySelector('[aria-invalid="true"]');
      if (firstInvalid) firstInvalid.focus();
      return;
    }

    status.textContent = '';
    status.className = 'form-status';
    document.getElementById('registered-name').textContent = document.getElementById('full-name').value.trim();
    document.getElementById('registered-course').textContent = document.getElementById('course').options[document.getElementById('course').selectedIndex].text;
    document.getElementById('registered-year').textContent = document.getElementById('year').options[document.getElementById('year').selectedIndex].text.toLowerCase();
    successPanel.hidden = false;
    successPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
  });

  form.addEventListener('reset', function () {
    window.setTimeout(function () {
      var fields = form.querySelectorAll('input, select');
      for (var i = 0; i < fields.length; i += 1) clearError(fields[i]);
      document.getElementById('gender-error').textContent = '';
      document.getElementById('terms-error').textContent = '';
      document.getElementById('form-status').textContent = '';
      successPanel.hidden = true;
    }, 0);
  });
}());
