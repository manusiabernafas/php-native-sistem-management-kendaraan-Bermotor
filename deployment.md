# 📋 PANDUAN DEPLOYMENT APLIKASI SIMANDA
### Sistem Informasi Manajemen Armada — Dinas Kesehatan Kabupaten Bantul

| Atribut | Detail |
|---|---|
| **Arsitektur** | PHP Native 8.x (Custom MVC — tanpa Framework & tanpa SQL Database) |
| **Penyimpanan Data** | JSON Flat Files (`data/*.json`) — zero-install, tidak butuh MySQL |
| **Import Armada** | Upload file `.xlsx` / `.csv` dari Excel atau Google Sheets |
| **Tema** | Neo-Brutalism Green × App-Like UI (Terinspirasi Gojek) |
| **Target Platform** | PWA Mobile-First + Monitor Desktop |

---

## 🗺️ 1. Struktur Direktori Lengkap

```text
simandav1/
├── app/                                    # ZONA PRIVAT
│   ├── config/
│   │   └── config.php                      # Base URL, path data/, secret key
│   ├── controllers/
│   │   ├── AuthController.php              # Login, Logout, Session
│   │   ├── AdminController.php             # Dashboard, Armada, Servis, User, Import
│   │   ├── DriverController.php            # Home Driver, Form Laporan
│   │   └── PublicController.php            # Scan QR Publik (Tanpa Login)
│   ├── core/
│   │   ├── App.php                         # Router URL → Controller::method
│   │   ├── Controller.php                  # Base: view(), model(), redirect(), json()
│   │   ├── JsonDB.php                      # ⭐ JSON Database Engine (Ganti MySQL)
│   │   └── Session.php                     # Auth Guard, Flash Message, Role Check
│   ├── models/
│   │   ├── ArmadaModel.php                 # CRUD data/armada.json
│   │   ├── AlertModel.php                  # CRUD data/alerts.json
│   │   ├── ServiceModel.php                # CRUD data/service_history.json
│   │   ├── ImportModel.php                 # ⭐ Parse CSV/XLSX → Tulis ke JSON
│   │   └── UserModel.php                   # CRUD data/users.json + bcrypt auth
│   └── views/
│       ├── admin/
│       │   ├── dashboard.php               # Statistik, Alert Feed, Quick Actions
│       │   ├── armada.php                  # Tabel Armada, QR, CRUD, Import Button
│       │   ├── service.php                 # Jadwal & Riwayat Service
│       │   └── users.php                   # Kelola User
│       ├── driver/
│       │   ├── home.php                    # Home Screen Driver (Gojek-style)
│       │   └── report.php                  # Form Laporan Kondisi Armada
│       ├── monitor/
│       │   └── index.php                   # Live Monitor TV Kantor
│       ├── public/
│       │   └── vehicle.php                 # Info Kendaraan via QR (Tanpa Login)
│       └── templates/
│           ├── header.php                  # <head>, Meta PWA, Font, Icons
│           ├── app_shell.php               # Bottom Nav, Splash Screen, Toast
│           └── footer.php                  # Script global, SW Register
│
├── data/                                   # ⭐ DATABASE JSON (Zona Privat)
│   ├── users.json                          # Daftar user (admin & driver)
│   ├── armada.json                         # Daftar unit kendaraan
│   ├── alerts.json                         # Laporan masalah dari driver
│   ├── service_history.json                # Riwayat perbaikan / service
│   └── service_schedule.json               # Jadwal service mendatang
│
├── uploads/                                # File Excel yang diupload (sementara)
│   └── .gitkeep
│
├── public/                                 # ZONA PUBLIK (Web Root)
│   ├── index.php                           # Single Entry Point
│   ├── .htaccess                           # Redirect semua URL ke index.php
│   ├── manifest.json                       # PWA Manifest
│   ├── css/
│   │   └── style.css                       # Neo-Brutalism Design System
│   ├── js/
│   │   ├── app.js                          # Frontend Logic, Fetch, Toast, Router
│   │   └── sw.js                           # Service Worker (Cache, Offline)
│   └── img/
│       ├── icon-192.png
│       ├── icon-512.png
│       └── maskable-icon.png
│
└── .htaccess                               # Root-level: arahkan ke /public/
```

---

## 📱 2. Arsitektur UI & App Shell (PWA ala Gojek)

Aplikasi SIMANDA dirancang dengan pendekatan **Mobile-First App Shell**. Untuk pengguna yang login (Admin / Driver), aplikasi akan terasa seperti aplikasi native (misal: Gojek) tanpa harus memuat ulang halaman.

### Flow Layar Utama Admin (Home Screen)
Saat Admin berhasil login, sistem **TIDAK** langsung mengarahkan ke Dashboard Statistik, melainkan ke **Menu Utama (Grid Menu)**.

