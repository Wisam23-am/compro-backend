# Award Module - Quick Reference

## 📁 File Locations

```
app/Models/Award.php
app/Filament/Resources/AwardResource.php
app/Http/Controllers/Api/AwardController.php
app/Policies/AwardPolicy.php
database/migrations/2025_12_25_000001_create_awards_table.php
```

## 🚀 Quick Commands

```bash
# Migration
php artisan migrate

# Seed data
php artisan db:seed --class=AwardSeeder

# Generate fake data
php artisan tinker
>>> Award::factory(10)->create()

# Run tests
php artisan test tests/Feature/AwardApiTest.php

# Clear cache
php artisan cache:clear
Cache::forget('awards.active')
Cache::forget('awards.featured')
```

## 🔌 API Endpoints

| Method | Endpoint               | Description              |
| ------ | ---------------------- | ------------------------ |
| GET    | `/api/awards`          | Get all active awards    |
| GET    | `/api/awards/featured` | Get featured awards only |

## 📊 Model Methods

```php
// Scopes
Award::active()->get();           // Only active awards
Award::featured()->get();         // Only featured awards
Award::ordered()->get();          // Ordered by sort_order
Award::active()->ordered()->get(); // Combine scopes

// Helper
Award::getNextSortOrder();        // Get next available sort order
```

## 🎨 Filament Features

### Table Actions

-   View, Edit, Delete
-   Toggle Featured
-   Bulk Activate/Deactivate
-   Drag & Drop Reordering

### Filters

-   Active Status (all/active/inactive)
-   Featured Status (all/featured/not featured)

### Navigation Badge

-   Shows total award count
-   Auto-updates

## 🧪 Testing

```php
// Example test
test('it returns active awards', function () {
    Award::factory(5)->active()->create();

    $response = $this->getJson('/api/awards');

    $response->assertStatus(200)
        ->assertJsonStructure(['success', 'data']);
});
```

## 🔒 Authorization

Edit `app/Policies/AwardPolicy.php`:

```php
public function create(User $user): bool
{
    return $user->hasRole('admin');
}
```

## 📦 Frontend Integration

```typescript
// Fetch awards
const response = await fetch("/api/awards");
const { success, data } = await response.json();

// Type definition
interface Award {
    id: number;
    title: string;
    location: string;
    featured: boolean;
}
```

## 🎯 Common Tasks

### Add New Award via Code

```php
Award::create([
    'title' => 'New Award',
    'location' => 'City, 2025',
    'featured' => false,
    'is_active' => true,
    'sort_order' => Award::getNextSortOrder(),
]);
```

### Update Award Status

```php
$award = Award::find(1);
$award->update(['is_active' => true]);
```

### Get Featured Awards

```php
$featured = Award::active()->featured()->ordered()->get();
```

## 🐛 Troubleshooting

| Issue              | Solution                                    |
| ------------------ | ------------------------------------------- |
| Awards not in API  | Check `is_active` status, clear cache       |
| Policy not working | Register in `AuthServiceProvider`           |
| Drag & drop issues | Clear browser cache                         |
| Tests failing      | Run `php artisan migrate:fresh` in test env |

## 📋 Data Structure

```json
{
    "id": 1,
    "title": "Award Title",
    "location": "City, 2025",
    "featured": false,
    "is_active": true,
    "sort_order": 0,
    "created_at": "2025-12-25T00:00:00.000000Z",
    "updated_at": "2025-12-25T00:00:00.000000Z"
}
```

## ✅ Checklist for New Features

-   [ ] Update migration if schema changes
-   [ ] Add validation rules in Resource
-   [ ] Update API response if needed
-   [ ] Write tests for new functionality
-   [ ] Update documentation
-   [ ] Clear cache after deployment

---

For detailed documentation, see [AWARD_MODULE_DOCUMENTATION.md](AWARD_MODULE_DOCUMENTATION.md)
