# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a clinic management system built with Laravel 12, designed for managing patients, appointments, invoices, and expenses. The application uses a multi-tenant approach where professionals can manage their own patients, while admins have full access.

## Development Commands

### Initial Setup
```bash
composer setup
# This runs: composer install, creates .env, generates key, migrates database, and builds frontend assets
```

### Development Server
```bash
composer dev
# Starts 4 concurrent services: PHP server, queue worker, log viewer (pail), and Vite dev server
# Or run individual commands:
php artisan serve                    # Development server (localhost:8000)
php artisan queue:listen --tries=1   # Queue worker
php artisan pail --timeout=0         # Real-time log viewer
npm run dev                          # Vite dev server for assets
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh database with seed data
php artisan db:seed              # Run seeders only
```

### Testing & Code Quality
```bash
composer test              # Run all tests (clears config cache first)
php artisan test           # Run PHPUnit tests
php artisan test --filter=UserTest  # Run specific test class
vendor/bin/pint            # Format code with Laravel Pint
```

### Frontend
```bash
npm install        # Install dependencies
npm run dev        # Development server with hot reload
npm run build      # Production build
```

### Common Artisan Commands
```bash
php artisan tinker                    # Interactive REPL
php artisan route:list                # List all routes
php artisan make:controller FooController  # Create controller
php artisan make:model Foo -m         # Create model with migration
php artisan make:migration create_foos_table  # Create migration
php artisan config:clear              # Clear config cache
php artisan cache:clear               # Clear application cache
```

## Architecture

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS 4, Vite
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Authentication**: Laravel UI with custom auth controllers
- **Permissions**: Spatie Laravel Permission package
- **Queue**: Database driver (can be changed to Redis)
- **Cache**: Database driver

### Domain Models & Relationships

**User** (professionals/admins)
- Has many patients (as professional)
- Has many appointments (as professional)
- Uses soft deletes
- Roles: admin, professional (via Spatie permissions)
- Additional fields: crp, specialties (JSON), address, phone

**Patient**
- Belongs to professional (User)
- Has many appointments
- Has many invoices
- Uses soft deletes
- Has unique code for prontuário

**Appointment**
- Belongs to patient
- Belongs to professional (User)
- Has status tracking (scheduled, completed, cancelled, etc.)
- Stores start/end time, notes, type

**Invoice**
- Belongs to patient
- Has many payments
- Tracks amounts, status (pending, paid, cancelled)

**Expense**
- Standalone expense tracking
- Categorized by type
- Linked to professional

**Payment**
- Belongs to invoice
- Tracks payment method, amount, date

### Directory Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Admin/          # Admin-only controllers (DashboardController, UserController)
│       ├── Auth/           # Laravel UI auth controllers
│       └── Controller.php
├── Models/                 # Eloquent models (all use SoftDeletes except User)
└── Providers/

routes/
├── web.php                 # All web routes (auth + protected routes)
└── console.php             # Artisan commands

resources/
├── views/
│   ├── auth/              # Login, register views
│   ├── admin/             # Admin dashboard and user management
│   ├── patients/          # Patient management
│   ├── layouts/           # Blade layouts
│   └── profile/           # User profile
├── js/
└── css/

database/
├── migrations/            # Schema definitions
├── seeders/               # Database seeders
└── factories/             # Model factories for testing
```

### Key Patterns & Conventions

**Route Organization**: All routes are in `routes/web.php` with prefix/name grouping. Many routes currently use closures; these should be moved to controllers as the app grows.

**Authorization**: Route-based middleware (`auth`) for authentication. Role-based checks use `auth()->user()->isAdmin()` and `auth()->user()->isProfessional()` methods defined in User model.

**Data Scoping**: Non-admin users only see their own patients/appointments via query scopes in routes/controllers.

**Soft Deletes**: All models except User use SoftDeletes trait. User has a separate trash system.

**Database**: Uses SQLite by default. The `composer setup` command creates the database file automatically. For MySQL/PostgreSQL, update .env file.

**Frontend**: Tailwind CSS 4 with Vite. Blade templates use Laravel UI scaffolding. Views are organized by domain (patients, admin, etc.).

**Testing**: PHPUnit configured with in-memory SQLite for fast tests. Test environment uses array drivers for cache/session.

## Important Notes

- The `composer dev` script requires `npx concurrently` which is installed via npm
- Queue and cache both use database driver - consider Redis for production
- The app uses database sessions - run `php artisan migrate` to create required tables
- Spatie permissions package requires `vendor:publish` for migrations (already included in migrations/)
- User model has custom attributes: `crp` (professional registration), `specialties` (JSON array)
- Patient `code` field is used for prontuário identification
- Routes use both controllers (Auth, Admin) and closures (patients, appointments, financial) - migrate closures to controllers as features mature
