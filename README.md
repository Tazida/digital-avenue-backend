# Digital Avenue — Backend

Backend for the Digital Avenue website. It provides the admin panel and the API that the frontend reads.

**Live site:** https://digital-avenue-frontend.pages.dev/

## Tech stack

- Laravel (PHP)
- PostgreSQL
- Filament admin panel
- Docker (Laravel Sail)

## Getting started

Requirements: Docker Desktop.

```bash
cp .env.example .env

docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install --ignore-platform-reqs

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

Then open http://localhost:8000.

## Tests

```bash
./vendor/bin/sail artisan test
```
