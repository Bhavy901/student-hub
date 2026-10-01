# Practical 7: PHP Form Processing with Server-Side Validation & CSV/JSON Storage

**Student Name:** Bhavy Gol  
**Roll No:** 25CS016  
**Course / Subject:** Web Development / PHP Backend Programming  

---

## 🎯 Problem Statement
Implement a web-based registration/contact form using **PHP** that receives form data via `POST` requests, performs robust **server-side input validation and sanitization**, and stores data securely into **CSV** and **JSON** file formats. The application displays clear success/error messages and includes extensions for viewing stored records and validating CSRF tokens.

---

## 🔑 Key Questions & Technical Analysis

| Key Question | Analysis & Implementation Strategy | Status |
| :--- | :--- | :---: |
| **1. Is the form submitted using POST?** | Verified via `$_SERVER['REQUEST_METHOD'] === 'POST'`. All sensitive data is transmitted via POST payload rather than URL parameters. | ✅ Pass |
| **2. Are inputs validated and sanitized server side?** | Inputs are sanitized using `trim()`, `stripslashes()`, and `htmlspecialchars()` to prevent XSS attacks. Validation rules check empty states, regex patterns for names/phones, `filter_var()` for email format, and white-list checks for select dropdowns. | ✅ Pass |
| **3. Is file writing handled safely?** | File operations use exclusive locks (`LOCK_EX`) via `flock()`. Ensures thread safety and avoids data corruption when multiple users submit concurrently. | ✅ Pass |
| **4. Are success and error responses displayed clearly?** | Form field errors are displayed directly under offending inputs with distinct CSS highlighting. Global CSRF/storage errors and success confirmation banners with record IDs are shown dynamically. | ✅ Pass |

---

## 🚀 Key Features & Extensions Implemented

1. **Server-Side Validation & Sanitization:**
   - Full Name: Length & character pattern checks (`/^[a-zA-Z\s\.\'-]+$/`).
   - Email: Cleaned and validated via `filter_var(..., FILTER_VALIDATE_EMAIL)`.
   - Phone: Validated against standard 10-15 digit formats (`/^[0-9\-\+\s\(\)]{10,15}$/`).
   - Department & Semester: Whitelist verification against allowed array values.
   - Subject & Message: Minimum length constraints (5 and 10 characters).

2. **Safe Dual File Storage (CSV & JSON):**
   - Automatically initializes `data/registrations.csv` and `data/registrations.json` if missing.
   - File locking (`LOCK_EX`) prevents race conditions during file writing.
   - Storage format switcher allowing storage in CSV, JSON, or both formats simultaneously.

3. **Advanced Extension: CSRF Token Validation:**
   - Implements cryptographically secure `bin2hex(random_bytes(32))` tokens stored in session (`$_SESSION['csrf_token']`).
   - Validates tokens using `hash_equals()` to protect against Cross-Site Request Forgery attacks.
   - Automatically regenerates tokens upon successful form submission.

4. **Intermediate Extension: Web Records Viewer (`records.php`):**
   - Interactive viewer page with tabbed view for JSON and CSV datasets.
   - Live client-side search filter by student name, email, department, or subject.
   - Direct download link for `registrations.csv` and raw JSON API endpoint view.

5. **Responsive Theme & UI:**
   - Built with StudentHub design aesthetic including dark mode toggle, interactive navigation, and form state memory on validation errors.

---

## 📁 File Directory Structure

```
Practical 7/
├── index.php               # Main Registration Form & PHP Processing Script
├── records.php             # Web Viewer Page for CSV & JSON Records (Intermediate)
├── css/
│   └── style.css           # Modern CSS Stylesheet with Dark Mode Support
├── js/
│   └── main.js             # Mobile Menu Toggle, Theme Toggle & Alert Timers
├── data/
│   ├── registrations.csv   # Stored Records in CSV Format
│   └── registrations.json  # Stored Records in JSON Format
└── README.md               # Laboratory Documentation & Analysis
```

---

## 🛠️ Tools & Technologies Used
- **Language:** PHP 8.x
- **Storage:** CSV (`fputcsv`), JSON (`json_encode` / `json_decode`)
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Server Environment:** PHP Built-in Server / XAMPP / WAMP / Apache

---

## 🧪 Test Cases & Validation Examples

### Test Case 1: Successful Submission (Both Formats)
- **Input:**
  - Name: `Bhavy Gol`
  - Email: `bhavy.gol@example.com`
  - Phone: `+91 9876543210`
  - Department: `Computer Engineering`
  - Semester: `Semester 5`
  - Subject: `Inquiry regarding PHP Workshop`
  - Message: `I would like to participate in the upcoming backend programming lab.`
  - Storage: `Both CSV & JSON`
- **Expected Outcome:** Success alert displayed with unique Record ID (`REG-XXXXX`). Entry appended into both `registrations.csv` and `registrations.json`.

### Test Case 2: Validation Failure (Invalid Email & Short Name)
- **Input:**
  - Name: `Al`
  - Email: `invalid-email-address`
- **Expected Outcome:** Form submission blocked by server side. Red error text displayed under Name ("Must be at least 3 characters") and Email ("Please enter a valid email address"). Entered data preserved in form inputs.

### Test Case 3: CSRF Protection Verification
- **Input:** Modified or missing `csrf_token` POST field.
- **Expected Outcome:** Form submission rejected with red alert: *"Invalid CSRF token. Please refresh the page and try submitting again."*

---

## ⚙️ How to Run & Verify

1. **Using PHP Built-in Web Server:**
   Navigate to the repository root directory in your terminal and start PHP server:
   ```bash
   php -S localhost:8000 -t "Practical 7"
   ```
2. Open your web browser and navigate to:
   - Form Submission: [http://localhost:8000/index.php](http://localhost:8000/index.php)
   - View Stored Records: [http://localhost:8000/records.php](http://localhost:8000/records.php)

---

## 🎓 Learning Outcomes & CO Mapping
- **CO1:** Understood HTTP POST request processing and server-side lifecycle in PHP.
- **CO5:** Implemented safe file handling operations with file locking (`flock`) and anti-CSRF token verification.
