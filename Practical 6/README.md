# StudentHub – Dynamic Data Fetching & Interactive UI Components

**Student:** Bhavy Gol  
**Roll No:** 25CS016  

---

## 🎯 Objective
Build a dynamic, data-driven web application by fetching JSON data asynchronously and rendering dynamic UI components across StudentHub pages. This includes live filtering, search functionality, modal popups, tab switching, and localized state persistence.

---

## 📋 Features Implemented
- **Dynamic JSON Data Fetching:** Asynchronously loads student, event, and FAQ data from JSON datasets (`students.json`, `events.json`, `faqs.json`).
- **Student Directory & Filtering:** Interactive admin panel with live search, department/status filter, pagination, and modal dialogs for adding/editing students.
- **Interactive Events Hub:** Dynamic event card rendering, tab filtering (Upcoming, Workshops, Cultural), and modal RSVP registration.
- **FAQ Accordion & Search:** Interactive expandable FAQ categories with instant search filtering.
- **Responsive Layout & Design:** Fully responsive grid and flexbox layout designed for desktop, tablet, and mobile views.

---

## 📁 Project Structure

```
Practical 6/
├── index.html          # Main Dashboard
├── admin.html          # Student Management Directory
├── events.html         # Events Portal
├── faq.html            # Help & FAQ Center
├── profile.html        # Student Profile Page
├── notices.html        # Announcements & Notices
├── css/
│   └── style.css       # Global & Component Styles
├── js/
│   ├── main.js         # Navigation & Theme Control
│   ├── students-app.js # Student Management Logic & JSON Fetch
│   ├── events-app.js   # Events Rendering & Filtering Logic
│   ├── faq-app.js      # Interactive FAQ Logic
│   └── utils.js        # Helper Utilities & Storage Handlers
├── data/
│   ├── students.json   # Student Records Dataset
│   ├── events.json     # Campus Events Dataset
│   └── faqs.json       # FAQ Dataset
└── README.md           # Documentation
```

---

## 🛠️ Technologies Used
- HTML5 (Semantic Structure)
- CSS3 (Flexbox & Grid Layouts)
- JavaScript ES6+ (Async/Fetch API, DOM Manipulation)
- JSON (Structured Local Data Storage)
