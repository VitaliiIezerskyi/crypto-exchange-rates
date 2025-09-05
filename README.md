# Crypto Exchange Rates API

Web application providing API endpoints for cryptocurrency exchange rates (EUR to BTC, ETH, LTC) using Binance API as data source.

## Features

- Periodic exchange rate updates every 5 minutes via cron job
- REST API endpoints for retrieving historical rates
- Docker-based deployment with MySQL database
- Comprehensive logging and error handling
- Full type safety and validation

## Requirements

- Docker & Docker Compose
- PHP 8.4+ (handled by Docker)
- MySQL 8.0 (handled by Docker)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd crypto-exchange-rates
```

2. Copy environment configuration:
```bash
cp .env .env.local
cp .env.test .env.test.local
```

3. Install PHP dependencies:

```bash
composer install
```

4. Start the application:
```bash
docker-compose up -d
```

4. Run database migrations:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

## API Endpoints

### Get Last 24 Hours Exchange Rates

```
GET /api/rates/last-24h?pair=EUR/BTC
```

**Parameters:**
- `pair` (required): Currency pair. Supported values: `EUR/BTC`, `EUR/ETH`, `EUR/LTC`

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "currencyPair": "EUR/BTC",
        "rate": "96037.13000000",
        "createdAt": 1757067571
      }
    ],
    "page": 1,
    "perPage": 25,
    "total": 5
  }
}
```

### Get Exchange Rates for Specific Day

```
GET /api/rates/day?pair=EUR/BTC&date=2025-09-04
```

**Parameters:**
- `pair` (required): Currency pair. Supported values: `EUR/BTC`, `EUR/ETH`, `EUR/LTC`
- `date` (required): Date in YYYY-MM-DD format

**Response:**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "currencyPair": "EUR/ETH",
        "rate": "3767.48000000",
        "createdAt": 1757067571
      }
    ],
    "page": 1,
    "perPage": 25,
    "total": 5
  }
}
```

## Example Requests

```bash
# Get EUR/BTC rates for last 24 hours
curl "http://localhost/api/rates/last-24h?pair=EUR/BTC"

# Get EUR/ETH rates for specific day
curl "http://localhost/api/rates/day?pair=EUR/ETH&date=2025-09-05"

# Get EUR/LTC rates for specific day
curl "http://localhost/api/rates/day?pair=EUR/LTC&date=2025-09-05"
```

## Response Format

All API responses follow a consistent format with success/error indicators and pagination:

**Success Response:**
```json
{
  "success": true,
  "data": {
    "items": [],
    "page": 1,
    "perPage": 25,
    "total": 100
  }
}
```

**Error Response:**
```json
{
  "success": false,
  "error": {
    "code": 404,
    "message": "Invalid currency pair. Supported pairs: \"EUR/BTC\", \"EUR/ETH\", \"EUR/LTC\"",
    "description": "Invalid currency pair. Supported pairs: \"EUR/BTC\", \"EUR/ETH\", \"EUR/LTC\""
  }
}
```

**Pagination Parameters:**
- `page` (optional): Page number (default: 1)  
- `limit` (optional): Items per page (default: 25)

## Manual Rate Update

You can manually trigger exchange rate updates:

```bash
docker-compose exec php php bin/console app:update-exchange-rates
```

### Code Quality

```bash
# PHP CS Fixer
docker-compose exec php vendor/bin/php-cs-fixer fix

# PHPStan
docker-compose exec php vendor/bin/phpstan analyse
```

### Database Commands

```bash
# Create new migration
docker-compose exec php php bin/console make:migration

# Run migrations
docker-compose exec php php bin/console doctrine:migrations:migrate

# Check migration status
docker-compose exec php php bin/console doctrine:migrations:status
```

## Architecture

- **Entity**: `ExchangeRate` - Stores exchange rate data
- **Repository**: `ExchangeRateRepository` - Data access layer
- **Services**: 
  - `BinanceApiClient` - Fetches rates from Binance API
  - `ExchangeRateService` - Business logic for rate management
- **Command**: `UpdateExchangeRatesCommand` - Console command for rate updates
- **Controller**: `ExchangeRateController` - API endpoints
