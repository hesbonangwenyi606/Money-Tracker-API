# 💰 Money Tracker API

A RESTful API built with **PHP Laravel** that allows users to manage multiple wallets and track income/expense transactions.

---

## 📋 Features

- Create user accounts (no authentication required)
- Create multiple wallets per user (e.g. Personal, Business, Savings)
- Add income and expense transactions to wallets
- View user profile with all wallets and balances
- View individual wallet with full transaction history
- Balances calculated dynamically: income adds, expenses subtract
- Full input validation with meaningful error messages
- JSON responses throughout

---

## 🗄️ Database Schema

```
users
  - id (PK)
  - name
  - email (unique)
  - timestamps

wallets
  - id (PK)
  - user_id (FK → users.id)
  - name
  - description (nullable)
  - timestamps

transactions
  - id (PK)
  - wallet_id (FK → wallets.id)
  - type (enum: income | expense)
  - amount (decimal, always positive)
  - description (nullable)
  - date
  - timestamps
```

---

## 🚀 Setup Instructions

### Prerequisites
- PHP >= 8.1
- Composer
- MySQL (or SQLite for quick testing)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/your-username/money-tracker-api.git
cd money-tracker-api

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_tracker
DB_USERNAME=root
DB_PASSWORD=your_password

# 6. Run migrations
php artisan migrate

# 7. (Optional) Seed with sample data
php artisan db:seed

# 8. Start the server
php artisan serve
```

> **Quick SQLite setup** (no MySQL needed):
> Set `DB_CONNECTION=sqlite` and `DB_DATABASE=/absolute/path/to/database/database.sqlite` in `.env`, then run `touch database/database.sqlite` before migrating.

---

## 📡 API Endpoints

Base URL: `http://localhost:8000/api`

---

### Users

#### `POST /api/users` — Create a User
```json
// Request Body
{
  "name": "Jane Doe",
  "email": "jane@example.com"
}

// Response 201
{
  "message": "User created successfully.",
  "data": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

#### `GET /api/users/{id}` — Get User Profile
```json
// Response 200
{
  "data": {
    "id": 1,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "total_balance": 9235.00,
    "wallets": [
      {
        "id": 1,
        "name": "Personal",
        "description": "Day-to-day personal expenses",
        "balance": 2185.00,
        "created_at": "2024-01-01T00:00:00.000000Z"
      },
      {
        "id": 2,
        "name": "Business",
        "description": "Business income and expenses",
        "balance": 7050.00,
        "created_at": "2024-01-01T00:00:00.000000Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### Wallets

#### `POST /api/users/{id}/wallets` — Create a Wallet
```json
// Request Body
{
  "name": "Business",
  "description": "Business income and expenses"
}

// Response 201
{
  "message": "Wallet created successfully.",
  "data": {
    "id": 2,
    "name": "Business",
    "description": "Business income and expenses",
    "balance": 0.00,
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

#### `GET /api/wallets/{id}` — Get Wallet Detail
```json
// Response 200
{
  "data": {
    "id": 1,
    "name": "Personal",
    "description": "Day-to-day personal expenses",
    "balance": 2185.00,
    "transactions": [
      {
        "id": 2,
        "type": "income",
        "amount": 200.00,
        "description": "Freelance side income",
        "date": "2024-01-15",
        "created_at": "2024-01-01T00:00:00.000000Z"
      },
      ...
    ],
    "created_at": "2024-01-01T00:00:00.000000Z"
  }
}
```

---

### Transactions

#### `POST /api/wallets/{id}/transactions` — Add a Transaction
```json
// Request Body
{
  "type": "income",
  "amount": 1500.00,
  "description": "Salary payment",
  "date": "2024-01-01"
}

// Response 201
{
  "message": "Transaction added successfully.",
  "data": {
    "transaction": {
      "id": 1,
      "type": "income",
      "amount": 1500.00,
      "description": "Salary payment",
      "date": "2024-01-01",
      "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "wallet_balance": 1500.00
  }
}
```

---

## ✅ Validation Rules

| Field   | Rules                                             |
|---------|---------------------------------------------------|
| `name`  | Required, string, max 255                         |
| `email` | Required, valid email, unique                     |
| `type`  | Required, must be `income` or `expense`           |
| `amount`| Required, numeric, must be **greater than zero**  |
| `date`  | Optional, must be a valid date (defaults to today)|

**Validation errors return 422:**
```json
{
  "message": "Validation failed.",
  "errors": {
    "amount": ["Amount must be a positive number greater than zero."],
    "type": ["Transaction type must be either \"income\" or \"expense\"."]
  }
}
```

---

## 📁 Project Structure

```
money-tracker-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── UserController.php
│   │   │   ├── WalletController.php
│   │   │   └── TransactionController.php
│   │   └── Requests/
│   │       ├── StoreUserRequest.php
│   │       ├── StoreWalletRequest.php
│   │       └── StoreTransactionRequest.php
│   └── Models/
│       ├── User.php
│       ├── Wallet.php
│       └── Transaction.php
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._create_wallets_table.php
│   │   └── ..._create_transactions_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
└── routes/
    └── api.php
```

---

## 🔄 Suggested Git Commit History

```
git commit -m "Initial Laravel project setup"
git commit -m "Created user, wallet and transaction migrations"
git commit -m "Added User, Wallet and Transaction models with relationships"
git commit -m "Added UserController with store and show methods"
git commit -m "Added WalletController with store and show methods"
git commit -m "Implemented TransactionController store method"
git commit -m "Added form request validation for all endpoints"
git commit -m "Defined API routes"
git commit -m "Added database seeder with sample data"
git commit -m "Added README with setup and API documentation"
```

---

## 📝 Notes

- **Balance calculation**: balances are calculated dynamically from transactions (not stored). This avoids data inconsistency.
- **No authentication**: As per the assessment brief, authentication is not required.
- **CORS**: Configured to allow all origins by default — adjust `config/cors.php` for production.
