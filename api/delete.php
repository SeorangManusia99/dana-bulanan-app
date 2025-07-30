<?php
require 'koneksi.php';

$data = json_decode(file_get_contents("php://input"));

if(isset($data->id)) {
    $id = $data->id;
    $sql = "DELETE FROM transaksi WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['message' => 'Transaksi berhasil dihapus']);
    } else {
        echo json_encode(['message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['message' => 'ID tidak ditemukan']);
}

$conn->close();
?>