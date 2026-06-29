# Security Upgrade Notes

This branch replaces the stale Dependabot PR pile with one coordinated
frontend and Composer dependency cleanup.

## Completed in this branch

- Root frontend build now uses npm with `package-lock.json`.
- Laravel Mix, Vue, and the npm `botman-tinker` package were removed. The root
  build now uses Webpack 5 for JavaScript and Dart Sass directly for CSS.
- The `/botman/tinker` page is first-party JavaScript backed by the authenticated
  `POST /botman` web-driver endpoint. The endpoint now uses the normal `web`
  stack, auth middleware, and CSRF token from the tinker page instead of the
  old CSRF exception.
- Root frontend dependencies were updated to patched compatible lines:
  Axios 1.x, Bootstrap 4.6, jQuery 3.7, Lodash 4.17, Dart Sass.
- `public/dashboard` dependencies were refreshed and now audit clean.
- `public/dashboard/gulpfile.js` no longer relies on fragile stream-based vendor
  copying; it uses deterministic filesystem copies for vendor assets.
- Removed unused Composer packages that pulled vulnerable or obsolete
  dependencies: `botman/studio-addons`, `botman/tinker`, `laravel/tinker`,
  `barryvdh/laravel-debugbar`, and `beyondcode/laravel-dump-server`.
- Migrated the backend from Laravel 5.8 to Laravel 13 on PHP 8.3, including
  `laravel/framework` 13.17, `spatie/laravel-permission` 6.25, `laravel/ui`
  4.6, `botman/botman` 2.8.11, and PHPUnit 11.5.
- Removed Composer dependencies that were obsolete or tied to vulnerable
  framework-era packages, including `fideloper/proxy`, `swiftmailer`, and
  `symfony/debug`.
- Replaced Laravel 5-era APIs removed by newer framework versions:
  `Auth::routes()`, global string helpers, the Fideloper proxy middleware, and
  the old Spatie middleware namespace.

## Verification

```sh
npm run production
npm audit
npm audit --omit=dev

cd public/dashboard
npm audit --omit=dev
npx gulp build

docker run --rm -v "$PWD":/app -w /app composer:2 composer validate --strict --no-check-publish
docker run --rm -v "$PWD":/app -w /app composer:2 composer audit --locked --format=plain
docker run --rm -v "$PWD":/app -w /app php:8.3-cli vendor/bin/phpunit --fail-on-phpunit-deprecation
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php artisan route:list --path=botman -vv
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php artisan route:list
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php artisan config:cache
docker run --rm -v "$PWD":/app -w /app php:8.3-cli php artisan view:cache
```

## PHP Risk Status

The root npm audit and `public/dashboard` production npm audit are clean.

The previous Composer audit findings from Laravel 5.8 / Symfony 4.4 are
resolved by the Laravel 13 migration. `composer audit --locked` reports no
security vulnerability advisories.

Production, CI, and local development must now run PHP 8.3 or newer.
