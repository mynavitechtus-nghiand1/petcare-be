# PetCare+ Backend

REST API for a pet care e-commerce platform. Built with Laravel 12, PostgreSQL, Redis, and Docker.

## Stack

| Layer | Technology |
|---|---|
| Runtime | PHP 8.4 + Laravel 12 |
| Database | PostgreSQL 16 |
| Cache | Redis |
| Auth | Laravel Sanctum (access + refresh token) |
| OAuth | Google via Laravel Socialite |
| Container | Docker (nginx + php-fpm + postgres + redis) |
| Tests | Pest |

## Quick Start

Requires Docker Desktop running.

```bash
make start        # build + migrate + seed
make test         # run Pest test suite
make shell        # open shell inside app container
make stop         # stop all containers
```

Health check: `GET http://localhost:8080/api/v1/health`

## API Endpoints

All routes are prefixed `/api/v1`.

### Auth

| Method | Path | Description |
|---|---|---|
| POST | `/auth/register` | Register, returns access + refresh token |
| POST | `/auth/login` | Login by email/password |
| POST | `/auth/refresh` | Rotate refresh token |
| GET | `/auth/google` | Get Google OAuth redirect URL |
| GET | `/auth/google/callback` | Google OAuth callback |

### Products

| Method | Path | Description |
|---|---|---|
| GET | `/products` | List published products (paginated, cached) |
| GET | `/products/{id}` | Product detail |

Query params: `?brand_id=`, `?category_id=`, `?q=` (search), `?page=`, `?per_page=`

### Cart

| Method | Path | Description |
|---|---|---|
| GET | `/cart` | View cart |
| POST | `/cart/items` | Add item |
| PATCH | `/cart/items/{id}` | Update quantity |
| DELETE | `/cart/items/{id}` | Remove item |

### Orders

| Method | Path | Description |
|---|---|---|
| POST | `/orders/checkout` | Checkout cart (supports voucher_code) |
| GET | `/orders` | Order history |
| GET | `/orders/{id}` | Order detail |

### Admin (requires admin role)

| Method | Path | Description |
|---|---|---|
| GET | `/admin/products` | List all products |
| POST | `/admin/products` | Create product |
| PATCH | `/admin/products/{id}` | Update product |
| DELETE | `/admin/products/{id}` | Delete product |

## Authentication

Tokens are issued at login/register:

```json
{
  "access_token": "...",   
  "refresh_token": "...",  
  "token_type": "Bearer"
}
```

- Access token expires in **15 minutes**
- Refresh token expires in **30 days** (ability: `auth:refresh`)
- Rate limit on login: **5 attempts/minute per IP**

Pass token in header: `Authorization: Bearer <access_token>`

## Database Schema

```
users           — id, name, email, password, role (customer|admin)
brands          — id, name, slug, is_active
categories      — id, name, slug
products        — id, brand_id, name, slug, sku, status (published|draft)
product_categories — product_id, category_id
product_prices  — id, product_id, currency, amount
inventories     — id, product_id, quantity, version (optimistic lock)
carts           — id, user_id
cart_items      — id, cart_id, product_id, quantity
vouchers        — id, code, discount_amount, expires_at, is_active
orders          — id, user_id, voucher_id, status, total_amount
order_items     — id, order_id, product_id, quantity, unit_price
payments        — id, order_id, amount, status, method
```

## Environment

Copy `.env.example` to `.env` and fill in:

```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8080/api/v1/auth/google/callback
```

Default seed credentials:
- Admin: `admin@petcare.com` / `password`
- Customer: `learner@example.com` / `password`
