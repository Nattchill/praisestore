# 🗺️ Routes Reference

Base URL: **https://praisestore.onrender.com**

---

## Public Routes

| Method | URI | Controller | Description |
|---|---|---|---|
| GET | `/` | `HomeController@index` | Homepage |
| GET | `/shop` | `ShopController@index` | Product listing |
| GET | `/shop/{slug}` | `ShopController@show` | Product detail |
| GET | `/cart` | `CartController@index` | Shopping cart |
| POST | `/cart/add` | `CartController@add` | Add item to cart |
| PATCH | `/cart/{productId}` | `CartController@update` | Update cart item quantity |
| DELETE | `/cart/{productId}` | `CartController@remove` | Remove cart item |
| GET | `/checkout` | `CheckoutController@index` | Checkout page |
| POST | `/checkout` | `CheckoutController@store` | Place order |
| GET | `/order/confirmation/{number}` | `CheckoutController@confirmation` | Order confirmation |
| GET | `/auth/google` | `SocialAuthController@redirectToGoogle` | Google OAuth redirect |
| GET | `/auth/google/callback` | `SocialAuthController@handleGoogleCallback` | Google OAuth callback |

---

## Authenticated Routes

> Requires login via Jetstream (Sanctum session)

| Method | URI | Controller | Description |
|---|---|---|---|
| GET | `/welcome` | closure | Post-login splash screen |
| GET | `/dashboard` | closure | Redirects to splash |

### Customer Account (`/account/*`)

| Method | URI | Controller | Description |
|---|---|---|---|
| GET | `/account` | `CustomerController@dashboard` | Customer dashboard |
| GET | `/account/profile` | `CustomerController@profile` | Profile page |
| POST | `/account/profile` | `CustomerController@updateProfile` | Update profile info |
| POST | `/account/profile/avatar` | `CustomerController@updateAvatar` | Update profile photo |
| POST | `/account/profile/password` | `CustomerController@updatePassword` | Change password |
| GET | `/account/orders` | `CustomerController@orders` | Order history |
| GET | `/account/orders/track/{number}` | `CustomerController@trackOrder` | Track specific order |
| POST | `/account/orders/search` | `CustomerController@searchOrder` | Search orders by number |

### Chat (`/chat/*`)

| Method | URI | Controller | Description |
|---|---|---|---|
| POST | `/chat/send` | `ChatController@send` | Send message (bot replies instantly) |
| GET | `/chat/fetch` | `ChatController@fetch` | Fetch conversation history |
| GET | `/chat/unread` | `ChatController@unread` | Get unread message count |

---

## Admin Routes (`/admin/*`)

> Requires login + `is_admin = true`

### Dashboard

| Method | URI | Description |
|---|---|---|
| GET | `/admin` | Admin dashboard |

### Products

| Method | URI | Description |
|---|---|---|
| GET | `/admin/products` | List products (search, filter) |
| GET | `/admin/products/create` | Create product form |
| POST | `/admin/products` | Store new product |
| GET | `/admin/products/{id}/edit` | Edit product form |
| PUT | `/admin/products/{id}` | Update product |
| DELETE | `/admin/products/{id}` | Delete product |

### Categories

| Method | URI | Description |
|---|---|---|
| GET | `/admin/categories` | List categories |
| POST | `/admin/categories` | Create category |
| PUT | `/admin/categories/{id}` | Update category |
| DELETE | `/admin/categories/{id}` | Delete category |

### Orders

| Method | URI | Description |
|---|---|---|
| GET | `/admin/orders` | List all orders (filter by status) |
| GET | `/admin/orders/{id}` | Order detail |
| PATCH | `/admin/orders/{id}/status` | Update order status |

### Customers

| Method | URI | Description |
|---|---|---|
| GET | `/admin/customers` | Customer list |
| GET | `/admin/customers/{id}` | Customer detail |
| PATCH | `/admin/customers/{id}/toggle` | Toggle customer active status |

### Payments

| Method | URI | Description |
|---|---|---|
| GET | `/admin/payments` | Payment tracking |
| PATCH | `/admin/payments/{id}/status` | Update payment status |

### Reports & Settings

| Method | URI | Description |
|---|---|---|
| GET | `/admin/reports` | Sales reports |
| GET | `/admin/settings` | Settings page |
| POST | `/admin/settings` | Save settings |
| POST | `/admin/settings/password` | Change admin password |

### Chat Monitor

| Method | URI | Description |
|---|---|---|
| GET | `/admin/chat/conversations` | List all user conversations |
| GET | `/admin/chat/fetch/{userId}` | Fetch one user's conversation |
| GET | `/admin/chat/unread` | Unread customer message count |

---

## Middleware

| Middleware | Applied To | Purpose |
|---|---|---|
| `auth:sanctum` | All authenticated routes | Requires login |
| `verified` | Customer account routes | Requires email verification |
| `admin` | All `/admin/*` routes | Requires `is_admin = true` |
