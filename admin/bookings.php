<?php
session_start();
require_once '../config/database.php';

$page_title = 'Kelola Pesanan';
include 'includes/header.php';

$db = new Database();

$success_message = '';
$error_message = '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];
    
    $db->query('UPDATE bookings SET status = :status WHERE id = :id');
    $db->bind(':status', $new_status);
    $db->bind(':id', $booking_id);
    
    if ($db->execute()) {
        $success_message = 'Status pesanan berhasil diperbarui!';
    } else {
        $error_message = 'Gagal memperbarui status pesanan!';
    }
}

// Filter
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Build query
$query = 'SELECT b.*, s.name as service_name, s.category 
          FROM bookings b 
          JOIN services s ON b.service_id = s.id 
          WHERE 1=1';
$params = [];

if (!empty($status_filter)) {
    $query .= ' AND b.status = :status';
    $params[':status'] = $status_filter;
}

if (!empty($date_filter)) {
    $query .= ' AND DATE(b.booking_date) = :date';
    $params[':date'] = $date_filter;
}

$query .= ' ORDER BY b.created_at DESC';

$db->query($query);
foreach ($params as $key => $value) {
    $db->bind($key, $value);
}
$bookings = $db->resultset();

// Status options
$status_options = [
    'requested' => 'Menunggu Persetujuan',
    'approved' => 'Disetujui',
    'in_progress' => 'Sedang Dikerjakan',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan'
];

$status_badges = [
    'requested' => 'bg-warning',
    'approved' => 'bg-success',
    'in_progress' => 'bg-info',
    'completed' => 'bg-primary',
    'cancelled' => 'bg-danger'
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Kelola Pesanan</h2>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <?php foreach ($status_options as $key => $value): ?>
                    <option value="<?php echo $key; ?>" <?php echo $status_filter == $key ? 'selected' : ''; ?>>
                        <?php echo $value; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date_filter); ?>" onchange="this.form.submit()">
        </form>
    </div>
</div>

<?php if ($success_message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $success_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error_message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h4>Tidak ada pesanan ditemukan</h4>
                <p class="text-muted">Belum ada pesanan atau coba ubah filter pencarian</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo $booking['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($booking['customer_name']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars($booking['customer_email']); ?></small>
                                <br><small class="text-muted"><?php echo htmlspecialchars($booking['customer_phone']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($booking['service_name']); ?></strong>
                                <br><small class="text-muted"><?php echo ucfirst($booking['category']); ?></small>
                            </td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($booking['booking_date'])); ?>
                                <br><small><?php echo date('H:i', strtotime($booking['booking_time'])); ?></small>
                            </td>
                            <td>
                                <span class="badge <?php echo $status_badges[$booking['status']]; ?>">
                                    <?php echo $status_options[$booking['status']]; ?>
                                </span>
                            </td>
                            <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="viewBooking(<?php echo htmlspecialchars(json_encode($booking)); ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="updateStatus(<?php echo $booking['id']; ?>, '<?php echo $booking['status']; ?>')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- View Booking Modal -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="booking_id" id="status_booking_id">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Baru</label>
                        <select class="form-select" name="status" id="status_select" required>
                            <?php foreach ($status_options as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewBooking(booking) {
    const statusLabels = {
        'requested': 'Menunggu Persetujuan',
        'approved': 'Disetujui',
        'in_progress': 'Sedang Dikerjakan',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
    };
    
    const statusBadges = {
        'requested': 'bg-warning',
        'approved': 'bg-success',
        'in_progress': 'bg-info',
        'completed': 'bg-primary',
        'cancelled': 'bg-danger'
    };
    
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <h6>Informasi Pelanggan</h6>
                <p><strong>Nama:</strong> ${booking.customer_name}</p>
                <p><strong>Email:</strong> ${booking.customer_email}</p>
                <p><strong>Telepon:</strong> ${booking.customer_phone}</p>
            </div>
            <div class="col-md-6">
                <h6>Detail Layanan</h6>
                <p><strong>Layanan:</strong> ${booking.service_name}</p>
                <p><strong>Kategori:</strong> ${booking.category}</p>
                <p><strong>Harga:</strong> Rp ${parseInt(booking.total_price).toLocaleString('id-ID')}</p>
            </div>
            <div class="col-12">
                <h6>Jadwal & Lokasi</h6>
                <p><strong>Tanggal:</strong> ${new Date(booking.booking_date).toLocaleDateString('id-ID')}</p>
                <p><strong>Waktu:</strong> ${booking.booking_time}</p>
                <p><strong>Alamat:</strong> ${booking.address}</p>
            </div>
            ${booking.notes ? `
            <div class="col-12">
                <h6>Catatan</h6>
                <p>${booking.notes}</p>
            </div>
            ` : ''}
            <div class="col-12">
                <h6>Status & Waktu</h6>
                <p><strong>Status:</strong> <span class="badge ${statusBadges[booking.status]}">${statusLabels[booking.status]}</span></p>
                <p><strong>Dibuat:</strong> ${new Date(booking.created_at).toLocaleString('id-ID')}</p>
            </div>
        </div>
    `;
    
    document.getElementById('bookingDetails').innerHTML = content;
    new bootstrap.Modal(document.getElementById('viewBookingModal')).show();
}

function updateStatus(bookingId, currentStatus) {
    document.getElementById('status_booking_id').value = bookingId;
    document.getElementById('status_select').value = currentStatus;
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}
</script>

<?php include 'includes/footer.php'; ?>