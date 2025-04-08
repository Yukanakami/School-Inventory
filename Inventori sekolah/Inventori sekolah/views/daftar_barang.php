<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
include'../koneksi.php';

$role = $_SESSION['role'];
$username = $_SESSION['username'];

$data_barang = [];
$result = $koneksi->query("SELECT * FROM barang ORDER BY kategori ASC, nama_barang ASC");
while ($row = $result->fetch_assoc()) {
    $data_barang[] = $row;
}

// Mengelompokkan berdasarkan kategori
$barang_berdasarkan_kategori = [];
foreach ($data_barang as $item) {
    $kategori = $item['kategori'];
    if (!isset($barang_berdasarkan_kategori[$kategori])) {
        $barang_berdasarkan_kategori[$kategori] = [];
    }
    $barang_berdasarkan_kategori[$kategori][] = $item;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Barang - Inventory System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/logowebkitanajayy.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 fade-in">

    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 w-64 h-screen bg-white shadow-md">
        <div class="p-6 border-b">
            <img src="../assets/logowebkitanajayy.ico" class="h-10 mb-2">
            <h1 class="text-xl font-bold text-blue-700">Inventory System</h1>
        </div>
        <nav class="mt-4">
            <a href="dashboard.php" class="block px-6 py-3 hover:bg-blue-100">🏠 Dashboard</a>
            <?php if ($role == 'admin' || $role == 'petugas'): ?>
                <a href="../controllers/tambah_barang.php" class="block px-6 py-3 hover:bg-blue-100">➕ Tambah Barang</a>
            <?php if ($role == 'admin'): ?>
                    <a href="users.php" class="block px-6 py-3 hover:bg-blue-100">👤 Kelola User</a>
            <?php endif; ?>
            <?php endif; ?>
            <a href="daftar_barang.php" class="block px-6 py-3 hover:bg-blue-100">📦 Daftar Barang</a>
            <a href="../auth/logout.php" class="block px-6 py-3 text-red-600 hover:bg-red-100">🚪 Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 p-8" id="main-content">
        <header class="mb-6">
            <h2 class="text-3xl font-bold text-blue-700">Daftar Barang</h2>
            <p class="mt-1 text-gray-600">Selamat datang, <strong><?= $username; ?></strong>!</p>
        </header>

        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'hapus_berhasil'): ?>
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg border border-green-300">
        ✅ Barang berhasil dihapus!
    </div>
<?php endif; ?>


        <section class="bg-white p-6 rounded-xl shadow">
    <input type="text" id="cariBarang" placeholder="Cari nama barang..." class="border rounded px-3 py-2 mb-4 w-full" onkeyup="filterBarang()">

    <?php if (count($barang_berdasarkan_kategori) > 0): ?>
        <?php foreach ($barang_berdasarkan_kategori as $kategori => $daftar_barang): ?>
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-blue-600 mb-3"><?= htmlspecialchars($kategori); ?></h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700 border mb-4">
                        <thead class="bg-blue-100 text-blue-700 font-semibold sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama Barang</th>
                                <th class="px-4 py-2 border">Jumlah</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Kondisi</th>
                                <?php if ($role == 'admin' || $role == 'petugas'): ?>
                                    <th class="px-4 py-2 border">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($daftar_barang as $item): ?>
                                <tr class="hover:bg-gray-100 transition">
                                    <td class="px-4 py-2 border"><?= $no++; ?></td>
                                    <td class="px-4 py-2 border"><?= htmlspecialchars($item['nama_barang']); ?></td>
                                    <td class="px-4 py-2 border"><?= $item['jumlah']; ?></td>
                                    <td class="px-4 py-2 border">
                                        <?php if ($item['jumlah'] < 5): ?>
                                            <span class="text-red-500 font-semibold">Hampir Habis</span>
                                        <?php else: ?>
                                            <span class="text-green-600 font-semibold">Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-2 border"><?= htmlspecialchars($item['kondisi']); ?></td>
                                    <?php if ($role == 'admin' || $role == 'petugas'): ?>
                                        <td class="px-4 py-2 border">
                                            <a href="../controllers/edit_barang.php?id=<?= $item['id_barang']; ?>" class="text-blue-600 hover:underline">Edit</a> |
                                            <a href="../auth/hapus_barang.php?id=<?=$item['id_barang']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-600">Tidak ada data barang.</p>
    <?php endif; ?>
</section>

    </main>

    <script>
        function loadContent(page) {
            fetch('../views/' + page + '.php')
                .then(res => res.text())
                .then(html => document.getElementById('main-content').innerHTML = html)
                .catch(err => console.error('Gagal load halaman:', err));
        }

        function filterBarang() {
            const keyword = document.getElementById('cariBarang').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const namaBarang = row.children[1].textContent.toLowerCase();
                row.style.display = namaBarang.includes(keyword) ? '' : 'none';
            });
        }
    </script>

</body>
</html>
