# Award Management System - Documentation

## 📋 Overview

This is a complete Laravel Filament CRUD implementation for managing company awards and achievements. It provides a robust backend content management system that integrates seamlessly with the frontend Awards component (`award.tsx`).

## 🏗️ File Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── AwardResource.php                    # Main Filament resource
│   │   └── AwardResource/
│   │       └── Pages/
│   │           ├── ListAwards.php               # List page
│   │           ├── CreateAward.php              # Create page
│   │           ├── EditAward.php                # Edit page
│   │           └── ViewAward.php                # View page
│   └── Widgets/
│       ├── AwardStatsWidget.php                 # Statistics widget
│       └── LatestAwardsWidget.php               # Latest awards widget
├── Http/
│   └── Controllers/
│       └── Api/
│           └── AwardController.php              # Public API controller
├── Models/
│   └── Award.php                                # Eloquent model
└── Policies/
    └── AwardPolicy.php                          # Authorization policy

database/
├── factories/
│   └── AwardFactory.php                         # Factory for testing
├── migrations/
│   └── 2025_12_25_000001_create_awards_table.php
└── seeders/
    └── AwardSeeder.php                          # Sample data seeder

tests/
└── Feature/
    └── AwardApiTest.php                         # API feature tests

routes/
└── api.php                                      # API route definitions
```

## 🗄️ Database Schema

### Awards Table

| Column     | Type      | Description                            |
| ---------- | --------- | -------------------------------------- |
| id         | bigint    | Primary key                            |
| title      | string    | Award title (unique)                   |
| location   | string    | Location and year (e.g., "Bali, 2020") |
| featured   | boolean   | Featured status                        |
| is_active  | boolean   | Active/visible status                  |
| sort_order | integer   | Display order (lower = first)          |
| created_at | timestamp | Creation timestamp                     |
| updated_at | timestamp | Last update timestamp                  |

**Indexes:**

-   `(is_active, sort_order)` - Composite index for efficient querying
-   `featured` - Index for featured awards filtering

## 🚀 Installation & Setup

### 1. Run Migration

```bash
php artisan migrate
```

### 2. Seed Sample Data

```bash
php artisan db:seed --class=AwardSeeder
```

Or use the factory for random data:

```bash
php artisan tinker
>>> Award::factory(10)->create()
```

### 3. Register Policy (if not auto-discovered)

Add to `app/Providers/AuthServiceProvider.php`:

```php
protected $policies = [
    Award::class => AwardPolicy::class,
];
```

### 4. Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
```

## 🎨 Filament Features

### Form Fields

**Award Information Section:**

-   **Title** - Unique text input with validation
-   **Location** - Text input for location and year

**Display Settings Section:**

-   **Active Status** - Toggle for visibility
-   **Featured Award** - Toggle for featured status
-   **Sort Order** - Numeric input for ordering

### Table Features

1. **Columns:**

    - Sort Order (sortable, searchable)
    - Title (sortable, searchable, bold)
    - Location (sortable, searchable)
    - Featured (icon with tooltip)
    - Active Status (toggle column with instant update)
    - Timestamps (toggleable, hidden by default)

2. **Filters:**

    - Active Status (all/active/inactive)
    - Featured Status (all/featured/not featured)

3. **Actions:**

    - View - View award details
    - Edit - Edit award
    - Toggle Featured - Quick toggle featured status
    - Delete - Delete with confirmation

4. **Bulk Actions:**

    - Activate Selected
    - Deactivate Selected
    - Delete Selected

5. **Drag & Drop Reordering:**
    - Reorder awards by dragging rows
    - Automatically updates `sort_order` field

### Navigation

-   **Icon:** Trophy icon
-   **Group:** Content Management
-   **Badge:** Shows total award count
-   **Badge Color:** Success (if >10), Primary (if ≤10)

### Widgets

**1. Award Stats Widget**

-   Total Awards
-   Active Awards
-   Featured Awards
-   Mini charts for visual representation

**2. Latest Awards Widget**

-   Displays 5 most recent awards
-   Shows title, location, featured status, active status
-   Quick edit links

## 🔌 API Endpoints

### Base URL

```
/api/awards
```

### Endpoints

#### 1. Get All Active Awards

```http
GET /api/awards
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Solid Fundamental Crafter Async",
            "location": "Bali, 2020",
            "featured": false
        },
        {
            "id": 2,
            "title": "Small Things Made Much Big Impacts",
            "location": "Zurich, 2022",
            "featured": true
        }
    ]
}
```

**Features:**

-   Returns only active awards (`is_active = true`)
-   Ordered by `sort_order` ascending
-   Cached for 1 hour
-   Public endpoint (no authentication required)

#### 2. Get Featured Awards

