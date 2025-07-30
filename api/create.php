<?php
require 'koneksi.php';

// Ambil data JSON yang dikirim dari frontend
$data = json_decode(file_get_contents("php://input"));

// Pastikan data tidak kosong
if (
    isset($data->deskripsi) && 
    isset($data->jumlah) && 
    isset($data->jenis) && 
    isset($data->tanggal)
) {
    $deskripsi = $conn->real_escape_string($data->deskripsi);
    $jumlah = $data->jumlah;
    $jenis = $data->jenis;
    $tanggal = $data->tanggal;

    $sql = "INSERT INTO transaksi (deskripsi, jumlah, jenis, tanggal) VALUES ('$deskripsi', '$jumlah', '$jenis', '$tanggal')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Transaksi berhasil ditambahkan']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'Data tidak lengkap']);
}

$conn->close();
?>