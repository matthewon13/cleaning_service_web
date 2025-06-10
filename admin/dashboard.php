<?php
session_start();
require_once '../config/database.php';

$page_title = 'Dashboard';
include 'includes/header.php';

$db = new Database();

// Statistik
$db->query('SELECT COUNT(*) as total FROM services WHERE status = "active"');
$total_services = $db->single()['total'];

$db->query('SELECT COUNT(*) as total FROM bookings');
$total_bookings = $db->single()['total'];

$db->query('SELECT COUNT(*) as total FROM bookings WHERE status = "requested"');
$pending_bookings = $db->single()['total'];

$db->query('SELECT SUM(total_price) as total FROM bookings WHERE status IN ("approved", "completed")');
$total_revenue = $db->single()['total'] ?? 0;

// Pesanan terbaru
$db->query('SELECT b.*, s.name as service_name 
           FROM bookings b 
           JOIN services s ON b.service_id = s.id 
           ORDER BY b.created_at DESC 
           LIMIT 5');
$recent_bookings = $db->resultset();

// Data untuk chart (pesanan per bulan)
$db->query('SELECT DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count 
           FROM bookings 
           WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
           GROUP BY DATE_FORMAT(created_at, "%Y-%m")
           ORDER BY month');
$monthly_bookings = $db->resultset();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <div class="text-muted">
        <i class="fas fa-calendar me-2"></i><?php echo date('d F Y'); ?>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-0"><?php echo $total_services; ?></h3>
                        <p class="mb-0">Total Layanan</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-concierge-bell fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card-2">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-0"><?php echo $total_bookings; ?></h3>
                        <p class="mb-0">Total Pesanan</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-calendar-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card-3">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-0"><?php echo $pending_bookings; ?></h3>
                        <p class="mb-0">Menunggu Persetujuan</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6">
        <div class="card stats-card-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="fw-bold mb-0">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <div class="ms-3">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Bookings -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i>Pesanan Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recent_bookings)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada pesanan</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_bookings as $booking): ?>
                                <tr>
                                    <td>
                                        <div>
                                            <strong><?php echo htmlspecialchars($booking['customer_name']); ?></strong>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($booking['customer_email']); ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($booking['booking_date'])); ?></td>
                                    <td>
                                        <?php
                                        $status_badges = [
                                            'requested' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'in_progress' => 'bg-info',
                                            'completed' => 'bg-primary',
                                            'cancelled' => 'bg-danger'
                                        ];
                                        $status_labels = [
                                            'requested' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'in_progress' => 'Dikerjakan',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                        ?>
                                        <span class="badge <?php echo $status_badges[$booking['status']]; ?>">
                                            <?php echo $status_labels[$booking['status']]; ?>
                                        </span>
                                    </td>
                                    <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="services.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Layanan Baru
                    </a>
                    <a href="bookings.php" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>Lihat Semua Pesanan
                    </a>
                    <a href="bookings.php?status=requested" class="btn btn-outline-warning">
                        <i class="fas fa-clock me-2"></i>Pesanan Menunggu
                        <?php if ($pending_bookings > 0): ?>
                            <span class="badge bg-warning text-dark ms-2"><?php echo $pending_bookings; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="card mt-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                </h5>
            </div>
            <div class="card-body">
                <div class="small">
                    <div class="d-flex justify-content-between mb-2">
                        <span>PHP Version:</span>
                        <span><?php echo PHP_VERSION; ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Server Time:</span>
                        <span><?php echo date('H:i:s'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Admin:</span>
                        <span><?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>