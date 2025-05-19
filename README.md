# Clean MVC CRUD Application

A very clean and maintainable MVC CRUD application built with PHP 8.3+, running on Apache with MySQL database and PHPMyAdmin5.

## Features

- MVC Architecture
- PHP 8.3+ with modern features
- Docker development environment
- Clean and maintainable code structure
- MySQL database with phpMyAdmin
- Basic CRUD operations

## Project Structure

```
.
├── config/                 # Configuration files
│   ├── bootstrap.php       # Application bootstrap
│   └── routes.php          # Route definitions
├── database/               # Database scripts
│   └── migrate.php         # Database migration script
├── public/                 # Public directory
│   ├── .htaccess           # URL rewriting for Apache
│   └── index.php           # Application entry point
├── src/                    # Application source code
│   ├── Controllers/        # Controller classes
│   ├── Core/               # Framework core classes
│   ├── Exceptions/         # Custom exceptions
│   ├── Models/             # Model classes
│   └── Views/              # View templates
├── .env.example            # Environment template
├── .gitignore              # Git ignore file
├── Dockerfile              # Docker configuration
├── apache-config.conf      # Apache configuration
├── composer.json           # Composer dependencies
└── docker-compose.yml      # Docker compose configuration
```

## Requirements

- Docker and Docker Compose
- Git (optional)

## Installation

1. Clone the repository (or download and extract the zip file):

```bash
git clone https://github.com/Retro-Artist/Docker-Studies.git
cd htdocs
```

1. Simply rename ".env.example" to ".env" or create a new .env file using ".env.example" as a template:

```bash
docker-compose mv .env.example .env
```

3. Start the Docker containers:

```bash
docker-compose up -d
```

4. Install Composer dependencies (optional):

```bash
docker-compose exec app composer install
```

5. Run the database migration script:

```bash
docker-compose exec app php database/migrate.php
```

6. Access the application in your browser:

- Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (Server: mysql, Username: root, Password: root_password)

## Development

The Docker environment maps the `src` directory from your local machine to the container, so any changes you make to the files will be immediately reflected in the application.

### Development Workflow

1. Make changes to the code on your local machine.
2. Refresh the browser to see the changes.
3. Use phpMyAdmin to manage the database if needed.

### Database Access

- Host: localhost
- Port: 3306
- Database: mvc_crud
- Username: root
- Password: root_password

For phpMyAdmin:
- URL: http://localhost:8081
- Server: mysql
- Username: root
- Password: root_password

## Deployment to Production

To deploy to a Linux production server:

1. Clone the repository on the server.
2. Configure your production Apache server to point to the `public` directory.
3. Set up the MySQL database and update the `.env` file with the production credentials.
4. Install Composer dependencies with `composer install --no-dev`.
5. Run the database migration script.

## License

This project is open-sourced software licensed under the MIT license.