```text
┌─────────────────────────────────┐
│  [☰]  SIMANDA              [10:30]│  ← Top Bar (Sticky)
├─────────────────────────────────┤
│                                 │
│  Halo, Admin! 👋                │  ← Greeting Personal
│  Pilih menu di bawah ini:       │
│                                 │
│  ┌──────────┐     ┌──────────┐  │
│  │    📊    │     │    🚗    │  │  ← Grid Menu Cards (Neo-Brutalism)
│  │ Dashboard│     │  Armada  │  │     Tampilan awal persis seperti 
│  └──────────┘     └──────────┘  │     menu utama Gojek
│                                 │
│  ┌──────────┐     ┌──────────┐  │
│  │    📋    │     │    👥    │  │
│  │ Laporan  │     │   User   │  │
│  └──────────┘     └──────────┘  │
│                                 │
├───────────────────────────────────────┤
│  🏠 Menu  🚗 Armada  📱 QR  📅 Servis   │  ← Bottom Nav (QR di tengah)
└───────────────────────────────────────┘
```

### Penjelasan Flow Layar:
1. **Layar Login**: Layar penuh dengan branding SIMANDA.
2. **Layar Menu Utama**: Muncul segera setelah login. Berisi tombol-tombol kotak besar (`Dashboard`, `Armada`, `Scan QR`, `User`, `Jadwal Servis`).
3. **Layar Fitur**: Jika admin mengklik "Dashboard", barulah mereka melihat grafik dan statistik. Jika mengklik "Armada", mereka melihat daftar kendaraan.

### Desain "Neo-Brutalism"
- **Border**: Hitam tebal (3px - 4px).
- **Shadow**: Solid (tanpa blur), contoh: `box-shadow: 4px 4px 0px #000;`.
- **Warna Aksen**: Hijau (`#2ECC71`), Kuning (`#F1C40F`), Merah (`#FF4757`).

### 📱 5 Tampilan Utama Aplikasi (Main Views)
Sistem ini terbagi menjadi 5 pintu masuk antarmuka yang saling terhubung:
1. **Halaman Utama (Monitor Pemantauan)**
   - *Target*: Layar TV besar di kantor Dinas Kesehatan (Desktop View).
   - *Fungsi*: Auto-refresh tiap 10 detik, menampilkan statistik total, *banner* alert merah berkedip kencang jika ada darurat, dan jam digital besar. Tanpa interaksi, hanya untuk dipantau.
2. **Halaman QR Tanpa Login (Public Info)**
   - *Target*: Masyarakat umum / Pegawai Dinkes yang meminjam mobil.
   - *Fungsi*: Saat kode QR di kaca mobil di-scan, langsung muncul status mobil saat ini (Aman/Rusak) dan 3 riwayat servis terakhir. Tidak bisa mengubah data.
3. **Halaman QR Untuk Driver (Login Required)**
   - *Target*: Sopir yang membawa unit armada tersebut.
   - *Fungsi*: Scan QR → Wajib Login Driver → Langsung masuk ke formulir "Check-in" atau "Lapor Darurat" untuk mobil spesifik yang di-scan.
4. **Halaman Admin (PWA / Web)**
   - *Target*: Staf pengelola garasi & Kepala Mekanik (Mobile & Desktop).
   - *Fungsi*: Memiliki *App Shell* lengkap dengan *Grid Menu* dan *Bottom Navigation*. Bisa mengakses semua modul CRUD (Armada, User, Servis, Import Excel).
5. **Halaman Driver (PWA / Web)**
   - *Target*: Sopir ambulans / mobil dinas (Mobile Only).
   - *Fungsi*: Tampilan sangat sederhana, tombol besar. Fokus pada "Mulai Shift", "Lapor Rutin", dan tombol "PANIC/URGENT" warna merah layar penuh.

### 🧩 Elemen UI Krusial yang Terlewat (Missing UI States)
Dari kelima tampilan di atas, agar alur penggunanya (User Journey) benar-benar sempurna layaknya aplikasi profesional, kita perlu menambahkan **5 Elemen State Visual**:

1. **Tampilan Mode Offline (No Internet Banner)**
   - *Konteks*: Sopir ambulans sering masuk ke pelosok Bantul yang susah sinyal.
   - *Solusi UI*: Jika koneksi putus, munculkan banner kuning tebal di atas aplikasi: *"⚠️ Sedang Offline. Laporan Anda akan disimpan dan dikirim otomatis saat sinyal kembali."*
2. **Tampilan "Kosong" (Empty States)**
   - *Konteks*: Saat admin membuka tab "Laporan Masuk" tapi tidak ada mobil rusak. Kotak putih kosong ala brutalism akan terlihat seperti aplikasi error.
   - *Solusi UI*: Desain *Empty State* yang ramah. Tampilkan teks tebal besar: *"✅ Mantap! Semua armada sehat hari ini."*
