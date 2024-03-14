Proyek ini merupakan implementasi sederhana dari REST API yang menyediakan endpoint untuk konversi waktu dan operasi CRUD (Create, Read, Update, Delete) Untuk Inventori. Dibuat menggunakan PHP Native dan MySQL.

detikcom/
│
├── config/
│ └── database.php
│
├── controllers/
│ ├── ConvertTimeController.php
│ └── CrudController.php
│
├── .htaccess
└── index.php

1. config: Folder untuk konfigurasi, termasuk koneksi ke database MySQL.
2. controllers: Folder yang berisi semua file controller untuk REST API.
4. htaccess: File konfigurasi untuk pengalihan ke `index.php`.
5. index.php: File utama untuk routing dan mengarahkan ke controller yang sesuai.

## Cara Menjalankan Migrasi Database
1. bisa mengguakan endpoin /migration

