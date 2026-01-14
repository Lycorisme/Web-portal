# Rancangan Tabel Database - Web Portal

**Total: 17 Tabel | 161 Fields**

> **Terakhir diperbarui**: 14 Januari 2026 (setelah efisiensi 5 tabel dihapus)

---

## Efisiensi Tabel (Ringkasan)

| No | Tabel | Normalisasi | Index | Soft Delete | Keterangan Efisiensi |
|----|-------|-------------|-------|-------------|----------------------|
| 1 | users | 3NF | 4 | ✓ | Unique email, index login fields |
| 2 | articles | 3NF | 7 | ✓ | Multi-index untuk filtering cepat |
| 3 | categories | 3NF | 3 | ✓ | Index slug untuk lookup URL |
| 4 | tags | 3NF | 1 | ✓ | Unique slug constraint |
| 5 | article_tag | 3NF | 2 | ✗ | Composite PK, pivot efisien |
| 6 | article_comments | 3NF | 4 | ✗ | Self-referencing untuk nested |
| 7 | article_likes | 3NF | 2 | ✗ | Unique constraint (user+article) |
| 8 | galleries | 3NF | 5 | ✓ | Multi-index untuk filtering |
| 9 | site_settings | 2NF | 1 | ✗ | Key-value store design |
| 10 | activity_logs | 3NF | 5 | ✓ | Polymorphic index |
| 11 | blocked_clients | 3NF | 3 | ✗ | Composite index IP+blocked |
| 12 | otp_codes | 3NF | 3 | ✗ | Index untuk cleanup expired |
| 13 | sessions | 3NF | 2 | ✗ | Index last_activity untuk cleanup |
| 14 | personal_access_tokens | 3NF | 2 | ✗ | Polymorphic tokenable |
| 15 | cache | 1NF | 1 | ✗ | Simple key-value |
| 16 | cache_locks | 1NF | 1 | ✗ | Simple key-value |
| 17 | migrations | 1NF | 1 | ✗ | Laravel internal |

### Tabel yang Dihapus (Efisiensi 14 Jan 2026)

| Tabel | Alasan Dihapus |
|-------|----------------|
| ~~password_reset_tokens~~ | Diganti dengan sistem OTP via `otp_codes` |
| ~~jobs~~ | Queue tidak digunakan di aplikasi ini |
| ~~job_batches~~ | Queue tidak digunakan di aplikasi ini |
| ~~failed_jobs~~ | Queue tidak digunakan di aplikasi ini |
| ~~pages~~ | Tidak ada controller/route/view yang menggunakan |

---

## 1. Tabel `users`

