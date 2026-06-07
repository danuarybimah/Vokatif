# Vokatif — Event & Ticketing Platform

Platform manajemen event berbasis Laravel dengan REST API, JWT Authentication, QR Ticket System, dan Organizer Dashboard.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 |
| Authentication | JWT (tymon/jwt-auth) + API Key |
| Database | MySQL |
| Frontend | Blade + Tailwind CSS |
| PDF | barryvdh/laravel-dompdf |
| QR Code | simplesoftwareio/simple-qrcode |

---

## Setup & Installation

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Copy environment file
cp .env.example .env

# 3. Generate keys
php artisan key:generate
php artisan jwt:secret

# 4. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vokatif
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations & seeders
php artisan migrate --seed

# 6. Create storage symlink
php artisan storage:link

# 7. Start development servers
php artisan serve          # Backend: http://127.0.0.1:8000
npm run dev                # Frontend asset compiler
```

---

## Authentication Overview

Vokatif menggunakan **dua sistem autentikasi terpisah**:

| Tipe | Header | Digunakan untuk |
|---|---|---|
| JWT Bearer | `Authorization: Bearer <token>` | User, Organizer, Admin |
| API Key | `X-VOKATIF-KEY: vokatif_demo_key_2026` | Public API access |

---

## Akun Demo (Seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@vokatif.test | password |
| Organizer | organizer@vokatif.test | password |
| User | user@vokatif.test | password |

---

## API Base URL

```
http://127.0.0.1:8000/api/v1
```

---

# Panduan Pengujian API via Postman

## Collection Setup

Di Postman, buat **Collection** bernama `Vokatif API` dengan dua variable:

| Variable | Value |
|---|---|
| `base_url` | `http://127.0.0.1:8000/api/v1` |
| `token` | *(diisi setelah login)* |

---

## 1. Health Check

### GET /health

Cek apakah server berjalan.

```
GET {{base_url}}/health
```

**Response (200):**
```json
{
    "success": true,
    "message": "Vokatif API is running",
    "version": "1.0"
}
```

---

## 2. Authentication (JWT)

### POST /auth/login — Login User

```
POST {{base_url}}/auth/login
Content-Type: application/json
```

**Body (raw JSON):**
```json
{
    "email": "user@vokatif.test",
    "password": "password"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 3,
            "name": "Test User",
            "email": "user@vokatif.id",
            "role": "user"
        }
    }
}
```

> Simpan nilai `token` ke variable Postman `{{token}}`

---

### GET /auth/me — Profil Saya

```
GET {{base_url}}/auth/me
Authorization: Bearer {{token}}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 3,
        "name": "Test User",
        "email": "user@vokatif.id",
        "role": "user"
    }
}
```

---

### POST /auth/logout — Logout

```
POST {{base_url}}/auth/logout
Authorization: Bearer {{token}}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

---

### POST /auth/refresh — Refresh Token

```
POST {{base_url}}/auth/refresh
Authorization: Bearer {{token}}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1Qi...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

## 3. Public Events (API Key)

### GET /events — Daftar Event

```
GET {{base_url}}/events
X-VOKATIF-KEY: vokatif_demo_key_2026
```

**Query Parameters (opsional):**

| Param | Contoh | Keterangan |
|---|---|---|
| `search` | `?search=tech` | Cari berdasarkan judul |
| `category` | `?category=technology` | Filter kategori |
| `city` | `?city=jakarta` | Filter kota |

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Vokatif Tech Summit 2026",
            "slug": "vokatif-tech-summit-2026",
            "city": "Jakarta",
            "start_at": "2026-05-27T09:00:00",
            "category": { "name": "Technology" },
            "ticket_types": [
                { "name": "Early Bird", "price": 150000, "quota": 100 }
            ]
        }
    ]
}
```

---

### GET /events/{slug} — Detail Event

```
GET {{base_url}}/events/vokatif-tech-summit-2026
X-VOKATIF-KEY: vokatif_demo_key_2026
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Vokatif Tech Summit 2026",
        "description": "Konferensi teknologi modern...",
        "city": "Jakarta",
        "location": "Grand City Hall",
        "start_at": "2026-05-27T09:00:00",
        "ticket_types": [
            {
                "id": 1,
                "name": "Early Bird",
                "price": 150000,
                "quota": 100,
                "sold": 45
            }
        ]
    }
}
```

---

## 4. Order & Pembayaran

### POST /orders — Buat Order (Beli Tiket)

```
POST {{base_url}}/orders
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body:**
```json
{
    "event_id": 1,
    "items": [
        {
            "ticket_type_id": 1,
            "quantity": 2
        }
    ]
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Order created successfully",
    "data": {
        "order_id": "ORD-2026-001",
        "event": "Vokatif Tech Summit 2026",
        "total_amount": 300000,
        "payment_status": "pending",
        "items": [
            {
                "ticket_type": "Early Bird",
                "quantity": 2,
                "subtotal": 300000
            }
        ]
    }
}
```

---

### POST /orders/{id}/pay — Simulasi Pembayaran

```
POST {{base_url}}/orders/1/pay
Authorization: Bearer {{token}}
Content-Type: application/json
```

**Body:**
```json
{
    "payment_method": "transfer"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Payment successful",
    "data": {
        "order_id": "ORD-2026-001",
        "payment_status": "paid",
        "tickets": [
            {
                "ticket_code": "VOK-2026-ABCDE",
                "status": "active"
            }
        ]
    }
}
```

---

### GET /orders — Riwayat Order Saya

```
GET {{base_url}}/orders
Authorization: Bearer {{token}}
```

---

### GET /orders/{id} — Detail Order

