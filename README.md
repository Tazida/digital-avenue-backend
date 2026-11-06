# Digital Avenue — Backend

Laravel backend for the Digital Avenue website: the PostgreSQL database, the Filament admin panel and the internal API that the SvelteKit frontend reads. Everything runs in Docker.

- **Local development:** Laravel Sail (`compose.yaml`): app, queue worker, PostgreSQL
- **Production:** `Dockerfile` (Laravel on FrankenPHP), behind Caddy on the VPS

## Requirements

- Docker Desktop

No PHP or Composer is needed on your machine; they run inside containers.

## First run (fresh clone)

```bash
cp .env.example .env

# install PHP dependencies without local PHP
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php84-composer:latest composer install --ignore-platform-reqs

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:filament-user
```

Then open:

- Admin panel: http://localhost:8000/admin
- App: http://localhost:8000

Tip: add `alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'` to `~/.zshrc`, then use `sail ...`.

## Services (compose.yaml)

| Service       | What it does                        | Host port |
| ------------- | ----------------------------------- | --------- |
| `laravel.test`| Laravel app (PHP 8.5)               | 8000      |
| `queue`       | Queue worker (sends inquiry emails) | —         |
| `pgsql`       | PostgreSQL 18                       | 5432      |

Change host ports with `APP_PORT` and `FORWARD_DB_PORT` in `.env`.

## Everyday commands

```bash
sail up -d                  # start
sail down                   # stop
sail logs -f                # follow logs
sail artisan <command>      # artisan
sail composer <command>     # composer
sail restart queue          # reload the queue worker after code changes
```

## Production image

```bash
docker build -t digital-avenue-backend:prod .
```

The image bakes in the code and dependencies. Secrets come from environment variables on the server, never from the image.

## Rules

- Never commit `.env` or the client's profile PDF.
- Contract values and certificate numbers stay private unless switched on in the admin panel (see the project docs).
