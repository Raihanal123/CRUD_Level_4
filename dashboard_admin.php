<?php
session_start();
include 'koneksi.php';

// 1. PROTEKSI HALAMAN: Cek apakah sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. PROTEKSI ROLE: Cek apakah rolenya admin atau superadmin
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin') {
    echo "<script>
            alert('Akses ditolak! Halaman ini khusus Admin.');
            window.location.href='dashboard_user.php';
          </script>";
    exit;
}

// 3. LOGIKA PENCARIAN
$keyword = "";
if (isset($_POST['search'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
    // Mencari di tabel alumni berdasarkan nama, angkatan, atau jurusan
    $query = "SELECT * FROM alumni WHERE 
              nama LIKE '%$keyword%' OR 
              angkatan LIKE '%$keyword%' OR 
              jurusan LIKE '%$keyword%' 
              ORDER BY id_alumni DESC";
} else {
    // Tampilkan semua data jika tidak ada pencarian
    $query = "SELECT * FROM alumni ORDER BY id_alumni DESC";
}

$data = mysqli_query($koneksi, $query);

// Cek jika query gagal (misal tabel belum dibuat)
if (!$data) {
    die("Error pada database: " . mysqli_error($koneksi));
}
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
    </style>
</head>

<body class="bg-[#f8fafc] flex flex-col min-h-screen text-slate-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="dashboard_admin.php" class="hover:opacity-80 transition-opacity">
                    <img src="img/Logo.png" alt="Logo Alumni" class="h-10 md:h-12 w-auto object-contain">
                </a>
                <div class="hidden md:block h-8 w-[1px] bg-slate-200 mx-2"></div>
                <span class="hidden md:block text-sm font-bold text-slate-500 uppercase tracking-widest">Panel Admin</span>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-xs font-bold text-slate-400 uppercase hidden sm:block">Login sebagai: <span class="text-red-800"><?= $_SESSION['username']; ?></span></span>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" class="bg-red-600 hover:bg-red-500 transition-all px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg shadow-red-900/20 border border-red-500/20">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Data Alumni</h2>
                    <p class="text-slate-500 mt-1 font-medium italic">Manajemen database alumni SMK Telkom Lampung</p>
                </div>
                
                <a href="tambah.php" class="flex items-center justify-center bg-red-800 hover:bg-red-900 text-white px-8 py-4 rounded-2xl text-sm font-black transition-all hover:scale-105 active:scale-95 shadow-xl shadow-red-900/20 group border border-red-700/50 uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Data
                </a>
            </div>

            <div class="mb-12 flex justify-center">
                <form action="" method="POST" class="relative w-full max-w-4xl group">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, angkatan, atau jurusan..." 
                           class="w-full pl-6 pr-20 py-5 rounded-[2rem] border-2 border-slate-200 bg-white focus:outline-none focus:border-red-800 focus:ring-8 focus:ring-red-900/5 transition-all shadow-md text-lg font-medium">
                    
                    <div class="absolute right-4 top-3 flex items-center gap-2">
                        <?php if($keyword != ""): ?>
                            <a href="dashboard_admin.php" class="p-3 bg-slate-100 hover:bg-red-100 text-slate-400 hover:text-red-700 rounded-2xl transition-all" title="Reset Pencarian">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        <?php endif; ?>
                        <button type="submit" name="search" class="bg-red-800 text-white p-3 rounded-2xl hover:bg-red-900 transition-all active:scale-90 shadow-lg shadow-red-900/40">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.06)]">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm md:text-base">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="py-6 px-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] w-24">No</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Biodata Alumni</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Angkatan</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Jurusan</th>
                                <th class="py-6 px-6 text-center text-xs font-black text-slate-500 uppercase tracking-widest">Opsi Manajemen</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($data) > 0) {
                                while ($row = mysqli_fetch_assoc($data)) { 
                            ?>
                            <tr class="group hover:bg-red-50/40 transition-all">
                                <td class="py-7 px-8 text-center">
                                    <span class="text-sm font-black text-slate-300 group-hover:text-red-700 transition-colors">#<?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="font-extrabold text-slate-900 text-lg group-hover:text-red-900 transition-colors"><?= htmlspecialchars($row['nama']); ?></div>
                                    <div class="flex items-center text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">Status: Terverifikasi</div>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="px-5 py-2 bg-slate-100 rounded-full text-xs font-black text-slate-600 border border-slate-200 group-hover:bg-white transition-colors uppercase">Angkatan <?= htmlspecialchars($row['angkatan']); ?></span>
                                </td>
                                <td class="py-7 px-6 text-center sm:text-left">
                                    <span class="text-sm font-black text-red-900/70 uppercase tracking-widest border-b-2 border-red-100 pb-1"><?= htmlspecialchars($row['jurusan']); ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="edit.php?id=<?= $row['id_alumni']; ?>"
                                            class="bg-white border-2 border-slate-100 text-slate-600 px-5 py-2.5 rounded-xl hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all text-[10px] font-black uppercase tracking-widest shadow-sm">Edit</a>
                                        <a href="delete.php?id=<?= $row['id_alumni']; ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= $row['nama']; ?>?')"
                                            class="bg-white border-2 border-slate-100 text-slate-400 px-5 py-2.5 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all text-[10px] font-black uppercase tracking-widest shadow-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="py-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-300">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                            <p class="text-slate-400 font-black uppercase tracking-[0.2em] text-xs">Data tidak ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-100 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="img/Logo.png" alt="Logo" class="h-6 opacity-30">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Sistem Informasi Alumni</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                Developed with ❤️ by <span class="text-red-800">Raihan Alfiansyah</span>
            </p>
        </div>
    </footer>

</body>
</html>