# Online Shop Project

## Description

A simple online shop offering features such as:

* User registration and login using JWT (JSON Web Tokens).
* Product management (adding, deleting, fetching collections and single products).
* Category management (adding, fetching lists and single categories).
* Product rating system for logged-in users.
* Placing orders by logged-in users.
* Retrieving order history for the logged-in user.
* Stock management (updating and setting quantities).
* Role-based access control (e.g., ROLE_ADMIN for certain operations).

## Tech Stack

* **Backend:** PHP (>=7.2.5), Symfony (5.3.*), Doctrine, PostgreSQL, LexikJWTAuthenticationBundle, GesdinetJWTRefreshTokenBundle.
* **Frontend:** JavaScript, Vue.js (^3.0.0), Vue Router (^4.0.0-0), HTML, CSS.

## Setup & Configuration

### Backend (API Server - `Api` directory)

1.  Navigate to the `Api` directory.
2.  Install dependencies:
    ```bash
    composer install
    ```
   
3.  Configure environment variables. Create a `.env` file (or `.env.local`) and set variables like `DATABASE_URL`, `APP_SECRET`, `JWT_SECRET_KEY`, `JWT_PUBLIC_KEY`. You can base it on `.env.dist` if it exists, or standard Symfony variables.
4.  Configure the database and run migrations:
    ```bash
    php bin/console doctrine:database:create # If the database does not exist
    php bin/console doctrine:migrations:migrate
    ```
   
5.  Start the Symfony server:
    ```bash
    symfony server:start
    ```
    Alternatively, configure a traditional web server (e.g., Apache, Nginx).

* **Optional (Docker):** The project includes Docker configuration.
    1.  Configure variables in the `.env` file according to Docker needs (e.g., `POSTGRES_DB`, `POSTGRES_PASSWORD`, `POSTGRES_USER`).
    2.  Start the containers:
        ```bash
        docker-compose up -d
        ```
       

### Frontend (Client Application - `front-end` directory)

1.  Navigate to the `front-end` directory.
2.  Install dependencies:
    ```bash
    npm install
    ```
   
3.  Configure the backend API URL. This likely requires modifying the `src/assets/env.js` file or setting an environment variable (e.g., `VUE_APP_API_URL`) and adjusting the code.
4.  Start the development server:
    ```bash
    npm run serve
    ```
   
5.  Compile and minify for production:
    ```bash
    npm run build
    ```
   

## API Endpoints

API endpoints are defined in `Api/config/routes.yaml` and documented in the `Api/docs/` directory. Access to some endpoints requires JWT authentication and appropriate roles (e.g., ROLE_ADMIN).

### Authentication & User (API)

<details>
<summary><code>POST /api/auth/token</code> - Login and generate JWT token</summary>

**Request Body:**
```json
{
  "username": "String", // Username
  "password": "String"  // Password
}
```
**Response Body (success):**
```json
{
  "token": "String",         // JWT Token
  "refresh_token": "String" // Refresh Token
}
```

</details>

<details>
<summary><code>POST /api/auth/refresh-token</code> - Refresh JWT token</summary>

**Request Body:**
```json
{
  "refresh_token": "String" // Refresh token obtained during login
}
```
**Response Body (success):**
```json
{
  "token": "String",         // New JWT Token
  "refresh_token": "String" // New Refresh Token
}
```

</details>

<details>
<summary><code>POST /api/auth/register</code> - Register a new user</summary>

**Request Body:**
```json
{
  "username": "String", // Username (6-13 characters)
  "password": "String", // Password (min 8 characters)
  "passwordRepeat": "String" // Repeat password
}
```
</details>

<details>
<summary><code>POST /api/user/get-login</code> - Get user information</summary>

**Requires JWT token in `Authorization: Bearer {token}` header.**

**Response Body (success):**
```json
{
  "username": "String",
  "roles": ["String"] // e.g., ["ROLE_USER"]
}
```
</details>

<details>
<summary><code>POST /api/user/change-password</code> - Change password</summary>

**Requires JWT token in `Authorization: Bearer {token}` header.**

**Request Body:**
```json
{
  "oldPassword": "String", // Current password
  "newPassword": "String", // New password
  "newPasswordRepeat": "String" // Repeat new password
}
```
</details>

### Products (API)

<details>
<summary><code>GET /api/product/collection</code> - Get product collection (with pagination)</summary>

**Query Parameters:** `page` (int, optional, default 1), `limit` (int, optional, default 10), `category` (int, optional)
</details>

<details>
<summary><code>GET /api/product/rated</code> - Get top-rated products</summary>
<br>
</details>

<details>
<summary><code>GET /api/product/{id}</code> - Get a single product</summary>
<br>
</details>

<details>
<summary><code>POST /api/product</code> - Add a new product</summary>

**Requires `ROLE_ADMIN` role.**

**Request Body:**
```json
{
  "name": "String", // Product name
  "description": "String", // Description
  "price": "Number", // Price
  "category": "Number" // Category ID
}
```
</details>

<details>
<summary><code>DELETE /api/product/{id}</code> - Delete a product</summary>

**Requires `ROLE_ADMIN` role.**
</details>

### Categories (API)

<details>
<summary><code>GET /api/category/all/{visible}</code> - Get category collection</summary>

**Path Parameter:** `visible` (0 or 1) - whether to fetch only visible categories
</details>

<details>
<summary><code>GET /api/category/{id}</code> - Get a single category</summary>
<br>
</details>

<details>
<summary><code>POST /api/category</code> - Add a new category</summary>

**Requires `ROLE_ADMIN` role.**

**Request Body:**
```json
{
  "name": "String", // Category name
  "visible": "Boolean" // Is visible
}
```
</details>

### Orders (API)

<details>
<summary><code>POST /api/order</code> - Place a new order</summary>

**Requires authentication (JWT).**

**Request Body:**
```json
{
  "products": ["Number"] // List of product IDs
}
```
</details>

<details>
<summary><code>GET /api/order/user</code> - Get user orders</summary>

**Requires authentication (JWT).**
</details>

### Ratings (API)

<details>
<summary><code>POST /api/rate/{id}/{rating}</code> - Add a rating to a product</summary>

**Requires authentication (JWT).**

**Path Parameters:** `id` (Product ID), `rating` (Rating 1-5)
</details>

### Stock (API)

<details>
<summary><code>PATCH /api/stock/update/{productId}/{amount}</code> - Update stock level</summary>

**Requires `ROLE_ADMIN` role.**

**Path Parameters:** `productId` (Product ID), `amount` (Amount to add/subtract)
</details>

<details>
<summary><code>PATCH /api/stock/set/{productId}/{amount}</code> - Set stock level</summary>

**Requires `ROLE_ADMIN` role.**

**Path Parameters:** `productId` (Product ID), `amount` (New amount)
</details>

## Additional Information

* The application uses JWT (JSON Web Tokens) for authentication via LexikJWTAuthenticationBundle and GesdinetJWTRefreshTokenBundle.
* Role-based access control is implemented (e.g., `ROLE_USER`, `ROLE_ADMIN`).
* Database interaction is handled via Doctrine ORM.
* Docker configuration is available for easier setup (`docker-compose.yml`).