**Deskripsi**: Menyimpan data pengguna sistem (Super Admin, Admin, Editor).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | name | VARCHAR | 255 | Nama lengkap pengguna |
| 3 | email | VARCHAR | 255 | Email (Unique), untuk login |
| 4 | email_verified_at | TIMESTAMP | - | Waktu verifikasi email |
| 5 | password | VARCHAR | 255 | Password terenkripsi (bcrypt) |
| 6 | phone | VARCHAR | 255 | Nomor telepon pengguna |
| 7 | position | VARCHAR | 255 | Jabatan/posisi pengguna |
| 8 | bio | TEXT | - | Biografi singkat pengguna |
| 9 | location | VARCHAR | 255 | Lokasi/alamat pengguna |
| 10 | profile_photo | VARCHAR | 255 | Path foto profil |
| 11 | role | VARCHAR | 255 | Role: super_admin, admin, editor |
| 12 | remember_token | VARCHAR | 100 | Token "Remember Me" |
| 13 | last_login_at | TIMESTAMP | - | Waktu login terakhir |
| 14 | last_login_ip | VARCHAR | 45 | IP address login terakhir |
| 15 | failed_login_count | INT | 10 | Jumlah gagal login |
| 16 | locked_until | TIMESTAMP | - | Waktu kunci akun berakhir |
| 17 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 18 | updated_at | TIMESTAMP | - | Waktu data diupdate |
| 19 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id` (BIGINT, Auto Increment)
- **Unique Index**: `email`
- **Index**: `deleted_at` (soft delete query)
- **Normalisasi**: 3NF - tidak ada dependensi transitif

---

## 2. Tabel `articles`

**Deskripsi**: Menyimpan konten artikel/berita.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | title | VARCHAR | 255 | Judul artikel |
| 3 | slug | VARCHAR | 255 | URL slug (Unique) |
| 4 | excerpt | TEXT | - | Ringkasan/kutipan artikel |
| 5 | content | LONGTEXT | - | Isi artikel lengkap (HTML) |
| 6 | thumbnail | VARCHAR | 255 | Path gambar thumbnail |
| 7 | category | VARCHAR | 255 | Nama kategori (legacy) |
| 8 | category_id | BIGINT | 20 | Foreign Key ke categories.id |
| 9 | read_time | INT | 11 | Estimasi waktu baca (menit) |
| 10 | status | ENUM | - | Status: draft, pending, published, rejected |
| 11 | security_status | ENUM | - | Status scan: pending, passed, warning, danger |
| 12 | security_message | VARCHAR | 255 | Pesan hasil scan keamanan |
| 13 | security_detail | VARCHAR | 255 | Detail temuan keamanan |
| 14 | author_id | BIGINT | 20 | Foreign Key ke users.id |
| 15 | meta_title | VARCHAR | 255 | SEO: Meta title |
| 16 | meta_description | TEXT | - | SEO: Meta description |
| 17 | meta_keywords | VARCHAR | 255 | SEO: Meta keywords |
| 18 | views | BIGINT | 20 | Jumlah views artikel |
| 19 | published_at | TIMESTAMP | - | Waktu publikasi |
| 20 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 21 | updated_at | TIMESTAMP | - | Waktu data diupdate |
| 22 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Index**: `slug`
- **Foreign Keys**: `author_id` → users, `category_id` → categories
- **Index**: `status`, `category`, `author_id`, `published_at`, `deleted_at`
- **Normalisasi**: 3NF - kategori di-normalisasi ke tabel terpisah

---

## 3. Tabel `categories`

**Deskripsi**: Menyimpan master data kategori artikel.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | name | VARCHAR | 255 | Nama kategori |
| 3 | slug | VARCHAR | 255 | URL slug (Unique) |
| 4 | description | TEXT | - | Deskripsi kategori |
| 5 | color | VARCHAR | 20 | Warna badge (HEX/nama) |
| 6 | icon | VARCHAR | 50 | Icon class (FontAwesome) |
| 7 | sort_order | INT | 10 | Urutan tampilan |
| 8 | is_active | BOOLEAN | 1 | Status aktif/tidak |
| 9 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 10 | updated_at | TIMESTAMP | - | Waktu data diupdate |
| 11 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Index**: `slug`
- **Index**: `is_active`, `sort_order`
- **Normalisasi**: 3NF

---

## 4. Tabel `tags`

**Deskripsi**: Menyimpan master data tag artikel.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | name | VARCHAR | 255 | Nama tag |
| 3 | slug | VARCHAR | 255 | URL slug (Unique) |
| 4 | is_active | BOOLEAN | 1 | Status aktif/tidak |
| 5 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 6 | updated_at | TIMESTAMP | - | Waktu data diupdate |
| 7 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Index**: `slug`
- **Normalisasi**: 3NF

---

## 5. Tabel `article_tag` (Pivot)

**Deskripsi**: Tabel relasi many-to-many antara articles dan tags.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | article_id | BIGINT | 20 | PK, FK ke articles.id |
| 2 | tag_id | BIGINT | 20 | PK, FK ke tags.id |

**Efisiensi:**
- **Composite Primary Key**: (`article_id`, `tag_id`)
- **Index**: `tag_id` (reverse lookup)
- **Normalisasi**: 3NF - junction table untuk M:N

---

## 6. Tabel `article_comments`

**Deskripsi**: Menyimpan komentar pada artikel.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | article_id | BIGINT | 20 | Foreign Key ke articles.id |
| 3 | user_id | BIGINT | 20 | Foreign Key ke users.id |
| 4 | parent_id | BIGINT | 20 | FK ke article_comments.id (reply) |
| 5 | comment_text | TEXT | - | Isi komentar |
| 6 | status | ENUM | - | Status: visible, hidden, spam, reported |
| 7 | is_admin_reply | BOOLEAN | 1 | Apakah balasan dari admin |
| 8 | ip_address | VARCHAR | 45 | IP address komentator |
| 9 | created_at | TIMESTAMP | - | Waktu komentar dibuat |
| 10 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Foreign Keys**: `article_id`, `user_id`, `parent_id`
- **Composite Index**: (`article_id`, `status`)
- **Index**: `user_id`, `parent_id`, `created_at`
- **Normalisasi**: 3NF - self-referencing untuk nested comments

---

## 7. Tabel `article_likes`

**Deskripsi**: Menyimpan data like pada artikel.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | article_id | BIGINT | 20 | Foreign Key ke articles.id |
| 3 | user_id | BIGINT | 20 | Foreign Key ke users.id |
| 4 | created_at | TIMESTAMP | - | Waktu like dibuat |
| 5 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Constraint**: (`article_id`, `user_id`) - 1 user 1 like
- **Index**: `article_id`
- **Normalisasi**: 3NF

---

## 8. Tabel `galleries`

**Deskripsi**: Menyimpan galeri foto/video kegiatan.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | title | VARCHAR | 255 | Judul kegiatan |
| 3 | description | TEXT | - | Deskripsi kegiatan |
| 4 | image_path | VARCHAR | 255 | Path file gambar |
| 5 | thumbnail_path | VARCHAR | 255 | Path thumbnail |
| 6 | media_type | ENUM | - | Tipe: image, video |
| 7 | video_url | VARCHAR | 255 | URL video (YouTube, dll) |
| 8 | album | VARCHAR | 255 | Nama album/event |
| 9 | event_date | DATE | - | Tanggal kegiatan |
| 10 | location | VARCHAR | 255 | Lokasi kegiatan |
| 11 | is_featured | BOOLEAN | 1 | Tampil di homepage |
| 12 | is_published | BOOLEAN | 1 | Status publikasi |
| 13 | sort_order | INT | 10 | Urutan tampilan |
| 14 | uploaded_by | BIGINT | 20 | Foreign Key ke users.id |
| 15 | published_at | TIMESTAMP | - | Waktu publikasi |
| 16 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 17 | updated_at | TIMESTAMP | - | Waktu data diupdate |
| 18 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id`
- **Foreign Key**: `uploaded_by` → users
- **Index**: `album`, `event_date`, `is_featured`, `is_published`, `published_at`
- **Normalisasi**: 3NF

