# BanScan Inventory Management and Barcode System

## About BanScan

BanScan is a web-based Inventory Management and Barcode System developed to automate and simplify inventory operations in retail businesses. The system provides an efficient platform for managing products, categories, suppliers, sales transactions, returns, barcode generation, reporting, and dashboard analytics.

The application is designed to reduce manual record keeping, improve inventory accuracy, and provide business owners with real-time insights into stock and sales performance.

---

## Features

### User Management

- Secure Login Authentication
- Email Verification
- Role-Based Access Control (Admin and Cashier)
- Profile Management
- Account Security Settings

### Inventory Management

- Category Management
- Item Management
- Supplier Management
- Stock Monitoring
- Low Stock Alerts
- Expiry Date Tracking

### Barcode Module

- Generate Product Barcodes
- Print Barcodes
- Barcode-Based Product Identification

### Sales Module

- Create Sales Transactions
- Generate Sales Receipts
- Support for Custom Sales Items
- Sales History Tracking

### Returns Module

- Process Product Returns
- Return History Management
- Automatic Stock Adjustment

### Reports and Analytics

- Sales Reports
- Stock Reports
- Most Selling Items
- Recently Sold Items
- Profit Summary
- Dashboard Statistics

---

## Technologies Used

BanScan is developed using modern web technologies:

- **Backend:** Laravel 12, Livewire 3, PHP 8.2
- **Frontend:** HTML5, Tailwind CSS, JavaScript
- **Database:** MySQL
- **Development Tools:** Visual Studio Code, Composer, Node.js
- **Version Control:** Git and GitHub

---

## Installation

### Clone the Repository

```bash
git clone https://github.com/lahnambhah/BanScan.git
cd BanScan
```

### Install Dependencies

```bash
composer install
npm install
```

### Configure Environment File

```bash
cp .env.example .env
php artisan key:generate
```

Update the database configuration inside the `.env` file.

### Run Database Migrations

```bash
php artisan migrate
```

### Start the Development Server

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

---

## System Modules
License
This project is developed for academic and educational purposes.
=======
The BanScan system consists of the following modules:

- Dashboard Module
- Inventory Management Module
- Barcode Management Module
- Sales Module
- Returns Module
- Report Module
- Settings Module

---

## Database

The system uses MySQL as its database management system and stores information related to:

- Users
- Categories
- Suppliers
- Items
- Sales
- Sale Items
- Returns
- Return Items

---

## Project Information

**Project Title:** BanScan Inventory Management and Barcode System

**Developed By:** Lahnambhah Manner

**Organization:** NIELIT Shillong

**Year:** 2026

---

## License

This project was developed for academic and educational purposes as part of an internship project at NIELIT Shillong.