```http
GET /api/awards/featured
```

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 3,
            "title": "Small Things Made Much Big Impacts",
            "location": "Zurich, 2022",
            "featured": true
        }
    ]
}
```

**Features:**

-   Returns only featured AND active awards
-   Ordered by `sort_order` ascending
-   Cached for 1 hour

### Error Handling

All endpoints return consistent error responses:

```json
{
    "success": false,
    "message": "Failed to fetch awards",
    "error": "Error details (only in debug mode)"
}
```

## 🔒 Security & Authorization

### Policy Methods

-   `viewAny()` - View awards list
-   `view()` - View single award
-   `create()` - Create new award
-   `update()` - Update existing award
-   `delete()` - Delete award
-   `reorder()` - Reorder awards

**Default:** All methods return `true` (allow all authenticated users)

**Customize:** Uncomment and modify policy methods for role-based access:

```php
public function create(User $user): bool
{
    return $user->hasRole('admin') || $user->can('create-awards');
}
```

### Input Sanitization

-   All inputs are validated using Laravel validation rules
-   Unique constraint on `title` field
-   XSS protection via Laravel's default escaping

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/AwardApiTest.php

# Run with coverage
php artisan test --coverage
```

### Test Coverage

The test suite covers:

-   ✅ Fetch all active awards
-   ✅ Awards ordered by `sort_order`
-   ✅ Inactive awards excluded
-   ✅ Featured awards endpoint
-   ✅ Correct data structure
-   ✅ Empty state handling
-   ✅ Error handling

## 📊 Model Scopes

Use these scopes in your code:

```php
// Get only active awards
Award::active()->get();

// Get featured awards
Award::featured()->get();

// Get ordered awards
Award::ordered()->get();

// Combine scopes
Award::active()->featured()->ordered()->get();
```

## 🎯 Frontend Integration

### React/TypeScript Integration

```typescript
// Fetch awards from API
const fetchAwards = async () => {
    const response = await fetch("/api/awards");
    const { success, data } = await response.json();

    if (success) {
        setAwards(data);
    }
};

// Type definition (matches API response)
interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}
```

### Example Usage in award.tsx

Replace the static `awards` array with API data:

```typescript
import { useEffect, useState } from 'react';

export function Awards() {
  const [awards, setAwards] = useState<Award[]>([]);

  useEffect(() => {
    fetch('/api/awards')
      .then(res => res.json())
      .then(({ data }) => setAwards(data));
  }, []);

  return (
    // ... rest of component
  );
}
```

## 🔧 Customization

### Change Cache Duration

Edit `app/Http/Controllers/Api/AwardController.php`:

```php
// Cache for 30 minutes instead of 1 hour
$awards = Cache::remember('awards.active', 1800, function () {
    // ...
});
```

### Clear Cache

```bash
# Clear all cache
php artisan cache:clear

# Clear specific cache key
php artisan tinker
>>> Cache::forget('awards.active')
>>> Cache::forget('awards.featured')
```

### Add More Filters

Edit `AwardResource.php`:

```php
Tables\Filters\SelectFilter::make('location')
    ->options([
        'Bali, 2020' => 'Bali, 2020',
        'Shanghai, 2021' => 'Shanghai, 2021',
        // ... more options
    ]),
```

### Add More Columns

```php
Tables\Columns\ImageColumn::make('image')
    ->circular(),

Tables\Columns\TextColumn::make('description')
    ->limit(50),
```

## 📝 Best Practices

1. **Always use scopes** for querying active/featured awards
2. **Cache API responses** to reduce database load
3. **Clear cache** after bulk updates in Filament
4. **Use factories** for testing and development
5. **Validate unique titles** to prevent duplicates
6. **Use sort_order** instead of manual ordering
7. **Test API endpoints** after any changes

## 🐛 Troubleshooting

### Awards not showing in API

-   Check `is_active` status
-   Clear cache: `php artisan cache:clear`
-   Check database: `php artisan tinker` → `Award::all()`

### Policy not working

-   Ensure policy is registered in `AuthServiceProvider`
-   Run: `php artisan config:clear`
-   Check user authentication

### Drag & drop not working

-   Ensure table has `->reorderable('sort_order')`
-   Check JavaScript console for errors
-   Clear browser cache

## 📚 Additional Resources

-   [Filament Documentation](https://filamentphp.com/docs)
-   [Laravel Documentation](https://laravel.com/docs)
-   [Pest PHP Testing](https://pestphp.com/docs)

## 🎉 Quick Start Commands

```bash
# Complete setup
php artisan migrate
php artisan db:seed --class=AwardSeeder
php artisan cache:clear

# Run tests
php artisan test tests/Feature/AwardApiTest.php

# Generate fake data
php artisan tinker
>>> Award::factory(20)->create()

# Clear and reseed
php artisan migrate:fresh --seed
```

## 📞 Support

For issues or questions:

1. Check this documentation
2. Review test files for usage examples
3. Check Laravel/Filament documentation
4. Review error logs: `storage/logs/laravel.log`

---

**Version:** 1.0.0  
**Last Updated:** December 25, 2025  
**Laravel Version:** 10.x  
**Filament Version:** 3.x
