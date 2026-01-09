# RBAC (Role-Based Access Control) - BTIKP Portal

## Daftar Role

| Role | Kode | Deskripsi |
|------|------|-----------|
| **Super Admin** | `super_admin` | Full akses ke seluruh sistem |
| **Admin** | `admin` | Akses ke seluruh sistem kecuali tidak bisa mengubah Super Admin |
| **Editor** | `editor` | Akses ke manajemen konten (berita, kategori, tag, galeri) |
| **Penulis** | `author` | Hanya bisa membuat dan mengedit berita/galeri miliknya sendiri |

---

## Matriks Akses

### Menu Sidebar

| Menu | Super Admin | Admin | Editor | Penulis |
|------|:-----------:|:-----:|:------:|:-------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Kelola Berita | ✅ | ✅ | ✅ | ✅ (Miliknya saja) |
| Kategori | ✅ | ✅ | ✅ | ❌ |
| Tag | ✅ | ✅ | ✅ | ❌ |
| Galeri | ✅ | ✅ | ✅ | ✅ |
| Tong Sampah | ✅ | ✅ | ✅ | ✅ (Miliknya saja) |
| Activity Log | ✅ | ✅ | ❌ | ❌ |
| IP Terblokir | ✅ | ✅ | ❌ | ❌ |
| Pengaturan Situs | ✅ | ✅ | ❌ | ❌ |
| Profil Saya | ✅ | ✅ | ✅ | ✅ |
| Kelola User | ✅ | ✅ | ❌ | ❌ |

### Aksi pada Berita

| Aksi | Super Admin | Admin | Editor | Penulis |
|------|:-----------:|:-----:|:------:|:-------:|
| Buat Berita | ✅ | ✅ | ✅ | ✅ |
| Publish Berita | ✅ | ✅ | ✅ | ❌ (Otomatis jadi Pending) |
| Edit Berita Orang Lain | ✅ | ✅ | ✅ | ❌ |
| Hapus Berita Orang Lain | ✅ | ✅ | ✅ | ❌ |
| Ubah Status ke Published | ✅ | ✅ | ✅ | ❌ |

### Manajemen User

| Aksi | Super Admin | Admin | Editor | Penulis |
|------|:-----------:|:-----:|:------:|:-------:|
| Lihat User | ✅ | ✅ | ❌ | ❌ |
| Tambah User | ✅ | ✅ | ❌ | ❌ |
| Edit User | ✅ | ✅ (Kecuali Super Admin) | ❌ | ❌ |
| Hapus User | ✅ | ✅ (Kecuali Super Admin) | ❌ | ❌ |
| Buat Akun Super Admin | ✅ | ❌ | ❌ | ❌ |

---

## File yang Dimodifikasi

### Middleware
- `app/Http/Middleware/CheckRole.php` - **BARU** - Middleware untuk validasi role

### Bootstrap
- `bootstrap/app.php` - Mendaftarkan alias middleware `role`

### Models
- `app/Models/User.php` - Menambahkan helper methods:
  - `isEditor()`
  - `isAuthor()`
  - `canManageContent()`
  - `canManageUsers()`
  - `canAccessSecurity()`
  - `canPublishArticle()`
  - `canManageCategories()`
  - `canManageTags()`
  - `canAccessSettings()`

### Controllers
- `app/Http/Controllers/ArticleController.php`:
  - `getData()` - Filter artikel untuk penulis
  - `store()` - Set author_id ke current user, cegah penulis publish langsung
  - `update()` - Cek kepemilikan artikel untuk penulis
  - `destroy()` - Cek kepemilikan artikel untuk penulis
  - `bulkDestroy()` - Filter artikel milik sendiri untuk penulis
  - `toggleStatus()` - Cegah penulis publish langsung

- `app/Http/Controllers/UserController.php`:
  - `store()` - Tambah validasi role 'editor', cegah non-super_admin buat super_admin
  - `update()` - Tambah proteksi update super_admin oleh non-super_admin
  - `getRoleLabel()` - Tambah label 'Editor'

### Routes
- `routes/web.php` - Reorganisasi dengan middleware `role`:
  - Routes umum (semua authenticated users)
  - Routes Editor+ (super_admin, admin, editor)
  - Routes Admin Only (super_admin, admin)

### Views
- `resources/views/partials/sidebar.blade.php`:
  - Kondisi `@if(auth()->user()->canManageCategories())` untuk menu Kategori
  - Kondisi `@if(auth()->user()->canManageTags())` untuk menu Tag
  - Kondisi `@if(auth()->user()->canAccessSecurity())` untuk menu Keamanan
  - Kondisi `@if(auth()->user()->canAccessSettings())` untuk menu Pengaturan Situs
  - Kondisi `@if(auth()->user()->canManageUsers())` untuk menu Kelola User

- `resources/views/users/partials/form-modal.blade.php`:
  - Tambah opsi 'Editor' ke select role

- `resources/views/users/partials/filter.blade.php`:
  - Tambah opsi 'Editor' ke filter role

---

## Cara Membuat User dengan Role Baru

1. Login sebagai Super Admin atau Admin
2. Buka menu **Kelola User**
3. Klik **Tambah User**
4. Pilih role yang diinginkan:
   - **Penulis** - Untuk kontributor yang hanya menulis artikel
   - **Editor** - Untuk moderator yang mereview dan publish artikel
   - **Admin** - Untuk administrator sistem
   - **Super Admin** - Hanya bisa dibuat oleh Super Admin

---

## Alur Kerja Berita (Workflow)

```
[Penulis membuat berita] → [Status: Draft/Pending]
                              ↓
                     [Editor/Admin mereview]
                              ↓
                    [Approve → Published]
                         atau
                    [Reject → Rejected]
```

Penulis tidak bisa langsung publish berita. Jika penulis mencoba mengubah status ke "Published", sistem akan otomatis mengubahnya ke "Pending" untuk direview oleh Editor atau Admin.
