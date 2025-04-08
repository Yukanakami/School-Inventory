    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: views/login.php");
        exit();
    }
    
    include'../koneksi.php';

    $role = $_SESSION['role'];
    $username = $_SESSION['username'];

    $total_barang = 0;
    $barang_hampir_habis = 0;

    if ($role == 'admin' || $role == 'petugas') {
        $total_barang = $koneksi->query("SELECT COUNT(*) AS total FROM barang")->fetch_assoc()['total'];
        $barang_hampir_habis = $koneksi->query("SELECT COUNT(*) AS total FROM barang WHERE jumlah < 5")->fetch_assoc()['total'];
    }

    $history_result = false;

    if ($role == 'admin' || $role == 'petugas' || $role == 'siswa') {
        $history_result = $koneksi->query("SELECT * FROM history ORDER BY id DESC LIMIT 10");
    }
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <title>Home - Inventory System</title>
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

    <body class="bg-blue-50 text-gray-800 fade-in">

       
<aside class="fixed top-0 left-0 w-64 h-screen bg-white shadow-md flex flex-col justify-between">
    <div>
        <div class="p-6 border-b">
            <img src="../assets/logowebkitanajayy.ico" class="h-10 mb-2">
            <h1 class="text-xl font-bold text-blue-700">Inventory System</h1>
        </div>
        <nav class="mt-4">
            <a href="dashboard.php" class="block px-6 py-3 hover:bg-blue-100">🏠 Home</a>
            <?php if ($role == 'admin' || $role == 'petugas'): ?>
                <a href="../controllers/tambah_barang.php" class="block px-6 py-3 hover:bg-blue-100">➕ Tambah Barang</a>
                <?php if ($role == 'admin'): ?>
                    <a href="users.php" class="block px-6 py-3 hover:bg-blue-100">👤 Kelola User</a>
                <?php endif; ?>
            <?php endif; ?>
            <a href="daftar_barang.php" class="block px-6 py-3 hover:bg-blue-100">📦 Daftar Barang</a>
            <a href="../auth/logout.php" class="block px-6 py-3 text-red-600 hover:bg-red-100">🚪 Logout</a>
        </nav>
    </div>
    <div class="p-4 border-t">
        <a href="buku_panduan.php" class="block px-4 py-2 text-blue-700 rounded hover:bg-blue-100">📘 Buku Panduan</a>
    </div>
</aside>


        <!-- Main -->
        <main class="ml-64 p-10">
            <!-- Hero -->
            <section class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-blue-700">Selamat Datang di Inventory Sekolah</h1>
                <p class="mt-4 text-lg text-blue-500">Hai <strong><?= $username; ?></strong>, kamu login sebagai <strong><?= ucfirst($role); ?></strong>. Yuk kelola barang dengan rapi dan efisien!</p>
            </section>

            <?php if ($role == 'admin' || $role == 'petugas' || $role == 'siswa'): ?>
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-600">Total Barang</h2>
                            <p class="text-3xl font-bold text-blue-600"><?= $total_barang; ?></p>
                        </div>
                        <div class="text-blue-600 text-4xl">📦</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow hover:scale-105 transition-transform">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-600">Barang Hampir Habis</h2>
                            <p class="text-3xl font-bold text-yellow-500"><?= $barang_hampir_habis; ?></p>
                        </div>
                        <div class="text-yellow-500 text-4xl">⚠️</div>
                    </div>
                </div>
            </section>

            <!-- History Log -->
            <section class="mt-12">
                <h2 class="text-2xl font-bold text-gray-700 mb-4">📜 Riwayat Aktivitas Terbaru</h2>
                <div class="overflow-x-auto bg-white rounded-xl shadow">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100 text-gray-600 uppercase">
                            <tr>
                                <th class="px-6 py-3">Aksi</th>
                                <th class="px-6 py-3">Nama Barang</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Kondisi</th>
                                <th class="px-6 py-3">User</th>
                                <th class="px-6 py-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
        <?php if ($history_result && $history_result->num_rows > 0): ?>
            <?php while ($row = $history_result->fetch_assoc()): ?>
            <tr class="border-b hover:bg-blue-50">
                <td class="px-6 py-4"><?= $row['aksi']; ?></td>
                <td class="px-6 py-4"><?= $row['nama_barang']; ?></td>
                <td class="px-6 py-4"><?= $row['jumlah']; ?></td>
                <td class="px-6 py-4"><?= $row['kategori']; ?></td>
                <td class="px-6 py-4"><?= $row['kondisi']; ?></td>
                <td class="px-6 py-4 font-semibold"><?= $row['user']; ?></td>
                <td class="px-6 py-4 text-sm text-gray-500"><?= $row['waktu'] ?? '-'; ?></td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat aktivitas.</td>
            </tr>
        <?php endif; ?>
    </tbody>
                    </table>
                </div>
            </section>

            <section class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="../controllers/tambah_barang.php" class="p-6 text-white bg-green-500 rounded-lg text-center hover:bg-green-600 transition">
                    ➕ Tambah Barang Baru
                </a>
                <a href="daftar_barang.php" class="p-6 text-white bg-blue-500 rounded-lg text-center hover:bg-blue-600 transition">
                    📄 Lihat Daftar Barang
                </a>
            </section>
            <?php endif; ?>
        </main>

    </body>
    </html>
