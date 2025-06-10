<?php
require_once 'config/database.php';

$page_title = 'CleanPro Service - Layanan Pembersihan Profesional';
include 'includes/header.php';

$db = new Database();

// Ambil beberapa layanan untuk ditampilkan
$db->query('SELECT * FROM services WHERE status = "active" LIMIT 4');
$featured_services = $db->resultset();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Layanan Pembersihan Profesional</h1>
                <p class="lead mb-4">Kami menyediakan layanan pembersihan terbaik untuk rumah dan kantor Anda. Dengan tim profesional dan peralatan modern.</p>
                <div class="d-flex gap-3">
                    <a href="booking.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>Pesan Sekarang
                    </a>
                    <a href="services.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-list me-2"></i>Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://images.pexels.com/photos/4239146/pexels-photo-4239146.jpeg?auto=compress&cs=tinysrgb&w=600" 
                     alt="Cleaning Service" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold mb-3">Mengapa Memilih Kami?</h2>
                <p class="text-muted">Kami berkomitmen memberikan layanan terbaik dengan standar kualitas tinggi</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-users text-white fa-2x"></i>
                    </div>
                    <h4>Tim Profesional</h4>
                    <p class="text-muted">Tim berpengalaman dan terlatih dengan sertifikasi internasional</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-leaf text-white fa-2x"></i>
                    </div>
                    <h4>Ramah Lingkungan</h4>
                    <p class="text-muted">Menggunakan produk pembersih yang aman dan ramah lingkungan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-clock text-white fa-2x"></i>
                    </div>
                    <h4>Tepat Waktu</h4>
                    <p class="text-muted">Layanan tepat waktu sesuai jadwal yang telah disepakati</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold mb-3">Layanan Unggulan</h2>
                <p class="text-muted">Pilihan layanan terbaik untuk kebutuhan pembersihan Anda</p>
            </div>
        </div>
        <div class="row g-4">
            <?php foreach($featured_services as $service): ?>
            <div class="col-lg-3 col-md-6">
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
                            ?>
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="<?php echo $icons[$service['category']]; ?> text-white fa-lg"></i>
                            </div>
                        </div>
                        <h5 class="card-title text-center mb-3"><?php echo htmlspecialchars($service['name']); ?></h5>
                        <p class="card-text text-muted small"><?php echo htmlspecialchars(substr($service['description'], 0, 100)) . '...'; ?></p>
                        <div class="text-center">
                            <span class="price-badge">Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></span>
                            <p class="small text-muted mt-2">
                                <i class="fas fa-clock me-1"></i><?php echo $service['duration']; ?> jam
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="services.php" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right me-2"></i>Lihat Semua Layanan
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold mb-2">Siap untuk Rumah yang Bersih?</h3>
                <p class="mb-0">Hubungi kami sekarang dan dapatkan konsultasi gratis untuk kebutuhan pembersihan Anda</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="booking.php" class="btn btn-light btn-lg">
                    <i class="fas fa-phone me-2"></i>Pesan Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>