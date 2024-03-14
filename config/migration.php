<?php

require_once 'database.php';

$sql = "CREATE TABLE IF NOT EXISTS inventori (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    foto VARCHAR(255)
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabel inventori berhasil dibuat atau sudah ada.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Menambahkan data dummy ke tabel inventori
$sql = "INSERT INTO inventori (nama, harga, deskripsi, foto) VALUES
    ('Produk A', 100.00, 'Deskripsi produk A', 'uploads/product_a.png'),
    ('Produk B', 150.00, 'Deskripsi produk B', 'uploads/product_b.png'),
    ('Produk C', 200.00, 'Deskripsi produk C', 'uploads/product_c.png')";

if ($conn->query($sql) === TRUE) {
    echo "Data dummy berhasil ditambahkan.<br>";
} else {
    echo "Error inserting data: " . $conn->error;
}

// Menutup koneksi ke database
$conn->close();
