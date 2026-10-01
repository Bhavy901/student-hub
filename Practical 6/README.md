# Practical 6: Fetch API, JSON, Search & Filter

## Objective
Fetch external JSON data using the **Fetch API** and dynamically display it with search, filtering, sorting, and pagination.

## Technologies
- HTML5
- CSS3
- JavaScript ES6+
- JSON
- Fetch API

## Project Structure

```text
Practical-6/
├── css/
├── data/
│   ├── events.json
│   ├── students.json
│   └── faqs.json
├── js/
│   ├── event-app.js
│   ├── faq-app.js
│   ├── main.js
│   ├── students-app.js
│   └── utils.js
├── events.html
├── admin.html
├── faq.html
└── README.md
```

## Main Features

### Events
- Fetch `events.json`
- Search events
- Filter by category
- Sort by date/title
- Pagination
- Event details modal

### Students
- Fetch `students.json`
- Search by name/ID
- Filter by branch
- Sort students
- Pagination

### FAQs
- Fetch `faqs.json`
- Search questions/answers
- Filter by category
- Pagination
- Accordion for answers

## JavaScript Modules

- **`event-app.js`** – Event fetching, rendering, search, filter, sort and pagination.
- **`students-app.js`** – Student fetching, rendering, search, filter, sort and pagination.
- **`faq-app.js`** – FAQ fetching, rendering, search, filter and pagination.
- **`utils.js`** – Common functions such as `fetchJSON()`, `debounce()`, pagination, loading and error handling.
- **`main.js`** – Common UI features such as navigation, theme, modal, banner and other interactions.

## Important Array Methods

| Method       | Purpose          |
|--------------|------------------|
| `map()`      | Render data      |
| `filter()`   | Search/filter    |
| `sort()`     | Sort records     |
| `slice()`    | Pagination       |
| `forEach()`  | Iterate elements |
| `includes()` | Search text      |

## Fetch API

```javascript
fetchJSON('data/events.json')
    .then(data => {
        // Render data
    })
    .catch(error => {
        // Handle error
    });
```

## Loading & Error Handling

The application displays a loading message while data is being fetched and shows an error with a **Retry** option if loading fails.

## Dataset

The project contains:

- `events.json`
- `students.json`
- `faqs.json`

Each dataset contains **at least 15 records**.

## Testing

Test the following:

- JSON loading
- Search
- Filter
- Sorting
- Pagination
- Modal/accordion
- Loading state
- Error and Retry state
- Browser console errors

## How to Run

Use **VS Code Live Server** to run the project because JSON files are loaded using the Fetch API.

Open:
- `events.html` → Events
- `admin.html` → Students
- `faq.html` → FAQs

## Learning Outcome

Students will learn to **fetch JSON data, process arrays, dynamically render frontend views, and implement search, filtering, sorting, pagination, and error handling**.



## Conclusion

This practical demonstrates dynamic frontend development using **JSON, Fetch API, DOM manipulation, array methods, search, filter, sorting, pagination, and modular JavaScript**.
