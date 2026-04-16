<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin') {
    echo "Akses ditolak!";
    exit;
}

// Fitur Search Logika
$keyword = "";
if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    // Mencari berdasarkan nama, angkatan, atau jurusan
    $query = "SELECT * FROM alumni WHERE 
              nama LIKE '%$keyword%' OR 
              angkatan LIKE '%$keyword%' OR 
              jurusan LIKE '%$keyword%'";
} else {
    $query = "SELECT * FROM alumni";
}

$data = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #7f1d1d; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #991b1b; }
    </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <nav class="bg-red-950 text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-red-700 rounded-lg flex items-center justify-center font-bold">A</div>
                <h1 class="text-xl font-bold tracking-tight">Dashboard Admin</h1>
            </div>
            <a href="logout.php" 
               class="bg-red-700 hover:bg-red-600 transition-colors px-5 py-2 rounded-md text-sm font-semibold shadow-inner">
                Logout
            </a>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-10">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Data Alumni</h2>
                    <p class="text-gray-500">Kelola informasi alumni dalam sistem pusat.</p>
                </div>
                
                <form action="" method="POST" class="flex w-full md:w-auto">
                    <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari nama, jurusan..." 
                           class="w-full md:w-64 px-4 py-2 rounded-l-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-800 focus:border-transparent">
                    <button type="submit" name="search" class="bg-red-800 text-white px-4 py-2 rounded-r-lg hover:bg-red-900 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    <?php if($keyword != ""): ?>
                        <a href="dashboard_admin.php" class="ml-2 text-red-700 self-center hover:underline text-sm font-bold">Reset</a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="bg-white shadow-2xl shadow-red-900/5 rounded-xl overflow-hidden border border-gray-100">
                
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <a href="tambah.php"
                        class="bg-red-800 text-white px-5 py-2.5 rounded-lg hover:bg-red-900 transition-all transform hover:-translate-y-0.5 shadow-md inline-flex items-center font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Data Alumni
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-red-900 text-white text-left uppercase text-xs tracking-wider font-semibold">
                            <tr>
                                <th class="py-4 px-6 text-center w-16">No</th>
                                <th class="py-4 px-6">Nama Lengkap</th>
                                <th class="py-4 px-6">Angkatan</th>
                                <th class="py-4 px-6">Jurusan</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) { 
                            ?>
                            <tr class="hover:bg-red-50/50 transition-colors">
                                <td class="py-4 px-6 text-center text-gray-400 font-mono text-xs"><?= $no++; ?></td>
                                <td class="py-4 px-6 font-medium text-gray-800 uppercase tracking-tight"><?= $row['nama']; ?></td>
                                <td class="py-4 px-6 text-gray-600"><?= $row['angkatan']; ?></td>
                                <td class="py-4 px-6 text-gray-600">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold uppercase italic"><?= $row['jurusan']; ?></span>
                                </td>
                                <td class="py-4 px-6 text-center space-x-1">
                                    <a href="edit.php?id=<?= $row['id_alumni']; ?>"
                                        class="inline-block bg-amber-500 px-4 py-1.5 rounded-md hover:bg-amber-600 text-white text-sm font-bold shadow-sm transition-all">Edit</a>
                                    <a href="delete.php?id=<?= $row['id_alumni']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                        class="inline-block bg-red-600 px-4 py-1.5 rounded-md hover:bg-red-700 text-white text-sm font-bold shadow-sm transition-all">Hapus</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (mysqli_num_rows($data) == 0) : ?>
                <div class="p-10 text-center text-gray-400 italic text-sm">
                    <?php if($keyword != ""): ?>
                        Data dengan kata kunci "<b class="text-red-800"><?= $keyword ?></b>" tidak ditemukan.
                    <?php else: ?>
                        Belum ada data alumni yang tersimpan.
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <footer class="bg-red-950 text-red-200/60 border-t border-red-900">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 text-center md:text-left">
                <div>
                    <h3 class="text-white font-bold text-lg mb-1">Sistem Manajemen Alumni</h3>
                    <p class="text-sm">Integritas data dalam satu kendali modern.</p>
                </div>
                <div class="text-sm">
                    <p>© 2026 | Developed by <span class="text-red-400 font-bold tracking-widest uppercase">Raihan Alfiansyah</span></p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>