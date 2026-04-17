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

$keyword = "";
if (isset($_POST['search'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #7f1d1d; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #991b1b; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>

<body class="bg-[#f8fafc] flex flex-col min-h-screen text-slate-800">

    <nav class="bg-red-950 text-white shadow-2xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-900 rounded-xl flex items-center justify-center font-black shadow-lg transform -rotate-3 border border-red-700/50">A</div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Dashboard Admin</h1>
                    <span class="text-[10px] text-red-400 uppercase tracking-[0.2em] font-bold">Alumni Central System</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="tambah.php" class="hidden md:flex items-center bg-white/10 hover:bg-white/20 border border-white/20 px-5 py-2.5 rounded-xl text-sm font-bold transition-all hover:scale-105 active:scale-95 group">
                    <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Alumni
                </a>
                <div class="h-8 w-px bg-white/10 mx-2 hidden md:block"></div>
                <a href="logout.php" class="bg-red-600 hover:bg-red-500 transition-all px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-red-900/40">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-12 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Data Alumni</h2>
                    <p class="text-slate-500 mt-2 font-medium">Mengelola <span class="text-red-800 font-bold"><?= mysqli_num_rows($data) ?></span> entitas alumni aktif dalam database.</p>
                </div>
                
                <form action="" method="POST" class="flex w-full lg:w-auto items-center">
                    <div class="relative w-full lg:w-80 group">
                        <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="Cari nama, angkatan..." 
                               class="w-full pl-5 pr-12 py-3.5 rounded-2xl border-2 border-slate-200 bg-white focus:outline-none focus:border-red-800 focus:ring-4 focus:ring-red-50 transition-all shadow-sm">
                        <button type="submit" name="search" class="absolute right-2 top-2 bg-red-800 text-white p-2 rounded-xl hover:bg-red-900 transition-all active:scale-90">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                    <?php if($keyword != ""): ?>
                        <a href="dashboard_admin.php" class="ml-4 p-3.5 bg-slate-200 hover:bg-red-100 hover:text-red-700 rounded-2xl transition-all text-slate-500" title="Reset Pencarian">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] overflow-hidden border border-slate-200 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.05)]">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="py-6 px-8 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest w-24">No</th>
                                <th class="py-6 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Biodata Alumni</th>
                                <th class="py-6 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Angkatan</th>
                                <th class="py-6 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Jurusan</th>
                                <th class="py-6 px-6 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi Strategis</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            <?php 
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($data)) { 
                            ?>
                            <tr class="group hover:bg-red-50/40 transition-all">
                                <td class="py-7 px-8 text-center">
                                    <span class="text-sm font-bold text-slate-300 group-hover:text-red-700 transition-colors">#<?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="font-bold text-slate-900 text-lg group-hover:text-red-900 transition-colors"><?= $row['nama']; ?></div>
                                    <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span> Database Verified
                                    </div>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="px-4 py-1.5 bg-slate-100 rounded-full text-sm font-bold text-slate-600 inline-block">
                                        <?= $row['angkatan']; ?>
                                    </div>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="text-sm font-bold text-red-800/80 uppercase tracking-wide border-b-2 border-red-100"><?= $row['jurusan']; ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="edit.php?id=<?= $row['id_alumni']; ?>"
                                            class="bg-white border-2 border-amber-100 text-amber-600 px-5 py-2 rounded-xl hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all text-xs font-black uppercase tracking-widest shadow-sm">Edit</a>
                                        <a href="delete.php?id=<?= $row['id_alumni']; ?>"
                                            onclick="return confirm('Hapus data alumni ini? Tindakan ini tidak dapat dibatalkan.')"
                                            class="bg-white border-2 border-red-50 text-red-500 px-5 py-2 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all text-xs font-black uppercase tracking-widest shadow-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <?php if (mysqli_num_rows($data) == 0) : ?>
                <div class="py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-50 rounded-full mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <p class="text-slate-400 font-medium">Tidak ada data alumni yang cocok dengan kriteria pencarian.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h3 class="text-red-950 font-black text-xl">SMA <span class="text-slate-400 font-light">Admin</span></h3>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mt-1">Management Console v2.0</p>
            </div>
            <div class="text-slate-500 text-sm font-medium">
                © 2026 | Developed by <span class="text-red-700 font-extrabold tracking-widest uppercase ml-1">Raihan Alfiansyah</span>
            </div>
        </div>
    </footer>

</body>
</html>