3. **Splash Screen & Layar Loading Transisi**
   - *Konteks*: Perpindahan halaman bisa makan waktu 1-2 detik.
   - *Solusi UI*: *Splash screen* warna hijau penuh dengan logo SIMANDA hitam besar saat aplikasi pertama kali dibuka. Untuk *loading*, gunakan *Skeleton Loader* (blok abu-abu berkedip) daripada layar nge-*blank*.
4. **Dark Mode (Mode Gelap Khusus Driver)**
   - *Konteks*: Sopir mengemudi shift malam. Layar putih cerah dari aplikasi akan sangat menyilaukan dan mengganggu penglihatan ke jalan.
   - *Solusi UI*: Layar PWA Driver secara default menggunakan *Dark Theme* (Latar hitam, font hijau neon/kuning) agar ramah di mata saat malam hari.
5. **Toast Notifications (Umpan Balik Instan)**
   - *Konteks*: Pengguna butuh kepastian apakah tombol yang mereka tekan berhasil diproses.
   - *Solusi UI*: Notifikasi *pop-up* kecil (Toast) bergaya brutalism jatuh dari atas layar dengan bunyi *"ting!"* tiap kali data berhasil disimpan (Misal: *"💾 Laporan Darurat Berhasil Terkirim!"*).
6. **Haptic Feedback (Efek Getaran Fisik)**
   - *Konteks*: Saat tombol krusial ditekan (seperti "PANIC" atau "Simpan Data"), pengguna butuh respon fisik.
   - *Solusi UI*: Memanfaatkan *HTML5 Vibration API*. Saat tombol DARURAT ditekan, HP sopir akan bergetar keras selama 500ms sebagai konfirmasi tindakan.
7. **Swipe Actions (Geser untuk Hapus/Setujui)**
   - *Konteks*: Layar HP sempit, menaruh banyak tombol (Edit, Hapus, Setujui) di setiap baris laporan akan membuat UI berantakan.
   - *Solusi UI*: Admin cukup *menggeser* (swipe) kartu daftar laporan ke kiri untuk "Hapus", dan geser ke kanan untuk "Tandai Selesai" (seperti aplikasi Gmail).
8. **Pull-to-Refresh (Tarik untuk Memperbarui)**
   - *Konteks*: Pengguna aplikasi *mobile* sudah terbiasa menarik layar ke bawah untuk memperbarui data.
   - *Solusi UI*: Implementasikan gestur tarik-layar (PWA/Mobile Web). Saat ditarik, muncul ikon panah memutar khas Brutalism, lalu halaman me-refresh data JSON terbaru.
9. **Micro-Sound Effects (Efek Suara Navigasi)**
   - *Konteks*: Aplikasi yang statis terasa mati.
   - *Solusi UI*: Tambahkan suara klik mekanis yang sangat pelan (*subtle*) saat mengetuk menu, suara *chime* (ting!) saat sukses menyimpan, dan alarm sirine keras di layar Monitor Utama.
10. **Custom "Add to Home Screen" (A2HS) Modal**
    - *Konteks*: Promosi instalasi PWA bawaan Chrome sering diabaikan pengguna.
    - *Solusi UI*: Buat *Bottom Sheet* khusus yang cantik untuk memandu pengguna: *"Instal SIMANDA ke layar utama HP Anda agar bisa diakses secara Offline & Super Cepat!"* beserta panduan instalasinya.
11. **Background Sync (Sinkronisasi Offline Otomatis)**
    - *Konteks*: Driver menekan tombol "Lapor" saat berada di zona *blank spot* parah.
    - *Solusi UI*: Laporan masuk ke mode "Tunda" (Disimpan di IndexedDB browser). Begitu HP mendeteksi adanya sinyal internet, *Service Worker* akan otomatis mengirimkan data tersebut ke server di *background*, tanpa driver harus menekan tombol kirim ulang.
12. **Voice-to-Text (Input Suara Tanpa Ketik)**
    - *Konteks*: Driver sedang mengganti ban bocor, tangannya kotor, tidak bisa mengetik di HP.
    - *Solusi UI*: Di kolom form laporan, tambahkan ikon Mikrofon (menggunakan *Web Speech API*). Driver cukup ngomong *"Ban depan sebelah kiri pecah"*, dan suara otomatis diubah menjadi teks laporan.
13. **Login Biometrik (Sidik Jari / Face ID)**
    - *Konteks*: Mengetik *username* dan *password* di layar HP sangat merepotkan, apalagi bagi sopir senior.
    - *Solusi UI*: Integrasikan *WebAuthn API*. Saat driver login untuk pertama kalinya, browser akan menawarkan opsi: *"Gunakan Sidik Jari/Face ID untuk login selanjutnya?"*. Login jadi instan dan sangat aman.
14. **Kamera OCR (Pindai Plat Nomor)**
    - *Konteks*: Stiker QR Code di kaca mobil luntur atau robek sehingga tidak bisa di-scan.
    - *Solusi UI*: Driver cukup memotret plat nomor mobil (misal: "AB 1234 XY"). Sistem akan membaca teks dari foto tersebut secara otomatis (*Optical Character Recognition*) dan langsung mengenali armada tersebut tanpa harus mengetik.
