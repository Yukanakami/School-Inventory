<?php
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit();
}

include'../koneksi.php';
$history = $koneksi->query("SELECT * FROM history ORDER BY waktu DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Aktivitas</title>
    <link rel="icon" href=" ../assets/logowebkitanajayy.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold text-blue-600 mb-4">Riwayat Aktivitas Barang</h1>
        <table class="w-full table-auto border border-gray-300">
            <thead class="bg-blue-100">
                <tr>
                    <th class="p-2 border">Waktu</th>
                    <th class="p-2 border">Aksi</th>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border">Jumlah</th>
                    <th class="p-2 border">Kategori</th>
                    <th class="p-2 border">Kondisi</th>
                    <th class="p-2 border">User</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history->fetch_assoc()) : ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border"><?= $row['waktu'] ?></td>
                        <td class="p-2 border"><?= $row['aksi'] ?></td>
                        <td class="p-2 border"><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td class="p-2 border"><?= $row['jumlah'] ?></td>
                        <td class="p-2 border"><?= $row['kategori'] ?></td>
                        <td class="p-2 border"><?= $row['kondisi'] ?></td>
                        <td class="p-2 border"><?= $row['user'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="inline-block mt-4 text-blue-500 hover:underline">← Kembali ke Dashboard</a>
    </div>
</body>
</html>
