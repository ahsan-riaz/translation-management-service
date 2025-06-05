# Translation Management Service (Laravel 12)

A scalable and efficient RESTful API to manage multilingual translations, built using Laravel 12. It supports CRUD operations, tagging, filtering, and exporting translation strings.

---

## Features

- Manage translation keys, values, and associated tags
- Locale-based export in JSON format
- Token-based API authentication (no OAuth)
- Optimized for high performance (response time < 200ms for general use)
- Scalability tested with 100,000+ records
- Dockerized for easy development

---

## Tech Stack

- Laravel 12 (Framework)
- MySQL 8 (Database)
- PHP 8.2 (via Docker)
- Nginx (Web server)
- PHPUnit / Pest (Testing)
- Token-based authentication (custom middleware)

---

## Installation (Dockerized)

1. **Clone the repository**

```bash
git clone https://github.com/your-username/translation-service.git
cd translation-service
````

2. **Copy environment file**

```bash
cp .env.example .env
```

3. **Start the Docker containers**

```bash
docker-compose up -d --build
```

4. **Install dependencies and run migrations**

```bash
docker exec -it laravel_app bash
composer install
php artisan migrate --seed
```

5. **Access the app**

```text
http://localhost:8080
```

---

## Authentication

This service uses **token-based auth** via an `Authorization: Bearer <token>` header.

### Example Token Setup

```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['api_token' => hash('sha256', 'your-token')]);
```

Then use the token in API requests:

```http
Authorization: Bearer your-token
```

---

## API Endpoints

All endpoints are prefixed with `/api/translations`.

| Method | Endpoint                            | Description                                               | Auth Required |
| ------ | ----------------------------------- | --------------------------------------------------------- | ------------- |
| GET    | `/api/translations`                 | List translations (filterable by `key`, `content`, `tag`) | ✅             |
| POST   | `/api/translations`                 | Create a new translation                                  | ✅             |
| GET    | `/api/translations/{id}`            | View a specific translation                               | ✅             |
| PUT    | `/api/translations/{id}`            | Update a translation                                      | ✅             |
| GET    | `/api/translations/export/{locale}` | Export all translations for a locale (JSON)               | ✅             |

---

### Export Example

```bash
curl -H "Authorization: Bearer your-token" \
     http://localhost:8080/api/translations/export/en
```

**Response:**

```json
{
  "greeting.hello": "Hello",
  "homepage.title": "Welcome"
}
```

---

## Scalability

* Includes a factory-based seed command to generate 100,000+ translation records for load testing.

Run with:

```bash
php artisan db:seed --class=LargeTranslationSeeder
```

---

## Running Tests

```bash
docker exec -it laravel_app php artisan test
```

## Test Users

You can generate test users via:

```bash
php artisan tinker
>>> \App\Models\User::factory()->create(['api_token' => hash('sha256', 'testtoken')]);
```

Use this in requests:

```http
Authorization: Bearer testtoken
```

---

## Docker Summary

| Service | URL                                            | Port |
| ------- | ---------------------------------------------- | ---- |
| App     | [http://localhost:8080](http://localhost:8080) | 8080 |
| MySQL   | Internal only                                  | 3306 |

