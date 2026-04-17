<?php
session_start();
include 'koneksi.php';

// 1. Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_alumni = mysqli_real_escape_string($koneksi, $_GET['id']);
$id_user_login = $_SESSION['id'];

// 2. VALIDASI KEPEMILIKAN: Ambil data hanya jika id_alumni DAN id_user cocok
$query = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_alumni = '$id_alumni' AND id_user = '$id_user_login'");
$data = mysqli_fetch_assoc($query);

// 3. Jika data tidak ditemukan (artinya ID tidak ada atau ID milik orang lain)
if (!$data) {
    echo "<script>
            alert('Akses Ditolak! Anda hanya diperbolehkan mengedit profil Anda sendiri.');
            window.location.href='dashboard_user.php';
          </script>";
    exit;
}

// 4. Proses Update Data
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);

    $update = mysqli_query($koneksi, "UPDATE alumni SET 
                nama = '$nama', 
                jurusan = '$jurusan', 
                angkatan = '$angkatan' 
                WHERE id_alumni = '$id_alumni' AND id_user = '$id_user_login'");

    if ($update) {
        echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href='dashboard_user.php';
              </script>";
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-sm">
        <div class="bg-white rounded-[1.5rem] shadow-2xl overflow-hidden border border-slate-200">
            
            <div class="pt-8 pb-4 text-center">
                <div class="inline-flex p-3 rounded-2xl bg-red-50 text-red-800 mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 vectors.5 2.5 0 113.536 3.536L17.5 21.036H14v-3.572L20.586 1.586z"/></svg>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Perbarui Profil</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Identitas Alumni</p>
            </div>

            <div class="px-8 pb-8 pt-4">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-xs font-bold"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= $data['nama'] ?>" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-100 transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Jurusan</label>
                        <select name="jurusan" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                            <option value="Teknik Komputer & Jaringan" <?= ($data['jurusan'] == 'Teknik Komputer & Jaringan') ? 'selected' : '' ?>>Teknik Komputer & Jaringan</option>
                            <option value="Rekayasa Perangkat Lunak" <?= ($data['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Jaringan Akses Telekomunikasi" <?= ($data['jurusan'] == 'Teknik Jaringan Akses Telekomunikasi') ? 'selected' : '' ?>>Teknik Jaringan Akses Telekomunikasi</option>
                            <option value="Animasi" <?= ($data['jurusan'] == 'Animasi') ? 'selected' : '' ?>>Animasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Tahun Lulus</label>
                        <input type="number" name="angkatan" value="<?= $data['angkatan'] ?>" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                    </div>

                    <div class="flex gap-2 pt-2">
                        <a href="dashboard_user.php" class="flex-1 text-center bg-slate-100 text-slate-600 py-3 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </a>
                        <button type="submit" name="update"
                            class="flex-[2] bg-red-800 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition-all shadow-lg shadow-red-900/20 uppercase tracking-widest text-[10px]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>