---

## 9. Tabel `site_settings`

**Deskripsi**: Menyimpan konfigurasi website (key-value store).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | key | VARCHAR | 255 | Nama setting (Unique) |
| 3 | value | LONGTEXT | - | Nilai setting |
| 4 | type | VARCHAR | 255 | Tipe: string, boolean, integer, json, text |
| 5 | group | VARCHAR | 255 | Grup: general, seo, appearance, security, social, media |
| 6 | label | VARCHAR | 255 | Label untuk tampilan |
| 7 | description | TEXT | - | Deskripsi setting |
| 8 | is_public | BOOLEAN | 1 | Bisa diakses publik |
| 9 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 10 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Index**: `key`
- **Normalisasi**: 2NF - EAV (Entity-Attribute-Value) pattern untuk fleksibilitas

---

## 10. Tabel `activity_logs`

**Deskripsi**: Menyimpan log aktivitas (Audit Trail).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | user_id | BIGINT | 20 | Foreign Key ke users.id (nullable) |
| 3 | action | VARCHAR | 50 | Jenis aksi: CREATE, UPDATE, DELETE, LOGIN |
| 4 | description | TEXT | - | Deskripsi aksi |
| 5 | subject_type | VARCHAR | 255 | Model class (polymorphic) |
| 6 | subject_id | BIGINT | 20 | ID model (polymorphic) |
| 7 | old_values | JSON | - | Data sebelum perubahan |
| 8 | new_values | JSON | - | Data setelah perubahan |
| 9 | ip_address | VARCHAR | 45 | IP address pelaku |
| 10 | user_agent | TEXT | - | Browser/device info |
| 11 | url | VARCHAR | 255 | URL yang diakses |
| 12 | level | VARCHAR | 20 | Level: info, warning, danger, critical |
| 13 | created_at | TIMESTAMP | - | Waktu log dibuat |
| 14 | deleted_at | TIMESTAMP | - | Waktu soft delete |

**Efisiensi:**
- **Primary Key**: `id`
- **Foreign Key**: `user_id` → users
- **Composite Index**: (`subject_type`, `subject_id`)
- **Index**: `user_id`, `action`, `created_at`, `level`
- **Normalisasi**: 3NF - polymorphic untuk multi-model logging

---

## 11. Tabel `blocked_clients`

**Deskripsi**: Menyimpan daftar IP yang diblokir (Rate Limiting).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | ip_address | VARCHAR | 45 | IP Address (IPv4/IPv6) |
| 3 | user_agent | TEXT | - | Browser/device info |
| 4 | attempt_count | INT | 10 | Jumlah percobaan gagal |
| 5 | is_blocked | BOOLEAN | 1 | Status blokir aktif |
| 6 | blocked_until | TIMESTAMP | - | Waktu blokir berakhir |
| 7 | reason | VARCHAR | 255 | Alasan blokir |
| 8 | blocked_route | VARCHAR | 255 | Route yang diblokir |
| 9 | created_at | TIMESTAMP | - | Waktu data dibuat |
| 10 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Index**: `ip_address`
- **Composite Index**: (`ip_address`, `is_blocked`)
- **Index**: `blocked_until`
- **Normalisasi**: 3NF

---

## 12. Tabel `otp_codes`

