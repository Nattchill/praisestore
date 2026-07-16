# 🚀 Deployment Guide

## Live URL

**https://praisestore.onrender.com**

---

## Option 1 — Render (Current Production)

### Initial Setup

1. Go to [render.com](https://render.com) → **New Web Service**
2. Connect GitHub repo: `Nattchill/praisestore`
3. Set the following:

| Setting | Value |
|---|---|
| Environment | PHP |
| Build Command | `composer install --no-dev --optimize-autoloader && npm ci && npm run build` |
| Start Command | `php artisan serve --host=0.0.0.0 --port=10000` |

4. Add a **MySQL** or **PostgreSQL** database service
5. Set all environment variables (see [environment.md](environment.md))
6. Deploy

### After Deploy

```bash
# Run migrations (via Render shell)
php artisan migrate --seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Option 2 — Docker (Self-hosted VPS)

### Requirements
- Docker & Docker Compose installed on server
- Port 80 open

### Steps

```bash
# Clone repo
git clone https://github.com/Nattchill/praisestore.git
cd praisestore

# Set environment
cp .env.example .env
# Edit .env with production values

# Build and start
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --seed --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

### Services started by Docker Compose

| Container | Purpose | Port |
|---|---|---|
| `praisestore_app` | Laravel + Nginx + PHP-FPM | 8080 |
| `praisestore_db` | PostgreSQL 16 | internal |
| `praisestore_pma` | pgAdmin 4 | 8081 |

---

## Option 3 — Traditional VPS (SSH)

```bash
# On your server
cd /var/www/praisestore
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl reload php8.2-fpm
```

---

## CI/CD Pipeline

Every push to `main` automatically:

1. **Builds & Tests** — PHP 8.2, MySQL 8.0, runs all PHPUnit tests
2. **Docker Build** — builds and optionally pushes image to Docker Hub
3. **Deploys** — SSH into server and runs deployment script

See `.github/workflows/ci-cd.yml` for full pipeline config.

### Required GitHub Secrets

| Secret | Description |
|---|---|
| `DOCKER_USERNAME` | Docker Hub username |
| `DOCKER_PASSWORD` | Docker Hub password / token |
| `SSH_HOST` | Production server IP |
| `SSH_USER` | SSH username |
| `SSH_PRIVATE_KEY` | SSH private key |

---

## Production Checklist

- [ ] `APP_ENV=production` in `.env`
- [ ] `APP_DEBUG=false` in `.env`
- [ ] `APP_URL` set to live URL
- [ ] `GOOGLE_REDIRECT_URI` set to live URL
- [ ] `CLOUDINARY_URL` configured
- [ ] `MAIL_*` configured with real SMTP credentials
- [ ] `php artisan config:cache` run
- [ ] `php artisan route:cache` run
- [ ] `php artisan view:cache` run
- [ ] Storage symlink created: `php artisan storage:link`
