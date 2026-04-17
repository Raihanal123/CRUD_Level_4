<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data alumni
$data = mysqli_query($koneksi, "SELECT * FROM alumni");
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
    </style>
</head>

<body class="bg-[#f8fafc] flex flex-col min-h-screen text-slate-800">

    <nav class="bg-red-950 text-white shadow-2xl sticky top-0 z-50 border-b border-red-800/30">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-900 rounded-xl flex items-center justify-center font-black shadow-lg transform -rotate-3 border border-red-700/50">U</div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Dashboard User</h1>
                    <span class="text-[10px] text-red-400 uppercase tracking-[0.2em] font-bold">Alumni Portal Interface</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:block text-right mr-2">
                    <p class="text-[10px] text-red-300 uppercase font-bold tracking-widest">Selamat Datang</p>
                    <p class="text-sm font-bold text-white"><?= $_SESSION['username'] ?? 'Rekan Alumni'; ?></p>
                </div>
                <a href="logout.php" 
                   class="bg-red-600 hover:bg-red-500 transition-all px-5 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-red-900/40 border border-red-500/20">
                   Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 md:p-12 lg:px-24">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Rekan Alumni</h2>
                    <p class="text-slate-500 mt-2 font-medium">Terhubung kembali dengan <span class="text-red-800 font-bold"><?= $total_alumni ?></span> rekan sejawat dalam sistem.</p>
                </div>
                <div class="bg-white px-4 py-2 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter italic">Data Terverifikasi</span>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] overflow-hidden border border-slate-200 shadow-[0_20px_50px_rgba(0,0,0,0.04)] transition-all">
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
                            <tr class="group hover:bg-red-50/40 transition-all duration-300">
                                <td class="py-7 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold group-hover:bg-red-100 group-hover:text-red-700 transition-colors">
                                            <?= substr($row['nama'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-lg group-hover:text-red-950 transition-colors"><?= $row['nama']; ?></div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">Anggota Aktif</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-7 px-6">
                                    <span class="bg-slate-100 text-slate-600 px-4 py-1.5 rounded-xl text-sm font-bold border border-slate-200 group-hover:border-red-200 group-hover:bg-white transition-all">
                                        <?= $row['angkatan']; ?>
                                    </span>
                                </td>
                                <td class="py-7 px-6">
                                    <div class="text-sm font-semibold text-slate-600 italic group-hover:text-slate-900 transition-colors">
                                        <?= $row['jurusan']; ?>
                                    </div>
                                </td>
                                <td class="py-7 px-8 text-center">
                                    <a href="edit.php?id=<?= $row['id_alumni']; ?>"
                                        class="inline-flex items-center gap-2 bg-white border-2 border-red-50 text-red-700 px-6 py-2.5 rounded-2xl hover:bg-red-800 hover:text-white hover:border-red-800 transition-all text-xs font-black uppercase tracking-widest shadow-sm active:scale-95 group/btn">
                                        <svg class="w-3.5 h-3.5 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50/50 px-8 py-5 border-t border-slate-100 flex justify-between items-center">
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest italic">
                        * Hubungi admin jika terdapat kesalahan data
                    </p>
                    <div class="flex gap-2 text-[11px] font-bold text-slate-400">
                        <span>Database v2.0</span>
                        <span>•</span>
                        <span>Secure Sync</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-10 mt-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <div>
                <h3 class="text-red-950 font-black text-xl tracking-tight">Portal <span class="text-slate-400 font-light">Alumni</span></h3>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-1">Sistem Informasi Terintegrasi</p>
            </div>
            <div class="text-slate-500 text-sm font-medium">
                © 2026 | Developed by <span class="text-red-800 font-extrabold tracking-widest uppercase ml-1">Reihan Alfiansyah</span>
            </div>
        </div>
    </footer>

</body>
</html>