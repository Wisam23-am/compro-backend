# Principle CRUD - Quick Start Guide

## 🚀 Quick Setup (3 Steps)

### 1. Run Migration

```bash
php artisan migrate
php artisan storage:link
```

### 2. Access Admin Panel

Navigate to: `http://your-domain.com/admin/principles`

### 3. Create Your First Principle

Click **"New Principle"** and fill in:

-   Title (required, unique)
-   Description (required)
-   Upload image/icon (optional)
-   Set sort order and status

## 📊 Dashboard Widgets

Add to your Filament dashboard (`app/Filament/Pages/Dashboard.php`):

```php
use App\Filament\Widgets\PrincipleStatsWidget;
use App\Filament\Widgets\LatestPrinciplesWidget;

protected function getHeaderWidgets(): array
{
    return [
        PrincipleStatsWidget::class,
        LatestPrinciplesWidget::class,
    ];
}
```

## 🔌 API Usage

### Get All Principles

```bash
curl http://your-domain.com/api/principles
```

### Get Single Principle

```bash
curl http://your-domain.com/api/principles/1
```

### Get Statistics

```bash
curl http://your-domain.com/api/principles/stats/overview
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run principle tests only
php artisan test --filter PrincipleApiTest
```

## 📋 Features Checklist

-   ✅ Full CRUD operations
-   ✅ Image upload with editor
-   ✅ SVG icon support
-   ✅ Drag-and-drop reordering
-   ✅ Bulk actions
-   ✅ Status toggle
-   ✅ Search & filters
-   ✅ Soft deletes
-   ✅ Dashboard widgets
-   ✅ Public REST API
-   ✅ Response caching
-   ✅ Policy-based authorization
-   ✅ Comprehensive tests
-   ✅ Input sanitization

## 🎯 Key Files Created

| File                                               | Purpose                    |
| -------------------------------------------------- | -------------------------- |
| `app/Models/Principle.php`                         | Eloquent model with scopes |
| `app/Filament/Resources/PrincipleResource.php`     | Main Filament resource     |
| `app/Filament/Widgets/PrincipleStatsWidget.php`    | Stats widget               |
| `app/Filament/Widgets/LatestPrinciplesWidget.php`  | Recent items widget        |
| `app/Http/Controllers/Api/PrincipleController.php` | API controller             |
| `app/Policies/PrinciplePolicy.php`                 | Authorization policy       |
| `database/factories/PrincipleFactory.php`          | Test data factory          |
| `tests/Feature/PrincipleApiTest.php`               | API tests                  |
| `routes/api.php`                                   | API routes                 |

## 💡 Quick Tips

1. **Reorder**: Drag rows in the table to change display order
2. **Bulk Edit**: Select multiple rows and use bulk actions
3. **Quick Toggle**: Use the status action to activate/deactivate
4. **Search**: Use the search box to filter by title or subtitle
5. **Cache**: API responses are cached for 1 hour

## 🐛 Troubleshooting

**Images not showing?**

```bash
php artisan storage:link
```

**Cache issues?**

```bash
php artisan cache:clear
php artisan config:clear
```

**Permission errors?**
Check policy settings in `app/Policies/PrinciplePolicy.php`

## 📚 Full Documentation

See [PRINCIPLE_CRUD_DOCUMENTATION.md](PRINCIPLE_CRUD_DOCUMENTATION.md) for complete details.

---

**Need Help?** Check the comprehensive documentation or Laravel/Filament docs.
