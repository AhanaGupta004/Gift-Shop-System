#  Gift Shop Management System

A web-based **Gift Shop Management System** developed using **PHP, MySQL, HTML, CSS, JavaScript, and Bootstrap**. The system allows customers to browse gifts, add products to the cart, place orders, and manage their accounts, while providing an admin panel to manage products, categories, users, and customer orders.

---

##  Features

###  Customer Module
- User Registration & Login
- Browse Gift Products
- Search Products
- Filter by Categories
- View Product Details
- Add to Cart
- Update Cart Quantity
- Remove Products from Cart
- Checkout with Delivery Address
- GST Calculation
- Place Orders
- View Order History
- User Profile Management

###  Admin Module
- Admin Login
- Dashboard
- Manage Categories
  - Add Category
  - Update Category
  - Delete Category
- Manage Products
  - Add Products
  - Edit Products
  - Delete Products
- Manage Customers
- View Customer Orders
- Update Order Status
- Manage Inventory
- Sales Overview

---

##  Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP | Backend Development |
| MySQL | Database |
| HTML5 | Web Structure |
| CSS3 | Styling |
| Bootstrap | Responsive Design |
| JavaScript | Client-side Functionality |
| XAMPP | Local Development Server |

---

##  Project Structure

```
GiftShopManagement/
│
├── admin/
│   ├── dashboard.php
│   ├── manage_products.php
│   ├── manage_categories.php
│   ├── manage_orders.php
│   ├── manage_users.php
│   └── ...
│
├── css/
├── images/
├── uploads/
├── includes/
│
├── index.php
├── login.php
├── register.php
├── products.php
├── product_details.php
├── cart.php
├── checkout.php
├── payment.php
├── order_history.php
├── profile.php
├── logout.php
│
└── gift_shop.sql
```

---

##  Database

The project uses **MySQL** to store application data.

### Main Tables

- users
- admin
- categories
- products
- cart
- cart_items
- orders
- order_items
- payments
- addresses

---

##  Installation

### 1. Clone Repository

```bash
git clone https://github.com/yourusername/gift-shop-management-system.git
```

### 2. Move Project

Copy the project folder to:

```
xampp/htdocs/
```

### 3. Import Database

- Open **phpMyAdmin**
- Create a database (e.g., `gift_shop`)
- Import the provided SQL file.

### 4. Configure Database

Update the database connection file.

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "gift_shop";
```

### 5. Start Server

Start **Apache** and **MySQL** from the XAMPP Control Panel.

### 6. Open Browser

```
http://localhost/GiftShopManagement/
```

---

##  Shopping Workflow

1. Register/Login
2. Browse Gift Products
3. Search or Filter Products
4. View Product Details
5. Add Products to Cart
6. Update Cart
7. Enter Delivery Address
8. Review Order Summary
9. GST Calculation
10. Complete Payment
11. Order Confirmation
12. View Order History

---


##  Key Features

- Responsive User Interface
- Secure Login System
- Category-wise Product Browsing
- Shopping Cart Management
- GST-Based Billing
- Customer Order Tracking
- Admin Product Management
- Inventory Management
- Order Status Updates

---

##  Future Enhancements

- Online Payment Gateway (Razorpay/Stripe)
- Email Order Confirmation
- Wishlist Feature
- Product Reviews & Ratings
- Coupon & Discount System
- Invoice Generation (PDF)
- Stock Alerts
- Order Cancellation & Refund
- Delivery Tracking
- Admin Analytics Dashboard

---

##  Learning Outcomes

This project helped in understanding:

- PHP CRUD Operations
- MySQL Database Design
- Session Management
- User Authentication
- Shopping Cart Implementation
- Order Processing
- GST Calculation
- Responsive Web Design
- E-commerce Workflow
- Database Relationships

---

##  Author

**Ahana Gupta**

