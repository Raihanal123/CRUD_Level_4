<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Hanya admin
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'superadmin') {
    echo "Akses ditolak!";
    exit;
}

// Proses tambah data
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($koneksi, "INSERT INTO alumni (nama, angkatan, jurusan) VALUES ('$nama', '$angkatan', '$jurusan')");

    header("Location: dashboard_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border border-gray-100 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-red-900"></div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-50 text-red-900 rounded-2xl mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Tambah Alumni</h2>
            <p class="text-gray-500 text-sm mt-1">Masukkan informasi data alumni baru ke sistem.</p>
        </div>

        <form method="POST" class="space-y-5">

            <div class="group">
                <label class="block text-xs font-bold text-gray-400 mb-1 ml-1 uppercase tracking-widest group-focus-within:text-red-900 transition-colors">
                    Nama Lengkap
                </label>
                <input type="text" name="nama" required
                    placeholder="Masukan nama anda"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-900/10 focus:border-red-900 transition-all font-medium">
            </div>

            <div class="group">
                <label class="block text-xs font-bold text-gray-400 mb-1 ml-1 uppercase tracking-widest group-focus-within:text-red-900 transition-colors">
                    Tahun Angkatan
                </label>
                <input type="text" name="angkatan" required
                    placeholder="Masukan tahun angkatan"
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-900/10 focus:border-red-900 transition-all font-medium">
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
                    class="w-full bg-red-900 text-white py-3.5 rounded-xl font-bold hover:bg-red-800 transition-all transform active:scale-[0.98] shadow-xl shadow-red-950/20 flex items-center justify-center tracking-wide">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Simpan Data Alumni
                </button>
            </div>

            <a href="dashboard_admin.php" 
                class="flex items-center justify-center text-sm font-semibold text-gray-400 hover:text-red-900 transition-colors py-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>

        </form>

    </div>

</body>

</html>