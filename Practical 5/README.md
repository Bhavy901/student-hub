# StudentHub – Client-Side Form Validation using JavaScript

**Student:** Bhavy Gol  
**Roll No:** 25CS016  

---

## 🎯 Objective
Implement client-side form validation using JavaScript for the Student Registration form in StudentHub. The objective is to validate user inputs before submission, display dynamic inline error messages, ensure accessibility (using ARIA attributes), and present a success summary panel upon valid submission.

---

## 📋 Features Implemented
- **Full Name Validation:** Requires 2-50 characters containing only letters and spaces.
- **Email Address Validation:** Validates format (e.g., `student@example.com`).
- **Mobile Number Validation:** Ensures exactly 10 numeric digits.
- **Password Strength Validation:** Requires at least 8 characters including uppercase, lowercase, numbers, and special symbols.
- **Password Confirmation:** Ensures password and confirm password fields match.
- **Course & Year Selection:** Validates mandatory dropdown selections.
- **Gender Selection:** Validates radio button selection.
- **Terms Agreement:** Requires terms and conditions checkbox confirmation.
- **Accessible Dynamic Errors:** Uses `aria-live="polite"` for dynamic error updates.
- **Success Panel:** Displays customized confirmation message upon successful submission.

---

## 📁 Project Structure

```
Practical 5/
├── index.html        # Registration Form HTML Structure
├── css/
│   └── style.css     # Form Styling and Error State Styles
├── js/
│   └── validation.js # JavaScript Client-Side Validation Logic
└── README.md         # Documentation
```

---

## 🛠️ Technologies Used
- HTML5
- CSS3
- JavaScript (ES6)
