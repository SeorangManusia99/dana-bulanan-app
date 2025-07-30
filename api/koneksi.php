<?php
// Izinkan akses dari mana saja (untuk pengembangan)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json; charset=UTF-8");

// Konfigurasi Database
$host = "localhost";
$db_user = "root"; // User default XAMPP
$db_pass = "";     // Password default XAMPP
$db_name = "db_danabulanan";

// Buat Koneksi
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}
?>