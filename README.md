# Adibayu Test Project

This project is built with Laravel and uses [Laravel Sail](https://laravel.com/docs/sail) for local development with Docker.

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running.
- (Optional) [Composer](https://getcomposer.org/) installed locally (only if you want to install dependencies without Docker).

## Getting Started

Follow these steps to set up the project locally using Docker:

### 1. Clone the repository

```bash
git clone <repository-url>
cd AdibayuTest
```

### 2. Initial Setup

Copy the example environment file:

```bash
cp .env.example .env
```

### 3. Install Dependencies

You can install the PHP dependencies using a small Docker container to avoid needing PHP installed locally:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

*(Note: Adjust the image name `php84-composer` if you need a different PHP version, though Sail usually handles this.)*

### 4. Configure Environment

Open the `.env` file and ensure the database settings match the Docker configuration in `compose.yaml`:

```env
DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=adibayutest
DB_USERNAME=sail
DB_PASSWORD=password

REDIS_HOST=redis
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
```

### 5. Start the Application

Start the Docker containers using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

### 6. Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

### 7. Run Migrations and Seeders

```bash
./vendor/bin/sail artisan migrate --seed
```

### 8. Frontend Setup

Install NPM dependencies and build the assets:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Or run the development server:

```bash
./vendor/bin/sail npm run dev
```

## Accessing the Application

- **Web Application:** [http://localhost](http://localhost)
- **Mailpit (Email Testing):** [http://localhost:8025](http://localhost:8025)

## Common Sail Commands

- Start Sail: `./vendor/bin/sail up -d`
- Stop Sail: `./vendor/bin/sail stop`
- Run Artisan commands: `./vendor/bin/sail artisan ...`
- Run Composer commands: `./vendor/bin/sail composer ...`
- Run NPM commands: `./vendor/bin/sail npm ...`
- Run PHPUnit tests: `./vendor/bin/sail test`

## Troubleshooting

If you encounter permission issues, ensure your user has the necessary rights to the project directory or try running:

```bash
sudo chown -R $USER:$USER .
```