15. **Aksesibilitas Teks Besar (Untuk Sopir Senior)**
    - *Konteks*: Beberapa pegawai atau sopir senior di Dinkes mungkin sudah rabun dekat (presbiopia) dan kesulitan membaca font ukuran normal.
    - *Solusi UI*: Tambahkan tombol ikon "T+" (Perbesar Teks) di menu pengaturan. Sekali klik, seluruh tulisan di aplikasi PWA menjadi 30% lebih besar dan lebih tebal agar nyaman dibaca.

### ✨ Peningkatan UI/UX Premium (Ekstra Menarik)
Agar tampilan tidak hanya kaku ala Brutalism tapi juga terasa sangat modern dan hidup, fitur visual berikut akan diterapkan:
1. **Tombol QR Melayang (FAB)**: Tombol Scan QR di Bottom Nav dibuat menonjol ke atas (bulging) dan lebih besar, dengan warna kuning mencolok agar langsung menarik perhatian.
2. **Animasi "Press" Mekanis**: Setiap kali tombol atau menu card ditekan, bayangan (shadow) akan menghilang dan posisi elemen turun sedikit sejauh 4px (`transform: translate(4px, 4px)`). Ini memberi sensasi fisik seperti menekan tombol mekanis sungguhan.
3. **Glass-Brutalism pada Modal**: Latar belakang saat *Bottom Sheet* atau Modal terbuka tidak sekadar hitam transparan, melainkan dipadukan dengan efek *blur* (`backdrop-filter: blur(5px)`) ala Glassmorphism.
4. **Micro-Interactions**: Ikon pada Bottom Nav akan membesar dan memantul (bounce) saat tab aktif berpindah.
5. **Skeleton Loading**: Saat berpindah menu atau mengambil data, alih-alih menampilkan *spinner* biasa yang membosankan, kita tampilkan blok-blok abu-abu yang berkedip (*skeleton loader*) untuk menjaga layout tetap stabil.

### 🌟 Fitur Tambahan yang Direkomendasikan (Feature Enhancements)
Melihat fungsi utamanya sebagai Sistem Manajemen Armada, ada 3 fitur krusial yang **belum ada tapi sangat penting** untuk ditambahkan agar aplikasinya sempurna:

1. **Upload Foto Bukti Laporan (Kerusakan)**
   - *Masalah saat ini*: Driver hanya bisa lapor kerusakan lewat teks. Admin mungkin sulit membayangkan tingkat kerusakannya.
   - *Solusi*: Saat scan QR untuk lapor darurat, tambahkan fitur **Kamera/Upload Foto**. Foto akan disimpan di folder `uploads/` dan Admin bisa melihat fotonya langsung di dashboard.
2. **Tracking Kilometer (Odometer)**
   - *Masalah saat ini*: Jadwal servis hanya bergantung pada tanggal. Padahal servis mobil sangat bergantung pada jarak tempuh (KM).
   - *Solusi*: Wajibkan driver memasukkan **KM saat ini** setiap kali mereka lapor atau absen harian. Sistem akan memicu peringatan servis otomatis jika KM sudah mendekati batas (misal: kelipatan 5.000 KM).
3. **Sistem Peminjaman / Check-in & Check-out**
   - *Solusi*: Scan QR tidak hanya untuk lapor rusak, tapi juga untuk **"Mulai Pakai" (Check-in)** dan **"Selesai Pakai" (Check-out)**. Ini menciptakan *Log History* siapa saja yang membawa mobil tersebut pada hari tertentu.
4. **Inspeksi Harian (Daily Checklist) Sebelum Berangkat**
   - *Masalah saat ini*: Standar keselamatan (rem, lampu, ban) sering terabaikan sampai mobil mogok di jalan.
   - *Solusi*: Setelah driver *Check-in*, mereka diwajibkan mengisi formulir cepat (Ya/Tidak): "Rem berfungsi? Lampu rotator nyala? Bensin cukup?". Mobil tidak boleh dipakai jika statusnya berbahaya.
5. **Share Location (GPS) Saat Darurat**
   - *Masalah saat ini*: Saat lapor darurat (misal: ambulans mogok bawa pasien), driver harus mengetik nama jalan yang mungkin mereka sendiri tidak hafal.
   - *Solusi*: Tambahkan tombol **"Kirim Lokasi Saat Ini"**. Sistem akan meminta akses GPS HP driver dan melampirkan titik koordinat Google Maps langsung ke layar Admin.
6. **Tracking Anggaran / Biaya Servis (Cost Control)**
   - *Masalah saat ini*: Instansi pemerintah butuh laporan pertanggungjawaban dana pemeliharaan kendaraan.
   - *Solusi*: Setiap kali Admin menandai "Servis Selesai", ada kolom wajib untuk input **Total Biaya (Rp)** dan upload foto nota/kuitansi bengkel.
