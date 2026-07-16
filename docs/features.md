# ✨ System Features

## Customer Features

### 🏠 Homepage
- Animated hero slider (4 slides with gradient overlays)
- Live price ticker showing new arrivals and trending products
- Category grid with product counts
- Featured products section
- New arrivals section (latest 8 products)
- Promotional banner

### 🛍️ Shop
- Full product listing with pagination
- Filter by category
- Search by product name
- Sort options
- Product cards with discount badges

### 📦 Product Detail
- Full product image
- Product name, category, price, old price, discount %
- Stock availability
- Add to cart button
- Related products

### 🛒 Shopping Cart
- Add products from any page
- Update quantities
- Remove items
- Auto-calculated subtotal
- Persistent across page reloads (session-based)

### 💳 Checkout
- Customer info form (name, email, phone, address, city)
- Order summary with itemized list
- Payment method selection:
  - 📱 Mobile Money (MoMo) — MTN & Airtel
  - 💵 Cash on Delivery
- MoMo phone number field (shown conditionally)

### ✅ Order Confirmation
- Order number display
- Full order summary
- Payment method and status
- Shipping details
- Order confirmation email sent automatically

### 📍 Order Tracking
- Track by order number from account dashboard
- Search orders by order number
- View order status: Pending → Processing → Shipped → Delivered

### 🔐 Authentication
- Register with email and password
- Login with email and password
- Google OAuth (one-click sign-in)
- Profile management (name, phone, avatar)
- Password change
- Two-factor authentication (via Jetstream)

### 🎉 Welcome Splash
- Personalized animated splash screen after login
- Auto-redirects to admin dashboard or customer dashboard

### 💬 Live Chat Bot
- Floating chat widget on all pages
- Keyword-based auto-replies for:
  - Order tracking
  - Delivery & shipping info
  - Payment methods
  - Returns & refunds
  - Product availability
  - Account help
  - Contact info
- Unread message badge counter

---

## Admin Features

### 📊 Dashboard
- Total revenue (excluding cancelled orders)
- Total orders count
- Pending orders count
- Total customers count
- Total products count
- Total categories count
- Recent orders table (last 8)
- Monthly revenue chart (last 6 months)
- Top 5 best-selling products

### 📦 Products Management
- List all products with search, category filter, status filter
- Create product with:
  - Name, category, description
  - Price, old price (for discount display)
  - Stock quantity
  - Featured flag
  - Active/inactive status
  - Image upload to Cloudinary
- Edit product
- Delete product

### 🗂️ Categories Management
- List all categories with product counts
- Create category (name, slug auto-generated, image URL)
- Edit category
- Delete category (blocked if products exist)

### 📋 Orders Management
- List all orders with status filter
- View order detail (items, customer info, payment)
- Update order status:
  - Pending → Processing → Shipped → Delivered → Cancelled

### 👥 Customers Management
- List all customers with order counts
- View customer detail (profile + order history + total spent)
- Toggle customer account status (active/blocked)

### 💰 Payments Tracking
- List all orders with payment info
- Filter by payment method (MoMo / Cash on Delivery)
- Filter by payment status (pending / paid / failed)
- Update payment status per order
- Stats: total paid, pending MoMo count, MoMo revenue, COD count

### 📈 Reports
- Date range filter (7 / 30 / 90 days)
- Daily sales chart (orders + revenue)
- Top categories by revenue
- Top 10 products by revenue
- Orders by status breakdown
- Summary: total revenue, total orders, average order value, new customers

### ⚙️ Settings
- Store name, email, phone, address
- Currency setting
- Shipping fee configuration
- Admin password change

### 💬 Chat Monitor
- Read-only view of all customer conversations
- See last message preview per user
- View full conversation history per user
- Unread message count badge
