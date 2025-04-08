<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas')) {
    header("Location: login.php");
    exit();
}



include'../koneksi.php';


if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);
$success = $error = "";


$result = $koneksi->query("SELECT * FROM barang WHERE id_barang = $id");
if ($result->num_rows === 0) {
    die("Barang tidak ditemukan.");
}
$barang = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_barang = trim($_POST['nama_barang']);
    $jumlah = (int) $_POST['jumlah'];
    $kategori = trim($_POST['kategori']);
    $kondisi = trim($_POST['kondisi']);

    if (!empty($nama_barang) && $jumlah > 0 && !empty($kategori) && !empty($kondisi)) {
        $stmt = $koneksi->prepare("UPDATE barang SET nama_barang = ?, jumlah = ?, kategori = ?, kondisi = ? WHERE id_barang = ?");
        $stmt->bind_param("sissi", $nama_barang, $jumlah, $kategori, $kondisi, $id);

        if ($stmt->execute()) {
            $success = "Barang berhasil diperbarui!";
            
            $user = $_SESSION['username'];
            $aksi = "Edit";
            $log = $koneksi->prepare("INSERT INTO history (aksi, nama_barang, jumlah, kategori, kondisi, user) VALUES (?, ?, ?, ?, ?, ?)");
            $log->bind_param("ssisss", $aksi, $nama_barang, $jumlah, $kategori, $kondisi, $user);
            $log->execute();
            $log->close();

            $barang = [
                'nama_barang' => $nama_barang,
                'jumlah' => $jumlah,
                'kategori' => $kategori,
                'kondisi' => $kondisi,
            ];
        } else {
            $error = "Gagal mengedit barang.";
        }
        $stmt->close();
    } else {
        $error = "Semua data harus diisi dengan benar!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang</title>
    <link rel="icon" href=" ../assets/logowebkitanajayy.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl">
        <h1 class="text-2xl font-bold mb-4 text-center text-blue-600">Edit Barang Inventaris</h1>

        <?php if (!empty($success)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"><?= $success ?></div>
        <?php elseif (!empty($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block font-medium">Nama Barang</label>
                <input type="text" name="nama_barang" value="<?= htmlspecialchars($barang['nama_barang']) ?>" class="w-full p-3 border rounded">
            </div>

            <div>
                <label class="block font-medium">Jumlah</label>
                <input type="number" name="jumlah" value="<?= $barang['jumlah'] ?>" class="w-full p-3 border rounded">
            </div>

            <div>
                <label class="block font-medium">Kategori</label>
                <select name="kategori" class="w-full p-3 border rounded">
                    <option value="">Pilih Kategori</option>
                    <option value="Elektronik" <?= $barang['kategori'] == 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
                    <option value="Perabot" <?= $barang['kategori'] == 'Perabot' ? 'selected' : '' ?>>Perabot</option>
                    <option value="ATK" <?= $barang['kategori'] == 'ATK' ? 'selected' : '' ?>>ATK</option>
                    <option value="Lainnya" <?= $barang['kategori'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block font-medium">Kondisi</label>
                <select name="kondisi" class="w-full p-3 border rounded">
                    <option value="">Pilih Kondisi</option>
                    <option value="baik" <?= $barang['kondisi'] == 'baik' ? 'selected' : '' ?>>Baik</option>
                    <option value="rusak" <?= $barang['kondisi'] == 'rusak' ? 'selected' : '' ?>>Rusak</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700">Simpan Perubahan</button>
        </form>

        <a href="../views/daftar_barang.php" class="block text-center mt-4 text-blue-500 hover:underline"> Kembali ke Daftar Barang</a>
    </div>
</body>
</html>
