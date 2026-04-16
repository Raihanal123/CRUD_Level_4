<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil ID
$id = $_GET['id'];

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM alumni WHERE id_alumni='$id'");
$d = mysqli_fetch_assoc($data);

// Proses update
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($koneksi, "UPDATE alumni SET 
        nama='$nama', 
        angkatan='$angkatan', 
        jurusan='$jurusan' 
        WHERE id_alumni='$id'
    ");

    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_user.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Data Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border border-gray-100">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 text-red-700 rounded-full mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-800">Edit Data Alumni</h2>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi profil alumni di bawah ini.</p>
        </div>

        <form method="POST" class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1 ml-1 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" name="nama" value="<?= $d['nama']; ?>" required
                    placeholder="Contoh: Raihan Alfiansyah"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-800/20 focus:border-red-800 transition-all placeholder-gray-300">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1 ml-1 uppercase tracking-wider">Angkatan</label>
                <input type="text" name="angkatan" value="<?= $d['angkatan']; ?>" required
                    placeholder="Contoh: 2020"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-800/20 focus:border-red-800 transition-all placeholder-gray-300 font-mono">
            </div>

             <div class="group">
                <label class="block text-xs font-bold text-gray-400 mb-1 ml-1 uppercase tracking-widest group-focus-within:text-red-900 transition-colors">
                    Program Studi / Jurusan
                </label>
                <div class="group">
                <div class="relative">
                    <select name="jurusan" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-900/10 focus:border-red-900 transition-all font-medium appearance-none cursor-pointer">
                        <option value="" disabled selected>Pilih Jurusan</option>
                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                        <option value="Teknik Komunikasi dan Jaringan">Teknik Komunikasi dan Jaringan</option>
                        <option value="Animasi">Animasi</option>
                        <option value="Teknik Jaringan Akses Telekomunikasi">Teknik Jaringan Akses Telekomunikasi</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" name="submit"
                    class="w-full bg-red-800 text-white py-3 rounded-xl font-bold hover:bg-red-900 transition-all transform active:scale-[0.98] shadow-lg shadow-red-900/20">
                    Simpan Perubahan
                </button>
            </div>

            <a href="<?= ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') ? 'dashboard_admin.php' : 'dashboard_user.php'; ?>"
                class="flex items-center justify-center text-sm font-semibold text-gray-400 hover:text-red-800 transition-colors py-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>

        </form>

    </div>

</body>

</html>