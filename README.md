## Application Overview
This application is a simple RESTful API built using PHP, which allows users to interact with various endpoints for generating UUIDs, user authentication, and accessing protected resources. The application utilizes the following technologies:

- PHP: The server-side scripting language used to create the API.
- FastRoute: A fast routing library that maps HTTP requests to specific controllers and actions based on the request method and URI.
- Dotenv: A library to load environment variables from a .env file, allowing for secure management of configuration settings.
- Whoops: A PHP error handler that provides a detailed error page for development, making it easier to debug issues.

## Technologies Used
- PHP
- JWT (firebase/php-jwt)
- Docker

## Key Features
- UUID Generation: The application provides an endpoint to generate unique identifiers (UUIDs) using the UuidController.
- User Authentication: Users can log in through a designated endpoint, handled by the AuthController, which validates user credentials.
- Protected Routes: Some routes require authentication, ensuring that only authorized users can access certain resources.

## generate jwt token:
echo "JWT_SECRET=$(openssl rand -base64 32)" >> .env

## Clone the Repository
- git clone https://github.com/Amrou91/test-php-project.git
- cd test-php-project
- docker-compose up -d --build

## API Endpoints
- GET /api/uuid: Generates a new UUID.
- POST /api/login: Authenticates the user and returns a session token.
- GET /api/protected: Accesses a protected route that requires authentication.

## Curl
- curl -X GET http://localhost:8000/api/uuid

- curl -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d "{\"username\": \"admin\", \"password\": \"secret\"}"

- curl -X GET http://localhost:8000/api/protected -H "Authorization: Bearer your_token"