7. **Laporan Bulanan PDF / Excel Otomatis**
   - *Masalah saat ini*: Admin harus merangkum data secara manual tiap bulan untuk dilaporkan ke Kepala Dinas.
   - *Solusi*: Fitur 1-klik untuk men-download **Laporan Rekapitulasi Armada** (format PDF yang rapi dengan kop surat Dinkes Bantul) yang berisi total pengeluaran servis, jumlah laporan masuk, dan performa armada.
8. **Push Notification Native (PWA)**
   - *Masalah saat ini*: Admin harus terus membuka layar monitor agar bisa mendengar alarm darurat.
   - *Solusi*: Integrasikan *Push API* di Service Worker. Jadi, meskipun tab SIMANDA ditutup atau HP admin sedang dikunci, notifikasi darurat akan tetap masuk berbunyi seperti pesan WA.
9. **Peringatan Pajak & Perpanjangan STNK (Legal Documents)**
   - *Masalah saat ini*: Mobil dinas rentan lupa dibayar pajak tahunannya atau mati plat STNK-nya karena tertumpuk urusan operasional.
   - *Solusi*: Tambahkan field "Masa Berlaku Pajak" & "Masa Berlaku STNK" pada data Armada. Sistem akan memberi notifikasi H-30 sebelum jatuh tempo agar admin segera mengurus perpanjangan.
10. **Manajemen Kelelahan Sopir (Driver Fatigue Tracking)**
    - *Masalah saat ini*: Sopir ambulans kadang mengemudi nonstop yang membahayakan keselamatan pasien.
    - *Solusi*: Berdasarkan sistem *Check-in* dan *Check-out*, aplikasi menghitung jam kerja sopir. Jika seorang sopir sudah bertugas lebih dari 12 jam dalam sehari, namanya di panel admin akan diberi label merah (DILARANG JALAN) untuk wajib istirahat.
11. **Dashboard Analitik Eksekutif (Untuk Kepala Dinas)**
    - *Masalah saat ini*: Kepala Dinas butuh data ringkas untuk mengambil keputusan strategis (misal: mobil mana yang sudah saatnya dilelang karena boros perawatan).
    - *Solusi*: Buat satu halaman khusus (Executive View) yang berisi metrik pintar seperti: *"Top 3 Kendaraan Paling Sering Rusak"*, *"Biaya Operasional per Kilometer (Rp/KM)"*, dan *"Grafik Rasio Mobil Sehat vs Sakit"*.

---

## 🔒 3. Keamanan Database JSON (Anti-Hacker)
Karena kita tidak memakai MySQL, file `.json` rentan di-download langsung oleh orang iseng yang menebak URL (misal: `website.com/data/users.json`). Untuk mencegah hal ini, 3 lapis keamanan wajib diterapkan:

### Lapis 1: Isolasi Folder (Document Root)
Struktur folder dibuat terpisah antara **Zona Privat** dan **Zona Publik**.
Akses domain utama (`website.com`) HANYA akan diarahkan ke dalam folder `/public/`. Sementara folder `/data/` (tempat JSON berada) terletak **di luar** *document root*, sehingga mustahil diakses dari *browser* secara langsung.

### Lapis 2: Blokir Akses Web Server (`.htaccess`)
Di dalam folder `/data/` akan ditambahkan file `.htaccess` berisi baris perintah:
```apache
# Mencegah akses langsung ke semua file dalam folder ini
Require all denied
```
Jika server Apache membaca file ini, siapapun yang mencoba mengakses `website.com/data/` akan langsung mendapat layar **403 Forbidden**.

### Lapis 3: File `index.php` Kosong (Blank Page)
Sebagai pengaman ganda jika `.htaccess` gagal berfungsi di server tertentu, kita taruh file `index.php` kosong atau berisi pesan penolakan di dalam folder `/data/`.
```php
<?php
// data/index.php
header("HTTP/1.1 403 Forbidden");
die("Akses ditolak.");
?>
```

### Lapis 4 (Opsional Ekstrim): Enkripsi AES-256-CBC
Jika data Dinkes dianggap sangat rahasia, isi file JSON tidak akan disimpan dalam bentuk *plaintext*. Sebelum file disimpan oleh `JsonDB.php`, teks JSON akan dienkripsi dengan *password* rahasia dari `config.php`.
- Tampilan JSON asli: `{"username":"admin", "role":"admin"}`
- Tampilan JSON dienkripsi: `U2FsdGVkX1+x8y...bZ9=`
Jadi walaupun *hacker* berhasil mencuri file-nya, mereka hanya melihat deretan kode acak yang tidak bisa dibaca.

---

## 🗂️ 4. Format File JSON Database

### `data/armada.json`
```json
[
  {
    "id": "ARM-001",
    "no_polisi": "AB 1234 XY",
    "jenis": "Ambulance",
    "merk": "Toyota HiAce",
    "tahun": 2020,
    "status": "tersedia",
    "last_service": "2024-03-15",
    "next_service": "2024-09-15",
    "created_at": "2024-01-01T00:00:00"
  }
]
```

