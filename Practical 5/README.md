# Practical 5: Registration Form with Frontend Validation and User-Friendly Error Handling

## Practical Overview
This practical focuses on creating a student registration form using HTML5, CSS3, and JavaScript. JavaScript is used for client-side validation, Regular Expressions, password strength checking, and user-friendly error handling.

## Problem Definition
Create a student registration form with HTML5 input types and JavaScript validation for:
- Name
- Email
- Mobile Number
- Password
- Confirm Password
- Course
- Year
- Gender
- Terms Acceptance

Regular Expressions are used for validating name, email, mobile number, and password.

## Project Structure
```text
Practical 5/
├── css/
│   └── style.css
├── js/
│   └── validation.js
├── index.html
└── README.md
```

## Practical Outcome
This practical demonstrates how to build a complete student registration interface that validates user input before submission, guides the user with clear feedback, and provides a responsive, accessible form experience using HTML, CSS and JavaScript.


## Key Features

### 1. Name Validation
The name is validated using Regular Expression.

```javascript
/^[A-Za-z][A-Za-z ]{1,49}$/
```

The name must contain 2-50 letters and spaces.

### 2. Email Validation
The email is validated using Regular Expression.

```javascript
/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/
```

### 3. Mobile Number Validation
The mobile number must contain 10 digits and start with 6, 7, 8, or 9.

```javascript
/^[6-9][0-9]{9}$/
```

### 4. Password Validation
The password must contain:
- Minimum 8 characters
- Uppercase letter
- Lowercase letter
- Number
- Special character

```javascript
/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/
```

### 5. Confirm Password
The confirm password must match the original password.

### 6. Course and Year Validation
The user must select a valid course and year of study.

### 7. Gender Validation
The user must select one gender option.

### 8. Terms Acceptance
The user must accept the terms and conditions before registration.

## User-Friendly Error Handling
Validation errors are displayed near the relevant fields.

```javascript
function setError(field, message) {
    var error = document.getElementById(field.id + '-error');
    field.setAttribute('aria-invalid', 'true');
    error.textContent = message;
    return false;
}
```

The error messages clearly explain what the user needs to correct.

## Accessibility
The form supports accessibility through:
- Proper `<label>` elements
- `aria-invalid` attributes
- Keyboard-accessible controls
- Clear error messages
- Automatic focus on the first invalid field

Example:

```javascript
var firstInvalid = form.querySelector('[aria-invalid="true"]');
if (firstInvalid) firstInvalid.focus();
```

## Form Submission
The form prevents default submission and validates all fields using JavaScript.

If validation fails, the user receives an error message.

If all fields are valid, a success panel displays:
- Student name
- Selected course
- Selected year

## Reset Functionality
The reset button clears:
- Form values
- Error messages
- Form status
- Success panel



## Key Skills Addressed
- HTML Forms
- HTML5 Input Types
- JavaScript Validation
- Regular Expressions
- Password Strength Validation
- Error Handling
- Accessibility
- Browser Developer Tools

## Applications
This practical can be used in:
- Student registration systems
- College admission forms
- Employee registration systems
- Account creation systems
- Online data-entry systems
- Signup forms

## Test Cases

| Test Case           | Expected Result         |
|---------------------|-------------------------|
| Valid registration  | Registration successful |
| Empty name          | Name error displayed    |
| Invalid name        | Name validation error   |
| Invalid email       | Email error displayed   |
| Invalid mobile      | Mobile error displayed  |
| Weak password       | Password error displayed|
| Password mismatch   | Password mismatch error |
| Course not selected | Course error displayed  |
| Year not selected   | Year error displayed    |
| Gender not selected | Gender error displayed  |
| Terms not accepted  | Terms error displayed   |


## Post Laboratory Work
Submit validation test cases with screenshots for valid and invalid inputs.

Screenshots should include:
- Valid registration
- Empty fields
- Invalid email
- Invalid mobile number
- Weak password
- Password mismatch
- Missing course/year/gender
- Terms not accepted
- Successful registration

## Evaluation Strategy
Evaluation is based on:
- Input coverage
- Validation accuracy
- Regular Expression implementation
- Password validation
- Error handling
- Accessibility
- Code walkthrough
- Browser console testing

## Learning Outcome
Students will be able to:
1. Design HTML5 registration forms.
2. Use appropriate input types and attributes.
3. Validate user input using JavaScript.
4. Use Regular Expressions.
5. Implement password strength validation.
6. Display user-friendly error messages.
7. Create accessible forms.
8. Test and debug client-side validation.

## Conclusion
This practical demonstrates how HTML5, CSS3, and JavaScript can be combined to create an accessible and user-friendly student registration form with robust client-side validation and Regular Expression-based input checking.
