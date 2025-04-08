<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}



include'../koneksi.php';
$id = intval($_GET['id']);

// Ambil data barang sebelum dihapus
$data = $koneksi->query("SELECT * FROM barang WHERE id_barang = $id")->fetch_assoc();

if ($data) {
    $nama_barang = $data['nama_barang'];
    $jumlah = $data['jumlah'];
    $kategori = $data['kategori'];
    $kondisi = $data['kondisi'];
    $user = $_SESSION['username'];
    $aksi = "Hapus";

    // Simpan ke history
    $log = $koneksi->prepare("INSERT INTO history (aksi, nama_barang, jumlah, kategori, kondisi, user) VALUES (?, ?, ?, ?, ?, ?)");
    $log->bind_param("ssisss", $aksi, $nama_barang, $jumlah, $kategori, $kondisi, $user);
    $log->execute();
    $log->close();
}

// Hapus barang
$stmt = $koneksi->prepare("DELETE FROM barang WHERE id_barang = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../views/daftar_barang.php?msg=hapus_berhasil");
    exit();
} else {
    echo "Gagal menghapus barang.";
}

$stmt->close();
?>
