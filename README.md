# 🎨 TeoriWarna E-Commerce

Modern e-commerce platform built with Laravel 11 & Filament Admin Panel.

[![Laravel](https://img.shields.io/badge/Laravel-11-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3-orange.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

## ✨ Features

- 🛍️ **Product Catalog** - Browse products with search, filter & sort
- 🛒 **Shopping Cart** - Session & database cart support
- 💳 **Midtrans Payment** - Integrated payment gateway
- 📦 **Order Management** - Complete order tracking system
- ⭐ **Product Reviews** - Rating & review system
- 👤 **User Authentication** - Register, login, email verification
- 🎛️ **Admin Panel** - Filament-powered admin dashboard
- 📊 **Sales Analytics** - Dashboard with charts & statistics
- 🚀 **Performance Optimized** - Database indexing & eager loading
- 📱 **Responsive Design** - Mobile-friendly UI

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Composer 2.x
- MySQL 8.0+
- Node.js 18+
- NPM 9+

### Installation

```bash
# Clone repository
git clone <repository-url> teoriwarna
cd teoriwarna

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_DATABASE=teoriwarna_db
DB_USERNAME=root
DB_PASSWORD=

# Run migrations & seeders
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Build assets
npm run build

# Start server
php artisan serve
```

Visit:
- **Customer:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin

### Default Credentials

**Admin:**
- Email: `admin@teoriwarna.com`
- Password: `password`

**User:**
- Email: `user@example.com`
- Password: `password`

## 📖 Documentation

Full documentation available in [DOCUMENTATION.md](DOCUMENTATION.md)

### Key Sections

- [Installation Guide](DOCUMENTATION.md#3-installation-guide)
- [Features Documentation](DOCUMENTATION.md#5-features-documentation)
- [Database Schema](DOCUMENTATION.md#6-database-schema)
- [API Integration](DOCUMENTATION.md#7-api-integration)
- [User Guide](DOCUMENTATION.md#8-user-guide)
- [Admin Guide](DOCUMENTATION.md#9-admin-guide)
- [Troubleshooting](DOCUMENTATION.md#11-troubleshooting)

## 🛠️ Tech Stack

**Backend:**
- Laravel 11
- Filament 3
- MySQL 8

**Frontend:**
- Blade Templates
- Alpine.js
- Tailwind CSS

**Integrations:**
- Midtrans Payment Gateway
- Laravel Mail

## 📁 Project Structure

```
teoriwarna/
├── app/
│   ├── Filament/          # Admin panel resources
│   ├── Http/Controllers/  # Application controllers
│   ├── Mail/              # Email templates
│   └── Models/            # Eloquent models
├── config/
│   ├── midtrans.php       # Payment config
│   └── shipping.php       # Shipping costs
├── database/
│   ├── migrations/        # DB migrations
│   └── seeders/           # DB seeders
├── resources/
│   └── views/             # Blade templates
└── routes/
    └── web.php            # Application routes
```

## 🔧 Configuration

### Midtrans Setup

1. Get credentials from [Midtrans Dashboard](https://dashboard.midtrans.com/)
2. Update `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
```

### Shipping Costs

Edit `config/shipping.php`:

```php
'costs' => [
    'regular' => 15000,   // 3-5 days
    'express' => 30000,   // 1-2 days
    'same_day' => 50000,  // Same day
]
```

## 🧪 Testing

### Midtrans Sandbox

**Test Card:**
- Number: `4811 1111 1111 1114`
- CVV: `123`
- OTP: `112233`

### Run Tests

```bash
php artisan test
```

## 🚀 Deployment

### Production Checklist

```bash
# Set environment
APP_ENV=production
APP_DEBUG=false

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force
```

### Server Requirements

- PHP 8.2+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL
- MySQL 8.0+
- Composer
- Web server (Apache/Nginx)

## 📊 Database Schema

### Main Tables

- `users` - User accounts
- `products` - Product catalog
- `categories` - Product categories
- `carts` - Shopping carts
- `cart_items` - Cart contents
- `orders` - Customer orders
- `order_items` - Order line items
- `payments` - Payment records
- `product_reviews` - Product reviews
- `user_addresses` - Shipping addresses

See [full schema](DOCUMENTATION.md#6-database-schema) in documentation.

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Filament](https://filamentphp.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Midtrans](https://midtrans.com)

## 📧 Support

For issues or questions:
- 📫 Email: admin@teoriwarna.com
- 🐛 Issues: [GitHub Issues](<repository-url>/issues)
- 📚 Docs: [DOCUMENTATION.md](DOCUMENTATION.md)

---

**Made with ❤️ using Laravel & Filament**
