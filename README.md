# CertiCarte

A comprehensive school management system built with Laravel for educational institutions.

## Overview

CertiCarte is a modern web application designed to streamline school administration and student management. The system provides administrators with tools to manage students, classes, school years, and users efficiently. Built with Laravel 12 and featuring a responsive design with Tailwind CSS, CertiCarte offers an intuitive interface for educational institutions to maintain accurate student records and academic data.

## Features

### 🎓 **Student Management**
- Complete CRUD operations for student records
- Personal information management (name, matricule, date of birth, gender, address)
- Student photo upload and automatic avatar generation
- Class assignment and academic year tracking
- Bulk import from Excel/CSV files
- Advanced filtering and search capabilities

### 🏫 **Class Management**
- Create and manage classes within school years
- Class statistics and student count tracking
- School year association and filtering
- Class-based student organization

### 📅 **School Year Management**
- Track academic periods and organize classes by school years
- Year-based filtering and organization
- Academic period management

### 👥 **User Management**
- Admin user system with authentication
- Role-based access control with permissions
- User account creation, modification, and deletion
- Permission management for individual users

### 📊 **Dashboard Analytics**
- Comprehensive overview of key metrics
- Student counts, class statistics, and user metrics
- Recent activity tracking (daily, weekly, monthly)
- Current school year information
- Quick access to all system modules

### 📄 **Report Generation**
- **Student Certificates**: Professional PDF certificates with school branding
- **Student ID Cards**: High-quality ID cards with photos and school information
- **Attendance Lists**: Single-day and multi-day attendance tracking sheets
- Consolidated reports interface with dynamic form handling
- Unified route structure: `/reports/{type}/{id}`

### 🎨 **Modern UI/UX**
- Responsive design built with Tailwind CSS
- Alpine.js for interactive components
- Modern card-based layout
- Dynamic forms with AJAX-powered interactions
- Loading states and user feedback
- Mobile-friendly interface

### 🔒 **Security & Permissions**
- Laravel's built-in authentication system
- Role-based access control (RBAC)
- Permission-based feature access
- CSRF protection
- Secure file uploads
- Session management

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS, Alpine.js
- **Database**: MySQL/SQLite (configurable)
- **PDF Generation**: DomPDF
- **Authentication**: Laravel's built-in authentication system
- **Authorization**: Spatie Laravel Permission
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint
- **Asset Building**: Vite
- **Excel Import**: Maatwebsite Excel

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or SQLite
- Web server (Apache/Nginx) or Laravel development server

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
- View system statistics and recent activity
- Quick access to all modules
- Overview of students, classes, and users
- Recent activity tracking

#### **Student Management**
- **Add Students**: Create new student records with personal information
- **Edit Students**: Modify existing student data and class assignments
- **View Students**: Detailed student profiles with photos and academic information
- **Delete Students**: Remove student records (with confirmation)
- **Import Students**: Bulk import from Excel/CSV files
- **Search & Filter**: Find students by name, matricule, class, or gender

#### **Class Management**
- **Create Classes**: Add new classes within school years
- **Edit Classes**: Modify class information and school year associations
- **View Classes**: Class details with student lists and statistics
- **Delete Classes**: Remove classes (with confirmation)
- **Filter by Year**: View classes by academic year

#### **User Management**
- **Create Users**: Add new admin accounts
- **Edit Users**: Modify user information and permissions
- **View Users**: User profiles and permission details
- **Delete Users**: Remove user accounts
- **Permission Management**: Assign specific permissions to users

#### **Report Generation**
- **Student Certificates**: Generate professional PDF certificates
- **Student ID Cards**: Create high-quality student ID cards
- **Attendance Lists**: Generate single-day or multi-day attendance sheets
- **Unified Interface**: Step-based report generation with dynamic forms

### Default Credentials

After running the database seeder, you can use these default credentials:

- **Email**: `admin@certicarte.com`
- **Password**: `password`

**⚠️ Important**: Change these credentials immediately after first login for security purposes.

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

# File Storage
FILESYSTEM_DISK=local
```

### File Storage

- Student photos are stored in `storage/app/public/photos`
- Report PDFs are generated on-demand
- Ensure proper permissions are set for the storage directory
- Run `php artisan storage:link` to create symbolic links

### Database Configuration

The application supports both MySQL and SQLite databases. Configure your preferred database in the `.env` file and run migrations accordingly.

### Permissions System

The system uses Spatie Laravel Permission for role-based access control:

- **Admin Role**: Full system access
- **Secretary Role**: Limited access to specific modules
- **Custom Permissions**: Granular control over individual features

Available permissions:
- `view_students`, `create_students`, `edit_students`, `delete_students`
- `view_classes`, `create_classes`, `edit_classes`, `delete_classes`
- `view_users`, `create_users`, `edit_users`, `delete_users`
- `generate_certificates`, `generate_cards`, `generate_attendance_lists`

## API Endpoints

### Reports API
- `GET /reports/certificate/{student}` - Generate student certificate
- `GET /reports/id-card/{student}` - Generate student ID card
- `GET /reports/attendance-list/{classe}?days={1|2}` - Generate attendance list

### Data API
- `GET /api/classes/by-year/{yearId}` - Get classes by school year
- `GET /api/students/by-class/{classeId}` - Get students by class

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   ├── ClasseController.php
│   │   ├── UserController.php
│   │   ├── ReportsController.php
│   │   ├── LoginController.php
│   │   └── RoleManagementController.php
│   └── Requests/
│       ├── StoreStudentRequest.php
│       ├── UpdateStudentRequest.php
│       ├── StoreClasseRequest.php
│       ├── UpdateClasseRequest.php
│       ├── StoreUserRequest.php
│       └── UpdateUserRequest.php
├── Models/
│   ├── Student.php
│   ├── Classe.php
│   ├── SchoolYear.php
│   └── User.php
└── Imports/
    └── StudentsImport.php

resources/
├── views/
│   ├── reports/
│   │   ├── index.blade.php
│   │   ├── certificate.blade.php
│   │   ├── id-card.blade.php
│   │   ├── attendance-list.blade.php
│   │   └── attendance-list-2days.blade.php
│   ├── students/
│   ├── classes/
│   ├── users/
│   └── components/
└── css/
    └── app.css
```

## Contributing

We welcome contributions to CertiCarte! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write comprehensive PHPDoc comments for all methods
- Write tests for new features
- Update documentation as needed
- Ensure all tests pass before submitting
- Use meaningful commit messages

### Code Style

The project uses Laravel Pint for code formatting. Run the following command before committing:

```bash
./vendor/bin/pint
```

## Changelog

### Version 1.0.0 (Current)
- ✅ Complete student management system
- ✅ Class and school year management
- ✅ User management with role-based permissions
- ✅ Comprehensive dashboard with analytics
- ✅ Report generation system (certificates, ID cards, attendance lists)
- ✅ Modern responsive UI with Tailwind CSS
- ✅ AJAX-powered dynamic forms
- ✅ Excel/CSV import functionality
- ✅ Photo upload and management
- ✅ Consolidated reports interface
- ✅ Unified route structure for reports
- ✅ Comprehensive PHPDoc documentation

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue in the repository
- Contact the development team
- Check the documentation for common solutions

## Acknowledgments

- Built with Laravel Framework
- UI components powered by Tailwind CSS
- PDF generation by DomPDF
- Permission management by Spatie Laravel Permission