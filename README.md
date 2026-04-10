# Final Project - PBKK B

## What Is This Project?

This project is a web-based clinic/hospital management system built with Laravel 11, Vue 3, Inertia.js, and Tailwind CSS.

It provides a dashboard-driven interface and REST API for managing core healthcare operational data such as doctors, patients, appointments, treatments, and billing-related records.

## Features

- Authentication and user session handling using Laravel Jetstream and Sanctum
- Dashboard UI built with Vue 3 + Inertia.js
- CRUD management for:
  - Doctors
  - Patients
  - Appointments
  - Treatments
  - Rooms
  - Medications
  - Prescriptions
  - Invoices
  - Payments
- REST API endpoints for all core modules
- OpenAPI/Swagger annotations and documentation page integration
- Basic user listing with search and pagination
- Database migrations and factories for development/testing workflows

## Tech Stack

- Backend: Laravel 11 (PHP 8.2+)
- Frontend: Vue 3, Inertia.js, Tailwind CSS, Vite
- API/Auth: Laravel Sanctum
- API Docs: L5 Swagger
- Authorization support package: Spatie Laravel Permission

## Project Structure (Main Areas)

- `app/Models` - domain models (Doctor, Patient, Appointment, Invoice, etc.)
- `app/Http/Controllers` - web and API controllers
- `routes/web.php` - web routes and Inertia pages
- `routes/api.php` - REST API routes and Swagger annotations
- `resources/js/Pages` - Vue page components
- `database/migrations` - schema migrations

## Getting Started

### Prerequisites

- PHP 8.2 or newer
- Composer
- Node.js and npm
- A database (MySQL/PostgreSQL/SQLite)

### Installation

1. Clone the repository.
2. Install backend dependencies:

	```bash
	composer install
	```

3. Install frontend dependencies:

	```bash
	npm install
	```

4. Create environment file and app key:

	```bash
	copy .env.example .env
	php artisan key:generate
	```

5. Configure database values in `.env`.

6. Run migrations and seeders (if available):

	```bash
	php artisan migrate --seed
	```

### Run the Application

Use two terminals:

```bash
php artisan serve
```

```bash
npm run dev
```

Open the app in your browser at the URL shown by Laravel (commonly `http://127.0.0.1:8000`).

## API Documentation

- API routes are defined in `routes/api.php`.
- Swagger UI is available from the web route `/swagger`.

## Testing

Run tests with:

```bash
php artisan test
```

---

Built as a final project for PBKK B.
