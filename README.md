# HR & Payroll System

A modern, simplified HR and Payroll management system built with Laravel 12 and Vue 3.

## Features

- **Employee Management**: Full CRUD operations for employees
- **Department Management**: Organizational structure
- **Attendance Tracking**: Clock in/out with hours calculation
- **Leave Management**: Leave requests with approval workflow
- **Payroll Processing**: Automated payroll with configurable tax rules
- **Multi-Country Tax Support**: Configurable tax rules for any country (Kenya, US, etc.)

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2)
- **Frontend**: Vue 3 + Vite
- **Database**: MySQL

## Installation

### Backend Setup

```bash
cd hr-payroll
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### Frontend Setup

```bash
cd hr-payroll/frontend
npm install
npm run dev
```

## API Endpoints

### Departments
- `GET /api/departments` - List all departments
- `POST /api/departments` - Create department
- `GET /api/departments/{id}` - Get department
- `PUT /api/departments/{id}` - Update department
- `DELETE /api/departments/{id}` - Delete department

### Employees
- `GET /api/employees` - List all employees
- `POST /api/employees` - Create employee
- `GET /api/employees/{id}` - Get employee
- `PUT /api/employees/{id}` - Update employee
- `DELETE /api/employees/{id}` - Delete employee

### Attendance
- `GET /api/attendances` - List attendances (supports filtering by employee_id, date_from, date_to)
- `POST /api/attendances` - Create attendance record
- `GET /api/attendances/{id}` - Get attendance
- `PUT /api/attendances/{id}` - Update attendance
- `DELETE /api/attendances/{id}` - Delete attendance
- `POST /api/attendances/clock-in` - Clock in
- `POST /api/attendances/clock-out` - Clock out

### Leaves
- `GET /api/leaves` - List leaves (supports filtering by employee_id, status)
- `POST /api/leaves` - Create leave request
- `GET /api/leaves/{id}` - Get leave
- `PUT /api/leaves/{id}` - Update leave
- `DELETE /api/leaves/{id}` - Delete leave
- `POST /api/leaves/{id}/approve` - Approve leave
- `POST /api/leaves/{id}/reject` - Reject leave

### Payroll
- `GET /api/payrolls` - List payrolls (supports filtering by period, employee_id, status)
- `POST /api/payrolls` - Create payroll
- `GET /api/payrolls/{id}` - Get payroll
- `PUT /api/payrolls/{id}` - Update payroll
- `DELETE /api/payrolls/{id}` - Delete payroll
- `POST /api/payrolls/generate-period` - Generate payroll for all employees for a period

## Database Schema

### Tables
- **users**: System users with roles (admin, manager, employee)
- **departments**: Organizational departments
- **tax_rules**: Configurable tax rules for different countries
- **employees**: Employee profiles linked to departments and tax rules
- **attendances**: Daily attendance records
- **leaves**: Leave requests with approval workflow
- **payrolls**: Payroll records with full breakdown

## Tax Configuration

Tax rules are stored in the database and can be configured for any country:

- **PAYE Bands**: Progressive tax rates
- **Personal Relief**: Tax-free allowance
- **NSSF**: Social security contribution
- **SHIF**: Social Health Insurance Fund
- **Housing Levy**: Housing development levy

Example Kenyan tax rules are included in the seeder.

## Default Credentials

- **Admin**: admin@hr-payroll.com / password
- **Manager**: manager@hr-payroll.com / password

## License

MIT
"# hr-payroll" 
