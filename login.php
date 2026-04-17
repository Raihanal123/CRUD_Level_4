<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    // Menggunakan mysqli_real_escape_string untuk mencegah SQL Injection
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        /* PERUBAHAN DISINI:
           Ganti '$password == $user['password']' menjadi 'password_verify()'
           Ini karena password di database sekarang sudah di-hash (diacak)
        */
        if (password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin' || $user['role'] == 'superadmin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "User tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Alumni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top left, #450a0a, #000000);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="relative w-full max-w-md">
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-red-800 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-red-900 rounded-full blur-3xl opacity-20"></div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden relative border border-white/10">
            
            <div class="bg-white p-10 text-center">
                <div class="flex justify-center mb-6">
                    <img src="img/Logo.png" alt="Logo Alumni" class="h-16 w-auto drop-shadow-2xl">
                </div>
                <h2 class="text-3xl font-extrabold text-red-950 tracking-tight">Data Alumni</h2>
            </div>

            <div class="p-10">
                <?php if (isset($error)) { ?>
                <div class="bg-red-50 border-l-4 border-red-700 text-red-800 p-4 rounded-xl mb-6 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1-2 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="text-[11px] font-black uppercase italic tracking-wider"><?= $error ?></span>
                </div>
                <?php } ?>

                <form method="POST" class="space-y-6">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 mb-2 ml-1 uppercase tracking-[0.2em] group-focus-within:text-red-800 transition-colors">
                            Identitas Pengguna
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input type="text" name="username" required placeholder="Username"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-900/5 focus:border-red-900 transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 mb-2 ml-1 uppercase tracking-[0.2em] group-focus-within:text-red-800 transition-colors">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-red-900/5 focus:border-red-900 transition-all font-bold text-slate-700">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" name="login"
                            class="w-full bg-red-900 text-white py-4 rounded-2xl font-black hover:bg-red-800 transition-all transform active:scale-[0.97] shadow-xl shadow-red-950/20 uppercase tracking-[0.2em] text-xs flex items-center justify-center">
                            Masuk Ke Sistem
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="p-8 bg-slate-50 text-center border-t border-slate-100">
                <p class="text-xs font-bold text-slate-500">
                    Belum Punya akun? 
                    <a href="register.php" class="text-red-800 hover:text-red-600 transition-colors ml-1 underline decoration-2 underline-offset-4">Buat Akun</a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-red-300/30 text-[9px] mt-8 uppercase tracking-[0.4em] font-bold">
            © 2026 Copyright.Raihan Alfianysah
        </p>
    </div>

</body>
</html>