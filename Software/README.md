# Smart Hydroponic IoT — Dashboard (Refactored)

Refactor dari project HTML/CSS/JS-native menjadi struktur **PHP Native
MVC** yang modular, tanpa mengubah fitur atau tampilan yang sudah ada
(kecuali yang secara eksplisit ditandai sebagai perbaikan bug atau
fitur baru di bawah).

## Cara Menjalankan (Lokal)

```bash
php -S localhost:8000 -t public
```
Lalu buka `http://localhost:8000`.

> Web server production (Apache/Nginx) harus di-set document root ke
> folder `public/`, **bukan** root project — ini standar keamanan MVC
> supaya file di `app/`, `resources/`, `config/`, dll tidak bisa
> diakses langsung lewat URL.

## Struktur Folder

```
app/
├── Config/          → Factory konfigurasi (mis. FirebaseClientFactory)
├── Controllers/      → Logic request-response (Dashboard, Api/*)
├── Models/            → Query Firebase (Sensor, Relay, Telegram, Log, Session)
├── Services/           → (reserved untuk business logic lanjutan)
├── Helpers/             → Fungsi utilitas (response.php, request.php)
├── Middleware/            → Kontrak middleware (reserved, belum aktif)
└── Libraries/               → Autoloader, Router, FirebaseClient

public/
├── index.php          → Front Controller (satu-satunya pintu masuk)
└── assets/
    ├── css/            → 12 file CSS modular (variables, reset, layout, dst.)
    ├── js/              → 10 modul JS (config, utils, api, chart, sensor, weather, dashboard, dst.)
    ├── fonts/, img/       → Aset statis
    └── vendor/              → Bootstrap, Bootstrap Icons, Chart.js (hanya file dist)

resources/views/
├── layouts/            → app.php (shell HTML), header, bottom-nav, dll.
├── dashboard/           → dashboard.php (view utama)
├── components/            → weather-card, sensor-card, chart-card, quick-actions, activity
└── errors/                 → 404.php

routes/
├── web.php             → Routing halaman
└── api.php              → Routing REST API

config/
├── firebase.php        → URL Firebase Realtime Database
└── auth.php              → ⚠️ Kredensial admin (GANTI sebelum production)
```

## Database: Firebase Realtime Database

Project ini **tidak** memakai MySQL — atas instruksi eksplisit, database
tetap memakai Firebase Realtime Database yang sama dengan yang sudah
dipakai frontend (`monitoring` node). Backend PHP mengaksesnya lewat
`app/Libraries/FirebaseClient.php` (REST API, tanpa SDK/Composer).

## REST API

| Endpoint | Keterangan |
|---|---|
| `GET /api/sensor` | Data sensor terbaru |
| `POST /api/sensor` | Tulis pembacaan sensor baru |
| `GET /api/history?limit=10` | Histori pembacaan |
| `GET /api/dashboard` | Payload gabungan sensor + relay |
| `POST /api/relay` | ⚠️ Kontrol relay (fitur baru, perlu firmware ESP32 pendamping) |
| `POST /api/login` | ⚠️ Login admin (fitur baru, kredensial default di `config/auth.php`) |
| `GET /api/statistic?limit=20` | min/max/avg suhu & TDS |

## Bug yang Ditemukan & Diperbaiki

1. **Path Bootstrap lokal salah** (`.../css/bootstrap.min.css` → seharusnya `.../dist/css/bootstrap.min.css`) — CSS/JS Bootstrap lokal sebelumnya 404.
2. **Bootstrap Icons dimuat 3×** (CDN + 2 path lokal berbeda, salah satunya juga 404) — disederhanakan jadi 1 sumber lokal.
3. **Font Poppins tidak pernah tampil** — file `.ttf` di-bundle tapi tidak ada `@font-face`. Sudah ditambahkan di `reset.css`.
4. **`bottom-nav` tidak pernah ditampilkan** meski CSS & elemen-nya sudah lengkap (`.mobile-app` punya `padding-bottom:110px` yang persis pas untuk nav ini) — diaktifkan kembali.
5. Chart.js dialihkan dari CDN ke vendor lokal (konsisten dengan perbaikan #1–2, mengurangi dependency eksternal).

## Fitur Baru (ditandai jelas di kode, sesuai roadmap Step 7–8)

- `RelayModel` + `POST /api/relay` — kontrol relay (sebelumnya read-only).
- `TelegramModel` — penyimpanan subscriber & log pesan (belum ada UI-nya).
- `LogModel` — log aktivitas (belum terhubung ke "Recent Activity", masih statis).
- `POST /api/login` + `SessionModel` — autentikasi token sederhana.

## Belum Dikerjakan / Menunggu Keputusan Anda

- **Step 9 (Optimasi Dashboard)**: widget *Last Update*, *WiFi RSSI*,
  *Notification bell*, dan *Telegram status* di UI **belum ditambahkan** —
  masih menunggu konfirmasi Anda widget mana yang mau ditampilkan (lihat
  percakapan). *Water Level* sengaja belum dirender karena sensornya
  belum ada secara fisik.
- Kontrol relay di `POST /api/relay` **belum divalidasi** oleh token
  login (`Middleware` kontraknya sudah siap di `app/Middleware/Middleware.php`,
  tinggal diaktifkan).
- Ganti `config/auth.php` password default sebelum deploy ke production.

## Verifikasi yang Sudah Dilakukan

- Seluruh 49 selector CSS asli diverifikasi otomatis (script Python)
  menghasilkan nilai identik dengan versi modular baru.
- Seluruh file PHP lolos pengecekan balance brace/parenthesis.
- Seluruh namespace class diverifikasi cocok 100% dengan struktur folder (PSR-4).
- Seluruh file JS lolos `node --check` (syntax valid).
- Jalur eksekusi end-to-end (`index.php → Router → Controller → View → Layout`)
  ditelusuri manual baris-per-baris.

> Catatan: sandbox pengembangan tidak memiliki PHP CLI/akses jaringan,
> jadi pengujian live (render browser sungguhan, request Firebase
> sungguhan) belum dilakukan. Mohon uji di server Anda sebelum deploy.
