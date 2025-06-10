<?php
require_once 'config/database.php';

$page_title = 'Pesanan Saya - CleanPro Service';
include 'includes/header.php';

$db = new Database();

$search_email = '';
$bookings = [];

// Proses pencarian berdasarkan email
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_email'])) {
    $search_email = trim($_POST['search_email']);
    
    if (!empty($search_email)) {
        $db->query('SELECT b.*, s.name as service_name, s.category 
                   FROM bookings b 
                   JOIN services s ON b.service_id = s.id 
                   WHERE b.customer_email = :email 
                   ORDER BY b.created_at DESC');
        $db->bind(':email', $search_email);
        $bookings = $db->resultset();
    }
}

// Status badges
$status_badges = [
    'requested' => 'bg-warning',
    'approved' => 'bg-success',
    'in_progress' => 'bg-info',
    'completed' => 'bg-primary',
    'cancelled' => 'bg-danger'
];

$status_labels = [
    'requested' => 'Menunggu Persetujuan',
    'approved' => 'Disetujui',
    'in_progress' => 'Sedang Dikerjakan',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan'
];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-list-alt me-2"></i>Pesanan Saya
                    </h2>
                </div>
                <div class="card-body p-5">
                    <!-- Form Pencarian -->
                    <div class="row mb-4">
                        <div class="col-md-8 mx-auto">
                            <form method="POST" class="d-flex gap-2">
                                <input type="email" class="form-control" name="search_email" 
                                       placeholder="Masukkan email Anda untuk melihat pesanan" 
                                       value="<?php echo htmlspecialchars($search_email); ?>" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </form>
                            <small class="text-muted">
                                Masukkan email yang Anda gunakan saat memesan untuk melihat status pesanan
                            </small>
                        </div>
                    </div>

                    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                        <?php if (empty($bookings)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h4>Tidak ada pesanan ditemukan</h4>
                                <p class="text-muted">Tidak ada pesanan dengan email tersebut atau belum pernah melakukan pemesanan.</p>
                                <a href="booking.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Buat Pesanan Baru
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <h5>Ditemukan <?php echo count($bookings); ?> pesanan untuk: <?php echo htmlspecialchars($search_email); ?></h5>
                            </div>
                            
                            <!-- Daftar Pesanan -->
                            <div class="row g-4">
                                <?php foreach ($bookings as $booking): ?>
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h5 class="mb-0 me-3"><?php echo htmlspecialchars($booking['service_name']); ?></h5>
                                                        <span class="badge <?php echo $status_badges[$booking['status']]; ?>">
                                                            <?php echo $status_labels[$booking['status']]; ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="row text-muted small">
                                                        <div class="col-sm-6">
                                                            <p class="mb-1">
                                                                <i class="fas fa-user me-2"></i>
                                                                <?php echo htmlspecialchars($booking['customer_name']); ?>
                                                            </p>
                                                            <p class="mb-1">
                                                                <i class="fas fa-phone me-2"></i>
                                                                <?php echo htmlspecialchars($booking['customer_phone']); ?>
                                                            </p>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <p class="mb-1">
                                                                <i class="fas fa-calendar me-2"></i>
                                                                <?php echo date('d/m/Y', strtotime($booking['booking_date'])); ?>
                                                            </p>
                                                            <p class="mb-1">
                                                                <i class="fas fa-clock me-2"></i>
                                                                <?php echo date('H:i', strtotime($booking['booking_time'])); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="mb-2">
                                                        <i class="fas fa-map-marker-alt me-2"></i>
                                                        <small><?php echo htmlspecialchars($booking['address']); ?></small>
                                                    </p>
                                                    
                                                    <?php if (!empty($booking['notes'])): ?>
                                                    <p class="mb-0">
                                                        <i class="fas fa-sticky-note me-2"></i>
                                                        <small><em><?php echo htmlspecialchars($booking['notes']); ?></em></small>
                                                    </p>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-2">
                                                        <h4 class="text-primary mb-0">
                                                            Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?>
                                                        </h4>
                                                    </div>
                                                    <small class="text-muted">
                                                        Pesanan: <?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?>
                                                    </small>
                                                    
                                                    <?php if ($booking['status'] == 'requested'): ?>
                                                    <div class="mt-2">
                                                        <small class="text-warning">
                                                            <i class="fas fa-hourglass-half me-1"></i>
                                                            Menunggu konfirmasi admin
                                                        </small>
                                                    </div>
                                                    <?php elseif ($booking['status'] == 'approved'): ?>
                                                    <div class="mt-2">
                                                        <small class="text-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Pesanan disetujui
                                                        </small>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>Cek Status Pesanan Anda</h4>
                            <p class="text-muted">Masukkan email yang Anda gunakan saat memesan untuk melihat status dan detail pesanan Anda.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>