**Deskripsi**: Menyimpan kode OTP untuk verifikasi.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | email | VARCHAR | 255 | Email penerima OTP |
| 3 | code | VARCHAR | 6 | Kode OTP 6 digit |
| 4 | type | VARCHAR | 255 | Tipe: password_reset, login_2fa, email_verification |
| 5 | attempts | INT | 11 | Jumlah percobaan verifikasi |
| 6 | expires_at | TIMESTAMP | - | Waktu kadaluarsa OTP |
| 7 | created_at | TIMESTAMP | - | Waktu OTP dibuat |
| 8 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Index**: `email`
- **Composite Index**: (`email`, `type`)
- **Index**: `expires_at` (cleanup expired)
- **Normalisasi**: 3NF

---

## 13. Tabel `sessions`

**Deskripsi**: Menyimpan sesi login aktif pengguna.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | VARCHAR | 255 | Primary Key, Session ID |
| 2 | user_id | BIGINT | 20 | Foreign Key ke users.id |
| 3 | ip_address | VARCHAR | 45 | IP Address pengguna |
| 4 | user_agent | TEXT | - | Browser/device info |
| 5 | payload | LONGTEXT | - | Data sesi terenkripsi |
| 6 | last_activity | INT | 11 | Unix timestamp aktivitas terakhir |

**Efisiensi:**
- **Primary Key**: `id` (string)
- **Index**: `user_id`, `last_activity`
- **Normalisasi**: 3NF

---

## 14. Tabel `personal_access_tokens`

**Deskripsi**: Menyimpan token API (Laravel Sanctum).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | id | BIGINT | 20 | Primary Key, Auto Increment |
| 2 | tokenable_type | VARCHAR | 255 | Model class (polymorphic) |
| 3 | tokenable_id | BIGINT | 20 | ID model (polymorphic) |
| 4 | name | VARCHAR | 255 | Nama token |
| 5 | token | VARCHAR | 64 | Token hash (Unique) |
| 6 | abilities | TEXT | - | Permissions (JSON) |
| 7 | last_used_at | TIMESTAMP | - | Waktu terakhir digunakan |
| 8 | expires_at | TIMESTAMP | - | Waktu kadaluarsa |
| 9 | created_at | TIMESTAMP | - | Waktu token dibuat |
| 10 | updated_at | TIMESTAMP | - | Waktu data diupdate |

**Efisiensi:**
- **Primary Key**: `id`
- **Unique Index**: `token`
- **Composite Index**: (`tokenable_type`, `tokenable_id`)
- **Normalisasi**: 3NF - polymorphic design

---

## 15. Tabel `cache`

**Deskripsi**: Menyimpan data cache aplikasi (Laravel Cache).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | key | VARCHAR | 255 | Primary Key, Cache key |
| 2 | value | MEDIUMTEXT | - | Nilai cache (serialized) |
| 3 | expiration | INT | 11 | Unix timestamp kadaluarsa |

**Efisiensi:**
- **Primary Key**: `key`
- **Normalisasi**: 1NF - simple key-value store

---

## 16. Tabel `cache_locks`

**Deskripsi**: Menyimpan atomic locks untuk cache.

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | key | VARCHAR | 255 | Primary Key, Lock key |
| 2 | owner | VARCHAR | 255 | Pemilik lock |
| 3 | expiration | INT | 11 | Unix timestamp kadaluarsa |

**Efisiensi:**
- **Primary Key**: `key`
- **Normalisasi**: 1NF

---

## 17. Tabel `migrations`

**Deskripsi**: Menyimpan history migrasi database (Laravel Internal).

| No | Field Name | Type | Width | Information |
|----|------------|------|-------|-------------|
| 1 | | INT | 10 | Primary Key, Auto Increment |
| 2 | migration | VARCHAR | 255 | Nama file migration |
| 3 | batch | INT | 11 | Nomor batch eksekusi |

**Efisiensi:**
- **Primary Key**: `id`
- **Normalisasi**: 1NF - simple tracking table

---

## Ringkasan Jumlah Field per Tabel

| No | Nama Tabel | Jumlah Field | Kategori |
|----|------------|--------------|----------|
| 1 | users | 19 | Aplikasi |
| 2 | articles | 22 | Aplikasi |
| 3 | categories | 11 | Aplikasi |
| 4 | tags | 7 | Aplikasi |
| 5 | article_tag | 2 | Aplikasi |
| 6 | article_comments | 10 | Aplikasi |
| 7 | article_likes | 5 | Aplikasi |
| 8 | galleries | 18 | Aplikasi |
| 9 | site_settings | 10 | Aplikasi |
| 10 | activity_logs | 14 | Aplikasi |
| 11 | blocked_clients | 10 | Aplikasi |
| 12 | otp_codes | 8 | Aplikasi |
| 13 | sessions | 6 | Laravel System |
| 14 | personal_access_tokens | 10 | Laravel System |
| 15 | cache | 3 | Laravel System |
| 16 | cache_locks | 3 | Laravel System |
| 17 | migrations | 3 | Laravel System |
| | **TOTAL** | **161 fields** | |
