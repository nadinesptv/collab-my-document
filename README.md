<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

# 📄 My Document

Aplikasi kolaborasi dokumen real-time berbasis web yang dibangun dengan **Laravel 12**, **Laravel Reverb**, dan **Vite**. Memungkinkan beberapa pengguna mengedit dokumen yang sama secara bersamaan dengan sinkronisasi konten dan live cursor.

---

## ✨ Fitur

- 🔐 **Autentikasi** — Register, Login, Logout (Laravel Breeze)
- 📝 **Buat & Kelola Dokumen** — Setiap user hanya melihat dokumen miliknya
- ⚡ **Real-time Sync** — Perubahan konten langsung tersinkron ke semua kolaborator via WebSocket
- 🖱️ **Live Cursor** — Posisi kursor setiap user ditampilkan secara real-time
- 🕒 **Riwayat Revisi** — Setiap perubahan tersimpan dan bisa dipulihkan
- 🌙 **Auto Save** — Dokumen tersimpan otomatis saat mengetik

---

## 🛠️ Tech Stack

| Teknologi | Kegunaan |
|-----------|----------|
| Laravel 12 | Backend framework |
| Laravel Breeze | Autentikasi |
| Laravel Reverb | WebSocket server |
| Vite | Asset bundler |
| Tailwind CSS | Styling |
| MySQL | Database |

---

## ⚙️ Instalasi

### 1. Clone repository

```bash
git clone https://github.com/username/google_doc.git
cd google_doc
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Salin file environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=google_doc
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 5. Jalankan migrasi database

```bash
php artisan migrate
```

---

## 🚀 Menjalankan Aplikasi

Butuh **3 terminal** yang berjalan bersamaan:

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite (asset compiler)
npm run dev

# Terminal 3 — Reverb (WebSocket server)
php artisan reverb:start
```

Buka browser di: **http://localhost:8000**

---

## 📁 Struktur Penting

```
app/
├── Events/
│   ├── DocumentUpdatedEvent.php   # Broadcast saat konten berubah
│   └── CursorMovedEvent.php       # Broadcast saat kursor bergerak
├── Http/Controllers/
│   └── DocumentController.php     # Logic utama editor
├── Models/
│   ├── Document.php
│   └── Revision.php
resources/
├── js/
│   └── editor.js                  # Logic real-time di frontend
├── views/
│   ├── welcome.blade.php
│   ├── dashboard.blade.php
│   └── editor.blade.php
```

---

## 📡 WebSocket Events

| Event | Channel | Deskripsi |
|-------|---------|-----------|
| `document.updated` | `presence-document.{id}` | Sinkronisasi konten dokumen |
| `cursor.moved` | `presence-document.{id}` | Posisi kursor user |

---

## 🔑 Cara Penggunaan

1. **Register** akun baru di `/register`
2. **Login** di `/login`
3. Buat dokumen baru dari **Dashboard**
4. Bagikan link dokumen ke teman
5. Edit bersama secara real-time!

---

## 📋 Requirements

- PHP >= 8.2
- Node.js >= 18
- MySQL
- Composer

---

## 📝 Lisensi

Project ini dibuat untuk keperluan pembelajaran. Bebas digunakan dan dimodifikasi.

---
Nama: Nadine Septia Vynola
Nim: 240180026
Kelas:A1
