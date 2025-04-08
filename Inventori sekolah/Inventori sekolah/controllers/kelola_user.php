<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

include'../koneksi.php';

$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $cek = $koneksi->query("SELECT * FROM users WHERE username = '$username'");
    if ($cek->num_rows > 0) {
        $pesan = "❌ Username sudah digunakan!";
    } else {
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($koneksi->query($query)) {
            $pesan = "✅ User berhasil ditambahkan!";
        } else {
            $pesan = "❌ Gagal menambah user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User</title>
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
<body class="bg-blue-50 min-h-screen flex items-center justify-center px-4 py-8 fade-in">

    <div class="w-full max-w-2xl bg-white p-8 rounded-2xl shadow-xl border border-blue-100">
        
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-blue-700">👤 Tambah User Baru</h1>
            <a href="../views/dashboard.php" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
             Kembali
            </a>
        </div>

        
        <?php if ($pesan): ?>
            <div class="mb-6 px-4 py-3 rounded-lg text-white text-sm font-medium <?= str_contains($pesan, 'berhasil') ? 'bg-green-500' : 'bg-red-500' ?>">
                <?= $pesan ?>
            </div>
        <?php endif; ?>

        
        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan username">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan password">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                </select>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-200">
                    ➕ Tambah User
                </button>
            </div>
        </form>
    </div>

</body>
</html>
