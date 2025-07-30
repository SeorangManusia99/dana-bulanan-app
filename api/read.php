<?php
require 'koneksi.php';

$sql = "SELECT id, deskripsi, jumlah, jenis, DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal FROM transaksi ORDER BY tanggal DESC, id DESC";
$result = $conn->query($sql);

$transaksi = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $transaksi[] = $row;
    }
}

echo json_encode($transaksi);

$conn->close();
?>