### `data/users.json`
```json
[
  {
    "id": "USR-001",
    "name": "Admin SIMANDA",
    "username": "admin",
    "password": "$2y$10$hashedpassword...",
    "role": "admin",
    "created_at": "2024-01-01T00:00:00"
  },
  {
    "id": "USR-002",
    "name": "Budi Santoso",
    "username": "driver01",
    "password": "$2y$10$hashedpassword...",
    "role": "driver",
    "created_at": "2024-01-01T00:00:00"
  }
]
```

### `data/alerts.json`
```json
[
  {
    "id": "ALT-1716123456",
    "armada_id": "ARM-001",
    "driver_id": "USR-002",
    "driver_name": "Budi Santoso",
    "keterangan": "Ban bocor di jalan utama",
    "tipe": "urgent",
    "status": "open",
    "created_at": "2024-05-17T10:30:00"
  }
]
```

### `data/service_history.json`
```json
[
  {
    "id": "SVC-1716100000",
    "armada_id": "ARM-001",
    "tanggal": "2024-03-15",
    "jenis_service": "Ganti Oli & Filter",
    "biaya": 350000,
    "keterangan": "Oli diganti SAE 10W-40",
    "created_at": "2024-03-15T08:00:00"
  }
]
```

---

## 📥 4. Fitur Import Data dari Excel / Spreadsheet

### Format Template Excel yang Diterima

Download template dari Admin → Kelola Armada → **Unduh Template Excel**

| Kolom | Tipe | Wajib | Contoh |
|---|---|---|---|
| `no_polisi` | Text | ✅ | AB 1234 XY |
| `jenis` | Text | ✅ | Ambulance |
| `merk` | Text | ✅ | Toyota HiAce |
| `tahun` | Angka | ✅ | 2020 |
| `status` | Text | ✅ | tersedia / service / rusak |
| `last_service` | Tanggal (YYYY-MM-DD) | ❌ | 2024-03-15 |
| `next_service` | Tanggal (YYYY-MM-DD) | ❌ | 2024-09-15 |

### Format File yang Didukung
- ✅ `.csv` (Comma Separated Values) — direkomendasikan, paling ringan
- ✅ `.xlsx` (Excel 2007+) — via library PhpSpreadsheet atau parsing manual
- ✅ **Google Sheets** → Export sebagai CSV → Upload

### Cara Import (Flow Admin)
```
Admin buka halaman Armada
         ↓
Klik tombol [IMPORT DARI EXCEL]
         ↓
Muncul Bottom Sheet: drag & drop atau pilih file .csv/.xlsx
         ↓
Klik [PREVIEW DATA] → Tampil tabel preview sebelum disimpan
         ↓
Validasi otomatis: cek format nomor polisi, tipe status, tahun
         ↓
Klik [SIMPAN SEMUA] → ImportModel::parseAndSave() →
data ditulis ke data/armada.json (append / update jika nopol sudah ada)
         ↓
Toast: "Berhasil import X unit armada baru"
```

### Cara Export Google Sheets ke CSV
1. Buka Google Sheets yang berisi data armada
2. **File → Download → Comma Separated Values (.csv)**
3. Simpan file → Upload ke SIMANDA

---

## 🧩 5. Referensi Fungsi Detail per File

### `app/core/JsonDB.php` — JSON Database Engine ⭐
```php
JsonDB::read(string $file) : array
  Baca file JSON dari direktori data/, decode ke array PHP.
  Jika file tidak ada, buat file baru dengan array kosong [].

JsonDB::write(string $file, array $data) : bool
  Encode array ke JSON (pretty print) dan tulis ke file.
  Menggunakan file_put_contents dengan LOCK_EX untuk thread safety.

JsonDB::findById(string $file, string $id) : array|null
  Cari satu record di JSON berdasarkan field 'id'.

JsonDB::insert(string $file, array $newRecord) : bool
  Baca data → append record baru → tulis balik ke file.

JsonDB::update(string $file, string $id, array $updatedData) : bool
  Baca data → cari ID → merge data lama + baru → tulis balik.

JsonDB::delete(string $file, string $id) : bool
  Baca data → filter hapus record dengan id tersebut → tulis balik.

JsonDB::where(string $file, string $field, mixed $value) : array
  Filter records: return semua record dimana $field === $value.
  Contoh: JsonDB::where('alerts', 'status', 'open')

JsonDB::generateId(string $prefix) : string
  Return ID unik format: PREFIX-timestamp. Contoh: ARM-1716123456.
```

