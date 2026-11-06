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
# set INTERNAL_API_TOKEN in .env: openssl rand -hex 32

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

## Code structure

Same layering as ParkFu: thin controllers, business logic in services.

```
app/
  Constant/ResponseCodes.php     \ResponseCode["Not Found"] etc. (autoloaded in composer.json)
  Exceptions/                    DAException → 409, NotFoundException → 404
  Http/Controllers/Api/          thin controllers: request → service → resource
  Http/Middleware/               EnsureInternalToken (checks X-Internal-Token)
  Http/Requests/                 form requests; extend ApiRequest
  Http/Resources/                JSON shape of each model (hides private fields)
  Models/                        Eloquent models (ULID ids, constants such as TYPES)
  Services/                      queries and business rules
  Traits/ApiResponse.php         success(), error(), handle()
routes/api.php                   internal API under /api/v1.0
tests/Feature/                   one test file per API area
```

**Response shape**

```json
{ "success": true, "data": [ ... ] }
{ "error": true, "message": "Lot not found." }
{ "error": true, "message": { "type": ["The selected type is invalid."] } }
```

**Adding an API area** (see Clients as the example)

1. Migration, model and factory: `sail artisan make:model Project -mf`
2. `app/Services/ProjectService.php`: queries and rules; throw `NotFoundException` or `DAException`
3. `app/Http/Requests/...Request.php`: extends `ApiRequest`, only `rules()`
4. `app/Http/Resources/ProjectResource.php`: never expose private fields (contract values, certificate numbers)
5. `app/Http/Controllers/Api/ProjectController.php`: `use ApiResponse;` and wrap each action in `$this->handle(...)`
6. Route in `routes/api.php`
7. `tests/Feature/ProjectTest.php`, then `sail artisan test`

**Internal API token:** every `/api` call needs the header `X-Internal-Token: <INTERNAL_API_TOKEN>`. Without a token configured, every call is refused.

## Production image

```bash
docker build -t digital-avenue-backend:prod .
```

The image bakes in the code and dependencies. Secrets come from environment variables on the server, never from the image.

## Rules

- Never commit `.env` or the client's profile PDF.
- Contract values and certificate numbers stay private unless switched on in the admin panel (see the project docs).
