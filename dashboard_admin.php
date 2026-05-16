<?php
session_start();
include 'koneksi.php';

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. PROTEKSI ROLE
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
    $query = "SELECT * FROM alumni WHERE 
              nama LIKE '%$keyword%' OR 
              angkatan LIKE '%$keyword%' OR 
              jurusan LIKE '%$keyword%' 
              ORDER BY id_alumni DESC";
} else {
    $query = "SELECT * FROM alumni ORDER BY id_alumni DESC";
}

$data = mysqli_query($koneksi, $query);

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
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-attachment: fixed;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(12px) saturate(180%);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #7f1d1d; border-radius: 10px; }
    </style>
</head>

<body class="bg-[url('img/Telkom.jpeg')] bg-cover bg-center flex flex-col min-h-screen text-slate-800">

    <nav class="glass-nav sticky top-0 z-50 border-b border-slate-200/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="dashboard_admin.php">
                    <img src="img/Logo.png" alt="Logo" class="h-10 md:h-12 w-auto object-contain">
                </a>
                <div class="hidden md:block h-8 w-[1px] bg-slate-200/60 mx-2"></div>
                <span class="hidden md:block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Panel Manajemen</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex flex-col items-end mr-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Administrator</span>
                    <span class="text-sm font-bold text-red-800"><?= $_SESSION['username']; ?></span>
                </div>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" 
                   class="bg-red-600 hover:bg-red-700 transition-all px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-white shadow-lg active:scale-95">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Database Alumni</h2>
                    <p class="text-slate-500 mt-1 font-medium italic">Kelola informasi alumni SMK Telkom Lampung</p>
                </div>
                
                <a href="tambah.php" class="inline-flex items-center justify-center bg-red-800 hover:bg-red-900 text-white px-8 py-4 rounded-2xl text-xs font-black transition-all hover:scale-105 shadow-xl shadow-red-900/20 uppercase tracking-widest group">
                    <svg class="w-5 h-5 mr-2 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    Tambah Data Baru
                </a>
            </div>

            <div class="mb-12">
                <form action="" method="POST" class="relative max-w-3xl mx-auto group">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, angkatan, atau jurusan..." 
                           class="w-full pl-6 pr-20 py-5 rounded-[2rem] border-2 border-slate-100 bg-white focus:outline-none focus:border-red-800 focus:ring-8 focus:ring-red-900/5 transition-all shadow-xl shadow-slate-200/50 text-lg font-medium">
                    
                    <div class="absolute right-4 top-3 flex items-center gap-2">
                        <?php if($keyword != ""): ?>
                            <a href="dashboard_admin.php" class="p-3 bg-slate-100 hover:bg-red-100 text-slate-400 hover:text-red-700 rounded-2xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        <?php endif; ?>
                        <button type="submit" name="search" class="bg-red-800 text-white p-3 rounded-2xl hover:bg-red-900 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white/90 backdrop-blur-sm rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-left">
                                <th class="py-6 px-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] w-20">No</th>
                                <th class="py-6 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Biodata Alumni</th>
                                <th class="py-6 px-6 text-xs font-black text-slate-500 uppercase tracking-widest w-48">Angkatan</th>
                                <th class="py-6 px-6 text-xs font-black text-slate-500 uppercase tracking-widest">Jurusan</th>
                                <th class="py-6 px-6 text-center text-xs font-black text-slate-500 uppercase tracking-widest w-56">Opsi Manajemen</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            <?php 
                            $no = 1;
                            if(mysqli_num_rows($data) > 0) {
                                while ($row = mysqli_fetch_assoc($data)) { 
                            ?>
                            <tr class="group hover:bg-red-50/30 transition-all duration-300">
                                <td class="py-7 px-8 text-center">
                                    <span class="text-sm font-black text-slate-300 group-hover:text-red-700">#<?= str_pad($no++, 2, '0', STR_PAD_LEFT); ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-200 overflow-hidden border-2 border-white shadow-md">
                                            <img src="uploads/<?= !empty($row['foto']) ? $row['foto'] : 'profile.jpg' ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-lg group-hover:text-red-900 transition-colors uppercase tracking-tight"><?= htmlspecialchars($row['nama']); ?></div>
                                            <div class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Verified Data</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="inline-flex items-center px-4 py-1.5 bg-white text-slate-600 rounded-lg text-xs font-extrabold border border-slate-200 shadow-sm whitespace-nowrap">
                                        <svg class="w-3 h-3 mr-2 text-red-800" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/></svg>
                                        ANGKATAN <?= htmlspecialchars($row['angkatan']); ?>
                                    </span>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="text-sm font-black text-slate-600 uppercase tracking-widest border-b-2 border-red-50 pb-1"><?= htmlspecialchars($row['jurusan']); ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="edit.php?id=<?= $row['id_alumni']; ?>" class="bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all text-[10px] font-black uppercase tracking-widest shadow-sm">Edit</a>
                                        <a href="delete.php?id=<?= $row['id_alumni']; ?>" onclick="return confirm('Hapus permanen data ini?')" class="bg-white border border-slate-200 text-slate-400 px-4 py-2.5 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-all text-[10px] font-black uppercase tracking-widest shadow-sm">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else { ?>
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Tidak Ditemukan</h3>
                                        <p class="text-sm text-slate-400 font-medium mt-2">Maaf, data <span class="text-red-700 font-bold">"<?= htmlspecialchars($keyword) ?>"</span> tidak ada.</p>
                                        <a href="dashboard_admin.php" class="mt-8 text-[10px] font-black text-red-800 border-2 border-red-800 px-8 py-3 rounded-2xl hover:bg-red-800 hover:text-white transition-all uppercase tracking-widest">Reset</a>
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

    <footer class="bg-white/80 backdrop-blur-md border-t border-slate-100 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div class="flex items-center gap-3">
                <img src="img/Logo.png" alt="Logo" class="h-6 opacity-30">
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Administrator System</span>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                &copy; 2026 Developed by <span class="text-red-800">Raihan Alfiansyah</span>
            </p>
        </div>
    </footer>

</body>
</html>