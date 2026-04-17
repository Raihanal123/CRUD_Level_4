<?php
include 'koneksi.php';

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'user';

    // 1. Validasi: Apakah ada field yang kosong?
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi!";
    } 
    // 2. Validasi: Apakah password dan konfirmasi cocok?
    elseif ($password !== $confirm_password) {
        $error = "Konfirmasi password tidak sesuai!";
    } 
    else {
        // 3. Validasi: Cek apakah username sudah digunakan
        $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
        
        if (mysqli_num_rows($cek_user) > 0) {
            $error = "Username '$username' sudah terdaftar. Gunakan nama lain.";
        } else {
            // 4. Keamanan: Hash password sebelum disimpan
            // Password tidak boleh disimpan dalam teks biasa (plain text)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // 5. Eksekusi: Simpan ke database
            $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
            
            if (mysqli_query($koneksi, $query)) {
                $success = "Akun berhasil dibuat! Silakan login.";
            } else {
                $error = "Terjadi kesalahan sistem: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Sistem Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #450a0a, #000000);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="relative w-full max-w-md">
        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-white/10">
            
            <div class="bg-red-950 p-10 text-center">
                <div class="flex justify-center mb-6">
                    <img src="img/Logo.png" alt="Logo Alumni" class="h-16 w-auto drop-shadow-2xl">
                </div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Buat Akun</h2>
                <p class="text-red-300/60 text-[10px] mt-2 uppercase tracking-[0.3em] font-bold">Join Alumni Network</p>
            </div>

            <div class="p-10">
                <?php if ($error): ?>
                    <div class="bg-red-50 border-l-4 border-red-700 text-red-800 p-4 rounded-xl mb-6 text-[11px] font-black uppercase tracking-wider italic">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 rounded-xl mb-6 text-[11px] font-black uppercase tracking-wider italic">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-5">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 mb-2 ml-1 uppercase tracking-[0.2em] group-focus-within:text-red-800">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input type="text" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required placeholder="Username baru"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-red-900 transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 mb-2 ml-1 uppercase tracking-[0.2em] group-focus-within:text-red-800">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required placeholder="Buat password"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-red-900 transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 mb-2 ml-1 uppercase tracking-[0.2em] group-focus-within:text-red-800">Konfirmasi Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </span>
                            <input type="password" name="confirm_password" required placeholder="Ulangi password"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:border-red-900 transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" name="register"
                            class="w-full bg-red-900 text-white py-4 rounded-2xl font-black hover:bg-red-800 transition-all transform active:scale-[0.97] shadow-xl shadow-red-950/20 uppercase tracking-[0.2em] text-xs flex items-center justify-center">
                            Daftarkan Sekarang
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-8 bg-slate-50 text-center border-t border-slate-100">
                <p class="text-xs font-bold text-slate-500">
                    Sudah punya akun? 
                    <a href="login.php" class="text-red-800 hover:text-red-600 ml-1 underline decoration-2 underline-offset-4">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>