<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $angkatan = mysqli_real_escape_string($koneksi, $_POST['angkatan']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_raw = $_POST['password'];
    
    // Keamanan: Hash password agar tidak bisa dibaca langsung di database
    $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
    $role = 'user'; 

    // 1. Masukkan data ke tabel 'users' untuk keperluan login
    $query_user = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hashed', '$role')";
    
    if (mysqli_query($koneksi, $query_user)) {
        // 2. Ambil ID user yang baru saja dibuat oleh sistem
        $id_user_baru = mysqli_insert_id($koneksi); 

        // 3. Masukkan ke tabel 'alumni' dengan menyertakan 'profile.jpg' sebagai foto default
        $query_alumni = "INSERT INTO alumni (id_user, nama, jurusan, angkatan, foto) 
                         VALUES ('$id_user_baru', '$nama', '$jurusan', '$angkatan', 'profile.jpg')";
        
        if (mysqli_query($koneksi, $query_alumni)) {
            echo "<script>
                    alert('Registrasi Berhasil! Foto profil default telah diset. Silakan Login.');
                    window.location.href='login.php';
                  </script>";
        }
    } else {
        $error = "Pendaftaran gagal: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Alumni SMK Telkom</title>
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
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md">
        <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden border border-white/40">
            
            <div class="pt-10 pb-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-800 text-white rounded-2xl mb-4 shadow-lg shadow-red-900/30 rotate-3 hover:rotate-0 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">JOIN ALUMNI</h2>
                <p class="text-[10px] font-black text-red-800/60 uppercase tracking-[0.3em] mt-1">Akun baru otomatis menggunakan foto profile.jpg</p>
            </div>

            <div class="px-10 pb-8 pt-4">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 text-red-700 p-3 rounded-xl mb-4 text-[10px] font-bold text-center border border-red-100 italic"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="nama" required placeholder="Masukkan nama sesuai ijazah"
                                class="w-full px-5 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-800 focus:ring-4 focus:ring-red-900/5 transition-all text-sm font-semibold shadow-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Jurusan</label>
                                <select name="jurusan" required 
                                    class="w-full px-4 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-800 transition-all text-[11px] font-bold appearance-none cursor-pointer">
                                    <option value="">Pilih...</option>
                                    <option value="Teknik Komputer & Jaringan">TKJ</option>
                                    <option value="Rekayasa Perangkat Lunak">RPL</option>
                                    <option value="Teknik Jaringan Akses Telekomunikasi">TJA</option>
                                    <option value="Animasi">Animasi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Tahun Lulus</label>
                                <input type="number" name="angkatan" required placeholder="2024"
                                    class="w-full px-5 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center"><span class="w-full border-t border-slate-200"></span></div>
                        <div class="relative flex justify-center text-[8px] uppercase font-black tracking-[0.3em]"><span class="bg-white px-3 text-slate-400 rounded-full border border-slate-100">Kredensial Login</span></div>
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Username</label>
                            <input type="text" name="username" required placeholder="Contoh: raihan123"
                                class="w-full px-5 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold shadow-sm">
                        </div>

                        <div>
                            <label class="block text-[9px] font-black text-slate-400 mb-1 ml-1 uppercase tracking-widest">Password</label>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full px-5 py-3 bg-white/50 border border-slate-200 rounded-2xl focus:outline-none focus:border-red-800 transition-all text-sm font-semibold shadow-sm">
                        </div>
                    </div>

                    <button type="submit" name="register"
                        class="w-full bg-red-800 text-white py-4 rounded-2xl font-black hover:bg-red-900 transition-all active:scale-95 shadow-xl shadow-red-900/20 uppercase tracking-widest text-[10px] mt-6">
                        Daftar
                    </button>
                </form>
            </div>

            <div class="py-6 bg-slate-50/50 text-center border-t border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    Sudah punya akun? 
                    <a href="login.php" class="text-red-800 hover:text-red-600 ml-1 transition-colors">Masuk Sekarang</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>