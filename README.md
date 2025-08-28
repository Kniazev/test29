# Car Management API

This is a simple API for managing cars, built with Laravel.

## Local Development Setup

This project uses Docker for local development. Make sure you have [Docker](https://www.docker.com/products/docker-desktop/) installed on your system.

### 1. Clone the Repository

```bash
git clone git@github.com:Kniazev/test29.git
cd test29
```

### 2. Create Environment File

Copy the example environment file and create your own `.env` file.

```bash
cp .env.example .env
```

The default settings in `.env.example` are configured to work with the provided Docker setup.

### 3. Build and Run Docker Containers

Use Docker Compose to build the images and run the containers in the background.

```bash
docker-compose up -d --build
```

### 4. Install PHP Dependencies

Use the `composer` service to install the project dependencies.

```bash
docker-compose exec composer install
```

### 5. Generate Application Key

```bash
docker-compose exec app php artisan key:generate
```

### 6. Run Database Migrations

Create the necessary tables in the database by running the migrations.

```bash
docker-compose exec app php artisan migrate
```

Optionally, you can seed the database with some initial data if seeders are available:
```bash
docker-compose exec app php artisan migrate --seed
```

### 7. Generate API Documentation

This project uses Swagger for API documentation. Generate the documentation with this command:

```bash
docker-compose exec app php artisan l5-swagger:generate
```

### Setup Complete!

Your local development environment is now ready.

- **API URL**: `http://localhost:8000`
- **API Documentation**: `http://localhost:8000/api/documentation`
- **phpMyAdmin**: `http://localhost:8080`
