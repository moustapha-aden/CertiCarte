# CertiCarte

A comprehensive school management system built with Laravel for educational institutions.

## Overview

CertiCarte is a modern web application designed to streamline school administration and student management. The system provides administrators with tools to manage students, classes, school years, and users efficiently. Built with Laravel 12 and featuring a responsive design with Tailwind CSS, CertiCarte offers an intuitive interface for educational institutions to maintain accurate student records and academic data.

## Features

- **Student Management**: Complete CRUD operations for student records including personal information, photos, and academic details
- **Class Management**: Organize students into classes with proper academic year associations
- **School Year Management**: Track academic periods and organize classes by school years
- **User Management**: Admin user system with authentication and role-based access
- **Dashboard Analytics**: Overview of key metrics including student counts, class statistics, and recent activity
- **Responsive Design**: Modern UI built with Tailwind CSS and Alpine.js for optimal user experience
- **PDF Generation**: Built-in PDF generation capabilities for reports and certificates
- **Photo Management**: Student photo upload and automatic avatar generation
- **Dynamic Forms**: AJAX-powered forms for seamless user interactions

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/SQLite (configurable)
- **PDF Generation**: DomPDF
- **Authentication**: Laravel's built-in authentication system
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or SQLite

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

- **Dashboard**: View system statistics, recent activity, and quick access to all modules
- **Student Management**: Add, edit, view, and delete student records
- **Class Management**: Create and manage classes within school years
- **User Management**: Admin user account management
- **Reports**: Generate PDF reports and certificates

### Default Credentials

[Replace with actual default admin credentials or setup instructions]

## Configuration

### Environment Variables

Key configuration options in `.env`:

```env
APP_NAME="CertiCarte"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=certicarte
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

### File Storage

- Student photos are stored in `storage/app/public/photos`
- Ensure proper permissions are set for the storage directory
- Run `php artisan storage:link` to create symbolic links

### Database Configuration

The application supports both MySQL and SQLite databases. Configure your preferred database in the `.env` file and run migrations accordingly.

## Contributing

We welcome contributions to CertiCarte! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.