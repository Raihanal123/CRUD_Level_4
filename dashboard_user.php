<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM alumni");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard User | Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <nav class="bg-red-900 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-6 bg-red-400 rounded-full"></div>
                <h1 class="text-xl font-bold tracking-tight">Dashboard User</h1>
            </div>
            
            <a href="logout.php" 
            class="bg-white/10 hover:bg-white/20 border border-red-400/30 transition-all px-5 py-2 rounded-md text-sm font-semibold">
                Logout
            </a>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-10">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Rekan Alumni</h2>
                <p class="text-gray-500">Berikut adalah data seluruh alumni yang terdaftar dalam sistem.</p>
            </div>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-red-950 text-red-50">
                            <tr>
                                <th class="py-4 px-6 text-left text-xs uppercase tracking-widest font-bold">Nama</th>
                                <th class="py-4 px-6 text-left text-xs uppercase tracking-widest font-bold">Angkatan</th>
                                <th class="py-4 px-6 text-left text-xs uppercase tracking-widest font-bold">Jurusan</th>
                                <th class="py-4 px-6 text-center text-xs uppercase tracking-widest font-bold">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                            <tr class="hover:bg-red-50/30 transition-colors">
                                <td class="py-4 px-6 text-sm font-semibold text-gray-700">
                                    <?= $row['nama']; ?>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    <span class="bg-gray-100 px-2 py-1 rounded text-gray-500 font-mono"><?= $row['angkatan']; ?></span>
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-600 italic">
                                    <?= $row['jurusan']; ?>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <a href="edit.php?id=<?= $row['id_alumni']; ?>"
                                        class="inline-flex items-center bg-red-700 hover:bg-red-800 text-white text-xs font-bold py-2 px-4 rounded-full transition-transform active:scale-95 shadow-md">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                    <p class="text-xs text-gray-400 italic text-right">* Data ini dikelola oleh administrasi pusat.</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-red-950 text-white">
        <div class="max-w-6xl mx-auto px-4 py-8 text-center">
            <h2 class="text-lg font-bold tracking-wide mb-1 uppercase">Sistem Manajemen Alumni</h2>
            <p class="text-red-300/60 text-sm mb-4">
                Terhubung, Berbagi, dan Menginspirasi.
            </p>
            <hr class="border-red-800 mb-4 w-1/4 mx-auto">
            <p class="text-xs text-red-400/80">
                © 2026 | Dibuat dengan dedikasi oleh <span class="text-white font-bold">Reihan Alfiansyah</span>
            </p>
        </div>
    </footer>

</body>

</html>