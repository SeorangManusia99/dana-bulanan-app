<?php
require 'koneksi.php';

$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->id) && 
    isset($data->deskripsi) && 
    isset($data->jumlah) && 
    isset($data->jenis) && 
    isset($data->tanggal)
) {
    $id = $data->id;
    $deskripsi = $conn->real_escape_string($data->deskripsi);
    $jumlah = $data->jumlah;
    $jenis = $data->jenis;
    $tanggal = $data->tanggal;

    $sql = "UPDATE transaksi SET deskripsi='$deskripsi', jumlah='$jumlah', jenis='$jenis', tanggal='$tanggal' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Transaksi berhasil diupdate']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'Data tidak lengkap']);
}

$conn->close();
?>