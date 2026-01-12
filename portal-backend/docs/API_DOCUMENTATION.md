# REST API v1 Documentation

## Overview

REST API v1 untuk Web Portal dengan support untuk Android/iOS mobile app.

- **Base URL**: `http://localhost:8000/api/v1`
- **Authentication**: Bearer Token (Laravel Sanctum)
- **Rate Limiting**: 
  - Public: 60 requests/minute
  - Authenticated: 120 requests/minute

---

## Authentication

### Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123",
  "device_name": "Android App" // optional
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "avatar": "http://...",
      "role": "member"
    },
    "token": "1|abcd1234...",
    "token_type": "Bearer"
  }
}
```

### Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "user@example.com",
  "password": "Password123",
  "password_confirmation": "Password123",
  "phone": "081234567890" // optional
}
```

### Get Current User
```http
GET /api/v1/auth/user
Authorization: Bearer {token}
```

### Update Profile
```http
PUT /api/v1/auth/user
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Updated",
  "phone": "081234567890",
  "bio": "My bio text"
}
```

### Change Password
```http
PUT /api/v1/auth/password
Authorization: Bearer {token}
Content-Type: application/json

{
  "current_password": "oldPassword123",
  "password": "newPassword123",
  "password_confirmation": "newPassword123"
}
```

### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### Logout All Devices
```http
POST /api/v1/auth/logout-all
Authorization: Bearer {token}
```

---

## Articles

### List Articles (Public)
```http
GET /api/v1/articles
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search by title/excerpt |
| `category` | string | Filter by category slug |
| `tag` | string | Filter by tag slug |
| `sort` | string | `latest` (default), `popular`, `oldest` |
| `per_page` | int | Items per page (max 50) |
| `page` | int | Page number |

### Get Featured Articles
```http
GET /api/v1/articles/featured?limit=5
```

### Get Popular Articles
```http
GET /api/v1/articles/popular?limit=10
```

### Get Article Detail (Public)
```http
GET /api/v1/articles/{slug}
```

### Create Article (Auth Required)
```http
POST /api/v1/articles
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Judul Artikel",
  "excerpt": "Ringkasan artikel",
  "content": "<p>Konten artikel...</p>",
  "category_id": 1,
  "tags": [1, 2, 3],
  "status": "draft", // draft, pending, published
  "thumbnail": "path/to/image.jpg",
  "meta_title": "SEO Title",
  "meta_description": "SEO Description"
}
```

### Update Article (Auth Required)
```http
PUT /api/v1/articles/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Updated Title",
  "status": "published"
}
```

### Delete Article (Auth Required)
```http
DELETE /api/v1/articles/{id}
Authorization: Bearer {token}
```

### Toggle Like (Auth Required)
```http
POST /api/v1/articles/{slug}/like
Authorization: Bearer {token}
```

---

## Categories

### List Categories (Public)
```http
GET /api/v1/categories
GET /api/v1/categories?with_articles=true
```

### Get Category with Articles (Public)
```http
GET /api/v1/categories/{slug}
```

### Create Category (Admin Only)
```http
POST /api/v1/categories
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Kategori Baru",
  "description": "Deskripsi kategori",
  "color": "#10B981",
  "icon": "folder"
}
```

### Update Category (Admin Only)
```http
PUT /api/v1/categories/{id}
Authorization: Bearer {token}
```

### Delete Category (Admin Only)
```http
DELETE /api/v1/categories/{id}
Authorization: Bearer {token}
```

---

## Tags

### List Tags (Public)
```http
GET /api/v1/tags
GET /api/v1/tags?search=keyword&sort=popular&limit=20
```

### Get Tag with Articles (Public)
```http
GET /api/v1/tags/{slug}
```

### Create/Update/Delete Tag (Admin Only)
Same pattern as Categories.

---

## Gallery

### List Gallery Items (Public)
```http
GET /api/v1/gallery
```

**Query Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `type` | string | `image` or `video` |
| `album` | string | Filter by album name |
| `featured` | boolean | Featured items only |
| `sort` | string | `latest`, `oldest`, `event_date` |
| `per_page` | int | Items per page (max 50) |

### Get Albums List
```http
GET /api/v1/gallery/albums
```

### Get Gallery Item
```http
GET /api/v1/gallery/{id}
```

### Upload Gallery (Admin Only)
```http
POST /api/v1/gallery
Authorization: Bearer {token}
Content-Type: multipart/form-data

title: Image Title
description: Description
image: [file]
album: Album Name
event_date: 2026-01-12
is_featured: true
is_published: true
```

---

## Comments

### Get Article Comments (Public)
```http
GET /api/v1/articles/{slug}/comments
```

### Add Comment (Auth Required)
```http
POST /api/v1/articles/{slug}/comments
Authorization: Bearer {token}
Content-Type: application/json

{
  "comment_text": "Komentar saya...",
  "parent_id": null // for reply, set parent comment ID
}
```

### Update Comment (Owner Only, 15 min limit)
```http
PUT /api/v1/comments/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "comment_text": "Komentar yang diedit..."
}
```

### Delete Comment (Owner/Admin)
```http
DELETE /api/v1/comments/{id}
Authorization: Bearer {token}
```

---

## Site Settings (Public)
```http
GET /api/v1/settings
```

**Response:**
```json
{
  "success": true,
  "data": {
    "site_name": "Web Portal",
    "site_tagline": "Tagline",
    "site_logo": "http://...",
    "contact_email": "contact@example.com",
    "social_links": {
      "facebook": "https://facebook.com/...",
      "instagram": "https://instagram.com/..."
    }
  }
}
```

---

## Users (Admin Only)

### List Users
```http
GET /api/v1/users
Authorization: Bearer {token}
```

### Get User Profile (Public)
```http
GET /api/v1/users/{id}
```

### Update User (Admin Only)
```http
PUT /api/v1/users/{id}
Authorization: Bearer {token}
```

### Delete User (Admin Only)
```http
DELETE /api/v1/users/{id}
Authorization: Bearer {token}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Anda tidak memiliki izin untuk..."
}
```

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email sudah digunakan"]
  }
}
```

### 429 Too Many Requests (Rate Limit)
```json
{
  "message": "Too Many Attempts."
}
```

---

## Android/Mobile Implementation Tips

### Store Token
```kotlin
// Android Kotlin example
val sharedPrefs = getSharedPreferences("auth", MODE_PRIVATE)
sharedPrefs.edit().putString("token", response.data.token).apply()
```

### Add Token to Requests
```kotlin
// Using Retrofit
val client = OkHttpClient.Builder()
    .addInterceptor { chain ->
        val token = getToken()
        val request = chain.request().newBuilder()
            .addHeader("Authorization", "Bearer $token")
            .addHeader("Accept", "application/json")
            .build()
        chain.proceed(request)
    }
    .build()
```

### Handle 401 Response
```kotlin
if (response.code() == 401) {
    // Token expired, redirect to login
    clearToken()
    navigateToLogin()
}
```
