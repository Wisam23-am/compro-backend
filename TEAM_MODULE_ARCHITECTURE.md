# Team Module - Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         TEAM MODULE ARCHITECTURE                         │
└─────────────────────────────────────────────────────────────────────────┘

┌───────────────────────────────────────────────────────────────────────────────┐
│                              PRESENTATION LAYER                                │
├───────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌──────────────────────────┐       ┌─────────────────────────────────┐     │
│  │   FILAMENT ADMIN PANEL   │       │      PUBLIC API ENDPOINTS       │     │
│  │  /admin/teams            │       │  /api/team                      │     │
│  ├──────────────────────────┤       ├─────────────────────────────────┤     │
│  │ • List Teams (Table)     │       │ • GET /api/team                 │     │
│  │ • Create Team (Form)     │       │ • GET /api/team/{id}            │     │
│  │ • Edit Team (Form)       │       │ • GET /api/team/stats/overview  │     │
│  │ • View Team (Details)    │       └─────────────────────────────────┘     │
│  │ • Bulk Actions           │                                                 │
│  │ • Search & Filter        │       ┌─────────────────────────────────┐     │
│  │ • Reorder Rows           │       │     DASHBOARD WIDGETS           │     │
│  └──────────────────────────┘       ├─────────────────────────────────┤     │
│                                      │ • TeamStatsWidget               │     │
│                                      │   - Total count                 │     │
│                                      │   - Active/Inactive             │     │
│                                      │   - Growth chart                │     │
│                                      │                                 │     │
│                                      │ • LatestTeamWidget              │     │
│                                      │   - 5 Recent members            │     │
│                                      │   - Quick actions               │     │
│                                      └─────────────────────────────────┘     │
└───────────────────────────────────────────────────────────────────────────────┘
                                         │
                                         ▼
┌───────────────────────────────────────────────────────────────────────────────┐
│                              APPLICATION LAYER                                 │
├───────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌───────────────────────┐                ┌──────────────────────────┐       │
│  │   TeamResource.php    │                │  TeamController.php       │       │
│  ├───────────────────────┤                │  (API)                    │       │
│  │ • Form Schema         │                ├──────────────────────────┤       │
│  │ • Table Configuration │                │ • index()                 │       │
│  │ • Actions             │                │ • show($id)               │       │
│  │ • Bulk Actions        │                │ • stats()                 │       │
│  │ • Filters             │                └──────────────────────────┘       │
│  └───────────────────────┘                                                    │
│           │                                           │                        │
│           ├────────────┬──────────────┬───────────────┤                       │
│           ▼            ▼              ▼               ▼                        │
│    ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌────────────┐                │
│    │ListTeams │  │CreateTeam│  │EditTeam  │  │ViewTeam    │                │
│    │   .php   │  │   .php   │  │  .php    │  │  .php      │                │
│    └──────────┘  └──────────┘  └──────────┘  └────────────┘                │
│                                                                                │
│  ┌─────────────────────────────────────────────────────────────────┐         │
│  │                       AUTHORIZATION                              │         │
│  │                      TeamPolicy.php                              │         │
│  ├─────────────────────────────────────────────────────────────────┤         │
│  │ • viewAny()  • view()  • create()  • update()  • delete()       │         │
│  └─────────────────────────────────────────────────────────────────┘         │
└───────────────────────────────────────────────────────────────────────────────┘
                                         │
                                         ▼
┌───────────────────────────────────────────────────────────────────────────────┐
│                               BUSINESS LOGIC LAYER                             │
├───────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────┐        │
│  │                         Team.php (Model)                          │        │
│  ├──────────────────────────────────────────────────────────────────┤        │
│  │  Properties:                                                      │        │
│  │  • id, name, position, location, image                           │        │
│  │  • is_active, sort_order, timestamps                             │        │
│  │                                                                   │        │
│  │  Scopes:                                                          │        │
│  │  • active()    - WHERE is_active = true                          │        │
│  │  • ordered()   - ORDER BY sort_order ASC                         │        │
│  │                                                                   │        │
│  │  Accessors:                                                       │        │
│  │  • image_url   - Full URL to image                               │        │
│  │                                                                   │        │
│  │  Casts:                                                           │        │
│  │  • is_active   → boolean                                         │        │
│  │  • sort_order  → integer                                         │        │
│  └──────────────────────────────────────────────────────────────────┘        │
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────┐        │
│  │                    Cache Management                               │        │
│  │                (AppServiceProvider.php)                           │        │
│  ├──────────────────────────────────────────────────────────────────┤        │
│  │  Events:                                                          │        │
│  │  • Team::saved()   → Clear cache                                 │        │
│  │  • Team::deleted() → Clear cache                                 │        │
│  └──────────────────────────────────────────────────────────────────┘        │
└───────────────────────────────────────────────────────────────────────────────┘
                                         │
                                         ▼
