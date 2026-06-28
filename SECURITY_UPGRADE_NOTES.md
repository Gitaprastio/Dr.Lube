# Security Upgrade Notes

This branch replaces the stale Dependabot PR pile with one coordinated Node
dependency refresh.

## Completed in this branch

- Root frontend build now uses npm with `package-lock.json` instead of the old
  Yarn v1 lockfile.
- Laravel Mix was upgraded from v2 to v6 while keeping Webpack pinned to a
  version that Mix 6 can compile with.
- Root frontend dependencies were updated to patched compatible lines:
  Axios 1.x, Bootstrap 4.6, jQuery 3.7, Lodash 4.17, Vue 2.7, Dart Sass.
- `public/dashboard` dependencies were refreshed and now audit clean.
- `public/dashboard/gulpfile.js` no longer relies on fragile stream-based vendor
  copying; it uses deterministic filesystem copies for vendor assets.

## Verification

```sh
npm run production
cd public/dashboard
npm audit
npx gulp build
```

The root npm audit still reports low/moderate advisories from Vue 2 and
Laravel Mix transitive development tooling. Forcing the recommended Webpack
upgrade currently breaks Laravel Mix 6, so the branch keeps the buildable
version.

## Remaining PHP Risk

`composer audit --locked` reports 40 advisories across 14 packages, including
critical/high advisories in Laravel, Symfony, Guzzle, and Flysystem. These are
not safely fixable inside the current Laravel 5.8 / PHP 7.1-era dependency
constraints.

The PHP remediation should be a separate framework migration, at minimum:

- Remove or replace the old BotMan Studio Composer plugin path that blocks
  modern Composer 2 updates.
- Upgrade Laravel from 5.8 to a maintained major version.
- Move Symfony, Guzzle, Flysystem, SwiftMailer, PHPUnit, and related packages to
  supported versions.
- Re-run Composer install/update and the Laravel test suite in a PHP runtime
  matching the chosen target version.
