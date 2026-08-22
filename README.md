# RK DESIGNS — Interior Design Studio Website

Official website of **RK DESIGNS**, a top-rated interior design studio serving **Kolkata & Hooghly, West Bengal**. Specializing in luxury home interiors, 2BHK/3BHK flats, modular kitchens & office spaces — 200+ projects delivered.

🌐 **Live Site:** [www.rkdesignsinterior.in](https://www.rkdesignsinterior.in)

![Interior Design](https://img.shields.io/badge/Design-Interiors-1a1a1a?style=for-the-badge)
![PHP](https://img.shields.io/badge/Backend-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/Database-MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

---

## ✨ Features

- 🏠 **Modern responsive landing page** — hero slider, services, portfolio gallery, testimonials & contact sections
- 🔐 **Secured admin panel** (`/admin`) — session-based login, brute-force throttling, bcrypt password support
- 🖼️ **Dynamic portfolio** — projects stored in MySQL, managed via admin dashboard with validated image uploads
- 📩 **Newsletter subscription API** — REST endpoint storing subscribers
- 🛡️ **Hardened endpoints** — admin-only mutations, MIME-verified uploads, PHP execution blocked in uploads
- 🔍 **SEO optimized** — meta tags, Open Graph, Twitter cards, JSON-LD structured data, sitemap.xml & robots.txt
- ⚡ **Performance focused** — lazy loading, font preconnects, caching & gzip via `.htaccess`

## 🛠️ Tech Stack

| Layer      | Technology                     |
|------------|--------------------------------|
| Frontend   | HTML5, CSS3, Vanilla JavaScript |
| Fonts      | Google Fonts (Playfair Display, Inter) |
| Icons      | Font Awesome 6                 |
| Backend    | PHP 8.x (PDO), sessions        |
| Database   | MySQL                          |

## 📁 Project Structure

```
rkdesign/
├── index.html              # Main website (single page)
├── 404.html                # Custom error page
├── .htaccess               # HTTPS redirect, caching, security headers
├── css/
│   └── style.css           # Global styles
├── js/
│   └── script.js           # Frontend interactions
├── admin/
│   ├── login.php           # Admin login page
│   ├── index.php           # Dashboard / portfolio manager
│   └── logout.php          # Session logout
├── api/
│   ├── auth.php            # Login (rate-limited, bcrypt support)
│   ├── guard.php           # Admin session guard for mutations
│   ├── portfolio.php       # Public GET feed / admin POST+DELETE
│   ├── upload.php          # Admin-only image upload (MIME verified)
│   └── subscribe.php       # Newsletter signup
├── config/
│   ├── database.php        # PDO connection loader
│   ├── secrets.example.php # Template — copy to secrets.php
│   ├── secrets.php         # Real credentials (git-ignored)
│   └── schema.sql          # Table definitions
└── uploads/                # Media assets (.htaccess blocks script execution)
    └── documents/          # Private client files (git-ignored)
```

## 🗄️ Database Schema

```sql
CREATE TABLE IF NOT EXISTS portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    category VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🚀 Getting Started (Local Development)

### Prerequisites

- PHP 8.x
- MySQL 5.7+ / MariaDB
- Any local server stack (XAMPP, WAMP, Laragon, etc.)

### Setup

1. **Clone the repository**

   ```bash
   git clone https://github.com/rkprosit/RKDESIGNS.git
   cd rkdesign
   ```

2. **Create the database**

   Import `config/schema.sql` into your MySQL database:

   ```bash
   mysql -u root -p < config/schema.sql
   ```

3. **Configure credentials** *(never committed to git)*

   ```bash
   cp config/secrets.example.php config/secrets.php
   ```

   Edit `config/secrets.php` with your database and admin credentials.

4. **Run locally**

   ```bash
   php -S localhost:8000
   ```

   Visit `http://localhost:8000` for the site and `http://localhost:8000/admin` for the admin panel.

## 🔐 Security Notes

- **Secrets are never committed.** `config/secrets.php` holds real DB/admin credentials and is git-ignored. Only `secrets.example.php` is tracked as a template.
- **Deploy:** after pulling changes on the server, make sure `config/secrets.php` exists there too (upload it manually once).
- **Admin passwords** should be stored as a bcrypt hash:

  ```bash
  php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT);"
  ```

  Put the output into `'password_hash'` in `config/secrets.php`.
- **Uploads are locked down:** `uploads/.htaccess` denies script execution; uploads accept only verified JPG/PNG/WEBP ≤ 5 MB from logged-in admins.
- If credentials were ever exposed, rotate them immediately (DB password at hosting panel + admin login).

## 🔌 API Endpoints

### `POST /api/auth.php`

Login for the admin panel (session cookie issued on success).

```json
// Request body
{ "username": "admin", "password": "..." }

// Success
{ "success": true }

// Errors: 401 invalid credentials · 429 too many attempts
```

### `GET /api/portfolio.php`

Public JSON feed of portfolio items.

### `POST /api/portfolio.php` · `DELETE /api/portfolio.php?id={id}`

Admin-only (session required). Create or remove portfolio items.

### `POST /api/upload.php`

Admin-only image upload. Returns `{ "url": "uploads/<file>" }`.

### `POST /api/subscribe.php`

Subscribe an email to the newsletter.

```json
// Request body
{ "email": "user@example.com" }

// Success
{ "success": true }

// Errors
{ "error": "Invalid email" }
{ "error": "Already subscribed" }
```

## 🌍 Deployment

Production runs on a PHP-capable host pointed at **www.rkdesignsinterior.in**. Push to the server, import `schema.sql` if needed, and ensure `config/secrets.php` is present on the server.

---

© RK DESIGNS — All rights reserved.