```
GET {{base_url}}/orders/1
Authorization: Bearer {{token}}
```

---

## 5. Tiket User

### GET /tickets — Semua Tiket Saya

```
GET {{base_url}}/tickets
Authorization: Bearer {{token}}
```

**Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "ticket_code": "VOK-2026-ABCDE",
            "event": "Vokatif Tech Summit 2026",
            "ticket_type": "Early Bird",
            "status": "active",
            "qr_url": "/api/v1/tickets/VOK-2026-ABCDE/qr"
        }
    ]
}
```

---

### GET /tickets/{code} — Detail Tiket

```
GET {{base_url}}/tickets/VOK-2026-ABCDE
Authorization: Bearer {{token}}
```

---

## 6. Organizer Endpoints

### Login sebagai Organizer

```
POST {{base_url}}/auth/login

{
    "email": "organizer@vokatif.test",
    "password": "password"
}
```

> Simpan token organizer ke variable `{{org_token}}`

---

### POST /organizer/checkin — Check-in Tiket via QR

```
POST {{base_url}}/organizer/checkin
Authorization: Bearer {{org_token}}
Content-Type: application/json
```

**Body:**
```json
{
    "ticket_code": "VOK-2026-ABCDE"
}
```

**Response sukses (200):**
```json
{
    "success": true,
    "message": "Check-in berhasil",
    "data": {
        "ticket_code": "VOK-2026-ABCDE",
        "attendee": "Test User",
        "event": "Vokatif Tech Summit 2026",
        "checked_at": "2026-05-27T09:15:00"
    }
}
```

**Response gagal — tiket sudah digunakan (422):**
```json
{
    "success": false,
    "message": "Ticket sudah digunakan"
}
```

**Response gagal — tiket tidak valid (404):**
```json
{
    "success": false,
    "message": "Ticket tidak ditemukan"
}
```

---

### GET /organizer/overview — Statistik Organizer

```
GET {{base_url}}/organizer/overview
Authorization: Bearer {{org_token}}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "total_events": 5,
        "total_tickets_sold": 234,
        "total_revenue": 35100000,
        "total_checkins": 180
    }
}
```

---

## 7. Admin Endpoints

### Login sebagai Admin

```
POST {{base_url}}/auth/login

{
    "email": "admin@vokatif.test",
    "password": "password"
}
```

---

### GET /admin/overview — Statistik Admin

```
GET {{base_url}}/admin/overview
Authorization: Bearer {{admin_token}}
```

**Response (200):**
```json
{
    "success": true,
    "data": {
        "total_users": 150,
        "total_events": 30,
        "total_orders": 800,
        "total_revenue": 120000000
    }
}
```

---

## 8. Tes API Key (Public)

### GET /events dengan API Key

```
GET {{base_url}}/events
X-VOKATIF-KEY: vokatif_demo_key_2026
```

### GET /health tanpa auth

```
GET {{base_url}}/health
```

---

## Status Codes Referensi

| Code | Arti |
|---|---|
| 200 | OK — Request berhasil |
| 201 | Created — Data berhasil dibuat |
| 401 | Unauthorized — Token tidak valid atau tidak ada |
| 403 | Forbidden — Akses ditolak (role tidak sesuai) |
| 404 | Not Found — Data tidak ditemukan |
| 422 | Unprocessable — Validasi gagal |
| 500 | Internal Server Error |

---

## Flow Lengkap: Beli Tiket sampai Check-in

```
1. [POST] /auth/login              → Dapat token JWT
2. [GET]  /events                  → Lihat daftar event (gunakan API Key)
3. [GET]  /events/{slug}           → Lihat detail event & harga tiket
4. [POST] /orders                  → Buat order (butuh JWT)
5. [POST] /orders/{id}/pay         → Bayar order (simulasi)
6. [GET]  /tickets                 → Lihat tiket yang sudah dibeli
7. [GET]  /tickets/{code}          → Lihat detail + QR Code tiket
8. [POST] /organizer/checkin       → Scan QR oleh organizer (butuh JWT organizer)
```

---

## Postman Collection Import

Kamu bisa import collection berikut ke Postman:

1. Buka Postman → **Import**
2. Pilih **Raw Text** dan paste JSON collection
3. Set variable `base_url` = `http://127.0.0.1:8000/api/v1`
4. Jalankan **Login** terlebih dahulu untuk mendapatkan `token`

---

## Web Dashboard URL

| Halaman | URL | Role |
|---|---|---|
| Landing Page | `/` | Public |
| Explore Events | `/events` | Public |
| User Home | `/home` | User |
| My Tickets | `/my-tickets` | User |
| Download Sertifikat | `/my-tickets/{code}/certificate` | User (tiket USED) |
| Organizer Dashboard | `/organizer/dashboard` | Organizer |
| Organizer QR Scanner | `/organizer/checkin-scanner` | Organizer |
| Peserta Check-in | `/organizer/checkin-participants` | Organizer |
| Admin Dashboard | `/admin/dashboard` | Admin |
| Admin Users | `/admin/users` | Admin |
| Admin Events | `/admin/events` | Admin |
| Admin Pesan | `/admin/messages` | Admin |

---

## Catatan Penting

- **JWT token** expired setelah 1 jam — gunakan `/auth/refresh` untuk perpanjang
- **API Key** (`vokatif_demo_key_2026`) hanya untuk endpoint public, bukan pengganti JWT
- Sertifikat PDF hanya tersedia untuk tiket dengan status `used` pada kategori **Technology**, **Business**, atau **Education**
- QR Code berisi `ticket_code` string langsung (bukan JSON) untuk kompatibilitas scanner
- Semua API response menggunakan format standar: `{ success, message, data }`
