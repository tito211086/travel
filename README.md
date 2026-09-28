# Hello Travel — Public Site

Public-facing travel website built with **core PHP (8.1+), MySQL and HTML5/CSS3** — no frameworks, no build step.

## Features
- Home page: hero search, featured destinations, featured journeys, testimonials, CTA
- Destinations list (filter by region) and destination detail pages
- Journeys list with search, style / budget / length filters and sorting
- Journey detail with highlights, day-by-day itinerary, inclusions and an enquiry form
- Contact form, newsletter sign-up, About page, custom 404
- Security: PDO prepared statements, CSRF tokens, output escaping, honeypot spam trap, safe redirects, hardened session cookies
- Responsive, accessible (skip link, ARIA, focus styles, reduced motion)

## Setup
1. Import the database:
   ```bash
   mysql -u root -p < database/schema.sql
   ```
2. Configure credentials in `config/config.php` or via environment variables:
   `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `BASE_URL` (e.g. `/travel` if served from a sub-folder), `APP_DEBUG=1`.
3. Serve the folder with Apache/Nginx + PHP, or locally:
   ```bash
   php -S localhost:8000
   ```

## Structure
```
index.php, destinations.php, destination.php, packages.php, package.php,
about.php, contact.php, enquire.php (POST), subscribe.php (POST), 404.php
config/config.php        settings
includes/                bootstrap, db (PDO), helpers, header/footer, card partials
assets/css/style.css     design system + layout
assets/js/main.js        mobile nav, header state, client-side validation
database/schema.sql      tables + sample data
```

## Data
Form submissions are stored in `enquiries`, `contact_messages` and `subscribers`.
Destinations and packages support an optional `image_url`; without one, the card
renders a colour tile using `accent_color`.
