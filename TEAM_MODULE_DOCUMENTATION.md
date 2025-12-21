# Team Module - Complete Documentation

## 📋 Overview

This comprehensive Laravel Filament CRUD module manages team member profiles for both administrative dashboard and public-facing website display. Built following Laravel and Filament best practices, it includes advanced features like sorting, filtering, bulk actions, API integration, and dashboard widgets.

## 🎯 Features

### ✅ Core Filament Resources

-   **TeamResource.php**: Advanced form and table configuration
-   **CRUD Pages**: List, Create, Edit, and View
-   **Form Fields**:
    -   Name (required, unique)
    -   Position (required)
    -   Location (optional)
    -   Image upload with preview
    -   Active status toggle
    -   Sort order for manual arrangement

### ✅ Enhanced UI Features

-   **Image Upload**: Profile photos with editor, max 5MB
-   **Status Toggle**: Color-coded active/inactive indicators
-   **Reorderable Rows**: Drag-and-drop sort ordering
-   **Bulk Actions**:
    -   Activate/Deactivate multiple members
    -   Bulk delete with confirmation
-   **Badge Indicators**: Visual status indicators in table
-   **Search & Filters**: By name, position, and status

### ✅ Dashboard Widgets

1. **TeamStatsWidget**:

    - Total team members count
    - Active/inactive breakdown
    - Recently added (last 30 days)
    - Growth chart visualization

2. **LatestTeamWidget**:
    - Shows 5 most recent additions
    - Quick view/edit actions
    - Real-time status display

### ✅ API Integration

**Base URL**: `/api/team`

**Endpoints**:

1. `GET /api/team` - List all active team members
2. `GET /api/team/{id}` - Get specific member details
3. `GET /api/team/stats/overview` - Team statistics

**Response Format**:

```json
{
  "success": true,
  "message": "Team members retrieved successfully",
  "data": [...],
  "count": 10
}
```

### ✅ Security & Authorization

-   **TeamPolicy**: Comprehensive authorization rules
-   **Validation**: Required fields, unique names
-   **Image Security**: Stored in public disk with proper permissions
-   **Cache Management**: Auto-clears on model changes

### ✅ Testing & Quality

-   **TeamFactory**: Realistic test data generation
-   **Feature Tests**: Complete API test coverage
-   **TeamSeeder**: Sample data with various roles and statuses

## 📁 File Structure

```
app/
├── Filament/
│   ├── Resources/
│   │   ├── TeamResource.php              # Main resource
│   │   └── TeamResource/
│   │       └── Pages/
│   │           ├── ListTeams.php         # List page
│   │           ├── CreateTeam.php        # Create page
│   │           ├── EditTeam.php          # Edit page
│   │           └── ViewTeam.php          # View page
│   └── Widgets/
│       ├── TeamStatsWidget.php           # Statistics widget
│       └── LatestTeamWidget.php          # Recent members widget
├── Http/
│   └── Controllers/
│       └── Api/
│           └── TeamController.php        # API controller
├── Models/
│   └── Team.php                          # Team model with scopes
└── Policies/
    └── TeamPolicy.php                    # Authorization policy

database/
├── factories/
│   └── TeamFactory.php                   # Test data factory
├── migrations/
│   ├── 2024_12_20_000002_create_teams_table.php
│   └── 2025_12_21_191851_update_teams_table_structure.php
└── seeders/
    └── TeamSeeder.php                    # Sample data seeder

tests/
└── Feature/
    └── TeamApiTest.php                   # API tests

routes/
└── api.php                               # API routes
```

## 🗄️ Database Schema

### Table: `teams`

| Column     | Type         | Attributes                  | Description           |
| ---------- | ------------ | --------------------------- | --------------------- |
| id         | bigint       | PRIMARY KEY, AUTO_INCREMENT | Unique identifier     |
| image      | varchar(255) | NOT NULL                    | Profile image path    |
| name       | varchar(255) | NOT NULL, UNIQUE            | Member full name      |
| position   | varchar(255) | NOT NULL                    | Job title/role        |
| location   | varchar(255) | NULLABLE                    | City/region           |
| is_active  | boolean      | DEFAULT true                | Visibility status     |
| sort_order | integer      | DEFAULT 0                   | Display order         |
| created_at | timestamp    | NULLABLE                    | Creation timestamp    |
| updated_at | timestamp    | NULLABLE                    | Last update timestamp |

**Indexes**:

-   `idx_teams_is_active_sort_order` on (is_active, sort_order)

## 🔧 Installation & Setup

### 1. Run Migrations

```bash
cd compro-backend
php artisan migrate
```

This will:

-   Create the `teams` table
-   Update column names (role → position, featured → is_active)
-   Add sort_order field
-   Create performance indexes

### 2. Seed Sample Data

```bash
php artisan db:seed --class=TeamSeeder
```

Creates 11 sample team members:

-   7 active members (executives, developers, designers)
-   3 additional developers
-   1 inactive member for testing

### 3. Create Storage Link

```bash
php artisan storage:link
```

Enables public access to uploaded team member photos.

### 4. Configure Permissions

Ensure `storage/app/public/team-members/` directory exists and is writable:

```bash
mkdir -p storage/app/public/team-members
chmod -R 775 storage/app/public
```

## 🚀 Usage

### Admin Panel

**Access**: `/admin/teams`

**List View**:

-   Search by name or position
-   Filter by active/inactive status
-   Sort by any column
-   Reorder by dragging rows
-   Bulk activate/deactivate/delete

**Create/Edit**:

