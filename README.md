# Secure API with Authentication and Encryption in Laravel

This is a Laravel project that provides a secure RESTful API for managing articles. It includes user authentication, CRUD operations on articles, and encryption/decryption endpoints.

## Features

-   User registration and login with API tokens (Laravel Sanctum).
-   CRUD endpoints for articles.
-   Authorization to ensure users can only manage their own articles.
-   API rate limiting to prevent abuse.
-   API resources for structured JSON responses.
-   Encryption and decryption endpoints using the user's API token.

## Setup Instructions

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/piyushverma2001/rfni-task.git
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Configure your database:**
    Open the `.env` file and set up your database connection details (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

6.  **Run the database migrations:**
    ```bash
    php artisan migrate
    ```

7.  **Start the development server:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.

## API Endpoints

All endpoints are prefixed with `/api`.

### Authentication

*   **`POST /register`** - Register a new user.
    *   **Body:** `name` (string), `email` (string), `password` (string), `password_confirmation` (string)
    *   **Response:** `201` with user data.

*   **`POST /login`** - Login a user.
    *   **Body:** `email` (string), `password` (string)
    *   **Response:** `200` with an `access_token`.

### Articles (Authentication Required)

*   **`GET /articles`** - Retrieve a list of articles for the authenticated user.
*   **`POST /articles`** - Create a new article.
    *   **Body:** `title` (string), `content` (string)
*   **`GET /articles/{id}`** - Retrieve a single article.
*   **`PUT /articles/{id}`** - Edit an article.
    *   **Body:** `title` (string, optional), `content` (string, optional)
*   **`DELETE /articles/{id}`** - Delete an article.

### Encryption (Authentication Required)

*   **`POST /encrypt`** - Encrypt request data.
    *   **Body:** `data` (any) - The data to be encrypted.
    *   **Response:** Encrypted data string.

*   **`POST /decrypt`** - Decrypt response data.
    *   **Body:** `data` (string) - The encrypted data string from a response.
    *   **Response:** Decrypted data.

### How to use Encryption/Decryption

The encryption and decryption functionalities are tied to the user's API token.

1.  When you log in, you receive an `access_token`.
2.  Use this token in the `Authorization` header for all protected requests (`Bearer <token>`).
3.  The `/encrypt` and `/decrypt` endpoints use this token to derive an encryption key.
4.  If you log out and log in again, you will receive a new token. The new token will not be able to decrypt data encrypted with the old token.