### `app/models/ImportModel.php` — Excel/CSV Importer ⭐
```php
ImportModel::parseCsv(string $filePath) : array
  Baca file CSV dengan fgetcsv(), skip baris header pertama.
  Return array of associative arrays berdasarkan header kolom.

ImportModel::parseXlsx(string $filePath) : array
  Parse file XLSX menggunakan ZipArchive (native PHP) untuk membaca
  xl/worksheets/sheet1.xml tanpa library eksternal.
  Alternatif: gunakan PhpSpreadsheet jika diinstall via Composer.

ImportModel::validate(array $rows) : array
  Validasi setiap baris data:
  - no_polisi: tidak boleh kosong, format [A-Z]{1,2} \d{1,4} [A-Z]{1,3}
  - status: harus salah satu dari [tersedia, service, rusak]
  - tahun: harus angka 4 digit antara 1990–2030
  Return array ['valid' => [...], 'errors' => [...]]

ImportModel::saveToJson(array $validRows) : array
  Loop setiap row valid → cek apakah no_polisi sudah ada di armada.json:
    - Jika ada → UPDATE data yang existing (tanggal service, status)
    - Jika tidak ada → INSERT record baru dengan ID baru
  Return ['inserted' => int, 'updated' => int]

ImportModel::downloadTemplate() : void
  Generate file CSV kosong dengan header kolom yang benar.
  Kirim sebagai download (Content-Disposition: attachment).
```

### `app/models/ArmadaModel.php`
```php
ArmadaModel::getAll() : array
  JsonDB::read('armada') → sort by no_polisi.

ArmadaModel::findById(string $id) : array|null
  JsonDB::findById('armada', $id).

ArmadaModel::getStats() : array
  Hitung dari array: ['total', 'tersedia', 'service', 'rusak', 'urgent'].

ArmadaModel::create(array $data) : bool
  Assign ID baru → JsonDB::insert('armada', $data).

ArmadaModel::update(string $id, array $data) : bool
  JsonDB::update('armada', $id, $data).

ArmadaModel::delete(string $id) : bool
  JsonDB::delete('armada', $id).

ArmadaModel::search(string $keyword) : array
  Filter getAll() dimana no_polisi atau merk mengandung $keyword.
```

### `app/models/AlertModel.php`
```php
AlertModel::getAll() : array
  JsonDB::read('alerts') → sort by created_at DESC.

AlertModel::getOpen() : array
  JsonDB::where('alerts', 'status', 'open').

AlertModel::create(array $data) : bool
  Tambah id, created_at → JsonDB::insert('alerts', $data).
  Jika tipe=urgent: update armada status (opsional).

AlertModel::resolve(string $id) : bool
  JsonDB::update('alerts', $id, ['status' => 'resolved']).

AlertModel::countUrgent() : int
  count(getOpen() filter tipe=urgent).
```

### `app/models/UserModel.php`
```php
UserModel::findByCredentials(string $username, string $password) : array|null
  Cari user di users.json dengan username = $username.
  Verifikasi password dengan password_verify() (bcrypt).

UserModel::getAll() : array
  JsonDB::read('users') → hapus field 'password' dari output.

UserModel::create(array $data) : bool
  Cek username unik → hash password → JsonDB::insert('users').

UserModel::delete(string $id) : bool
  Cegah hapus jika hanya satu admin tersisa → JsonDB::delete('users', $id).
```

### `app/core/Session.php`
```php
Session::start()          → php_session_start() dengan cookie secure & httponly
Session::login(array $u)  → simpan user ke $_SESSION + regenerate_id()
Session::logout()         → session_destroy() + redirect monitor
Session::isLoggedIn()     → isset($_SESSION['user'])
Session::requireLogin()   → jika !isLoggedIn() → redirect /auth/login
Session::requireRole($r)  → jika role tidak cocok → redirect 403
Session::setFlash($k,$v)  → $_SESSION['flash'][$k] = $v
Session::getFlash($k)     → ambil + hapus dari session, return value
```

### `app/controllers/AdminController.php`
```php
AdminController::index()
  Statistik: total, service, urgent (dari ArmadaModel::getStats())
  Alert feed: AlertModel::getOpen() (5 terbaru)
  View: admin/dashboard

AdminController::armada()
  GET  /admin/armada         → list + search
  POST /admin/armada/add     → validasi → ArmadaModel::create()
  POST /admin/armada/edit/id → ArmadaModel::update()
  POST /admin/armada/del/id  → ArmadaModel::delete()
  GET  /admin/armada/qr/id   → generate QR URL → view

AdminController::import()
  GET  /admin/import          → tampil form upload
  POST /admin/import/preview  → ImportModel::parseCsv/Xlsx() → tampil preview tabel
  POST /admin/import/save     → ImportModel::saveToJson() → redirect dengan flash result
  GET  /admin/import/template → ImportModel::downloadTemplate()

AdminController::service()
  GET  /admin/service         → list jadwal + riwayat
  POST /admin/service/add     → ServiceModel::addSchedule()
  POST /admin/service/done/id → ServiceModel::markDone()

AdminController::users()
  GET  /admin/users           → list users
  POST /admin/users/add       → UserModel::create()
  POST /admin/users/del/id    → UserModel::delete()

AdminController::alerts()
  GET  /admin/alerts (JSON)   → AlertModel::getOpen()
  POST /admin/alerts/resolve/id → AlertModel::resolve()
```