┌───────────────────────────────────────────────────────────────────────────────┐
│                              DATA PERSISTENCE LAYER                            │
├───────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────┐        │
│  │                      Database: teams table                        │        │
│  ├──────────────────────────────────────────────────────────────────┤        │
│  │  Columns:                                                         │        │
│  │  • id (PK)           • name (UNIQUE)      • position             │        │
│  │  • location          • image              • is_active (bool)     │        │
│  │  • sort_order (int)  • created_at         • updated_at           │        │
│  │                                                                   │        │
│  │  Indexes:                                                         │        │
│  │  • PRIMARY KEY (id)                                              │        │
│  │  • INDEX (is_active, sort_order)  ← Performance optimization    │        │
│  └──────────────────────────────────────────────────────────────────┘        │
│                                                                                │
│  ┌────────────────────────┐      ┌─────────────────────────────────┐        │
│  │   TeamFactory.php      │      │      TeamSeeder.php             │        │
│  ├────────────────────────┤      ├─────────────────────────────────┤        │
│  │ • Default state        │      │ • 11 sample members             │        │
│  │ • active()             │      │ • Various positions             │        │
│  │ • inactive()           │      │ • Active & inactive samples     │        │
│  │ • executive()          │      │ • Realistic locations           │        │
│  │ • developer()          │      │ • Sequential sort_order         │        │
│  │ • position()           │      └─────────────────────────────────┘        │
│  │ • sortOrder()          │                                                  │
│  └────────────────────────┘                                                  │
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────┐        │
│  │              File Storage: storage/app/public/team-members/       │        │
│  │              (Symlinked to public/storage/team-members/)          │        │
│  └──────────────────────────────────────────────────────────────────┘        │
└───────────────────────────────────────────────────────────────────────────────┘
                                         │
                                         ▼
┌───────────────────────────────────────────────────────────────────────────────┐
│                                  TESTING LAYER                                 │
├───────────────────────────────────────────────────────────────────────────────┤
│                                                                                │
│  ┌──────────────────────────────────────────────────────────────────┐        │
│  │                    TeamApiTest.php (Pest)                         │        │
│  ├──────────────────────────────────────────────────────────────────┤        │
│  │  13 Test Cases:                                                   │        │
│  │                                                                   │        │
│  │  GET /api/team:                                                   │        │
│  │  ✓ Returns active members only                                   │        │
│  │  ✓ Returns members ordered by sort_order                         │        │
│  │  ✓ Returns empty array when no active members                    │        │
│  │  ✓ Includes image_url in response                                │        │
│  │                                                                   │        │
│  │  GET /api/team/{id}:                                              │        │
│  │  ✓ Returns specific active member                                │        │
│  │  ✓ Returns 404 for inactive member                               │        │
│  │  ✓ Returns 404 for non-existent member                           │        │
│  │  ✓ Includes timestamps in response                               │        │
│  │                                                                   │        │
│  │  GET /api/team/stats/overview:                                    │        │
│  │  ✓ Returns correct statistics                                    │        │
│  │  ✓ Handles zero members correctly                                │        │
│  │  ✓ Calculates percentage with precision                          │        │
│  │                                                                   │        │
│  │  Response Format:                                                 │        │
│  │  ✓ Always includes success & message                             │        │
│  │  ✓ Includes proper error structure                               │        │
│  └──────────────────────────────────────────────────────────────────┘        │
└───────────────────────────────────────────────────────────────────────────────┘


┌───────────────────────────────────────────────────────────────────────────────┐
│                              DATA FLOW DIAGRAM                                 │
└───────────────────────────────────────────────────────────────────────────────┘

USER REQUEST                                                      RESPONSE
    │                                                                 ▲
    │ Admin: /admin/teams                                            │
    ├────────────────────┐                                           │
    │                    │                                           │
    ▼                    ▼                                           │
┌─────────┐      ┌──────────────┐                           ┌──────────────┐
│Filament │      │ TeamResource │──────────────────────────▶│  View/Form   │
│  Panel  │      │    .php      │                           │   Rendered   │
└─────────┘      └──────────────┘                           └──────────────┘
                        │
                        ▼
                ┌──────────────┐        ┌──────────────┐
                │  TeamPolicy  │───────▶│ Authorized?  │
                │    .php      │        └──────────────┘
                └──────────────┘               │
                        │                      │
                        ▼                      ▼
                ┌──────────────┐        ┌──────────────┐
                │  Team Model  │◀───────│   Database   │
                │    .php      │        │    teams     │
                └──────────────┘        └──────────────┘
                        │
                        │ active()->ordered()
                        ▼
                ┌──────────────┐
                │  Collection  │
                │  of Members  │
                └──────────────┘

