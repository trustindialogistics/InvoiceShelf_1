# SS Gujarat Logistics Services InvoiceShelf

This repository contains the customized InvoiceShelf application prepared for official use by SS Gujarat Logistics Services. It is a Laravel + Vue application with custom invoice and LR receipt templates, LR receipt list actions, the company branding/logo work, and the local Windows setup used during development.

## Current Local Folder

The working application folder on this Windows machine is:

```powershell
C:\Users\18042\OneDrive\Documents\InvoiceShelf\InvoiceShelf
```

Open PowerShell and move into the project before running any project command:

```powershell
cd "C:\Users\18042\OneDrive\Documents\InvoiceShelf\InvoiceShelf"
```

What this does:

- `cd` means "change directory".
- The quoted path tells PowerShell to enter the application folder.
- After this, commands such as `php artisan serve` and `npm run dev` run against this application.

## Software Required On Windows

Install these before running the project on a fresh Windows machine:

1. PHP 8.4 or newer
2. Composer
3. Node.js 24 or newer
4. Git
5. A web browser

This project uses SQLite by default, so MySQL is not required for the local setup.

## Quick Start On This Machine

Use these commands when the dependencies are already installed and the `vendor`, `node_modules`, `.env`, and `database/database.sqlite` files already exist.

### 1. Go to the project folder

```powershell
cd "C:\Users\18042\OneDrive\Documents\InvoiceShelf\InvoiceShelf"
```

What this does:

- Opens the application folder in PowerShell.
- All later commands must be run from this folder.

### 2. Start the Laravel backend

```powershell
php artisan serve
```

What this does:

- Starts Laravel's local web server.
- The application backend runs at `http://127.0.0.1:8000`.
- Keep this PowerShell window open while using the app.

If `php` is not found on this machine, use the installed PHP path directly:

```powershell
& "C:\Users\18042\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" artisan serve
```

What this does:

- Runs the same Laravel server command using the full PHP executable path.
- The `&` tells PowerShell to execute a program whose path is inside quotes.

### 3. Start the frontend dev server

Open a second PowerShell window, then run:

```powershell
cd "C:\Users\18042\OneDrive\Documents\InvoiceShelf\InvoiceShelf"
npm run dev
```

What this does:

- `cd` enters the project folder in the second PowerShell window.
- `npm run dev` starts Vite, which builds and serves the Vue frontend assets while you work.
- Keep this second window open while using the app.

### 4. Open the app

Open this URL in your browser:

```text
http://127.0.0.1:8000
```

What this does:

- Loads the local InvoiceShelf application from the Laravel server.
- Login and use the application from the browser.

## Full Setup On A New Windows Machine

Use these steps when setting up the project from the GitHub repository on another Windows computer.

### 1. Clone the repository

```powershell
git clone <YOUR_GITHUB_REPOSITORY_URL>
cd InvoiceShelf
```

What this does:

- `git clone` downloads the complete code from GitHub.
- `cd InvoiceShelf` enters the downloaded project folder.

Replace `<YOUR_GITHUB_REPOSITORY_URL>` with the actual GitHub repository URL after the repository is created.

### 2. Install PHP dependencies

```powershell
composer install
```

What this does:

- Reads `composer.json` and `composer.lock`.
- Downloads Laravel and all PHP packages into the `vendor` folder.
- The `vendor` folder is not committed to GitHub because it can be recreated with this command.

### 3. Install Node dependencies

```powershell
npm install
```

What this does:

- Reads `package.json` and `package-lock.json`.
- Downloads Vue, Vite, Tailwind, and frontend tooling into the `node_modules` folder.
- The `node_modules` folder is not committed to GitHub because it can be recreated with this command.

### 4. Create the environment file

```powershell
copy .env.example .env
```

What this does:

- Creates a private `.env` configuration file from the example file.
- `.env` stores local settings such as app URL, database path, debug mode, and app key.
- `.env` is not committed to GitHub because it is machine-specific and may contain secrets.

### 5. Generate the Laravel app key

```powershell
php artisan key:generate
```

What this does:

- Creates a unique `APP_KEY` inside `.env`.
- Laravel uses this key for encryption and secure application sessions.

### 6. Create the SQLite database file

```powershell
New-Item -ItemType File -Path database\database.sqlite -Force
```

What this does:

- Creates the local SQLite database file.
- `-Force` avoids an error if the file already exists.

### 7. Configure SQLite in `.env`

Open `.env` and set these values:

```env
APP_ENV=local
APP_DEBUG=true
APP_NAME="SS Gujarat Logistics Services"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=C:/full/path/to/InvoiceShelf/database/database.sqlite

SESSION_DOMAIN=127.0.0.1
```

What this does:

- Sets the app to local development mode.
- Points Laravel to the SQLite database file.
- Makes browser sessions work correctly on `127.0.0.1`.

Use forward slashes in `DB_DATABASE`, even on Windows.

### 8. Run database migrations

```powershell
php artisan migrate
```

What this does:

- Creates all required database tables.
- Prepares the local database so the application can run.

### 9. Link public storage

```powershell
php artisan storage:link
```

What this does:

- Creates a public link for files stored by the application.
- This is needed for uploaded files, logos, and other public storage assets.

### 10. Build frontend assets for production use

```powershell
npm run build
```

What this does:

- Compiles the Vue frontend and CSS into optimized files.
- Writes the final assets into `public/build`.
- Use this before running the app without the Vite dev server.

### 11. Start the application

```powershell
php artisan serve
```

What this does:

- Starts the Laravel local server at `http://127.0.0.1:8000`.
- Open that URL in the browser to use the program.

For active development, also run this in a second PowerShell window:

```powershell
npm run dev
```

What this does:

- Starts Vite for live frontend asset updates.
- Useful when editing Vue, JavaScript, or CSS files.

## Useful Daily Commands

```powershell
php artisan serve
```

Starts the Laravel backend server.

```powershell
npm run dev
```

Starts the frontend development server.

```powershell
npm run build
```

Builds production frontend files.

```powershell
php artisan migrate
```

Applies new database changes.

```powershell
php artisan optimize:clear
```

Clears Laravel cached configuration, routes, views, and services. Use this if changes do not appear.

```powershell
git status
```

Shows which files are changed, staged, or committed.

```powershell
git add -A
```

Stages all current file changes for a commit.

```powershell
git commit -m "Describe the change"
```

Saves the staged changes into local Git history.

```powershell
git push
```

Uploads committed changes to GitHub.

## Important Files And Folders

- `app/` contains Laravel backend code.
- `resources/` contains Vue, JavaScript, CSS, and Blade/PDF template resources.
- `routes/` contains application route definitions.
- `database/migrations/` contains database table definitions.
- `database/database.sqlite` is the local SQLite database file.
- `public/` contains public assets.
- `.env` contains local machine settings and is intentionally not committed.
- `composer.json` lists PHP dependencies.
- `package.json` lists Node/frontend dependencies.

## GitHub Save Process

Use these commands when you want to save future work to GitHub:

```powershell
git status
git add -A
git commit -m "Your commit message"
git push
```

What this does:

- `git status` checks what changed.
- `git add -A` stages all changes.
- `git commit -m "Your commit message"` saves those changes locally with a message.
- `git push` uploads the saved commit to GitHub.

## Notes For Official Use

- Do not commit `.env`, `vendor`, `node_modules`, or private database backups.
- Keep a regular backup of `database/database.sqlite` if SQLite is used for real office data.
- For production hosting, use a proper web server, scheduled backups, HTTPS, and a secure database setup.
- Keep `DOMPDF_ENABLE_REMOTE=false` unless remote PDF images/CSS are fully trusted.
