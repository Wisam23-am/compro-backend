# Team Module - Quick Reference Guide

## 🚀 Quick Start

### Installation (3 commands)

```bash
cd compro-backend
php artisan migrate
php artisan db:seed --class=TeamSeeder
php artisan storage:link
```

## 📍 Key URLs

| Resource   | URL                        | Description               |
| ---------- | -------------------------- | ------------------------- |
| Admin List | `/admin/teams`             | Manage team members       |
| API List   | `/api/team`                | Get active members (JSON) |
| API Show   | `/api/team/{id}`           | Get member details        |
| API Stats  | `/api/team/stats/overview` | Team statistics           |

## 💻 Common Code Snippets

### Get Active Team Members

```php
use App\Models\Team;

// Basic query
$team = Team::active()->ordered()->get();

// With pagination
$team = Team::active()->ordered()->paginate(12);

// Count active
$activeCount = Team::active()->count();
```

### Create New Member

```php
Team::create([
    'name' => 'John Doe',
    'position' => 'Senior Developer',
    'location' => 'New York, NY',
    'image' => 'team-members/john-doe.jpg',
    'is_active' => true,
    'sort_order' => 10,
]);
```

### Update Member

```php
$member = Team::find(1);
$member->update([
    'is_active' => false,
    'sort_order' => 99,
]);
```

### Factory Usage

```php
// Create random member
Team::factory()->create();

// Create active executive
Team::factory()->executive()->active()->create();

// Create 10 developers
Team::factory()->developer()->count(10)->create();

// Specific sort order
Team::factory()->sortOrder(5)->create();
```

## 🔌 API Examples

### cURL

```bash
# List all active members
curl http://localhost/api/team

# Get specific member
curl http://localhost/api/team/1

# Get statistics
curl http://localhost/api/team/stats/overview
```

### JavaScript (Fetch)

```javascript
// Get all team members
fetch("/api/team")
    .then((res) => res.json())
    .then((data) => {
        console.log(data.data); // Array of members
        console.log(data.count); // Total count
    });

// Get specific member
fetch("/api/team/1")
    .then((res) => res.json())
    .then((data) => console.log(data.data));
```

### Axios

```javascript
// Get team members
axios.get("/api/team").then((response) => {
    const members = response.data.data;
    console.log(members);
});
```

### PHP (Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('http://your-domain.com/api/team');
$team = json_decode($response->getBody(), true);
```

## 🧪 Testing Commands

```bash
# Run all team tests
php artisan test --filter=TeamApi

# Run specific test
php artisan test --filter="returns active team members only"

# With coverage
php artisan test --coverage --filter=TeamApi
```

## 🎨 Blade Template Examples

### Display Team Members

```blade
@php
    $team = \App\Models\Team::active()->ordered()->get();
@endphp

<div class="team-grid">
    @foreach($team as $member)
        <div class="team-card">
            <img src="{{ $member->image_url }}" alt="{{ $member->name }}">
            <h3>{{ $member->name }}</h3>
            <p>{{ $member->position }}</p>
            @if($member->location)
                <span>{{ $member->location }}</span>
            @endif
        </div>
    @endforeach
</div>
```

### With Vue.js

```vue
<template>
    <div class="team-section">
        <div v-for="member in team" :key="member.id" class="member-card">
            <img :src="member.image" :alt="member.name" />
            <h3>{{ member.name }}</h3>
            <p>{{ member.position }}</p>
            <span v-if="member.location">{{ member.location }}</span>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            team: [],
        };
    },
    mounted() {
        fetch("/api/team")
            .then((res) => res.json())
            .then((data) => {
                this.team = data.data;
            });
    },
};
</script>
```

## 📊 Database Queries

### Useful Queries

```sql
-- Get all active members ordered
SELECT * FROM teams WHERE is_active = 1 ORDER BY sort_order ASC;

-- Count by status
SELECT is_active, COUNT(*) as count
FROM teams
GROUP BY is_active;

-- Members added this month
SELECT * FROM teams
WHERE created_at >= DATE_FORMAT(NOW(), '%Y-%m-01');

-- Update sort order sequentially
SET @row_number = 0;
UPDATE teams
SET sort_order = (@row_number:=@row_number + 1)
ORDER BY sort_order, created_at;
```

## 🔧 Artisan Commands

### Useful Commands

```bash
# Create new migration
php artisan make:migration add_field_to_teams_table

# Seed only team data
php artisan db:seed --class=TeamSeeder

# Refresh with seed
php artisan migrate:refresh --seed

# Generate factory
php artisan make:factory TeamFactory --model=Team

# Create test
php artisan make:test TeamTest --pest

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🎯 Filament Customizations

### Add Custom Action

```php
// In TeamResource table()
Tables\Actions\Action::make('promote')
    ->label('Promote')
    ->icon('heroicon-o-arrow-up')
    ->action(function (Team $record) {
        $record->update(['sort_order' => $record->sort_order - 1]);
    })
```

### Add Custom Filter

```php
// In TeamResource filters()
Tables\Filters\SelectFilter::make('location')
    ->options(
        Team::pluck('location', 'location')->unique()
    )
```

### Add Custom Column

```php
// In TeamResource columns()
Tables\Columns\TextColumn::make('member_since')
    ->getStateUsing(fn (Team $record) => $record->created_at->diffForHumans())
    ->label('Member Since')
```

## 🐛 Quick Fixes

### Storage Link Not Working

```bash
# Recreate storage link
rm public/storage
php artisan storage:link
```

### Images Not Showing

```bash
# Fix permissions
chmod -R 775 storage
chown -R www-data:www-data storage
```

### Migration Issues

```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Fresh start (dev only!)
php artisan migrate:fresh --seed
```

## 📦 Model Attributes

### Available Attributes

```php
$member->id              // Unique ID
$member->name            // Full name
$member->position        // Job title
$member->location        // City/region
$member->image           // Image path
$member->is_active       // Boolean status
$member->sort_order      // Display order
$member->created_at      // Created timestamp
$member->updated_at      // Updated timestamp
$member->image_url       // Full image URL (accessor)
```

### Available Scopes

```php
Team::active()           // Only active members
Team::ordered()          // Sorted by sort_order
```

## 🎨 Response Structures

### List Response

```json
{
  "success": true,
  "message": "Team members retrieved successfully",
  "data": [...],
  "count": 10
}
```

### Show Response

```json
{
  "success": true,
  "message": "Team member retrieved successfully",
  "data": { member_object }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error"
}
```

## 📞 Need Help?

1. **Full Documentation**: `TEAM_MODULE_DOCUMENTATION.md`
2. **Tests**: `tests/Feature/TeamApiTest.php`
3. **Examples**: `database/seeders/TeamSeeder.php`
4. **Logs**: `storage/logs/laravel.log`

---

**Quick Tip**: Bookmark this page for fast reference! 🚀
