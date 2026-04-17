<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    // 1. Ambil data dari form
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_raw = $_POST['password'];
    
    // 2. Hash password (KEAMANAN WAJIB)
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
    $role = 'user'; // Default pendaftar adalah user biasa

    // 3. Masukkan ke tabel 'users' untuk keperluan login
    $query_user = "INSERT INTO users (username, password, role) 
                   VALUES ('$username', '$password_hashed', '$role')";
    
    if (mysqli_query($koneksi, $query_user)) {
        // 4. Masukkan ke tabel 'alumni' agar OTOMATIS muncul di dashboard
        $query_alumni = "INSERT INTO alumni (nama, jurusan, angkatan) 
                         VALUES ('$nama', '$jurusan', '$angkatan')";
        
        if (mysqli_query($koneksi, $query_alumni)) {
            echo "<script>
                    alert('Registrasi Berhasil! Data Anda sudah masuk ke sistem.');
                    window.location.href='login.php';
                  </script>";
        }
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 rounded-[2rem] shadow-2xl w-full max-w-md border border-slate-200">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">REGISTRASI</h2>
            <p class="text-slate-500 font-medium">Lengkapi biodata alumni Anda</p>
        </div>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                <input type="text" name="nama" required placeholder="Nama sesuai ijazah"
                       class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 focus:border-red-800 focus:outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Jurusan</label>
                <select name="jurusan" required 
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 focus:border-red-800 focus:outline-none transition-all bg-white">
                    <option value="">Pilih Jurusan</option>
                    <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                    <option value="Teknik Jaringan Akses Telekomunikasi">Teknik Jaringan Akses Telekomunikasi</option>
                    <option value="Animasi">Animasi</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tahun Lulus (Angkatan)</label>
                <input type="number" name="angkatan" required placeholder="Contoh: 2024"
                       class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 focus:border-red-800 focus:outline-none transition-all">
            </div>

            <div class="h-[1px] bg-slate-100 my-6"></div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Username</label>
                <input type="text" name="username" required placeholder="Untuk login nanti"
                       class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 focus:border-red-800 focus:outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                       class="w-full px-4 py-3 rounded-xl border-2 border-slate-100 focus:border-red-800 focus:outline-none transition-all">
            </div>

            <button type="submit" name="register" 
                    class="w-full bg-red-800 hover:bg-red-900 text-white font-black py-4 rounded-2xl shadow-xl shadow-red-900/20 transition-all active:scale-95 uppercase tracking-widest text-sm">
                Buat Akun Sekarang
            </button>
        </form>

        <p class="text-center mt-8 text-slate-500 text-sm">
            Sudah punya akun? <a href="login.php" class="text-red-800 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>

</body>
</html>