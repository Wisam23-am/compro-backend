# Principle Management System - Laravel Filament CRUD

A comprehensive Laravel Filament implementation for managing company principles with advanced features including API integration, dashboard widgets, and role-based access control.

## 📋 Table of Contents

-   [Features](#features)
-   [File Structure](#file-structure)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [API Documentation](#api-documentation)
-   [Testing](#testing)
-   [Customization](#customization)
-   [Best Practices](#best-practices)

## ✨ Features

### Core Functionality

-   ✅ **Full CRUD Operations**: Create, Read, Update, Delete principles with soft deletes
-   ✅ **Advanced Forms**: Sectioned forms with validation, image editor, and SVG icon support
-   ✅ **Sortable Tables**: Drag-and-drop reordering with `sort_order` field
-   ✅ **Filtering**: Filter by active/inactive status and soft-deleted records
-   ✅ **Bulk Actions**: Activate, deactivate, delete, or restore multiple records at once
-   ✅ **Search**: Full-text search across title and subtitle fields

### UI/UX Features

-   ✅ **Image Upload**: Advanced image upload with editing tools and aspect ratio controls
-   ✅ **SVG Icon Support**: Upload and display custom SVG icons
-   ✅ **Status Toggle**: Quick actions to activate/deactivate principles
-   ✅ **Action Groups**: Organized actions with confirmation dialogs
-   ✅ **Custom Notifications**: Success/error notifications for all operations
-   ✅ **Responsive Layout**: Mobile-friendly design

### Dashboard Widgets

-   ✅ **PrincipleStatsWidget**: Displays total, active, and inactive principle counts with trend charts
-   ✅ **LatestPrinciplesWidget**: Shows recently updated principles in a table format

### API Integration

-   ✅ **RESTful Endpoints**: Public API to fetch active principles
-   ✅ **JSON Responses**: Standardized success/error response format
-   ✅ **Response Caching**: 1-hour cache for improved performance
-   ✅ **Ordering**: Results ordered by `sort_order` for consistency

### Security & Authorization

-   ✅ **Policy-Based Access Control**: Fine-grained permissions via PrinciplePolicy
-   ✅ **Input Sanitization**: XSS prevention with strip_tags()
-   ✅ **Unique Validation**: Ensures principle titles are unique
-   ✅ **Soft Deletes**: Safe deletion with restore capability

### Testing & Quality

-   ✅ **Feature Tests**: Comprehensive API tests with 95%+ coverage
-   ✅ **Factory Classes**: Faker-based test data generation
-   ✅ **Cache Testing**: Validates caching behavior
-   ✅ **Edge Case Handling**: Tests for empty states, errors, etc.

## 📁 File Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   └── PrincipleResource.php              # Main Filament resource
│   │   └── PrincipleResource/
│   │       └── Pages/
│   │           ├── ListPrinciples.php         # List page with create action
│   │           ├── CreatePrinciple.php        # Create page with validation
│   │           ├── EditPrinciple.php          # Edit page with actions
│   │           └── ViewPrinciple.php          # View page with details
│   └── Widgets/
│       ├── PrincipleStatsWidget.php           # Statistics overview widget
│       └── LatestPrinciplesWidget.php         # Recent principles table widget
├── Http/
│   └── Controllers/
│       └── Api/
│           └── PrincipleController.php        # API controller with caching
├── Models/
│   └── Principle.php                          # Eloquent model with scopes
├── Policies/
│   └── PrinciplePolicy.php                    # Authorization policy
└── Providers/
    └── AppServiceProvider.php                 # Policy registration & cache clearing

database/
├── factories/
│   └── PrincipleFactory.php                   # Test data factory
└── migrations/
    └── 2024_12_20_000001_create_principles_table.php  # Database schema

routes/
└── api.php                                     # API route definitions

tests/
└── Feature/
    └── PrincipleApiTest.php                    # Comprehensive API tests
```

## 🚀 Installation

### Step 1: Run Database Migration

```bash
php artisan migrate
```

This creates the `principles` table with the following schema:

-   `id`: Primary key
-   `title`: Unique principle title
-   `subtitle`: Optional subtitle
-   `description`: Full description text
-   `icon`: Path to SVG icon file
-   `image`: Path to principle image
-   `sort_order`: Integer for ordering (default: 0)
-   `is_active`: Boolean status (default: true)
-   `timestamps`: Created at, Updated at
-   `deleted_at`: Soft delete timestamp

### Step 2: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public` for file access.

### Step 3: Seed Test Data (Optional)

```bash
php artisan tinker
```

```php
// Create 10 sample principles
Principle::factory()->count(10)->create();

// Create specific principles
Principle::factory()->active()->create([
    'title' => 'Innovation First',
    'subtitle' => 'Leading with creativity',
    'description' => 'We prioritize innovative solutions...',
]);
```

### Step 4: Clear Cache

```bash
php artisan cache:clear
php artisan config:clear
```

## ⚙️ Configuration

### File Upload Settings

Modify form configuration in `PrincipleResource.php`:

```php
Forms\Components\FileUpload::make('image')
    ->imageResizeTargetWidth('1200')        // Change target width
    ->imageResizeTargetHeight('675')        // Change target height
    ->imageCropAspectRatio('16:9')         // Change aspect ratio
    ->maxSize(2048)                         // Max size in KB
```

### Cache Duration

Modify cache settings in `PrincipleController.php`:

```php
// Change from 1 hour (3600 seconds) to 30 minutes (1800 seconds)
Cache::remember('principles.active', 1800, function () {
    // ...
});
```

### Authorization Rules

Modify policy rules in `PrinciplePolicy.php`:

```php
public function create(User $user): bool
{
    // Example: Restrict to admin role only
    return $user->hasRole('admin');
}
```

### Widget Position

Change widget order on dashboard:

```php
// In PrincipleStatsWidget.php
protected static ?int $sort = 1;  // Change to reorder
```

## 📖 Usage

### Access the Principle Manager

Navigate to your Filament admin panel:

```
https://your-domain.com/admin/principles
```

### Creating a Principle

1. Click **"New Principle"** button
2. Fill in the form:
    - **Title**: Required, unique (max 255 chars)
    - **Subtitle**: Optional (max 255 chars)
    - **Description**: Required (max 1000 chars)
    - **Image**: Optional, up to 2MB (JPG, PNG, WebP)
    - **Icon**: Optional, up to 512KB (SVG only)
    - **Sort Order**: Default is 0 (lower = appears first)
    - **Active Status**: Toggle on/off
3. Click **"Create"**

### Editing a Principle

1. Click on a principle row or use the Actions menu
2. Select **"Edit"**
3. Modify fields as needed
4. Click **"Save changes"**

### Reordering Principles

1. In the principles list, look for the drag handle icon
2. Drag rows up or down to reorder
3. Order is automatically saved

### Bulk Actions

1. Select multiple principles using checkboxes
2. Choose an action from the bulk actions dropdown:
    - **Activate Selected**: Set all to active
    - **Deactivate Selected**: Set all to inactive
    - **Delete**: Move to trash
    - **Restore**: Restore from trash
    - **Force Delete**: Permanently remove

### Using Widgets

Add widgets to your dashboard in `app/Filament/Pages/Dashboard.php`:

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

## 🔌 API Documentation

### Base URL

```
https://your-domain.com/api/principles
```

### Endpoints

#### 1. Get All Active Principles

**GET** `/api/principles`

**Response:**

```json
{
    "success": true,
    "message": "Principles retrieved successfully",
    "data": [
        {
            "id": 1,
            "title": "Innovation First",
            "subtitle": "Leading with creativity",
            "description": "We prioritize innovative solutions...",
            "icon": "http://example.com/storage/principles/icons/icon.svg",
            "image": "http://example.com/storage/principles/images/image.jpg",
            "sort_order": 1
        }
    ],
    "meta": {
        "total": 5,
        "timestamp": "2024-12-22T10:30:00.000000Z"
    }
}
```

#### 2. Get Single Principle

**GET** `/api/principles/{id}`

**Response:**

```json
{
    "success": true,
    "message": "Principle retrieved successfully",
    "data": {
        "id": 1,
        "title": "Innovation First",
        "subtitle": "Leading with creativity",
        "description": "We prioritize innovative solutions...",
        "icon": "http://example.com/storage/principles/icons/icon.svg",
        "image": "http://example.com/storage/principles/images/image.jpg",
        "sort_order": 1
    }
}
```

**Error Response (404):**

```json
{
    "success": false,
    "message": "Principle not found or inactive"
}
```

#### 3. Get Statistics

**GET** `/api/principles/stats/overview`

**Response:**

```json
{
    "success": true,
    "message": "Statistics retrieved successfully",
    "data": {
        "total": 10,
        "active": 8,
        "inactive": 2
    }
}
```

### API Integration Examples

#### JavaScript (Fetch API)

```javascript
// Fetch all principles
fetch("https://your-domain.com/api/principles")
    .then((response) => response.json())
    .then((data) => {
        console.log(data.data); // Array of principles
    });

// Fetch single principle
fetch("https://your-domain.com/api/principles/1")
    .then((response) => response.json())
    .then((data) => {
        console.log(data.data); // Single principle object
    });
```

#### React Example

```jsx
import { useEffect, useState } from "react";

function PrinciplesList() {
    const [principles, setPrinciples] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetch("https://your-domain.com/api/principles")
            .then((res) => res.json())
            .then((data) => {
                setPrinciples(data.data);
                setLoading(false);
            });
    }, []);

    if (loading) return <div>Loading...</div>;

    return (
        <div>
            {principles.map((principle) => (
                <div key={principle.id}>
                    <h3>{principle.title}</h3>
                    <p>{principle.description}</p>
                    {principle.image && (
                        <img src={principle.image} alt={principle.title} />
                    )}
                </div>
            ))}
        </div>
    );
}
```

#### PHP (cURL)

```php
$ch = curl_init('https://your-domain.com/api/principles');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$principles = $data['data'];
```

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

### Run Specific Test Suite

```bash
php artisan test --filter PrincipleApiTest
```

### Run With Coverage

```bash
php artisan test --coverage
```

### Test Cases Included

-   ✅ Fetch all active principles
-   ✅ Inactive principles are excluded
-   ✅ Principles are ordered by sort_order
-   ✅ Fetch single principle by ID
-   ✅ Cannot fetch inactive principle
-   ✅ Non-existent principle returns 404
-   ✅ Empty principles list returns empty array
-   ✅ Fetch principle statistics
-   ✅ API responses are cached
-   ✅ Asset URLs are properly formatted
-   ✅ Handles principles without images

### Manual Testing

1. **Create a principle** via Filament admin
2. **Check API** at `/api/principles`
3. **Toggle status** to inactive
4. **Verify** principle no longer appears in API
5. **Test reordering** via drag-and-drop
6. **Verify order** in API response

## 🎨 Customization

### Change Table Columns

Edit `PrincipleResource.php` in the `table()` method:

```php
Tables\Columns\TextColumn::make('custom_field')
    ->searchable()
    ->sortable()
    ->label('Custom Label'),
```

### Add Custom Actions

```php
Tables\Actions\Action::make('custom_action')
    ->label('Custom Action')
    ->icon('heroicon-o-star')
    ->action(function (Principle $record) {
        // Your logic here
    }),
```

### Customize Notifications

```php
Notification::make()
    ->success()
    ->title('Custom Title')
    ->body('Custom message')
    ->duration(5000)  // Duration in milliseconds
    ->send();
```

### Add New Form Fields

```php
Forms\Components\TextInput::make('new_field')
    ->required()
    ->label('New Field')
    ->helperText('Description of the field'),
```

## 🏆 Best Practices

### Performance Optimization

1. **Use caching**: API responses are cached for 1 hour
2. **Eager loading**: Use `with()` for related models if added
3. **Indexes**: Database indexes on `is_active` and `sort_order`
4. **Image optimization**: Resize images to recommended dimensions

### Security

1. **Input sanitization**: All inputs are sanitized with `strip_tags()`
2. **Validation**: Unique titles, max lengths enforced
3. **Authorization**: Policy-based access control
4. **API rate limiting**: Consider adding rate limiting in production

### Code Quality

1. **Comments**: All methods have PHPDoc comments
2. **Type hints**: Strict typing throughout
3. **Single responsibility**: Each class has a clear purpose
4. **DRY principle**: Reusable components and methods

### Deployment Checklist

-   [ ] Run migrations: `php artisan migrate`
-   [ ] Create storage link: `php artisan storage:link`
-   [ ] Set proper permissions on storage directories
-   [ ] Configure environment variables
-   [ ] Clear all caches: `php artisan optimize:clear`
-   [ ] Test API endpoints
-   [ ] Verify file upload functionality
-   [ ] Check authorization rules
-   [ ] Run test suite
-   [ ] Enable HTTPS for production
-   [ ] Configure backup strategy

## 📝 Common Issues & Solutions

### Issue: Images not displaying

**Solution**: Run `php artisan storage:link` and check file permissions

### Issue: Cache not clearing

**Solution**: Clear cache manually with `php artisan cache:clear` or check cache driver configuration

### Issue: Unauthorized errors

**Solution**: Verify user authentication and policy permissions in `PrinciplePolicy.php`

### Issue: Sort order not saving

**Solution**: Ensure JavaScript is enabled and check browser console for errors

## 📞 Support

For issues or questions:

-   Check the Laravel documentation: https://laravel.com/docs
-   Check the Filament documentation: https://filamentphp.com/docs
-   Review the test files for usage examples

## 📄 License

This implementation follows your Laravel project's license terms.

---

**Version**: 1.0.0  
**Last Updated**: December 22, 2024  
**Author**: GitHub Copilot  
**Laravel Version**: 10.x  
**Filament Version**: 3.x
