<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Inventory Sekolah</title>
    <link rel="icon" href=" ../assets/logowebkitanajayy.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('../assets/ipentori.jpeg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="flex items-center justify-center h-screen backdrop-blur-md">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include'../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (isset($_POST['login'])) {
    $input = trim($_POST['identifier']); 
    $password = trim($_POST['password']); 

    $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] =  $data['role'];
            $_SESSION['id_user'] = $data['id_user'];
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Jika user tidak ditemukan, anggap user adalah siswa baru
        if (preg_match("/^\d{1,4}$/", $password)) {
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $koneksi->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'siswa')");
            $insert_stmt->bind_param("ss", $input, $hash_password);

            if ($insert_stmt->execute()) {
                $_SESSION['username'] = $input;
                $_SESSION['role'] = 'siswa';
                header("Location: ../views/dashboard.php");
                exit();
            } else {
                echo "<script>alert('Gagal membuat akun siswa.');</script>";
            }
        } else {
            echo "<script>alert('Username tidak ditemukan dan password bukan format siswa.');</script>";
        }
    }
}
?>


<!-- Form Login -->
<div class="relative w-96 overflow-hidden">
    <div id="form-wrapper" class="flex w-[200%] transition-transform duration-500">
        <div class="bg-slate-700 bg-opacity-80 p-8 rounded-lg shadow-lg w-1/2 text-center">
            <img src="../assets/logowebkitanajayy.ico" alt="Logo Inventory" class="mx-auto mb-4 w-16 h-16">
            <h2 class="text-2xl font-bold text-white">Sistem Inventory Sekolah</h2>
            <p class="text-gray-50 text-sm mb-6">Kelola barang dengan mudah dan efisien</p>

            <form method="POST" autocomplete="off">

                <div class="mb-4 text-left">
                    <label class="block text-white">Username (Nama Siswa atau Username)</label>
                    <input type="text" name="identifier" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                </div>

                <div class="mb-4 text-left">
                    <label class="block text-white">Password (NIK untuk Siswa)</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-300">
                </div>

                <button type="submit" name="login" class="w-full bg-emerald-400 hover:bg-emerald-300 text-white px-4 py-2 rounded-lg transition">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
