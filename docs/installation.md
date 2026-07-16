# 🛠️ Installation Guide

## Prerequisites

| Tool | Version |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| Node.js | 20+ |
| npm | 10+ |
| MySQL | 8.0 |
| Git | latest |

---

## Local Development Setup

### 1. Clone the repository

```bash
git clone https://github.com/Nattchill/praisestore.git
cd praisestore
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database

Open `.env` and set your MySQL credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=praisestore
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run migrations and seed

```bash
php artisan migrate --seed
```

This creates all tables and seeds:
- 1 admin user
- 4 categories (Women, Men, Kids, Accessories)
- 12 sample products

### 6. Build frontend assets

```bash
npm run build
```

### 7. Start the server

```bash
php artisan serve
```

Visit: **http://localhost:8000**

---

## One-Command Setup

```bash
composer run setup
```

Runs all steps above automatically.

---

## Development Mode (hot reload)

```bash
composer run dev
```

Starts concurrently:
- Laravel dev server
- Queue worker
- Log watcher (Pail)
- Vite dev server

---

## Running Tests

```bash
php artisan test
```

Or with parallel execution:

```bash
php artisan test --parallel
```

---

## Common Issues

| Problem | Fix |
|---|---|
| `php artisan key:generate` fails | Make sure `.env` file exists |
| MySQL connection refused | Check DB credentials in `.env` |
| Vite assets not loading | Run `npm run build` or `npm run dev` |
| Images not uploading | Set `CLOUDINARY_URL` in `.env` |
| Google login fails | Set `GOOGLE_CLIENT_ID` and `GOOGLE_CLIENT_SECRET` in `.env` |
