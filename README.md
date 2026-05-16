# Laravel Project Setup Guide

This guide will walk you through the process of cloning and setting up this Laravel project on your local machine.

## Prerequisites

Before you begin, make sure you have the following installed on your system:

- PHP >= 8.1
- Composer (PHP dependency manager)
- Node.js and npm (for frontend assets)
- MySQL or another database supported by Laravel
- Git

## Installation Steps

### 1. Clone the Repository

Open your terminal and run:

```bash
git clone https://github.com/DavidHaryono/Financial-Tracker.git
cd Financial-Tracker
```

### 2. Install PHP Dependencies

Install all PHP dependencies using Composer:

```bash
composer install
```

### 3. Install Node.js Dependencies

Install all frontend dependencies using npm:

```bash
npm install
```

### 4. Environment Configuration

#### Copy the Environment File

Laravel uses a `.env` file for environment-specific configuration. Copy the example file:

```bash
cp .env.example .env
```

#### Generate Application Key

Generate a unique application key:

```bash
php artisan key:generate
```

#### Configure Database

Open the `.env` file in your text editor and update the database configuration:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Replace `your_database_name`, `your_database_username`, and `your_database_password` with your actual database credentials.

### 5. Configure API Keys

This project requires two additional API keys. Add them to your `.env` file:

```dotenv
OCRSPACE_API_KEY=your_ocrspace_api_key
GROQ_API_KEY=your_groq_api_key
```

#### Getting the OCR.space API Key

OCR.space provides optical character recognition services.

**Option 1: Free API Key (Recommended for Development)**
1. Visit [OCR.space API](https://ocr.space/ocrapi)
2. Sign up for a free account
3. You'll receive an API key via email
4. Copy the API key and paste it in your `.env` file as `OCRSPACE_API_KEY`

**Option 2: Using OCR.space Desktop Application**
1. Download the OCR.space desktop application from their [GitHub releases](https://github.com/A9T9/Free-OCR-Software/releases)
2. Install the application on your system
3. Add the application to your system PATH (optional, for command-line usage)
4. If using the desktop version programmatically, configure the path in your application

#### Getting the Groq API Key

Groq provides AI language model APIs for fast inference.

1. Visit [Groq Console](https://console.groq.com)
2. Sign up for a free account or log in
3. Navigate to the API Keys section in your dashboard
4. Click "Create API Key"
5. Give your key a descriptive name (e.g., "Laravel Project")
6. Copy the generated API key
7. Paste it in your `.env` file as `GROQ_API_KEY`

**Important:** Keep your API keys secure and never commit them to version control!

### 6. Run Database Migrations

Create the necessary database tables:

```bash
php artisan migrate
```

If you have seeders to populate initial data:

```bash
php artisan db:seed
```

Or run both migrations and seeders together:

```bash
php artisan migrate --seed
```

### 7. Build Frontend Assets

Compile the frontend assets:

**For development:**
```bash
npm run dev
```

**For production:**
```bash
npm run build
```

### 8. Create Storage Link

Create a symbolic link from `public/storage` to `storage/app/public`:

```bash
php artisan storage:link
```

### 9. Set Proper Permissions

Ensure the storage and bootstrap/cache directories are writable:

**On Linux/Mac:**
```bash
chmod -R 775 storage bootstrap/cache
```

**On Windows:** Right-click the folders, go to Properties > Security, and ensure appropriate write permissions.

### 10. Start the Development Server

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

**Note:** If you're running `npm run dev` for hot module replacement, you may need to run it in a separate terminal window.

## Common Issues and Troubleshooting

### Issue: "No application encryption key has been specified"
**Solution:** Run `php artisan key:generate`

### Issue: Database connection errors
**Solution:** Verify your database credentials in the `.env` file and ensure your database server is running

### Issue: Permission denied errors
**Solution:** Ensure proper permissions are set on the `storage` and `bootstrap/cache` directories

### Issue: API errors related to OCR or Groq
**Solution:** Verify that your API keys are correctly set in the `.env` file and that they're valid

## Additional Commands

### Clear application cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Run tests:
```bash
php artisan test
```

## Project Structure

```
Laravel-Project/
├── app/              # Application core code
├── bootstrap/        # Framework bootstrap files
├── config/           # Configuration files
├── database/         # Database migrations and seeders
├── public/           # Public assets and entry point
├── resources/        # Views, raw assets, and language files
├── routes/           # Application routes
├── storage/          # Generated files and logs
└── tests/            # Automated tests
```

