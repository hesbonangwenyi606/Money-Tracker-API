Money Tracker API
---
A backend-only Money Tracker API built with Laravel. It allows users to manage multiple wallets and transactions, automatically calculates balances, and returns clear JSON responses.

---
Features
---
Create users (no authentication required)
Create multiple wallets per user
Add income or expense transactions
View user profile with per-wallet balances and total balance
View a wallet with balance and paginated transactions
Validation, soft deletes, and consistent error responses

---
Tech
---

Laravel 12
PHP 8.3
Supports SQLite, MySQL, or Postgres

---
Setup
---

Clone the repo and install dependencies:

composer install
Copy environment file and generate app key:
cp .env.example .env

php artisan key:generate
Configure your database in .env, then run migrations:
php artisan migrate

Run the server:
php artisan serve

Run tests:
php artisan test

---
Models & Relationships
---

User → hasMany Wallet
Wallet → belongsTo User, hasMany Transaction
Transaction → belongsTo Wallet

---
API Endpoints
---

Base URL: /api
Users
Create User
POST /api/users

Body:
{
  "name": "Hesbon",
  "email": "hesbon@example.com"
}


Response:
{
  "id": 1,
  "name": "Hesbon",
  "email": "hesbon@example.com"
}


Get User Profile
GET /api/users/{user_id}
Response:
{
  "id": 1,
  "name": "Hesbon",
  "email": "hesbon@example.com",
  "wallets": [
    {
      "id": 2,
      "name": "My Wallet",
      "balance": "150.00",
      "currency": "USD"
    },
    {
      "id": 3,
      "name": "Personal Wallet",
      "balance": "0.00",
      "currency": "USD"
    }
  ],
  "total_balance": "150.00"
}

---
Wallets
---

Create Wallet
POST /api/users/{user_id}/wallets
Body:
{
  "name": "Savings",
  "description": "Emergency fund",
  "currency": "USD"
}


Response:
{
  "id": 4,
  "user_id": 1,
  "name": "Savings",
  "balance": "0.00",
  "currency": "USD"
}

---
Get Wallet with Transactions
---

GET /api/wallets/{wallet_id}?per_page=15
Response:
{
  "wallet": {
    "id": 2,
    "name": "My Wallet",
    "balance": "150.00",
    "currency": "USD"
  },
  "transactions": {
    "data": [
      {
        "id": 10,
        "type": "expense",
        "amount": "50.00",
        "description": "Groceries",
        "created_at": "2026-02-24T20:13:19Z"
      },
      {
        "id": 9,
        "type": "income",
        "amount": "200.00",
        "description": "Test deposit",
        "created_at": "2026-02-24T20:12:00Z"
      }
    ],
    "links": {...},
    "meta": {...}
  }
}

---
Transactions
---

Add Transaction
POST /api/wallets/{wallet_id}/transactions
Body:
{
  "type": "income",
  "amount": 100,
  "description": "Initial deposit"
}


Response:
{
  "id": 11,
  "wallet_id": 2,
  "type": "income",
  "amount": "100.00",
  "amount_cents": 10000,
  "description": "Initial deposit",
  "created_at": "2026-02-24T20:15:00Z"
}

---
Validation Rules
Resource	Field	Rules
User	name	required
	email	required, unique
Wallet	name	required
	currency	optional, default USD
Transaction	type	required, must be income or expense
	amount	required, positive number

---
Balance Rules
---

Income → adds to wallet balance
Expense → subtracts from wallet balance
Total balance → sums all wallet balances for the user

---
Amount Storage
---

API accepts decimal amounts (e.g., 12.34)
Stored in amount_cents to avoid floating-point errors
Responses include both amount and amount_cents

---
Error Format
---
Validation Error
{
  "message": "Validation failed.",
  "errors": {
    "field": ["Error message"]
  }
}

Resource Not Found
{
  "message": "Resource not found."
}

---
Notes
---
No authentication required
Soft deletes enabled for wallets and transactions
Supports multiple database backends (SQLite/MySQL/Postgres)