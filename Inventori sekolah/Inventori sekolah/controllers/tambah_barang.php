<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit();
}



include'../koneksi.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = trim($_POST['nama_barang']);
    $jumlah = (int) $_POST['jumlah'];
    $kondisi = trim($_POST['kondisi']);
    $kategori = trim($_POST['kategori']);

    if (!empty($nama_barang) && $jumlah > 0 && !empty($kondisi) && !empty($kategori)) {
        $stmt = $koneksi->prepare("INSERT INTO barang (nama_barang, jumlah, kondisi, kategori) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $nama_barang, $jumlah, $kondisi, $kategori);

        if ($stmt->execute()) {
            $last_id = $koneksi->insert_id; // Ambil id_barang terakhir
        
            $success = "Barang berhasil ditambahkan!";
        
            $username = $_SESSION['username'];
            $aksi = "Tambah";
            $log = $koneksi->prepare("INSERT INTO history (aksi, id_barang, jumlah, kategori, kondisi, id_user, waktu, nama_barang, user) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
            $log->bind_param("siisssss", $aksi, $last_id, $jumlah, $kategori, $kondisi, $id_user, $nama_barang, $username);
            $log->execute();
            $log->close();
        } else {
            $error = "Gagal menambahkan barang. Silakan coba lagi.";
        }
        

    } else {
        $error = "Mohon isi semua data dengan benar!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Barang</title>
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
<body class="bg-blue-50 min-h-screen flex items-center justify-center fade-in">

    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl border border-blue-100">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-700">➕ Tambah Barang Inventaris</h1>

        <?php if (!empty($success)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $success ?>
            </div>
        <?php elseif (!empty($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-5">
            <div>
                <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan nama barang">
            </div>

            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" min="1" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan jumlah">
            </div>

            <div>
                <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                <select name="kategori" id="kategori" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Pilih Kategori</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Perabot">Perabot</option>
                    <option value="ATK">ATK</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi</label>
                <select name="kondisi" id="kondisi" required class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Pilih Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition duration-200">
                Simpan Barang
            </button>
        </form>

        <a href="../views/dashboard.php" class="block text-center mt-6 text-blue-500 hover:underline">← Kembali ke Dashboard</a>
    </div>
    
</body>
</html>
