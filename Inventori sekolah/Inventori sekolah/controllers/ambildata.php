<?php
session_start();
include'../koneksi.php';

$total_barang = $koneksi->query("SELECT COUNT(*) AS total FROM barang")->fetch_assoc()['total'];
$barang_hampir_habis = $koneksi->query("SELECT COUNT(*) AS total FROM barang WHERE jumlah < 5")->fetch_assoc()['total'];

echo json_encode([
    "total_barang" => $total_barang,
    "barang_hampir_habis" => $barang_hampir_habis
]);
?>
