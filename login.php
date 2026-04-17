<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #610201; /* Warna gelap solid agar lebih fokus */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-sm">
        <div class="bg-white rounded-[1.5rem] shadow-2xl overflow-hidden border border-slate-200">
            
            <div class="pt-8 pb-4 text-center">
                <div class="flex justify-center mb-4">
                    <img src="img/Logo.png" alt="Logo" class="h-12 w-auto">
                </div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Selamat Datang</h2>
                <p class="text-xs font-semibold text-slate-400">Masuk ke Sistem Alumni</p>
            </div>

            <div class="px-8 pb-8 pt-4">
                <?php if (isset($error)) { ?>
                <div class="bg-red-50 text-red-700 p-3 rounded-lg mb-4 flex items-center border border-red-100">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1-2 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/></svg>
                    <span class="text-[10px] font-bold uppercase tracking-tight"><?= $error ?></span>
                </div>
                <?php } ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input type="text" name="username" required placeholder="Username anda"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-100 transition-all text-sm font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 mb-1 ml-1 uppercase tracking-widest">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-100 transition-all text-sm font-medium">
                        </div>
                    </div>

                    <button type="submit" name="login"
                        class="w-full bg-red-800 text-white py-3 rounded-xl font-bold hover:bg-red-700 transition-all active:scale-[0.98] shadow-lg shadow-red-900/20 uppercase tracking-widest text-[10px] mt-2">
                        Masuk Sekarang
                    </button>
                </form>
            </div>

            <div class="py-4 bg-slate-50 text-center border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-500">
                    Belum punya akun? 
                    <a href="register.php" class="text-red-800 hover:underline ml-1">Daftar</a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-slate-500 text-[8px] mt-6 uppercase tracking-[0.2em] font-bold opacity-50">
            © 2026 Raihan Alfiansyah
        </p>
    </div>

</body>
</html>