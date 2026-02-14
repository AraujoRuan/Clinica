# GitHub Copilot Instructions

## Project Context

This is a **Clinic Management System** built with Laravel 12, designed for managing patients, appointments, invoices, and expenses. The application uses a multi-tenant approach where professionals can manage their own patients, while admins have full access to all system data.

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS 4, Vite
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Authentication**: Laravel UI with custom auth controllers
- **Permissions**: Spatie Laravel Permission package
- **Queue**: Database driver
- **Cache**: Database driver

## Code Conventions

### Naming Standards
- **Controllers**: PascalCase with `Controller` suffix (e.g., `PatientController`, `AppointmentController`)
- **Models**: PascalCase, singular (e.g., `Patient`, `Appointment`, `User`)
- **Routes**: kebab-case with named routes using dot notation (e.g., `patients.index`, `appointments.create`)
- **Database Tables**: snake_case, plural (e.g., `patients`, `appointments`, `users`)
- **Migrations**: timestamp prefix with descriptive name (e.g., `2026_01_14_083540_create_patients_tables.php`)

### Architecture Patterns

**Route Organization**: 
- All routes are in `routes/web.php` with prefix/name grouping
- Migrate closures to controllers as features mature
- Use controller methods instead of inline closures for better maintainability

**Authorization**:
- Route-based middleware (`auth`) for authentication
- Role-based checks use `auth()->user()->isAdmin()` and `auth()->user()->isProfessional()` methods
- Non-admin users only see their own patients/appointments via query scopes

**Soft Deletes**:
- All models except User use `SoftDeletes` trait
- User has a separate trash system
- Always use `withTrashed()` when intentionally retrieving deleted records

**Data Relationships**:
- User → hasMany → Patient, Appointment
- Patient → belongsTo → User (as professional)
- Patient → hasMany → Appointment, Invoice
- Invoice → hasMany → Payment
- Appointment → belongsTo → Patient, User (as professional)

## Domain Models

### User Model
- Represents professionals and admins
- Custom attributes: `crp` (professional registration), `specialties` (JSON array), `address`, `phone`
- Roles: `admin`, `professional` (via Spatie permissions)
- Helper methods: `isAdmin()`, `isProfessional()`

### Patient Model
- Linked to a professional (User)
- Has unique `code` field for prontuário identification
- Uses soft deletes
- Related to appointments and invoices

### Appointment Model
- Tracks patient sessions with professionals
- Status options: scheduled, completed, cancelled, etc.
- Stores start/end time, notes, appointment type

### Invoice Model
- Billing records for patients
- Has multiple payments
- Status: pending, paid, cancelled

### Expense Model
- Standalone expense tracking
- Categorized by type
- Linked to professional

## Development Guidelines

### When Creating Controllers:
- Extend `App\Http\Controllers\Controller`
- Use resource controllers when possible (`php artisan make:controller FooController --resource`)
- Apply authorization checks at the beginning of methods
- Scope queries by professional for non-admin users
- Return views with descriptive variable names

### When Creating Models:
- Use model factories for testing (`php artisan make:model Foo -mf`)
- Define relationships explicitly
- Add fillable/guarded properties
- Include casts for JSON, dates, and booleans
- Add soft deletes when appropriate

### When Creating Migrations:
- Use descriptive names
- Add indexes for foreign keys and frequently queried columns
- Use proper foreign key constraints with cascade options
- Include timestamps unless there's a specific reason not to

### When Creating Views:
- Extend `layouts.app` for authenticated pages
- Use Blade components for reusable elements
- Organize views by domain (patients/, appointments/, admin/)
- Use Tailwind CSS 4 utility classes
- Include CSRF tokens for all forms

### When Writing Routes:
- Group related routes with middleware
- Use named routes consistently
- Prefer controller actions over closures for complex logic
- Apply appropriate middleware (`auth`, `role`)

### When Writing Tests:
- Use in-memory SQLite for speed
- Create factories for all models
- Test authorization rules
- Test scoping (professionals only see their data)
- Clear cache before tests

## Command Reference

### Development
```bash
composer dev              # Start all services (server, queue, pail, vite)
php artisan serve         # Development server only
php artisan pail          # Real-time log viewer
npm run dev               # Vite dev server
```

### Database
```bash
php artisan migrate              # Run migrations
php artisan migrate:fresh --seed # Fresh DB with test data
php artisan db:seed              # Run seeders
```

### Code Quality
```bash
composer test        # Run all tests
vendor/bin/pint      # Format code
php artisan test --filter=FooTest  # Run specific test
```

### Common Tasks
```bash
php artisan make:controller FooController
php artisan make:model Foo -m
php artisan make:migration create_foos_table
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

## Important Notes

- **Database**: Uses SQLite by default. Run `composer setup` to initialize.
- **Queue**: Uses database driver. Consider Redis for production.
- **Sessions**: Database-based. Requires migrations.
- **Permissions**: Spatie package already configured in migrations.
- **Multi-tenancy**: Non-admin users are scoped to their own data automatically.
- **Professional Fields**: User model has `crp` (registration number) and `specialties` (JSON).
- **Patient Code**: The `code` field is used for prontuário identification - ensure uniqueness.

## Avoid Common Pitfalls

- ❌ Don't forget to scope queries by professional for non-admin users
- ❌ Don't use closures for complex route logic - create controllers
- ❌ Don't forget CSRF tokens in forms
- ❌ Don't skip authorization checks in controllers
- ❌ Don't forget soft deletes when querying models
- ✅ Always test authorization rules
- ✅ Use named routes for maintainability
- ✅ Validate user input with Form Requests
- ✅ Use transactions for multi-step database operations
- ✅ Clear config cache after .env changes (`php artisan config:clear`)

## Example Code Patterns

### Scoped Query (Professional-only)
```php
$patients = auth()->user()->isAdmin()
    ? Patient::all()
    : Patient::where('user_id', auth()->id())->get();
```

### Authorization in Controller
```php
public function store(Request $request)
{
    if (!auth()->user()->isProfessional()) {
        abort(403, 'Unauthorized action.');
    }
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        // ...
    ]);
    
    $patient = Patient::create([
        'user_id' => auth()->id(),
        ...$validated,
    ]);
    
    return redirect()->route('patients.show', $patient);
}
```

### Blade View Structure
```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('Page Title') }}</h1>
    {{-- Content here --}}
</div>
@endsection
```

## Questions to Ask

When implementing features, consider:
- Should this be accessible to all professionals or admin-only?
- Does this need to be scoped by professional?
- Should this use soft deletes?
- Are there related models that need updating?
- What validation rules apply?
- Should this trigger a notification or queue job?
- Are there authorization checks needed?
