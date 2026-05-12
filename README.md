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

Copy the example environment file. It is already pre-configured for the Docker Sail environment:

```bash
cp .env.example .env
```

### 3. Install Dependencies

You can install the PHP dependencies using one of the following methods:

#### Option A: Using Docker (Recommended if you don't have PHP/Composer)

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

*(Note: Adjust the image name `php84-composer` if you need a different PHP version.)*

#### Option B: Local PHP & Composer

If you already have PHP and Composer installed on your machine, simply run:

```bash
composer install
```

### 4. Start the Application

Start the Docker containers using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

### 5. Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

### 6. Run Migrations and Seeders

```bash
./vendor/bin/sail artisan migrate --seed
```

### 7. Frontend Setup

Install NPM dependencies and build the assets:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

Or run the development server:

```bash
./vendor/bin/sail npm run dev
```

## Default Credentials

After seeding the database, you can use the following accounts to log in (password for all is `password`):

| Role | Email | Permissions |
| :--- | :--- | :--- |
| **Admin** | `admin@mail.com` | Full access to all features and settings. |
| **Supervisor** | `supervisor@mail.com` | Manage Items, Sales, and Payments. |
| **Staff** | `staff@mail.com` | Manage Sales and Payments only. |

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