1. Enter member name (required, unique)
2. Specify position/role (required)
3. Add location (optional)
4. Upload profile photo (required, max 5MB)
5. Toggle active status
6. Set sort order (lower = higher priority)

**View Page**:

-   Displays full member profile
-   Shows all metadata and timestamps
-   Quick edit/delete actions

### Dashboard Widgets

Add to your Filament Panel Provider:

```php
use App\Filament\Widgets\TeamStatsWidget;
use App\Filament\Widgets\LatestTeamWidget;

public function panel(Panel $panel): Panel
{
    return $panel
        ->widgets([
            TeamStatsWidget::class,
            LatestTeamWidget::class,
        ]);
}
```

### API Usage

**Get All Active Members**:

```bash
curl http://your-domain.com/api/team
```

**Get Specific Member**:

```bash
curl http://your-domain.com/api/team/1
```

**Get Statistics**:

```bash
curl http://your-domain.com/api/team/stats/overview
```

## 🎨 Customization

### Model Scopes

**Active Members Only**:

```php
$activeTeam = Team::active()->get();
```

**Ordered by Priority**:

```php
$orderedTeam = Team::ordered()->get();
```

**Combined**:

```php
$team = Team::active()->ordered()->get();
```

### Factory Usage

**Create Test Members**:

```php
// Random member
Team::factory()->create();

// Active executive
Team::factory()->executive()->active()->create();

// Developer with specific order
Team::factory()->developer()->sortOrder(10)->create();

// Multiple members
Team::factory()->count(10)->create();
```

### Image Handling

**Get Image URL**:

```php
$member = Team::find(1);
echo $member->image_url; // Full URL with storage path
```

**Custom Image Path**:
Modify in TeamResource.php:

```php
Forms\Components\FileUpload::make('image')
    ->directory('team-members') // Change directory
    ->disk('public')            // Change disk
```

## 🧪 Testing

### Run All Tests

```bash
php artisan test --filter=TeamApi
```

### Run Specific Test

```bash
php artisan test --filter="returns active team members only"
```

### Test Coverage

-   ✅ API returns only active members
-   ✅ Correct sort order applied
-   ✅ Proper response structure
-   ✅ 404 for inactive/missing members
-   ✅ Statistics calculations
-   ✅ Image URL generation

## 📊 API Response Examples

### Success Response (List)

```json
{
    "success": true,
    "message": "Team members retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Sarah Johnson",
            "position": "Chief Executive Officer",
            "location": "San Francisco, CA",
            "image": "http://your-domain.com/storage/team-members/ceo.jpg",
            "sort_order": 1
        }
    ],
    "count": 1
}
```

### Success Response (Show)

```json
{
    "success": true,
    "message": "Team member retrieved successfully",
    "data": {
        "id": 1,
        "name": "Sarah Johnson",
        "position": "Chief Executive Officer",
        "location": "San Francisco, CA",
        "image": "http://your-domain.com/storage/team-members/ceo.jpg",
        "sort_order": 1,
        "created_at": "2024-12-21T10:30:00.000000Z",
        "updated_at": "2024-12-21T10:30:00.000000Z"
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Team member not found or is inactive",
    "error": "The requested team member does not exist or is not currently active"
}
```

### Statistics Response

```json
{
    "success": true,
    "message": "Team statistics retrieved successfully",
    "data": {
        "total": 10,
        "active": 7,
        "inactive": 3,
        "percentage_active": 70.0
    }
}
```

## 🔐 Security Features

1. **Authorization**: All actions protected by TeamPolicy
2. **Validation**: Server-side validation on all inputs
3. **Unique Names**: Database constraint prevents duplicates
4. **XSS Protection**: All output properly escaped
5. **File Upload Security**:
    - Max size: 5MB
    - Allowed types: Images only
    - Stored in isolated directory

## 🎯 Best Practices Implemented

-   ✅ Repository pattern with Eloquent models
-   ✅ Query scopes for reusable logic
-   ✅ Resource controllers for API
-   ✅ Form validation
-   ✅ Policy-based authorization
-   ✅ Factory for test data
-   ✅ Comprehensive tests
-   ✅ Proper error handling
-   ✅ Cache management
-   ✅ RESTful API design
-   ✅ Responsive UI
-   ✅ Accessible components

## 📝 Notes

-   **Images**: Placeholder images should be replaced with actual photos
-   **Permissions**: Modify TeamPolicy for role-based access control
-   **Caching**: API responses can be cached for better performance
-   **Pagination**: Consider adding pagination for large teams
-   **Soft Deletes**: Can be added if needed for audit trails

## 🐛 Troubleshooting

**Issue**: Images not displaying

```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

**Issue**: Migration fails

```bash
# Check for existing migrations
php artisan migrate:status

# Refresh migrations (dev only!)
php artisan migrate:fresh --seed
```

**Issue**: Widget not showing

```bash
# Clear cache
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

## 🔄 Future Enhancements

-   [ ] Add team departments/categories
-   [ ] Implement social media links
-   [ ] Add skills/expertise tags
-   [ ] Enable bio/description field
-   [ ] Add email contact field
-   [ ] Implement soft deletes
-   [ ] Add API pagination
-   [ ] Cache API responses
-   [ ] Add export functionality (CSV/PDF)
-   [ ] Implement advanced search filters

## 📞 Support

For issues or questions:

1. Check this documentation
2. Review test files for usage examples
3. Consult Laravel/Filament official docs
4. Check application logs: `storage/logs/laravel.log`

---

**Version**: 1.0.0  
**Last Updated**: December 21, 2025  
**Laravel Version**: 10.x  
**Filament Version**: 3.x
