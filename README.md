# Laravel API Authentication and User Management

This Laravel project showcases a comprehensive authentication and user management system utilizing JSON Web Tokens (JWT) for secure user interactions. The project provides a set of APIs for both administrators and users, enabling functionalities such as user login, user registration, user profile management, password reset, and more.

## Requirements
Before you start using this Laravel API Authentication and User Management project, please ensure that you have the following dependencies and software installed:

- PHP: This project requires PHP 8.2 or later. Make sure you have PHP installed on your system and properly configured.

- Composer: Composer is a dependency management tool for PHP. You'll need Composer to install the project's PHP dependencies. You can download and install Composer from https://getcomposer.org.

- Redis: Redis is used for caching and session management. Install and configure Redis according to your operating system and Laravel's requirements.

- Database: This project uses a database (such as MySQL, PostgreSQL, or SQLite) to store user information and other data. Make sure you have a database system installed and configured, and update the .env file with the appropriate database connection details.

## Table of Contents

- [Getting Started](#getting-started)
- [Features](#features)
- [Endpoints](#endpoints)
- [Tests](#tests)


## Getting Started

1. Clone the repository:

```bash
git clone https://github.com/1986webdeveloper/buckhill-petshop
```

2. Navigate to the project directory
```bash
cd buckhill-petshop
```

3. install the dependencies using Composer:
```bash
composer install
```

3. Copy the .env.example file to .env and configure your database settings and other environment variables:
```bash
cp .env.example .env
```

4. Generate an application key:
```bash
php artisan key:generate
```

5. Run database migrations:
```bash
php artisan migrate
```

6. Run database seeder:
```bash
php artisan db:seed
```

7. Generate Private/Public key pair for JWT
- new generated files will be stored in `jwtkeys` folder in projects root folder
```bash
openssl genpkey -algorithm RSA -out jwtkeys/private_key.pem
```

```bash
openssl rsa -pubout -in jwtkeys/private_key.pem -out jwtkeys/public_key.pem
```


9. Serve the application:
```bash
php artisan serve
```

## Features

- Admin/User Login: users can securely log in to the system using their credentials with JWT Authentication.
- Password Reset and Recovery: Provide the capability for users to reset their passwords if forgotten.
- Admin/User Logout: Allow users to securely log out of their accounts.
- Admin User Listing: Administrators can retrieve a list of all users for management purposes with paginated data and sorting/filters option.
- Admin Create User: Administrators can create new admin accounts.
- Admin Edit User: Administrators can modify user details.
- Admin Delete User: Administrators can delete user accounts.
- User Details: Retrieve details of the currently logged-in user.
- Edit User Profile: Allow users to edit their profile details.
- Delete User account: Allow users to delete their account.
- Create User: Users can create new user accounts.

## The following API endpoints are available:

Admin Endpoints:
- Admin Login: `POST /api/v1/admin/login`
- Admin User Listing: `GET /api/v1/admin/user-listing`
- Admin Create User: `POST /api/v1/admin/create`
- Admin Edit User: `POST /api/v1/admin/user-edit/{uuid}`
- Admin Delete User: `DELETE /api/v1/admin/user-delete/{uuid}`
- Admin Logout: `GET /api/v1/admin/logout`

User Endpoints:
- User Login: `POST /api/v1/user/login`
- Forgot Password: `POST /api/v1/user/forgot-password`
- Reset Password: `POST /api/v1/user/reset-password-token`
- User Details: `GET /api/v1/user`
- Create User: `POST /api/v1/user/create`
- Edit User: `POST /api/v1/user/edit`
- User Delete: `DELETE /api/v1/user`
- User Logout: `GET /api/v1/user/logout`

## Tests
The project includes comprehensive test coverage to ensure functionality and reliability. To run the tests:
```bash
php artisan test
```
