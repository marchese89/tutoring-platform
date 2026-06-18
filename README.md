# Lezioni Informatica - Laravel Project

Laravel application for managing students, lessons, exercises, custom lesson
requests, payments, invoices, and teacher-student support workflows.

The project is currently maintained as a technical portfolio project.

## Overview

The application supports two roles:

* Admin
  * Manages topic areas, subjects, courses, lessons, and exercises.
  * Reviews student requests and uploads custom solutions.
  * Monitors sales, orders, invoices, and support chats.

* Student
  * Purchases lessons, exercises, and custom lesson requests.
  * Accesses purchased content through protected routes.
  * Sends support messages and manages account settings.

## Main Features

* Custom authentication flow with admin and student roles.
* Course, lesson, and exercise management.
* Cart checkout and extra-payment flows through Stripe.
* Server-side payment verification before order fulfillment.
* Invoice generation and email delivery.
* Protected private file access for paid content and student documents.
* Support chat with authorized message writes and private broadcast channels.
* Italian and English localization.
* Automated feature coverage for payments, files, invoices, chat, routes,
  authorization, localization, factories, and main page flows.

## Tech Stack

* Backend: Laravel 12
* PHP: 8.2 or newer
* Database: MySQL or MariaDB for the application
* Test database: SQLite in memory
* Frontend: Blade, Bootstrap, JavaScript
* Payments: Stripe
* Realtime: Laravel Reverb

## Requirements

Required PHP extensions:

* bcmath
* ctype
* curl
* dom
* fileinfo
* gd
* mbstring
* openssl
* pdo_mysql
* pdo_sqlite
* sqlite3
* tokenizer
* xml
* zip

Notes:

* MySQL or MariaDB is used by the application through the `.env` database
  settings.
* Automated tests use SQLite in memory through `phpunit.xml`.
* The `intl` extension is not required by the current setup.
* On Windows, enable the required extensions in the active `php.ini`. For
  example, remove the leading semicolon from `extension=mbstring`. Use
  `php --ini` to locate the loaded configuration file and `php -m` to verify
  the enabled extensions.

## Installation

Clone the repository:

```bash
git clone https://github.com/marchese89/lezioni-informatica-laravel.git
cd lezioni-informatica-laravel
```

Install dependencies:

```bash
composer install
```

Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

Configure the database and service credentials in `.env`, then run:

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

The seed command creates a complete demo catalog, orders, invoices, lesson
requests, reviews, and chats.

### Demo Accounts

The default credentials come from `.env` and may be changed before seeding:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Student | `student@example.com` | `password` |
| Student | `student2@example.com` | `password` |

The corresponding variables are `SEED_ADMIN_*`, `SEED_STUDENT_*`, and
`SEED_STUDENT_2_*`.

## Testing

The test suite uses SQLite in memory and does not require a MySQL testing
database.

```bash
php artisan test
```

Run the complete local verification sequence before opening a pull request:

```bash
composer validate --strict
vendor/bin/pint --test
php artisan test
composer audit --locked --no-interaction
```

The same commands run automatically through GitHub Actions.

## Realtime Chat

Realtime chat uses Laravel Reverb. Set `BROADCAST_DRIVER=reverb` in `.env`,
keep the `REVERB_*` application and server values aligned, then start the
WebSocket server:

```bash
php artisan reverb:start
```

The example environment uses port `8080`. With the default
`BROADCAST_DRIVER=log`, the application remains usable without a WebSocket
server, but messages will not update in real time.

## Payments

Stripe keys must be configured in `.env`.

Payment completion is verified server-side before creating orders, invoices, or
granting access to purchased content.

## Project Status

The core application is functional and is being refactored toward a cleaner,
portfolio-ready codebase.

Completed refactoring areas include:

* payment verification and HTTP method safety
* authorization policies for protected content and chat
* private upload handling and validation
* invoice-number sequencing
* reusable Blade components for repeated UI patterns
* Italian and English localization
* automated tests and model factories
