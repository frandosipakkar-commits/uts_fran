<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin | My Store</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
    <main class="login-card">
        <div class="brand-icon">MS</div>
        <p class="eyebrow">MY STORE MANAGEMENT</p>
        <h1>Login Admin</h1>
        <p class="muted">Masuk untuk mengelola produk toko.</p>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <label for="username">Username</label>
            <input id="username" name="username" type="text" placeholder="Masukkan username" required autofocus>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Masukkan password" required>
            <button class="btn primary full" type="submit">Masuk ke Dashboard</button>
        </form>
        <div class="login-hint">Akun awal: <b>admin</b> / <b>admin123</b></div>
    </main>
</body>
</html>
