<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Buku Panduan - Inventory</title>
    <link rel="icon" href="../assets/logowebkitanajayy.ico">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">

    <div class="p-6">
        <h1 class="text-2xl font-bold text-blue-700 mb-4">📘 Buku Panduan Inventory</h1>

        <div class="w-full h-[90vh] bg-white rounded shadow">
            <iframe src="../assets/Buku Panduan Aplikasi.pdf" class="w-full h-full" frameborder="0"></iframe>
        </div>

        <a href="dashboard.php" class="mt-4 inline-block text-blue-600 hover:underline">⬅ Kembali ke Dashboard</a>
    </div>

</body>
</html>
