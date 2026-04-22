<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID user yang sedang login dari session
$id_login = $_SESSION['id']; 

// Menangani Pencarian
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
$total_alumni = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User | Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #7f1d1d; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #991b1b; }
        nav { background-color: white !important; }
    </style>
</head>

<body class="bg-[#f8fafc] flex flex-col min-h-screen text-slate-800">

    <nav class="bg-white shadow-xl sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <a href="dashboard_user.php" class="hover:opacity-80 transition-opacity">
                    <img src="img/Logo.png" alt="Logo Alumni" class="h-10 md:h-12 w-auto object-contain">
                </a>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:block text-right mr-2">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-0.5">User Aktif</p>
                    <p class="text-sm font-bold text-red-900"><?= $_SESSION['username'] ?? 'Rekan Alumni'; ?></p>
                </div>
                <a href="logout.php" 
                   class="bg-red-800 hover:bg-red-900 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-red-900/20 transition-all active:scale-95">
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Daftar Alumni</h2>
                    <p class="text-slate-500 mt-2 font-medium">Terhubung dengan <span class="text-red-800 font-bold"><?= $total_alumni ?></span> rekan sejawat.</p>
                </div>
                <div class="bg-white px-5 py-2.5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3 w-fit">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Akses User Terbatas</span>
                </div>
            </div>

            <div class="mb-12 flex justify-center">
                <form action="" method="POST" class="relative w-full max-w-4xl group">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari rekan berdasarkan nama, angkatan, atau jurusan..." 
                           class="w-full pl-6 pr-16 py-4 rounded-[1.5rem] border-2 border-slate-200 bg-white focus:outline-none focus:border-red-800 focus:ring-4 focus:ring-red-50 transition-all shadow-md text-lg">
                    
                    <div class="absolute right-3 top-2.5 flex items-center gap-2">
                        <?php if($keyword != ""): ?>
                            <a href="dashboard_user.php" class="p-2 bg-slate-100 hover:bg-red-100 text-slate-400 hover:text-red-700 rounded-xl transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </a>
                        <?php endif; ?>
                        <button type="submit" name="search" class="bg-red-800 text-white p-2.5 rounded-xl hover:bg-red-900 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-sm transition-all">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="py-6 px-8 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">Informasi Dasar</th>
                                <th class="py-6 px-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">Angkatan</th>
                                <th class="py-6 px-6 text-left text-[11px] font-black text-slate-400 uppercase tracking-widest">Program Studi</th>
                                <th class="py-6 px-8 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest">Opsi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-50">
                            <?php while ($row = mysqli_fetch_assoc($data)) { ?>
                            <tr class="group hover:bg-slate-50/50 transition-all duration-300">
                                <td class="py-7 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <img src="uploads/<?= ($row['foto'] != '') ? $row['foto'] : 'default.png' ?>" 
                                                 alt="Foto <?= $row['nama']; ?>" 
                                                 class="w-12 h-12 rounded-2xl object-cover border-2 border-white shadow-sm group-hover:shadow-md transition-all">
                                        </div>
                                        
                                        <div>
                                            <div class="font-bold text-slate-800 text-lg"><?= $row['nama']; ?></div>
                                            <?php if ($row['id_user'] == $id_login) : ?>
                                                <span class="text-[9px] bg-red-100 text-red-800 px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">Profil Saya</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="text-sm font-bold text-slate-600"><?= $row['angkatan']; ?></span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="text-sm font-semibold text-slate-500 italic"><?= $row['jurusan']; ?></div>
                                </td>
                                <td class="py-7 px-8 text-center">
                                    <?php if ($row['id_user'] == $id_login) : ?>
                                        <a href="edit_profil.php?id=<?= $row['id_alumni']; ?>"
                                           class="inline-flex items-center gap-2 bg-red-800 text-white px-6 py-2.5 rounded-2xl hover:bg-red-900 transition-all text-xs font-black uppercase tracking-widest shadow-lg shadow-red-900/10 active:scale-95">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            Edit Data
                                        </a>
                                    <?php else : ?>
                                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest cursor-not-allowed">Hanya Lihat</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <h3 class="text-red-950 font-black text-xl tracking-tight">Data <span class="text-slate-400 font-light">Alumni</span></h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-1">Sistem Informasi Terintegrasi</p>
            </div>
            <div class="text-slate-500 text-sm font-medium">
                © 2026 | Developed by <span class="text-red-800 font-extrabold tracking-widest uppercase ml-1">Raihan Alfiansyah</span>
            </div>
        </div>
    </footer>

</body>
</html>