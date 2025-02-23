# PHP JWT Login API
This is a simple PHP API application that provides user authentication using JSON Web Tokens (JWT). It validates incoming login requests and returns a JWT token upon successful login.

## Technologies Used
- PHP
- Laravel (Illuminate components)
- JWT (firebase/php-jwt)
- Docker

## generate jwt token:
echo "JWT_SECRET=$(openssl rand -base64 32)" >> .env

1. **Clone the Repository**
   git clone https://github.com/Amrou91/test-php-project.git
   cd test-php-project
   docker-compose up -d --build
