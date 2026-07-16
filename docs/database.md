# 🗄️ Database Design

## Overview

PraiseStore uses **MySQL 8.0** (local development) and **PostgreSQL 16** (Docker / production).

---

## Tables

### `users`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `name` | varchar(255) | Full name |
| `email` | varchar(255) unique | Email address |
| `password` | varchar(255) | Bcrypt hashed |
| `is_admin` | boolean | Admin flag (default false) |
| `phone` | varchar(20) nullable | Phone number |
| `google_id` | varchar(255) nullable | Google OAuth ID |
| `profile_photo_path` | varchar nullable | Profile photo path |
| `email_verified_at` | timestamp nullable | Email verification |
| `two_factor_secret` | text nullable | 2FA secret |
| `two_factor_recovery_codes` | text nullable | 2FA recovery codes |
| `remember_token` | varchar(100) nullable | Remember me token |
| `created_at` / `updated_at` | timestamp | Timestamps |

---

### `categories`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `name` | varchar(255) unique | Category name |
| `slug` | varchar(255) unique | URL-friendly name |
| `image` | varchar nullable | Image URL |
| `created_at` / `updated_at` | timestamp | Timestamps |

---

### `products`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `category_id` | bigint FK | References `categories.id` |
| `name` | varchar(255) | Product name |
| `slug` | varchar(255) unique | URL-friendly name |
| `description` | text nullable | Product description |
| `price` | decimal(12,2) | Current price (RWF) |
| `old_price` | decimal(12,2) nullable | Original price for discount display |
| `image` | varchar nullable | Cloudinary image URL |
| `stock` | integer | Available stock quantity |
| `featured` | boolean | Show on homepage featured section |
| `is_active` | boolean | Visible in shop (default true) |
| `created_at` / `updated_at` | timestamp | Timestamps |

**Computed attribute:** `discount_percent` = `round((old_price - price) / old_price * 100)`

---

### `orders`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `order_number` | varchar unique | e.g. `PS-ABCD1234` |
| `user_id` | bigint FK nullable | References `users.id` (null = guest) |
| `customer_name` | varchar(255) | Customer full name |
| `customer_email` | varchar(255) | Customer email |
| `customer_phone` | varchar(20) | Customer phone |
| `shipping_address` | text | Delivery address |
| `city` | varchar(100) | Delivery city |
| `subtotal` | decimal(12,2) | Items total |
| `shipping` | decimal(12,2) | Shipping fee |
| `total` | decimal(12,2) | Grand total |
| `status` | enum | `pending` `processing` `shipped` `delivered` `cancelled` |
| `payment_method` | varchar | `cash_on_delivery` or `mobile_money` |
| `momo_phone` | varchar(20) nullable | MoMo phone number |
| `payment_status` | varchar | `pending` `paid` `failed` |
| `created_at` / `updated_at` | timestamp | Timestamps |

---

### `order_items`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `order_id` | bigint FK | References `orders.id` |
| `product_id` | bigint FK nullable | References `products.id` |
| `product_name` | varchar(255) | Snapshot of product name at time of order |
| `price` | decimal(12,2) | Snapshot of price at time of order |
| `quantity` | integer | Quantity ordered |
| `subtotal` | decimal(12,2) | `price × quantity` |
| `created_at` / `updated_at` | timestamp | Timestamps |

---

### `chat_messages`

| Column | Type | Description |
|---|---|---|
| `id` | bigint PK | Auto-increment |
| `user_id` | bigint FK | References `users.id` |
| `message` | text | Message content |
| `is_admin` | boolean | true = bot reply, false = customer message |
| `is_read` | boolean | Read status |
| `created_at` / `updated_at` | timestamp | Timestamps |

---

## Relationships

```
users
  └── hasMany → orders
  └── hasMany → chat_messages

categories
  └── hasMany → products

products
  └── belongsTo → categories
  └── hasMany → order_items

orders
  └── belongsTo → users (nullable)
  └── hasMany → order_items

order_items
  └── belongsTo → orders
  └── belongsTo → products
```

---

## Order Status Flow

```
pending → processing → shipped → delivered
                              ↘ cancelled
```

---

## Migrations (15 files)

| Migration File | Purpose |
|---|---|
| `0001_01_01_000000_create_users_table` | Core users table |
| `0001_01_01_000001_create_cache_table` | Laravel cache |
| `0001_01_01_000002_create_jobs_table` | Queue jobs |
| `2026_07_01_000001_create_categories_table` | Product categories |
| `2026_07_01_000002_create_products_table` | Products catalog |
| `2026_07_01_000003_create_orders_table` | Customer orders |
| `2026_07_01_000004_create_order_items_table` | Order line items |
| `2026_07_01_000005_add_is_admin_to_users_table` | Admin flag |
| `2026_07_01_000006_add_phone_to_users_table` | Phone field |
| `2026_07_06_152940_add_two_factor_columns_to_users_table` | 2FA support |
| `2026_07_06_152941_create_passkeys_table` | Passkey auth |
| `2026_07_06_153129_create_personal_access_tokens_table` | Sanctum tokens |
| `2026_07_07_000001_add_payment_fields_to_orders_table` | MoMo / payment status |
| `2026_07_08_195256_add_google_id_to_users_table` | Google OAuth |
| `2026_07_08_210449_create_chat_messages_table` | Chat bot messages |

---

## Seeded Data

Run `php artisan migrate --seed` to populate:

| Data | Count |
|---|---|
| Admin user | 1 |
| Categories | 4 (Women, Men, Kids, Accessories) |
| Sample products | 12 |