### `app/controllers/DriverController.php`
```php
DriverController::index()   → list armada untuk select, view driver/home
DriverController::report()
  GET  → form laporan
  POST → validasi → AlertModel::create([tipe: urgent/rutin]) → flash + redirect
```

### `app/controllers/PublicController.php`
```php
PublicController::vehicle(string $id)
  Tanpa login. ArmadaModel::findById($id) + ServiceModel::getLastByArmada($id, 3)
  Jika tidak ada → tampil halaman "Kendaraan Tidak Ditemukan"
```

---

## ⚙️ 5. Konfigurasi (`app/config/config.php`)
```php
<?php
define('BASE_URL',   'http://localhost/simandav1/public');
define('DATA_PATH',  dirname(__DIR__, 2) . '/data/');
define('UPLOAD_PATH', dirname(__DIR__, 2) . '/uploads/');
define('SECRET_KEY', 'ganti_dengan_random_string_panjang');
define('APP_NAME',   'SIMANDA Dinkes Bantul');
```

---

## 🚀 6. Langkah Instalasi

### Prasyarat
- PHP 8.0+ (tanpa ekstensi tambahan — hanya php-json yang sudah built-in)
- Apache 2.4+ dengan `mod_rewrite` aktif
- **Tidak perlu MySQL** sama sekali

### Step-by-Step
```bash
# 1. Upload/clone ke server
cd /var/www/html
# Upload folder simandav1/

# 2. Set permission direktori data & uploads (WAJIB — agar PHP bisa write)
chmod 775 simandav1/data/
chmod 775 simandav1/uploads/
chown -R www-data:www-data simandav1/data/
chown -R www-data:www-data simandav1/uploads/

# 3. Aktifkan mod_rewrite
a2enmod rewrite
systemctl restart apache2
```

**4. Buat file JSON awal** di folder `data/` (pertama kali):
```bash
# Sistem akan auto-membuat file JSON saat pertama diakses
# Atau buat manual:
echo "[]" > /var/www/html/simandav1/data/armada.json
echo "[]" > /var/www/html/simandav1/data/alerts.json
echo "[]" > /var/www/html/simandav1/data/service_history.json
echo "[]" > /var/www/html/simandav1/data/service_schedule.json
```

**5. Buat user admin pertama** via `php artisan` sederhana atau akses URL setup:
```
http://localhost/simandav1/public/setup
→ Masukkan username & password admin pertama
→ File data/users.json otomatis dibuat dengan bcrypt hash
```

---

## 📲 7. Instalasi PWA di Smartphone

### Android (Chrome)
1. Buka URL di Chrome → tunggu banner "Tambahkan ke Layar Utama"
2. Atau: Menu ⋮ → **Add to Home Screen** → Install
3. Ikon SIMANDA muncul di launcher HP

### iOS (Safari)
1. Buka di Safari → Share → **Add to Home Screen** → Add

### Fitur PWA
| Fitur | Status |
|---|---|
| Installable | ✅ |
| Offline Shell (data terakhir) | ✅ |
| Splash Screen | ✅ |
| Status Bar Hijau | ✅ |
| Import Excel dari HP | ✅ |
| Push Notification | 🔄 Fase 2 |

---

## 🔐 8. Keamanan

| Aspek | Implementasi |
|---|---|
| Password | `password_hash()` bcrypt, cost factor 12 |
| Session | Regenerate ID setiap login, httponly cookie |
| File Upload | Validasi ekstensi + MIME type, simpan di luar public/ |
| JSON Write | `LOCK_EX` flag untuk cegah race condition |
| XSS | Semua output via `htmlspecialchars()` |
| Path Traversal | Validasi path file upload, jangan izinkan `../` |
| Auth Guard | Cek session di `__construct()` setiap controller |

---

## 🛠️ 9. Flowchart QR Code

```
Admin → Kelola Armada → Klik [QR] pada baris unit
          ↓
  Muncul Bottom Sheet dengan 2 QR Code:
  ┌──────────────────────────────────────────────┐
  │  QR DRIVER: /public/armada/{id}/driver       │
  │  → Wajib Login sebagai Driver                │
  │  → Setelah scan: langsung ke form laporan    │
  │                                              │
  │  QR UMUM: /public/armada/{id}/info           │
  │  → Tanpa Login                               │
  │  → Tampil: Status & Riwayat Service          │
  └──────────────────────────────────────────────┘
```

---

*Dokumen ini dibuat untuk deployment SIMANDA di Dinas Kesehatan Kabupaten Bantul.*  
*Versi: 4.0 | Diperbarui: Mei 2026 | Arsitektur: PHP + JSON Flat Files + Excel Import*