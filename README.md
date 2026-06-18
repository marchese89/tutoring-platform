# Tutoring Platform

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

## Docker Demo Installation

Docker is the recommended way to run the complete demo environment. It starts
the Laravel application, MySQL, and Laravel Reverb:

```bash
docker compose up --build -d
```

On the first start, the application generates and persists an application key,
runs the database migrations, and seeds the demo data when the users table is
empty.

Open:

* Application: `http://localhost:8000`
* Reverb WebSocket server: `http://localhost:8080`

The default demo accounts are listed below. To inspect the services or run the
test suite inside the application container:

```bash
docker compose ps
docker compose logs -f app
docker compose run --rm test
```

Stop the services while preserving application and database data:

```bash
docker compose down
```

To remove all Docker-managed application and database data and start again from
an empty installation, use `docker compose down -v`.

The host ports may be changed with `APP_PORT` and `REVERB_PUBLIC_PORT`.
Automatic demo seeding may be disabled with `DOCKER_SEED_DATABASE=false`.

The demo database is separate from any existing local MySQL installation. The
first seeded installation uses these accounts:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@example.com` | `password` |
| Student | `student@example.com` | `password` |
| Student | `student2@example.com` | `password` |

These credentials are for local demonstration only.

## Production Docker Deployment

`compose.production.yaml` is a separate production-oriented configuration. It:

* builds the image without development and testing dependencies;
* does not include a database container, so it can use a managed MySQL service;
* requires the application, database, and Reverb secrets at deployment time;
* disables demo seeding and automatic migrations;
* runs with `APP_ENV=production`, `APP_DEBUG=false`, and stderr logging.

Create the deployment environment file:

```bash
cp .env.production.example .env.production
```

Generate an application key and set it as `APP_KEY`:

```bash
docker run --rm php:8.3-cli php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Fill in the database, Reverb, mail, and payment values. Never commit
`.env.production`. Build the image and run the database migrations explicitly:

```bash
docker compose --env-file .env.production -f compose.production.yaml build
docker compose --env-file .env.production -f compose.production.yaml run --rm app php artisan migrate --force
docker compose --env-file .env.production -f compose.production.yaml up -d
```

Production does not create demo users. The first administrator must be created
through an explicit deployment procedure or directly in the database with a
securely hashed password.

GitHub Actions validates both Docker variants. Pushes to `master`, version tags,
and manual workflow runs publish the production image to GitHub Container
Registry as `ghcr.io/<owner>/<repository>`. Set `APP_IMAGE` to that image in
`.env.production` when deploying a prebuilt release.

The production Compose file is a portable single-host deployment baseline. A
specific cloud platform may replace its volume, ingress, secrets, database, and
deployment commands with managed equivalents.

## Local Installation

Clone the repository:

```bash
git clone https://github.com/marchese89/tutoring-platform.git
cd tutoring-platform
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

### Local Seed Accounts

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
