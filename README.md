# CertiCarte

A comprehensive school management system built with Laravel for educational institutions.

## Overview

CertiCarte is a modern web application designed to streamline school administration and student management. The system provides administrators with tools to manage students, classes, school years, and users efficiently. Built with Laravel 12 and featuring a responsive design with Tailwind CSS, CertiCarte offers an intuitive interface for educational institutions to maintain accurate student records and academic data.

## Features

### 🎓 **Student Management**

-   Complete CRUD operations for student records
-   Personal information management (name, matricule, date of birth, place of birth, gender, situation)
-   Student photo upload and automatic avatar generation (with consistent colors based on name)
-   Class assignment and academic year tracking
-   Bulk import from Excel/CSV files with support for:
-   French and English column names
-   Automatic school year and class creation
-   Excel date format conversion
-   Comprehensive error handling and logging
-   Advanced filtering and search capabilities

### 🏫 **Class Management**

-   Create and manage classes within school years
-   Class statistics and student count tracking
-   School year association and filtering
-   Class-based student organization

### 📅 **School Year Management**

-   Track academic periods and organize classes by school years
-   Year-based filtering and organization
-   Academic period management

### 👥 **User Management**

-   Admin and secretary user system with authentication
-   Role-based access control (RBAC) with granular permissions
-   User account creation, modification, and deletion
-   Permission management for individual users
-   Primary admin protection (ID 1 cannot be modified)
-   Two roles: Admin (full access) and Secretary (custom permissions)

### 📊 **Dashboard Analytics**

-   Comprehensive overview of key metrics
-   Student counts, class statistics, and user metrics
-   Recent activity tracking (daily, weekly, monthly)
-   Current school year information
-   Quick access to all system modules

### 📄 **Report Generation**

-   **Student Certificates**: Professional PDF certificates with school branding
-   **Student ID Cards**: High-quality ID cards with photos and school information
-   **Attendance Lists**: Single-day and multi-day attendance tracking sheets
-   Consolidated reports interface with dynamic form handling
-   Unified route structure: `/reports/{type}/{id}`

### 🎨 **Modern UI/UX**

-   Responsive design built with Tailwind CSS
-   Alpine.js for interactive components
-   Modern card-based layout
-   Dynamic forms with AJAX-powered interactions
-   Loading states and user feedback
-   Mobile-friendly interface

### 🔒 **Security & Permissions**

-   Laravel's built-in authentication system
-   Role-based access control (RBAC)
-   Permission-based feature access
-   CSRF protection
-   Secure file uploads
-   Session management

## Tech Stack

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Tailwind CSS, Alpine.js
-   **Database**: PostgreSQL/SQLite (configurable)
-   **PDF Generation**: DomPDF
-   **Authentication**: Laravel's built-in authentication system
-   **Authorization**: Spatie Laravel Permission
-   **Testing**: PHPUnit
-   **Code Quality**: Laravel Pint
-   **Asset Building**: Vite
-   **Excel Import**: Maatwebsite Excel

## Installation

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and npm
-   PostgreSQL or SQLite
-   Web server (Apache/Nginx) or Laravel development server

### Setup Instructions

1. **Clone the repository**

    ```bash
    git clone [repository-url]
    cd CertiCarte
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies**

    ```bash
    npm install
    ```

4. **Environment configuration**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Database setup**

    ```bash
    # Configure your database in .env file
    php artisan migrate
    php artisan db:seed
    ```

6. **Storage setup**

    ```bash
    php artisan storage:link
    ```

7. **Build assets**

    ```bash
    npm run build
    # or for development
    npm run dev
    ```

8. **Start the development server**
    ```bash
    php artisan serve
    ```

## Usage

### Accessing the Application

1. Navigate to `http://localhost:8000` in your browser
2. Login with your admin credentials
3. Access the dashboard to view system overview and statistics

### Key Functionalities

#### **Dashboard**

-   View system statistics and recent activity
-   Quick access to all modules
-   Overview of students, classes, and users
-   Recent activity tracking

#### **Student Management**

-   **Add Students**: Create new student records with personal information
-   **Edit Students**: Modify existing student data and class assignments
-   **View Students**: Detailed student profiles with photos and academic information
-   **Delete Students**: Remove student records (with confirmation)
-   **Import Students**: Bulk import from Excel/CSV files
-   **Search & Filter**: Find students by name, matricule, class, or gender

#### **Class Management**

-   **Create Classes**: Add new classes within school years
-   **Edit Classes**: Modify class information and school year associations
-   **View Classes**: Class details with student lists and statistics
-   **Delete Classes**: Remove classes (with confirmation)
-   **Filter by Year**: View classes by academic year

