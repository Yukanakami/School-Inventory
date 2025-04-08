<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Akses ditolak. Halaman ini hanya bisa diakses oleh Admin.";
    exit;
}

$role = $_SESSION['role'];
$username = $_SESSION['username'];

// Ambil total per role
$query = $koneksi->query("SELECT role, COUNT(*) AS total FROM users GROUP BY role");

// Ambil data user, diurutkan berdasarkan role
$data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY role ASC");
$users_by_role = [];

while ($row = mysqli_fetch_assoc($data)) {
    $users_by_role[$row['role']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Pengguna</title>
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
  </style>
</head>
<body class="bg-gray-100 text-gray-800">

<!-- Sidebar -->
<aside class="fixed top-0 left-0 w-64 h-screen bg-white shadow-md flex flex-col justify-between">
  <div>
    <div class="p-6 border-b">
      <img src="../assets/logowebkitanajayy.ico" class="h-10 mb-2">
      <h1 class="text-xl font-bold text-blue-700">Inventory System</h1>
    </div>
    <nav class="mt-4">
      <a href="dashboard.php" class="block px-6 py-3 hover:bg-blue-100">🏠 Home</a>
      <a href="../controllers/tambah_barang.php" class="block px-6 py-3 hover:bg-blue-100">➕ Tambah Barang</a>
      <a href="users.php" class="block px-6 py-3 hover:bg-blue-100 bg-blue-50 font-semibold">👤 Kelola User</a>
      <a href="daftar_barang.php" class="block px-6 py-3 hover:bg-blue-100">📦 Daftar Barang</a>
      <a href="../auth/logout.php" class="block px-6 py-3 text-red-600 hover:bg-red-100">🚪 Logout</a>
    </nav>
  </div>
  <div class="p-4 border-t">
    <a href="buku_panduan.php" class="block px-4 py-2 text-blue-700 rounded hover:bg-blue-100">📘 Buku Panduan</a>
  </div>
</aside>

<!-- Main Content -->
<main class="ml-64 p-10 space-y-10 max-w-7xl">

  <!-- Kategori Role -->
  <section>
    <h2 class="text-2xl font-bold text-gray-700 mb-4">📊 Jumlah Pengguna per Role</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <?php while ($row = $query->fetch_assoc()): ?>
        <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
          <h3 class="text-lg font-semibold text-blue-600 capitalize"><?= $row['role']; ?></h3>
          <p class="text-3xl font-bold text-gray-800"><?= $row['total']; ?> User</p>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

  <!-- Daftar Pengguna -->
  <div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-700">👤 Daftar Pengguna</h1>
      <a href="../controllers/kelola_user.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
        ➕ Tambah Pengguna
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full table-auto border border-gray-300 rounded-lg">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-3 text-left text-sm font-semibold text-gray-700">ID</th>
            <th class="p-3 text-left text-sm font-semibold text-gray-700">Username</th>
            <th class="p-3 text-left text-sm font-semibold text-gray-700">Role</th>
            <th class="p-3 text-left text-sm font-semibold text-gray-700">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white">
          <?php foreach ($users_by_role as $role => $users): ?>
            <tr class="bg-blue-100">
              <td colspan="4" class="p-3 font-semibold text-blue-700 uppercase"><?= strtoupper($role); ?></td>
            </tr>
            <?php foreach ($users as $user): ?>
              <tr class="border-t border-gray-300 hover:bg-gray-50">
                <td class="p-3 text-sm text-gray-800"><?= $user['id_user'] ?></td>
                <td class="p-3 text-sm text-gray-800"><?= $user['username'] ?></td>
                <td class="p-3 text-sm text-gray-800 capitalize"><?= $user['role'] ?></td>
                <td class="p-3 text-sm text-gray-800 space-x-2">
                  <a href="edit_user.php?id=<?= $user['id_user'] ?>" class="text-yellow-500 hover:underline">Edit</a>
                  <a href="hapus_user.php?id=<?= $user['id_user'] ?>" onclick="return confirm('Yakin ingin hapus?')" class="text-red-500 hover:underline">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

</body>
</html>
