<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user) {
        if ($password == $user['password']) {
            $_SESSION['login'] = true;
            $_SESSION['id'] = $user['user_id'];
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
    <style>
        body {
            /* Background dengan gradasi merah gelap yang elegan */
            background: radial-gradient(circle at top left, #450a0a, #000000);
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

    <div class="relative w-full max-w-md">
        <div class="absolute -top-10 -left-10 w-32 h-32 bg-red-800 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-red-900 rounded-full blur-3xl opacity-20"></div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden relative border border-white/10">
            
            <div class="bg-red-950 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-red-900 rounded-2xl rotate-12 mb-4 shadow-xl border border-red-800">
                    <svg class="w-8 h-8 text-white -rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">Sistem Alumni</h2>
                <p class="text-red-300/60 text-sm mt-1 uppercase tracking-widest font-semibold">Authentication Gateway</p>
            </div>

            <div class="p-8">
                <?php if (isset($error)) { ?>
                <div class="bg-red-50 border-l-4 border-red-700 text-red-800 p-4 rounded-lg mb-6 flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1-2 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span class="text-xs font-bold uppercase italic"><?= $error ?></span>
                </div>
                <?php } ?>

                <form method="POST" class="space-y-6">

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 uppercase tracking-widest group-focus-within:text-red-800 transition-colors">
                            Identitas Pengguna
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <input type="text" name="username" required placeholder="Username"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-900/10 focus:border-red-900 transition-all font-medium">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold text-gray-500 mb-2 ml-1 uppercase tracking-widest group-focus-within:text-red-800 transition-colors">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </span>
                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-red-900/10 focus:border-red-900 transition-all font-medium">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" name="login"
                            class="w-full bg-red-900 text-white py-3.5 rounded-xl font-bold hover:bg-red-800 transition-all transform active:scale-[0.98] shadow-xl shadow-red-950/20 uppercase tracking-widest text-sm flex items-center justify-center">
                            Masuk Ke Sistem
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>

                </form>
            </div>

            <div class="p-6 bg-gray-50 text-center border-t border-gray-100">
                <p class="text-[10px] text-gray-400 uppercase tracking-[3px] font-bold">
                   
                </p>
            </div>
        </div>
        
        <p class="text-center text-red-300/30 text-[10px] mt-8 uppercase tracking-widest">
            © 2026 Secured Alumni Management System
        </p>
    </div>

</body>

</html>