#### **User Management**

-   **Create Users**: Add new admin accounts
-   **Edit Users**: Modify user information and permissions
-   **View Users**: User profiles and permission details
-   **Delete Users**: Remove user accounts
-   **Permission Management**: Assign specific permissions to users
-   **Profile Management**: View and edit your own profile

#### **Report Generation**

-   **Student Certificates**: Generate professional PDF certificates
-   **Student ID Cards**: Create high-quality student ID cards
-   **Attendance Lists**: Generate single-day or multi-day attendance sheets
-   **Unified Interface**: Step-based report generation with dynamic forms

### Default Credentials

After running the database seeder, you can use these default credentials:

**Admin User:**

-   **Email**: `admin@example.com`
-   **Password**: `password`

**Secretary User:**

-   **Email**: `secretary@example.com`
-   **Password**: `password`

**⚠️ Important**: Change these credentials immediately after first login for security purposes.

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
APP_NAME="CertiCarte"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=certicarte
DB_USERNAME=postgres
DB_PASSWORD=
DB_SCHEMA=public

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

# File Storage
FILESYSTEM_DISK=local
```

### File Storage

-   Student photos are stored in `storage/app/public/photos`
-   Report PDFs are generated on-demand
-   Ensure proper permissions are set for the storage directory
-   Run `php artisan storage:link` to create symbolic links

### Database Configuration

The application supports both PostgreSQL and SQLite databases. Configure your preferred database in the `.env` file and run migrations accordingly.

### Permissions System

The system uses Spatie Laravel Permission for role-based access control:

-   **Admin Role**: Full system access
-   **Secretary Role**: Limited access to specific modules
-   **Custom Permissions**: Granular control over individual features

Available permissions:

-   `view_students`, `create_students`, `edit_students`, `delete_students`, `import_students`
-   `view_classes`, `create_classes`, `edit_classes`, `delete_classes`
-   `view_users`, `create_users`, `edit_users`, `delete_users`
-   `generate_certificates`, `generate_cards`, `generate_attendance_lists`

## Render Deployment Guide

The repository ships with a `render.yaml` blueprint so you can recreate the full stack (web service + PostgreSQL) on Render in a single step. If you already created a managed database manually, simply remove the `databases:` section before applying the blueprint and keep the `services:` block.

### 1. Prepare the database

1. In the Render dashboard, create a **PostgreSQL** instance (Free plan is enough for tests).
2. Copy the credentials (host, port `5432`, database name, username, password, connection string).  
3. If you apply the blueprint with the `databases` block intact, Render provisions this DB automatically and wires the variables for you.

### 2. Deploy the web service

1. Click **New → Blueprint** and select your fork of this repo.
2. Render reads `render.yaml`:
    - Builds the Docker image with the repo `Dockerfile`.
    - Creates a `certicart-web` service on the Free plan in the Oregon region.
    - Injects all required env vars (APP\_ENV, DB\_HOST, etc.) and wires them to the database defined in the blueprint.
    - Runs the `postDeployCommand` once so `php artisan key:generate`, `php artisan migrate --force`, and `php artisan storage:link` execute automatically after each deploy.
3. Wait for the deploy to finish; the public URL (for example `https://certicart-web.onrender.com`) appears on the service page.

### 3. Environment variables to review

- `APP_URL`: set it to your Render URL to fix asset links (e.g. `https://certicart-web.onrender.com`).
- `APP_KEY`: the blueprint leaves it empty but the post-deploy command calls `php artisan key:generate --force`. If you prefer to control it, set a base64 key manually and remove that command.
- `MAIL_*`: fill in your SMTP provider (Mailtrap, Mailgun, etc.).
- `FILESYSTEM_DISK`: defaults to `public`. Render disks are ephemeral, so for persistent student photos you can switch to S3-compatible storage and add the corresponding env vars.

### 4. Optional worker

If you need queues (`php artisan queue:work`) or scheduled tasks, duplicate the `certicart-web` block inside `render.yaml`, change `type` to `worker`, set `startCommand` to `php artisan queue:work --sleep=3 --tries=3`, and redeploy the blueprint. Both services can point to the same `certicart-db`.

### 5. Manual commands

- To re-run migrations or seed data: open the Render service → **Shell** → run `php artisan migrate --force` or `php artisan db:seed --force`.
- To inspect logs: use the **Logs** tab; we route logs to `stderr` so everything is visible there.

### 6. Redeploys & updates

1. Commit & push changes to GitHub.
2. Trigger a new deploy from Render (or enable auto-deploy on push).
3. The blueprint hooks rebuild the Docker image, run the post-deploy Artisan commands, and roll out the new containers automatically.
