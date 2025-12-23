# 🚀 Company Profile Backend API

> Powerful Laravel backend API with Filament Admin Panel for managing company profile content

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
<img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
<img src="https://img.shields.io/badge/Filament-3.3-F59E0B?style=for-the-badge&logo=filament&logoColor=white" alt="Filament 3.3">
<img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

---

## 📋 Table of Contents

-   [Overview](#overview)
-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Quick Start](#quick-start)
-   [API Documentation](#api-documentation)
-   [Project Structure](#project-structure)
-   [Available Modules](#available-modules)
-   [Development](#development)
-   [Testing](#testing)

## 🎯 Overview

A comprehensive backend system designed to power company profile websites. Built with Laravel 12 and Filament 3, this project provides a robust API and intuitive admin panel for managing all aspects of your company's digital presence.

### Why This Project?

✨ **Modern Stack** - Laravel 12 + Filament 3.3 + PHP 8.2  
🎨 **Beautiful Admin Panel** - Filament-powered interface  
🔒 **Secure & Tested** - Built with security best practices  
📱 **RESTful API** - Clean and well-documented endpoints  
⚡ **High Performance** - Optimized with caching strategies

## ✨ Features

### Core Modules

| Module           | Description                             | Status         |
| ---------------- | --------------------------------------- | -------------- |
| **Principles**   | Company values & principles management  | ✅ Ready       |
| **Team**         | Team members & organizational structure | ✅ Ready       |
| **Projects**     | Portfolio & case studies                | 🚧 Coming Soon |
| **Services**     | Service offerings management            | 🚧 Coming Soon |
| **Testimonials** | Client reviews & feedback               | 🚧 Coming Soon |

### Admin Panel Features

-   🎛️ **Full CRUD Operations** - Create, Read, Update, Delete
-   🔄 **Drag & Drop Reordering** - Visual content arrangement
-   📸 **Media Management** - Image and file uploads
-   🎨 **Rich Text Editor** - Content formatting
-   🔍 **Advanced Filtering** - Quick data access
-   📊 **Statistics Dashboard** - Real-time insights
-   🌐 **Multi-language Ready** - Internationalization support

### API Features

-   🔐 **Authentication** - Secure API access
-   📦 **Pagination** - Efficient data handling
-   💾 **Response Caching** - Improved performance
-   🔍 **Filtering & Sorting** - Flexible queries
-   📝 **Comprehensive Documentation** - Easy integration

## 🛠️ Tech Stack

```yaml
Backend Framework: Laravel 12.x
Admin Panel: Filament 3.3
Language: PHP 8.2+
Database: MySQL / PostgreSQL
Testing: Pest PHP
Cache: Redis (optional)
Queue: Redis / Database
```

### Dependencies

```json
{
    "laravel/framework": "^12.0",
    "filament/filament": "3.3",
    "php": "^8.2"
}
```

## 🚀 Quick Start

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   MySQL / PostgreSQL
-   Node.js & npm

### Installation

```bash
# Clone the repository
git clone <repository-url> compro-backend
cd compro-backend

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### One-Command Setup

```bash
composer run setup
```

This will:

-   Install PHP dependencies
-   Copy `.env` file
-   Generate app key
-   Run migrations
-   Install npm packages
-   Build frontend assets

### Access Admin Panel

```
URL: http://localhost:8000/admin
Default Credentials: (Set up during seeding)
```

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api/v1
```

### Available Endpoints

#### Principles Module

```http
GET    /api/v1/principles          # Get all principles
GET    /api/v1/principles/{id}     # Get single principle
GET    /api/v1/principles/stats    # Get statistics
```

#### Team Module

```http
GET    /api/v1/teams               # Get all team members
GET    /api/v1/teams/{id}          # Get single team member
```

### Response Format

```json
{
    "success": true,
    "message": "Data retrieved successfully",
    "data": [],
    "meta": {
        "total": 10,
        "per_page": 15,
        "current_page": 1
    }
}
```

📖 **Detailed Documentation**:

-   [PRINCIPLE_API_DOCUMENTATION.md](PRINCIPLE_API_DOCUMENTATION.md)
-   [TEAM_API_DOCUMENTATION.md](TEAM_API_DOCUMENTATION.md)

## 📁 Project Structure

```
compro-backend/
├── app/
│   ├── Filament/          # Admin panel resources
│   │   └── Resources/     # CRUD interfaces
│   ├── Http/
│   │   └── Controllers/   # API controllers
│   ├── Models/            # Eloquent models
│   └── Policies/          # Authorization policies
├── database/
│   ├── migrations/        # Database schemas
│   └── seeders/           # Sample data
├── routes/
│   ├── api.php           # API routes
│   └── web.php           # Web routes
├── tests/                # Test suites
├── public/               # Public assets
└── storage/              # File storage
```

## 📦 Available Modules

### 1. Principles Module

Manage company principles and core values.

**Features:**

-   CRUD operations via Filament
-   Public API endpoints
-   Icon & image management
-   Status toggle (active/inactive)
-   Ordering support

📖 [Full Documentation](PRINCIPLE_ARCHITECTURE.md)

### 2. Team Module

Manage team members and organizational structure.

**Features:**

-   Member profiles with photos
-   Role and position management
-   Social media links
-   Bio and description

📖 [Full Documentation](TEAM_MODULE_ARCHITECTURE.md)

## 💻 Development

### Run Development Server

```bash
# Start all services (server + queue + vite)
composer run dev
```

This runs:

-   Laravel development server (`php artisan serve`)
-   Queue worker (`php artisan queue:listen`)
-   Vite dev server (`npm run dev`)

### Individual Services

```bash
# API Server
php artisan serve

# Queue Worker
php artisan queue:listen

# Asset Compilation
npm run dev
```

### Code Quality

```bash
# Format code
composer run format

# Run linter
composer run lint

# Type checking
composer run analyse
```

## 🧪 Testing

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run specific test suite
php artisan test --filter=PrincipleTest
```

### Test Structure

```
tests/
├── Feature/              # Integration tests
│   ├── Api/             # API endpoint tests
│   └── Admin/           # Admin panel tests
└── Unit/                # Unit tests
    └── Models/          # Model tests
```

## 📖 Additional Documentation

| Document                                                           | Description                             |
| ------------------------------------------------------------------ | --------------------------------------- |
| [PRINCIPLE_QUICK_START.md](PRINCIPLE_QUICK_START.md)               | Quick start guide for Principles module |
| [PRINCIPLE_CRUD_DOCUMENTATION.md](PRINCIPLE_CRUD_DOCUMENTATION.md) | CRUD operations guide                   |
| [TEAM_QUICK_REFERENCE.md](TEAM_QUICK_REFERENCE.md)                 | Team module quick reference             |
| [ICON_FIX_NOTES.md](ICON_FIX_NOTES.md)                             | Icon handling troubleshooting           |

## 🔧 Configuration

### Environment Variables

```env
# Application
APP_NAME="Company Profile Backend"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=compro_db
DB_USERNAME=root
DB_PASSWORD=

# Cache & Queue
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 🆘 Support

-   📧 **Email**: support@yourcompany.com
-   📚 **Documentation**: [Full Docs](./docs)
-   🐛 **Bug Reports**: [GitHub Issues](https://github.com/yourusername/compro-backend/issues)

## 🎓 Learning Resources

-   [Laravel Documentation](https://laravel.com/docs)
-   [Filament Documentation](https://filamentphp.com/docs)
-   [Pest PHP Testing](https://pestphp.com)
-   [Laravel API Resources](https://laravel.com/docs/eloquent-resources)

---

<div align="center">

**Built with ❤️ using Laravel & Filament**

[Documentation](./docs) • [Report Bug](https://github.com/yourusername/compro-backend/issues) • [Request Feature](https://github.com/yourusername/compro-backend/issues)

</div>
