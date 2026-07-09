# 🛍️ PraiseStore — E-Commerce Fashion Platform

> **EWA408510 – E-Commerce and Web Application | Final Project**
> Faculty of Computing and Information Sciences, UNILAK | Academic Year 2025–2026

[![CI/CD Pipeline](https://github.com/YOUR_USERNAME/praisestore/actions/workflows/ci-cd.yml/badge.svg)](https://github.com/YOUR_USERNAME/praisestore/actions)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-22c55e)](LICENSE)

---

## 📋 Table of Contents

1. [Introduction](#1-introduction)
2. [Problem Statement](#2-problem-statement)
3. [Project Objectives](#3-project-objectives)
4. [System Features](#4-system-features)
5. [Technologies Used](#5-technologies-used)
6. [System Architecture](#6-system-architecture)
7. [Database Design](#7-database-design)
8. [Project Structure](#8-project-structure)
9. [Quick Start](#9-quick-start)
10. [Docker Setup](#10-docker-setup)
11. [CI/CD Pipeline](#11-cicd-pipeline)
12. [Routes Reference](#12-routes-reference)
13. [Admin Access](#13-admin-access)
14. [Environment Variables](#14-environment-variables)

---

## 1. Introduction

**PraiseStore** is a full-featured e-commerce fashion platform built for the Rwandan market. It enables customers to browse, search, and purchase clothing and accessories online, while providing administrators with a complete management dashboard.

The platform is built on **Laravel 12** with **Jetstream + Livewire**, styled with a custom Instagram-inspired design system, and ships with Docker containerization and a full GitHub Actions CI/CD pipeline.

| | |
|---|---|
| **Live URL** | `https://praisestore.example.com` |
| **GitHub** | `https://github.com/YOUR_USERNAME/praisestore` |
| **Stack** | Laravel 12 · PHP 8.2 · MySQL 8.0 · Blade · Vanilla CSS |

---

## 2. Problem Statement

Local fashion businesses in Rwanda lack accessible online platforms to reach customers beyond their physical locations. Customers face challenges finding quality clothing online with:

- Local pricing in **Rwandan Francs (RWF)**
- Local payment methods (**Mobile Money / MoMo**)
- Reliable delivery tracking
- A trustworthy, professional shopping experience

PraiseStore solves all of these by providing a dedicated, locally-focused e-commerce platform.

---

## 3. Project Objectives

- Build a responsive, professional e-commerce platform tailored for Rwanda
- Implement a full product catalog with categories, search, and filtering
- Provide a seamless cart and checkout experience
- Support local payment methods (Cash on Delivery, Mobile Money)
- Deliver a secure admin dashboard for complete store management
- Integrate Google OAuth for frictionless sign-in
- Containerize with Docker and automate deployments with CI/CD

---

## 4. System Features

### 🛒 Customer Features

| Feature | Description |
|---|---|
| **Homepage** | Hero banner, featured products, new arrivals, category grid |
| **Shop** | Product listing with category filter, search bar, sort options |
| **Product Detail** | Full product info, image, related products, add-to-cart |
| **Shopping Cart** | Add / remove / update quantities, auto-calculated totals |
| **Checkout** | Customer info form, order summary, payment method selection |
| **Order Confirmation** | Order number, full summary, status display |
| **Order Tracking** | Track orders by order number from account or guest page |
| **Authentication** | Register, login, profile management via Jetstream |
| **Google OAuth** | One-click sign-in with Google via Laravel Socialite |
| **Welcome Splash** | Personalized animated splash screen after login |
| **Live Chat Bot** | Floating help widget with AI-style keyword-based auto-replies |

### 🔧 Admin Features

| Feature | Description |
|---|---|
| **Dashboard** | Revenue stats, order counts, recent orders overview |
| **Products** | Create, edit, delete products with image upload |
| **Categories** | Manage product categories with slugs and images |
| **Orders** | View all orders, update order status (Pending → Delivered) |
| **Customers** | View customer list, toggle account status |
| **Payments** | Track payment status per order |
| **Reports** | Sales and revenue reporting |
| **Settings** | Store settings and admin password management |
| **Chat Monitor** | Read-only view of all customer bot conversations |

---

## 5. Technologies Used

| Layer | Technology | Version |
|---|---|---|
| Backend | PHP | 8.2 |
| Framework | Laravel | 12.x |
| Auth | Laravel Jetstream + Fortify | 5.5 / latest |
| OAuth | Laravel Socialite | 5.28 |
| Realtime UI | Livewire | 3.6 |
| API Auth | Laravel Sanctum | 4.0 |
| Frontend | Blade Templates + Vanilla CSS | — |
| Icons | Font Awesome | 6.x CDN |
| Database | MySQL | 8.0 |
| Containerization | Docker + Docker Compose | latest |
| CI/CD | GitHub Actions | — |
| Web Server | Nginx + PHP-FPM | Alpine |
| Process Manager | Supervisor | — |
| Build Tool | Vite | latest |

---

## 6. System Architecture

```
┌──────────────────────────────────────────────┐
│                   Browser                     │
│         (Blade Templates + Vanilla CSS)       │
└─────────────────────┬────────────────────────┘
                      │ HTTP / HTTPS
┌─────────────────────▼────────────────────────┐
│              Nginx Web Server                 │
│           (docker/nginx.conf)                 │
└─────────────────────┬────────────────────────┘
                      │ FastCGI (port 9000)
┌─────────────────────▼────────────────────────┐
│           PHP-FPM  ·  Laravel 12              │
│  ┌──────────┐  ┌────────────┐  ┌──────────┐  │
│  │  Routes  │  │Controllers │  │  Models  │  │
│  └──────────┘  └────────────┘  └────┬─────┘  │
│                                     │ Eloquent│
└─────────────────────────────────────┼─────────┘
                                      │
┌─────────────────────────────────────▼─────────┐
│                  MySQL 8.0                     │
│  users · categories · products · orders        │
│  order_items · chat_messages                   │
└────────────────────────────────────────────────┘
```

### Request Flow

```
User Request
  → Nginx (static files served directly)
  → PHP-FPM → Laravel Router
  → Middleware (auth, admin, sanctum)
  → Controller → Service / Model
  → Eloquent ORM → MySQL
  → Blade View → Response
```

---

## 7. Database Design

### Entity Relationship Overview

```
users ──────────────────────────────────────────────────────┐
  id, name, email, password, is_admin,                      │
  google_id, phone, profile_photo_path, created_at          │
                                                            │
categories                                                  │
  id, name, slug, image, created_at                         │
       │                                                    │
       │ 1:N                                                │
       ▼                                                    │
products                                                    │
  id, category_id (FK), name, slug, description,            │
  price, old_price, image, stock, featured,                 │
  is_active, created_at                                     │
       │                                                    │
       │ N:M (via order_items)                              │
       ▼                                                    │
orders ◄────────────────────────────────────────────────────┘
  id, order_number, user_id (FK nullable),                  1:N
  customer_name, customer_email, customer_phone,            │
  shipping_address, city, subtotal, shipping,               │
  total, status, payment_method,                            │
  payment_status, created_at                                │
       │                                                    ▼
       │ 1:N                                         chat_messages
       ▼                                               id, user_id (FK),
order_items                                            message, is_admin,
  id, order_id (FK), product_id (FK),                  is_read, created_at
  product_name, price, quantity,
  subtotal, created_at
```

### Order Status Flow

```
Pending → Processing → Shipped → Delivered
                              ↘ Cancelled
```

---

## 8. Project Structure

```
praisestore/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/AdminController.php     # All admin CRUD
│   │   │   ├── Customer/CustomerController.php
│   │   │   ├── HomeController.php
│   │   │   ├── ShopController.php
│   │   │   ├── CartController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── ChatController.php            # Bot auto-reply engine
│   │   │   └── SocialAuthController.php      # Google OAuth
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Product.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   └── ChatMessage.php
│   ├── Providers/
│   │   └── FortifyServiceProvider.php        # Login redirect → /welcome
│   └── Services/
│       └── CartService.php                   # Session-based cart logic
├── database/
│   ├── migrations/                           # 15 migration files
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   └── store.blade.php                   # Main layout + chat widget
│   ├── admin/                                # Admin panel views
│   ├── customer/                             # Customer dashboard views
│   ├── auth/
│   │   ├── login.blade.php                   # Custom standalone login
│   │   └── register.blade.php                # Custom standalone register
│   ├── home.blade.php
│   ├── shop.blade.php
│   ├── product.blade.php
│   ├── cart.blade.php
│   ├── checkout.blade.php
│   ├── order-confirmation.blade.php
│   └── welcome-splash.blade.php              # Post-login splash screen
├── routes/web.php
├── docker/
│   ├── nginx.conf
│   └── supervisord.conf
├── .github/workflows/ci-cd.yml
├── Dockerfile
└── docker-compose.yml
```

---

## 9. Quick Start

### Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 20+ & npm
- MySQL 8.0
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/praisestore.git
cd praisestore

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env
#    DB_DATABASE=praisestore
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Run migrations and seed sample data
php artisan migrate --seed

# 7. Build frontend assets
npm run build

# 8. Start the development server
php artisan serve
```

Visit: **http://localhost:8000**

### One-Command Setup (via Composer script)

```bash
composer run setup
```

This runs install → .env copy → key:generate → migrate → npm install → npm build automatically.

### Development Mode (with hot reload)

```bash
composer run dev
```

Starts Laravel server, queue worker, log watcher, and Vite dev server concurrently.

---

## 10. Docker Setup

### Start All Services

```bash
# Build images and start containers
docker-compose up -d --build

# Run migrations inside the app container
docker-compose exec app php artisan migrate --seed
```

### Service URLs

| Service | URL |
|---|---|
| PraiseStore App | http://localhost:8080 |
| phpMyAdmin | http://localhost:8081 |

### Docker Services

| Container | Image | Purpose |
|---|---|---|
| `praisestore_app` | Custom (PHP 8.2-FPM Alpine + Nginx) | Laravel app + web server |
| `praisestore_db` | `mysql:8.0` | Database |
| `praisestore_pma` | `phpmyadmin:latest` | DB management UI |

### Useful Docker Commands

```bash
# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan <command>

# Stop all services
docker-compose down

# Stop and remove volumes (full reset)
docker-compose down -v
```

---

## 11. CI/CD Pipeline

The GitHub Actions pipeline (`.github/workflows/ci-cd.yml`) triggers on every push to `main` or `develop` and on pull requests to `main`.

```
Push to main / develop
         │
         ▼
┌─────────────────────────┐
│   1. BUILD & TEST       │
│   • PHP 8.2 setup       │
│   • Composer install    │
│   • npm ci + build      │
│   • MySQL 8.0 service   │
│   • php artisan migrate │
│   • php artisan test    │
└────────────┬────────────┘
             │ success + main branch only
             ▼
┌─────────────────────────┐
│   2. DOCKER BUILD       │
│   • docker buildx       │
│   • Build image         │
│   • Push to registry    │
└────────────┬────────────┘
             │ success
             ▼
┌─────────────────────────┐
│   3. DEPLOY             │
│   • SSH into server     │
│   • git pull            │
│   • composer install    │
│   • npm build           │
│   • php artisan migrate │
│   • Cache config/routes │
│   • Reload PHP-FPM      │
└─────────────────────────┘
```

### Required GitHub Secrets

| Secret | Description |
|---|---|
| `DOCKER_USERNAME` | Docker Hub username |
| `DOCKER_PASSWORD` | Docker Hub password / token |
| `SSH_HOST` | Production server IP or hostname |
| `SSH_USER` | SSH username |
| `SSH_PRIVATE_KEY` | SSH private key for deployment |

---

## 12. Routes Reference

### Public Routes

| Method | URI | Description |
|---|---|---|
| GET | `/` | Homepage |
| GET | `/shop` | Product listing |
| GET | `/shop/{slug}` | Product detail |
| GET | `/cart` | Shopping cart |
| POST | `/cart/add` | Add item to cart |
| PATCH | `/cart/{id}` | Update cart item quantity |
| DELETE | `/cart/{id}` | Remove cart item |
| GET | `/checkout` | Checkout page |
| POST | `/checkout` | Place order |
| GET | `/order/confirmation/{number}` | Order confirmation |
| GET | `/auth/google` | Google OAuth redirect |
| GET | `/auth/google/callback` | Google OAuth callback |

### Authenticated Routes

| Method | URI | Description |
|---|---|---|
| GET | `/welcome` | Post-login splash screen |
| GET | `/account` | Customer dashboard |
| GET | `/account/profile` | Profile page |
| GET | `/account/orders` | Order history |
| GET | `/account/orders/track/{number}` | Track specific order |
| POST | `/chat/send` | Send chat message (bot replies instantly) |
| GET | `/chat/fetch` | Fetch conversation history |
| GET | `/chat/unread` | Get unread message count |

### Admin Routes (`/admin/*`)

| Method | URI | Description |
|---|---|---|
| GET | `/admin` | Admin dashboard |
| GET/POST | `/admin/products` | List / create products |
| GET/PUT/DELETE | `/admin/products/{id}` | Edit / delete product |
| GET/POST | `/admin/categories` | List / create categories |
| GET | `/admin/orders` | All orders |
| PATCH | `/admin/orders/{id}/status` | Update order status |
| GET | `/admin/customers` | Customer list |
| GET | `/admin/payments` | Payment tracking |
| GET | `/admin/reports` | Sales reports |
| GET | `/admin/settings` | Store settings |
| GET | `/admin/chat/conversations` | Chat monitor (read-only) |

---

## 13. Admin Access

After running `php artisan migrate --seed`, log in at `/login`:

| Field | Value |
|---|---|
| Email | `davidfnatt2002@gmail.com` |
| Password | `0778268118` |

Admin panel: `/admin`

> **Note:** The `is_admin` flag is set to `true` for this seeded user. Regular registered users are redirected to `/account` (customer dashboard).

---

## 14. Environment Variables

Key variables to configure in your `.env` file:

```env
# Application
APP_NAME=PraiseStore
APP_ENV=local
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=praisestore
DB_USERNAME=root
DB_PASSWORD=

# Google OAuth (Google Cloud Console)
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Mail (for password reset, order emails)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@praisestore.rw
MAIL_FROM_NAME=PraiseStore
```

---

## License

MIT License — © 2025 PraiseStore · Built for UNILAK EWA408510
