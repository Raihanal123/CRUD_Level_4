<?php
session_start();
include 'koneksi.php';

// 1. PROTEKSI HALAMAN
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_user_login = $_SESSION['id'];

// 2. LOGIKA PENCARIAN
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
    <title>Dashboard Alumni | SMK Telkom Lampung</title>
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
            -webkit-backdrop-filter: blur(12px) saturate(180%);
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
                <a href="dashboard_user.php">
                    <img src="img/Logo.png" alt="Logo" class="h-10 md:h-12 w-auto object-contain">
                </a>
                <div class="hidden md:block h-8 w-[1px] bg-slate-200/60 mx-2"></div>
                <span class="hidden md:block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Direktori Alumni</span>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex flex-col items-end mr-2">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Selamat Datang,</span>
                    <span class="text-sm font-bold text-red-800"><?= $_SESSION['username']; ?></span>
                </div>
                <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" 
                   class="bg-red-600 hover:bg-red-700 transition-all px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/20 active:scale-95">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-10 text-center md:text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Rekan Alumni</h2>
                <p class="text-slate-500 mt-1 font-medium italic">SMK Telkom Lampung Hub</p>
            </div>

            <div class="mb-12 flex justify-center">
                <form action="" method="POST" class="relative w-full max-w-4xl group">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, angkatan, atau jurusan..." 
                           class="w-full pl-6 pr-20 py-5 rounded-[2rem] border-2 border-slate-100 bg-white focus:outline-none focus:border-red-800 focus:ring-8 focus:ring-red-900/5 transition-all shadow-xl shadow-slate-200/50 text-lg font-medium">
                    
                    <div class="absolute right-4 top-3 flex items-center gap-2">
                        <?php if($keyword != ""): ?>
                            <a href="dashboard_user.php" class="p-3 bg-slate-100 hover:bg-red-100 text-slate-400 hover:text-red-700 rounded-2xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        <?php endif; ?>
                        <button type="submit" name="search" class="bg-red-800 text-white p-3 rounded-2xl hover:bg-red-900 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white/90 backdrop-blur-md rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="py-6 px-8 text-center text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] w-20">No</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Profil Alumni</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest w-48">Angkatan</th>
                                <th class="py-6 px-6 text-left text-xs font-black text-slate-500 uppercase tracking-widest">Jurusan</th>
                                <th class="py-6 px-6 text-center text-xs font-black text-slate-500 uppercase tracking-widest w-44">Aksi</th>
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
                                    <span class="text-sm font-black text-slate-300 group-hover:text-red-700">#<?= $no++; ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-200 overflow-hidden border-2 border-white shadow-md">
                                            <img src="uploads/<?= !empty($row['foto']) ? $row['foto'] : 'profile.jpg' ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-900 text-lg group-hover:text-red-900">
                                                <?= htmlspecialchars($row['nama']); ?>
                                                <?php if ($row['id_user'] == $id_user_login) : ?>
                                                    <span class="text-[9px] bg-red-800 text-white px-2 py-0.5 rounded-md font-black ml-1">SAYA</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Verified Alumni</div>
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
                                    <span class="text-sm font-black text-slate-600 uppercase tracking-widest"><?= htmlspecialchars($row['jurusan']); ?></span>
                                </td>
                                <td class="py-7 px-6 text-center">
                                    <?php if ($row['id_user'] == $id_user_login) : ?>
                                        <a href="edit_profil.php?id=<?= $row['id_alumni']; ?>" 
                                           class="inline-block bg-red-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-900/20 active:scale-95">
                                            Edit Profil
                                        </a>
                                    <?php else : ?>
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Read Only</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php 
                                } 
                            } else { ?>
                            <tr>
                                <td colspan="5" class="py-32 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tighter">Tidak Dapat Ditemukan</h3>
                                        <p class="text-sm text-slate-400 font-medium mt-2">Data <span class="text-red-700 font-bold">"<?= htmlspecialchars($keyword) ?>"</span> tidak ada di direktori.</p>
                                        <a href="dashboard_user.php" class="mt-8 text-[10px] font-black text-red-800 border-2 border-red-800 px-8 py-3 rounded-2xl hover:bg-red-800 hover:text-white transition-all uppercase tracking-widest">Lihat Semua</a>
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

    <footer class="py-10 text-center">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">
            Developed by <span class="text-red-800">Raihan Alfiansyah</span>
        </p>
    </footer>

</body>
</html>