API REQUEST
    │
    │ GET /api/team
    ▼
┌────────────────┐       ┌──────────────┐       ┌──────────────┐
│ TeamController │──────▶│  Team Model  │──────▶│   Database   │
│     .php       │       │    .php      │       │    teams     │
└────────────────┘       └──────────────┘       └──────────────┘
        │                        │
        │ Only Active &          │ WHERE is_active = true
        │ Ordered                │ ORDER BY sort_order
        │                        │
        ▼                        ▼
┌────────────────┐       ┌──────────────┐
│ JSON Response  │◀──────│  Collection  │
│   Formatted    │       │  Transformed │
└────────────────┘       └──────────────┘


┌───────────────────────────────────────────────────────────────────────────────┐
│                           FILE ORGANIZATION                                    │
└───────────────────────────────────────────────────────────────────────────────┘

compro-backend/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── TeamResource.php          ← Main resource configuration
│   │   │   └── TeamResource/
│   │   │       └── Pages/
│   │   │           ├── ListTeams.php     ← List page
│   │   │           ├── CreateTeam.php    ← Create page
│   │   │           ├── EditTeam.php      ← Edit page
│   │   │           └── ViewTeam.php      ← View page
│   │   └── Widgets/
│   │       ├── TeamStatsWidget.php       ← Statistics widget
│   │       └── LatestTeamWidget.php      ← Recent members widget
│   │
│   ├── Http/Controllers/Api/
│   │   └── TeamController.php            ← API controller
│   │
│   ├── Models/
│   │   └── Team.php                      ← Team model
│   │
│   ├── Policies/
│   │   └── TeamPolicy.php                ← Authorization
│   │
│   └── Providers/
│       └── AppServiceProvider.php        ← Policy & cache (modified)
│
├── database/
│   ├── factories/
│   │   └── TeamFactory.php               ← Test data factory
│   │
│   ├── migrations/
│   │   ├── 2024_12_20_000002_create_teams_table.php
│   │   └── 2025_12_21_191851_update_teams_table_structure.php
│   │
│   └── seeders/
│       └── TeamSeeder.php                ← Sample data
│
├── routes/
│   └── api.php                           ← API routes (modified)
│
├── storage/app/public/
│   └── team-members/                     ← Image uploads
│
├── tests/Feature/
│   └── TeamApiTest.php                   ← API tests
│
└── Documentation/
    ├── TEAM_MODULE_DOCUMENTATION.md      ← Full docs
    ├── TEAM_QUICK_REFERENCE.md           ← Quick guide
    ├── TEAM_MODULE_SUMMARY.md            ← Summary
    └── TEAM_MODULE_CHECKLIST.md          ← Verification


┌───────────────────────────────────────────────────────────────────────────────┐
│                              KEY FEATURES MAP                                  │
└───────────────────────────────────────────────────────────────────────────────┘

CRUD Operations          Search & Filter         Sorting & Ordering
    ├─ Create   ✓           ├─ Name       ✓          ├─ Manual Drag   ✓
    ├─ Read     ✓           ├─ Position   ✓          ├─ Sort Order    ✓
    ├─ Update   ✓           └─ Status     ✓          └─ Auto-Sequence ✓
    └─ Delete   ✓

Bulk Actions            Media Management        Status Management
    ├─ Activate   ✓         ├─ Upload     ✓          ├─ Toggle      ✓
    ├─ Deactivate ✓         ├─ Preview    ✓          ├─ Badge       ✓
    └─ Delete     ✓         └─ Editor     ✓          └─ Color Coded ✓

API Endpoints           Dashboard Widgets       Security
    ├─ List      ✓          ├─ Statistics ✓          ├─ Policy      ✓
    ├─ Show      ✓          └─ Latest     ✓          ├─ Validation  ✓
    └─ Stats     ✓                                    └─ Auth        ✓


┌───────────────────────────────────────────────────────────────────────────────┐
│                           TECHNOLOGY STACK                                     │
└───────────────────────────────────────────────────────────────────────────────┘

Backend Framework:     Laravel 10.x
Admin Panel:          Filament 3.x
Database:             MySQL (with migrations)
Testing:              Pest PHP
API:                  RESTful JSON
Authentication:       Laravel Sanctum
File Storage:         Laravel Storage (public disk)
Caching:              Laravel Cache
Validation:           Laravel Form Requests
Authorization:        Laravel Policies


┌───────────────────────────────────────────────────────────────────────────────┐
│                        IMPLEMENTATION STATUS: ✅ COMPLETE                      │
└───────────────────────────────────────────────────────────────────────────────┘
```
