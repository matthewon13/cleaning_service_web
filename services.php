<?php
require_once 'config/database.php';

$page_title = 'Layanan Kami - CleanPro Service';
include 'includes/header.php';

$db = new Database();

// Filter berdasarkan kategori
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = 'SELECT * FROM services WHERE status = "active"';
$params = [];

if (!empty($category_filter)) {
    $query .= ' AND category = :category';
    $params[':category'] = $category_filter;
}

if (!empty($search)) {
    $query .= ' AND (name LIKE :search OR description LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}

$query .= ' ORDER BY created_at DESC';

$db->query($query);
foreach ($params as $key => $value) {
    $db->bind($key, $value);
}
$services = $db->resultset();

// Kategori untuk filter
$categories = [
    'residential' => 'Rumah Tinggal',
    'commercial' => 'Komersial',
    'deep-cleaning' => 'Deep Cleaning',
    'maintenance' => 'Maintenance'
];
?>

<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="fw-bold mb-3">Layanan Pembersihan Kami</h1>
            <p class="text-muted">Pilih layanan yang sesuai dengan kebutuhan Anda</p>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" class="d-flex gap-3">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $key => $value): ?>
                        <option value="<?php echo $key; ?>" <?php echo $category_filter == $key ? 'selected' : ''; ?>>
                            <?php echo $value; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="search" class="form-control" placeholder="Cari layanan..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="booking.php" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Pesan Layanan
            </a>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="row g-4">
        <?php if (empty($services)): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4>Tidak ada layanan ditemukan</h4>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian</p>
            </div>
        <?php else: ?>
            <?php foreach($services as $service): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card service-card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <?php
                            $icons = [
                                'residential' => 'fas fa-home',
                                'commercial' => 'fas fa-building',
                                'deep-cleaning' => 'fas fa-sparkles',
                                'maintenance' => 'fas fa-tools'
                            ];
                            $colors = [
                                'residential' => 'primary',
                                'commercial' => 'success',
                                'deep-cleaning' => 'info',
                                'maintenance' => 'warning'
                            ];
                            ?>
                            <div class="bg-<?php echo $colors[$service['category']]; ?> rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 70px; height: 70px;">
                                <i class="<?php echo $icons[$service['category']]; ?> text-white fa-2x"></i>
                            </div>
                        </div>
                        
                        <h5 class="card-title text-center mb-3"><?php echo htmlspecialchars($service['name']); ?></h5>
                        
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2"><?php echo $categories[$service['category']]; ?></span>
                        </div>
                        
                        <p class="card-text text-muted"><?php echo htmlspecialchars($service['description']); ?></p>
                        
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="price-badge">
                                    Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <p class="mb-0">
                                    <i class="fas fa-clock me-1"></i><?php echo $service['duration']; ?> jam
                                </p>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="booking.php?service_id=<?php echo $service['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>