<?php
session_start();
include 'koneksi.php';

// 1. Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 2. Ambil ID dan sanitasi
$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// 3. Ambil data lama
$query_data = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_alumni='$id'");
$d = mysqli_fetch_assoc($query_data);

// Jika data tidak ditemukan
if (!$d) {
    header("Location: dashboard_admin.php");
    exit;
}

// 4. Proses update data
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);

    $update = mysqli_query($koneksi, "UPDATE alumni SET 
        nama='$nama', 
        angkatan='$angkatan', 
        jurusan='$jurusan' 
        WHERE id_alumni='$id'
    ");

    if ($update) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href='" . (($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') ? 'dashboard_admin.php' : 'dashboard_user.php') . "';
              </script>";
    } else {
        $error = "Gagal memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Alumni | SMK Telkom</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-image: url('img/Telkom.jpeg'); 
            background-size: cover; 
            background-position: center;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(15px) saturate(160%);
            -webkit-backdrop-filter: blur(15px) saturate(160%);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md">
        <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/40">
            
            <div class="pt-10 pb-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-red-800 text-white rounded-[2rem] mb-4 shadow-lg shadow-red-900/30">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Informasi</h2>
                <p class="text-[10px] font-black text-red-800/60 uppercase tracking-[0.3em] mt-1">Database Alumni SMK Telkom</p>
            </div>

            <div class="px-10 pb-10">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded-2xl mb-6 text-xs font-bold text-center border border-red-100 italic animate-bounce">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 mb-2 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($d['nama']); ?>" required
                            class="w-full px-5 py-3.5 bg-white/60 border border-white focus:bg-white focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-800 rounded-2xl transition-all text-sm font-bold text-slate-700 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 mb-2 ml-1 uppercase tracking-widest">Angkatan (Tahun Lulus)</label>
                        <input type="number" name="angkatan" value="<?= htmlspecialchars($d['angkatan']); ?>" required
                            class="w-full px-5 py-3.5 bg-white/60 border border-white focus:bg-white focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-800 rounded-2xl transition-all text-sm font-bold text-slate-700 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 mb-2 ml-1 uppercase tracking-widest">Program Keahlian</label>
                        <div class="relative">
                            <select name="jurusan" required
                                class="w-full px-5 py-3.5 bg-white/60 border border-white focus:bg-white focus:outline-none focus:ring-4 focus:ring-red-100 focus:border-red-800 rounded-2xl transition-all text-sm font-bold text-slate-700 shadow-sm appearance-none cursor-pointer">
                                <option value="" disabled>Pilih Jurusan</option>
                                <option value="Rekayasa Perangkat Lunak" <?= ($d['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                                <option value="Teknik Komunikasi dan Jaringan" <?= ($d['jurusan'] == 'Teknik Komunikasi dan Jaringan') ? 'selected' : ''; ?>>Teknik Komunikasi dan Jaringan</option>
                                <option value="Animasi" <?= ($d['jurusan'] == 'Animasi') ? 'selected' : ''; ?>>Animasi</option>
                                <option value="Teknik Jaringan Akses Telekomunikasi" <?= ($d['jurusan'] == 'Teknik Jaringan Akses Telekomunikasi') ? 'selected' : ''; ?>>Teknik Jaringan Akses Telekomunikasi</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col gap-3">
                        <button type="submit" name="submit"
                            class="w-full bg-red-800 text-white py-4 rounded-2xl font-black hover:bg-red-900 transition-all shadow-xl shadow-red-900/30 uppercase tracking-widest text-[10px] active:scale-95">
                            Simpan Perubahan
                        </button>

                        <a href="<?= ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') ? 'dashboard_admin.php' : 'dashboard_user.php'; ?>"
                            class="w-full text-center bg-white/50 text-slate-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white hover:text-slate-800 transition-all border border-white shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Batal & Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>
        
        <p class="text-center text-white/60 text-[10px] font-bold uppercase tracking-[0.4em] mt-8">
            © 2026 SMK Telkom Lampung
        </p>
    </div>

</body>
</html>