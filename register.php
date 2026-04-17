<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_raw = $_POST['password'];
    
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
    $role = 'user'; 

    // 1. Masukkan ke tabel 'users'
    $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', '$role')";
    
    if (mysqli_query($koneksi, $query_user)) {
        // 2. Ambil ID yang baru saja dibuat untuk menghubungkan ke tabel alumni
        $id_user_baru = mysqli_insert_id($koneksi); 

        // 3. Masukkan ke tabel 'alumni' dengan menyertakan id_user
        $query_alumni = "INSERT INTO alumni (id_user, nama, jurusan, angkatan) 
                         VALUES ('$id_user_baru', '$nama', '$jurusan', '$angkatan')";
        
        if (mysqli_query($koneksi, $query_alumni)) {
            echo "<script>
                    alert('Registrasi Berhasil! Silakan Login.');
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
    <title>Daftar | Sistem Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #610201; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-sm">
        <div class="bg-white rounded-[1.5rem] shadow-2xl overflow-hidden border border-slate-200">
            
            <div class="pt-8 pb-4 text-center">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight uppercase">Registrasi</h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi Biodata Alumni</p>
            </div>

            <div class="px-8 pb-8 pt-4">
                <form method="POST" class="space-y-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Nama Lengkap"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-100 transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Jurusan</label>
                        <select name="jurusan" required 
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                            <option value="">Pilih Jurusan</option>
                            <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
                            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Jaringan Akses Telekomunikasi">Teknik Jaringan Akses Telekomunikasi</option>
                            <option value="Animasi">Animasi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Tahun Lulus</label>
                        <input type="number" name="angkatan" required placeholder="2024"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                    </div>

                    <div class="h-[1px] bg-slate-100 my-4"></div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Username</label>
                        <input type="text" name="username" required placeholder="Username"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Password</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 transition-all text-sm font-medium">
                    </div>

                    <button type="submit" name="register"
                        class="w-full bg-red-800 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition-all active:scale-[0.98] shadow-lg shadow-red-900/20 uppercase tracking-widest text-[10px] mt-4">
                        Daftar Akun
                    </button>
                </form>
            </div>

            <div class="py-4 bg-slate-50 text-center border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-500">
                    Sudah punya akun? 
                    <a href="login.php" class="text-red-800 hover:underline ml-1">Login</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>