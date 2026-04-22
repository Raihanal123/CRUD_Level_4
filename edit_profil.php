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

// 2. VALIDASI KEPEMILIKAN
$query = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_alumni = '$id_alumni' AND id_user = '$id_user_login'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>
            alert('Akses Ditolak!');
            window.location.href='dashboard_user.php';
          </script>";
    exit;
}

// 3. Proses Update Data
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    
    // Logika Foto
    $foto_final = $data['foto']; 

    if ($_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];
        
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        // Menambah dukungan webp untuk upload jika diinginkan
        $list_ekstensi = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ekstensi, $list_ekstensi)) {
            $foto_baru = uniqid() . "." . $ekstensi;
            
            if (move_uploaded_file($tmp_name, 'uploads/' . $foto_baru)) {
                // PERBAIKAN: Pastikan tidak menghapus default.webp
                if ($data['foto'] != '' && $data['foto'] != 'default.webp' && file_exists('uploads/' . $data['foto'])) {
                    unlink('uploads/' . $data['foto']);
                }
                $foto_final = $foto_baru;
            }
        } else {
            $error = "Format file harus JPG, JPEG, PNG, atau WEBP.";
        }
    }

    if (!isset($error)) {
        $update = mysqli_query($koneksi, "UPDATE alumni SET 
                    nama = '$nama', 
                    jurusan = '$jurusan', 
                    angkatan = '$angkatan',
                    foto = '$foto_final'
                    WHERE id_alumni = '$id_alumni' AND id_user = '$id_user_login'");

        if ($update) {
            echo "<script>
                    alert('Profil berhasil diperbarui!');
                    window.location.href='dashboard_user.php';
                  </script>";
        } else {
            $error = "Gagal memperbarui database.";
        }
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #670404; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-sm">
        <div class="bg-white rounded-[2rem] shadow-2xl overflow-hidden border border-white/20">
            
            <div class="pt-8 pb-4 text-center">
                <div class="relative inline-block group">
                    <img src="uploads/<?= ($data['foto'] != '') ? $data['foto'] : 'default.webp' ?>" 
                         alt="Profile" 
                         class="w-24 h-24 rounded-3xl object-cover border-4 border-slate-50 shadow-lg">
                    
                    <div class="absolute -bottom-2 -right-2 bg-red-800 text-white p-2 rounded-xl shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-4">Perbarui Profil</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Identitas Alumni</p>
            </div>

            <div class="px-8 pb-8 pt-4">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-[11px] font-bold text-center border border-red-100 italic"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Pilih Foto Baru</label>
                        <input type="file" name="foto" accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-red-50 file:text-red-800 hover:file:bg-red-100 transition-all cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Jurusan</label>
                        <select name="jurusan" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold">
                            <option value="Teknik Komputer & Jaringan" <?= ($data['jurusan'] == 'Teknik Komputer & Jaringan') ? 'selected' : '' ?>>Teknik Komputer & Jaringan</option>
                            <option value="Rekayasa Perangkat Lunak" <?= ($data['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : '' ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Jaringan Akses" <?= ($data['jurusan'] == 'Teknik Jaringan Akses') ? 'selected' : '' ?>>Teknik Jaringan Akses</option>
                            <option value="Animasi" <?= ($data['jurusan'] == 'Animasi') ? 'selected' : '' ?>>Animasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Tahun Lulus</label>
                        <input type="number" name="angkatan" value="<?= $data['angkatan'] ?>" required
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold">
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