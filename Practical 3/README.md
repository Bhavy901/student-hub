# StudentHub – Responsive UI Design using CSS Grid, Flexbox, and Bootstrap/Tailwind

**Student:** Bhavy Gol  
**Roll No:** 25CS016  

---

## Overview

This project contains the HTML5 version of the StudentHub website. Existing HTML pages were updated to use HTML5 semantic elements, improving structure, readability, accessibility, and maintainability.

Responsive layouts were also designed for the **Home, About, Registration, Dashboard, and Events** pages using **CSS Grid and Flexbox** with a mobile-first approach.

## Objective

The project aims to:

* Convert existing StudentHub pages to HTML5.
* Use semantic HTML5 elements.
* Maintain a consistent page structure.
* Improve accessibility.
* Create responsive layouts using CSS Grid and Flexbox.
* Follow a mobile-first responsive design approach.
* Organize content with meaningful HTML and CSS structures.

## Pages Included

* Home (`index.html`)
* About (`about.html`)
* Register (`register.html`)
* Login (`login.html`)
* Dashboard (`dashboard.html`)
* Events (`events.html`)
* Profile (`profile.html`)
* Contact (`contact.html`)
* Admin (`admin.html`)
* FAQ (`faq.html`)
* Feedback (`feedback.html`)

## Responsive Pages

Responsive layouts were specifically designed for:

* Home
* About
* Registration
* Dashboard
* Events

The layouts automatically adjust according to different screen sizes such as:

* Mobile phones
* Tablets
* Laptops
* Desktop screens

## CSS Layout Techniques Used

### CSS Flexbox

Flexbox is used for:

* Navigation menus
* Header layouts
* Button groups
* Form elements
* Horizontal and vertical alignment
* Responsive content arrangement

Example:

```css
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
```

### CSS Grid

CSS Grid is used for:

* Dashboard cards
* Event cards
* Page sections
* Multi-column layouts
* Responsive content grids

Example:

```css
.dashboard-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}
```

## Mobile-First Design

The website follows a mobile-first approach.

The basic layout is designed for smaller screens first and then enhanced for larger screens using CSS media queries.

Example:

```css
.container {
    width: 100%;
    padding: 15px;
}

@media (min-width: 768px) {
    .container {
        max-width: 1200px;
        margin: auto;
    }
}
```

## HTML5 Semantic Elements Used

* `<header>`
* `<nav>`
* `<main>`
* `<section>`
* `<article>`
* `<aside>`
* `<footer>`

These elements provide meaningful structure to the website and improve readability and accessibility.

## Accessibility Features

* Semantic page landmarks
* Proper heading hierarchy
* Labels for form inputs
* Meaningful navigation structure
* Responsive viewport meta tag
* Descriptive page titles
* Accessible form structure
* Clear content organization

## Responsive Design Features

* Mobile-first layout
* Flexible containers
* Responsive navigation
* CSS Grid for card layouts
* Flexbox for alignment
* Media queries for larger screens
* Flexible spacing and sizing
* No fixed-width layouts that break on mobile devices

## Project Structure

```text
StudentHub/
│
├── index.html
├── about.html
├── register.html
├── login.html
├── dashboard.html
├── events.html
├── profile.html
├── contact.html
├── admin.html
├── faq.html
├── feedback.html
├── style.css
└── README.md
```

## Technologies Used

* HTML5
* CSS3
* CSS Flexbox
* CSS Grid
* CSS Media Queries
* Responsive Web Design

## Conclusion

The StudentHub website has been converted into a structured HTML5 project using semantic elements. Responsive layouts for the Home, About, Registration, Dashboard, and Events pages were created using **CSS Grid and Flexbox**. The mobile-first approach ensures that the website provides a consistent and accessible experience across mobile, tablet, and desktop devices.