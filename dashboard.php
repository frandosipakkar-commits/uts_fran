<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$total = (int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$active = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE status = 'Aktif'")->fetchColumn();
$discontinued = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE status = 'Discontinued'")->fetchColumn();
$recent = $pdo->query('SELECT * FROM products ORDER BY id DESC LIMIT 6')->fetchAll();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin | My Store</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="logo" href="dashboard.php"><span class="logo-mark">MS</span><span>My Store<small>Admin Panel</small></span></a>
        <nav>
            <a class="nav-link active" href="dashboard.php">▦ &nbsp; Dashboard</a>
            <a class="nav-link" href="products.php">▣ &nbsp; Kelola Produk</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="admin-mini"><span class="avatar">A</span><span><?= e($_SESSION['admin_username']) ?><small>Administrator</small></span></div>
            <a class="nav-link logout-link" href="logout.php">↪ &nbsp; Keluar</a>
        </div>
    </aside>
    <main class="main-content">
        <header class="topbar"><div><p class="eyebrow">RINGKASAN TOKO</p><h1>Dashboard</h1></div><div class="welcome">Selamat datang, <b><?= e($_SESSION['admin_username']) ?></b> 👋</div></header>
        <section class="welcome-banner">
            <div><p class="eyebrow light">ADMINISTRATION</p><h2>Kelola My Store dengan mudah.</h2><p>Pantau data produk dan perbarui katalog toko dari satu halaman.</p><a class="btn white" href="products.php">Kelola Produk →</a></div>
            <div class="banner-symbol">▣</div>
        </section>
        <section class="stats-grid">
            <article class="stat-card"><div class="stat-icon purple">▣</div><p>Total Produk</p><strong><?= $total ?></strong><small>Semua data produk</small></article>
            <article class="stat-card"><div class="stat-icon green">✓</div><p>Produk Aktif</p><strong><?= $active ?></strong><small>Masih dijual</small></article>
            <article class="stat-card"><div class="stat-icon orange">Ⅱ</div><p>Discontinued</p><strong><?= $discontinued ?></strong><small>Tidak diproduksi lagi</small></article>
        </section>
        <section class="panel">
            <div class="panel-heading"><div><h2>Produk Terbaru</h2><p>Enam produk terakhir di dalam database.</p></div><a class="btn primary" href="products.php">+ Lihat Semua Produk</a></div>
            <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th></tr></thead><tbody>
            <?php foreach ($recent as $p): ?>
                <tr><td><b><?= e($p['name']) ?></b><small class="subtext"><?= e($p['sku']) ?></small></td><td><?= e($p['category']) ?></td><td>Rp <?= number_format((float)$p['price'], 0, ',', '.') ?></td><td><?= (int)$p['stock'] ?></td><td><span class="badge <?= $p['status'] === 'Aktif' ? 'success' : 'muted-badge' ?>"><?= e($p['status']) ?></span></td></tr>
            <?php endforeach; ?>
            </tbody></table></div>
        </section>
        <footer>My Store Admin Panel · Project Praktik Pemrograman Web 2</footer>
    </main>
</div>
</body>
</html>
