<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/auth.php';
require_login();

$message = '';
$error = '';
$editProduct = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $sku = trim($_POST['sku'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock'] ?? null, FILTER_VALIDATE_INT);
            $status = $_POST['status'] ?? 'Aktif';

            if ($sku === '' || $name === '' || $category === '' || $price === false || $price < 0 || $stock === false || $stock < 0 || !in_array($status, ['Aktif', 'Discontinued'], true)) {
                throw new RuntimeException('Lengkapi semua kolom dengan data yang benar.');
            }

            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE products SET sku=?, name=?, category=?, price=?, stock=?, status=? WHERE id=?');
                $stmt->execute([$sku, $name, $category, $price, $stock, $status, $id]);
                $message = 'Produk berhasil diperbarui.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO products (sku, name, category, price, stock, status) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$sku, $name, $category, $price, $stock, $status]);
                $message = 'Produk baru berhasil ditambahkan.';
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE products SET status='Discontinued' WHERE id=?");
            $stmt->execute([$id]);
            $message = 'Produk ditandai discontinued dan tetap tersimpan di database.';
        } elseif ($action === 'activate') {
            $id = (int)($_POST['id'] ?? 0);
            $stmt = $pdo->prepare("UPDATE products SET status='Aktif' WHERE id=?");
            $stmt->execute([$id]);
            $message = 'Produk diaktifkan kembali.';
        }
    } catch (PDOException $e) {
        $error = 'Gagal menyimpan data. Pastikan kode SKU tidak digunakan produk lain.';
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id=?');
    $stmt->execute([(int)$_GET['edit']]);
    $editProduct = $stmt->fetch() ?: null;
}

$search = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$sql = 'SELECT * FROM products WHERE (name LIKE ? OR sku LIKE ? OR category LIKE ?)';
$params = ["%{$search}%", "%{$search}%", "%{$search}%"];
if (in_array($statusFilter, ['Aktif', 'Discontinued'], true)) {
    $sql .= ' AND status = ?';
    $params[] = $statusFilter;
}
$sql .= ' ORDER BY id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

function rupiah($value): string {
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Produk | My Store</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="logo" href="dashboard.php"><span class="logo-mark">MS</span><span>My Store<small>Admin Panel</small></span></a>
        <nav><a class="nav-link" href="dashboard.php">▦ &nbsp; Dashboard</a><a class="nav-link active" href="products.php">▣ &nbsp; Kelola Produk</a></nav>
        <div class="sidebar-bottom"><div class="admin-mini"><span class="avatar">A</span><span><?= e($_SESSION['admin_username']) ?><small>Administrator</small></span></div><a class="nav-link logout-link" href="logout.php">↪ &nbsp; Keluar</a></div>
    </aside>
    <main class="main-content">
        <header class="topbar"><div><p class="eyebrow">KATALOG TOKO</p><h1>Kelola Produk</h1></div><div class="welcome">Admin: <b><?= e($_SESSION['admin_username']) ?></b></div></header>
        <?php if ($message): ?><div class="alert success-alert"><?= e($message) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
        <section class="panel form-panel">
            <div class="panel-heading"><div><h2><?= $editProduct ? 'Edit Produk' : 'Tambah Produk Baru' ?></h2><p>Isi informasi produk dengan lengkap.</p></div></div>
            <form method="post" class="product-form">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" value="<?= (int)($editProduct['id'] ?? 0) ?>">
                <div class="form-grid">
                    <div><label>Kode SKU</label><input name="sku" required maxlength="40" value="<?= e($editProduct['sku'] ?? '') ?>" placeholder="Contoh: PRD-026"></div>
                    <div><label>Nama Produk</label><input name="name" required maxlength="120" value="<?= e($editProduct['name'] ?? '') ?>" placeholder="Nama produk"></div>
                    <div><label>Kategori</label><input name="category" required maxlength="80" value="<?= e($editProduct['category'] ?? '') ?>" placeholder="Contoh: Makanan"></div>
                    <div><label>Harga (Rp)</label><input name="price" type="number" min="0" step="100" required value="<?= e(isset($editProduct['price']) ? (string)$editProduct['price'] : '') ?>" placeholder="15000"></div>
                    <div><label>Stok</label><input name="stock" type="number" min="0" step="1" required value="<?= e(isset($editProduct['stock']) ? (string)$editProduct['stock'] : '0') ?>"></div>
                    <div><label>Status</label><select name="status"><option value="Aktif" <?= ($editProduct['status'] ?? 'Aktif') === 'Aktif' ? 'selected' : '' ?>>Aktif</option><option value="Discontinued" <?= ($editProduct['status'] ?? '') === 'Discontinued' ? 'selected' : '' ?>>Discontinued</option></select></div>
                </div>
                <div class="form-actions"><button class="btn primary" type="submit"><?= $editProduct ? 'Simpan Perubahan' : '+ Tambah Produk' ?></button><?php if ($editProduct): ?><a class="btn secondary" href="products.php">Batal Edit</a><?php endif; ?></div>
            </form>
        </section>
        <section class="panel">
            <div class="panel-heading"><div><h2>Daftar Produk</h2><p>Total hasil pencarian: <?= count($products) ?> produk</p></div></div>
            <form class="filters" method="get"><input type="search" name="q" value="<?= e($search) ?>" placeholder="Cari nama, SKU, atau kategori..."><select name="status"><option value="">Semua status</option><option value="Aktif" <?= $statusFilter === 'Aktif' ? 'selected' : '' ?>>Aktif</option><option value="Discontinued" <?= $statusFilter === 'Discontinued' ? 'selected' : '' ?>>Discontinued</option></select><button class="btn primary" type="submit">Cari</button><a class="btn secondary" href="products.php">Reset</a></form>
            <div class="table-wrap"><table><thead><tr><th>SKU</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
            <?php if (!$products): ?><tr><td colspan="7" class="empty-state">Produk tidak ditemukan.</td></tr><?php endif; ?>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= e($p['sku']) ?></td><td><b><?= e($p['name']) ?></b></td><td><?= e($p['category']) ?></td><td><?= rupiah($p['price']) ?></td><td><?= (int)$p['stock'] ?></td>
                    <td><span class="badge <?= $p['status'] === 'Aktif' ? 'success' : 'muted-badge' ?>"><?= e($p['status']) ?></span></td>
                    <td class="actions"><a class="btn tiny secondary" href="products.php?edit=<?= (int)$p['id'] ?>">Edit</a>
                    <?php if ($p['status'] === 'Aktif'): ?>
                    <form method="post" onsubmit="return confirm('Tandai produk ini sebagai discontinued?')"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn tiny danger" type="submit">Discontinue</button></form>
                    <?php else: ?>
                    <form method="post"><input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>"><input type="hidden" name="action" value="activate"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="btn tiny primary" type="submit">Aktifkan</button></form>
                    <?php endif; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody></table></div>
        </section>
        <footer>My Store Admin Panel · CRUD Produk</footer>
    </main>
</div>
</body>
</html>
