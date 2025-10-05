# Developer Onboarding Guide

Welcome to the SEP HRMS project. The steps below will bootstrap a local environment for development and testing.

## Requirements
- PHP 8.1+
- Composer
- Node.js 18+
- NPM or Yarn
- MySQL 8 (or compatible) local instance
- Redis (optional, recommended for queues/cache)

## Setup Steps
1. **Install dependencies**
    ```bash
    composer install
    npm install
    ```
2. **Environment files**
    ```bash
    cp .env.example .env
    cp .env.testing .env.playwright  # optional if you want isolated configs
    ```
    Update database credentials for your local MySQL instance. The repository ships with a realistic dataset at `DATABASE_DUMP/sep_hrms.sql`.

3. **Application key & storage**
    ```bash
    php artisan key:generate
    php artisan storage:link
    ```

4. **Database**
    ```bash
    php artisan migrate
    mysql -u root -p sep_hrms < DATABASE_DUMP/sep_hrms.sql
    ```
    > ⚠️ Seeded credentials (`password123`, etc.) are temporary and tracked in `docs/Security_Follow_ups.md` for future rotation.

5. **Queues and caches**
    - By default the application uses the `database` queue connection. For local testing you may switch to `sync` in `.env`.
    - Run `php artisan queue:work` when testing queued exports.

6. **Assets & Playwright**
    ```bash
    npm run build
    npm run playwright:install
    ```

7. **Run the app**
    ```bash
    php artisan serve
    npm run dev   # optional, for Vite hot reload
    ```

8. **Local testing**
    ```bash
    composer test         # PHPUnit
    npm run test:e2e      # Playwright
    ```

    When running PHPUnit the suite now boots the full MySQL schema from `sep_hrms.sql`. Ensure your testing database
    user can drop/recreate tables and that the dump stays in the project root. Queued export tests dispatch to the
    `reports` queue; either start a worker (`php artisan queue:work`) or set `QUEUE_CONNECTION=sync` in `.env.testing`
    while iterating locally.

## Default Accounts
- HR Admin: `hr@sarieldin.com` / `password123`
- Accounting Manager: `accounting@sarieldin.com` / `password123`

> These defaults remain for development only. See `docs/Security_Follow_ups.md` for deferred rotation tasks.
