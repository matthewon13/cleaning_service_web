<?php
require_once 'config/database.php';

$page_title = 'Pesan Layanan - CleanPro Service';
include 'includes/header.php';

$db = new Database();

// Ambil semua layanan aktif
$db->query('SELECT * FROM services WHERE status = "active" ORDER BY name');
$services = $db->resultset();

// Jika ada service_id dari parameter
$selected_service_id = isset($_GET['service_id']) ? $_GET['service_id'] : '';

$success_message = '';
$error_message = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $customer_email = trim($_POST['customer_email']);
    $customer_phone = trim($_POST['customer_phone']);
    $service_id = $_POST['service_id'];
    $address = trim($_POST['address']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $notes = trim($_POST['notes']);

    // Validasi
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || 
        empty($service_id) || empty($address) || empty($booking_date) || empty($booking_time)) {
        $error_message = 'Semua field wajib diisi!';
    } else {
        // Ambil harga layanan
        $db->query('SELECT price FROM services WHERE id = :service_id');
        $db->bind(':service_id', $service_id);
        $service = $db->single();
        
        if ($service) {
            // Insert booking
            $db->query('INSERT INTO bookings (customer_name, customer_email, customer_phone, service_id, address, booking_date, booking_time, notes, total_price) 
                       VALUES (:customer_name, :customer_email, :customer_phone, :service_id, :address, :booking_date, :booking_time, :notes, :total_price)');
            
            $db->bind(':customer_name', $customer_name);
            $db->bind(':customer_email', $customer_email);
            $db->bind(':customer_phone', $customer_phone);
            $db->bind(':service_id', $service_id);
            $db->bind(':address', $address);
            $db->bind(':booking_date', $booking_date);
            $db->bind(':booking_time', $booking_time);
            $db->bind(':notes', $notes);
            $db->bind(':total_price', $service['price']);
            
            if ($db->execute()) {
                $success_message = 'Pesanan berhasil dibuat! Kami akan menghubungi Anda segera.';
                // Reset form
                $customer_name = $customer_email = $customer_phone = $address = $booking_date = $booking_time = $notes = '';
                $selected_service_id = '';
            } else {
                $error_message = 'Terjadi kesalahan saat menyimpan pesanan.';
            }
        } else {
            $error_message = 'Layanan tidak ditemukan.';
        }
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Pesan Layanan Cleaning
                    </h2>
                </div>
                <div class="card-body p-5">
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

                    <form method="POST" id="bookingForm">
                        <div class="row g-3">
                            <!-- Informasi Pelanggan -->
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Pelanggan
                                </h5>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                       value="<?php echo isset($customer_name) ? htmlspecialchars($customer_name) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                       value="<?php echo isset($customer_email) ? htmlspecialchars($customer_email) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">No. Telepon *</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                                       value="<?php echo isset($customer_phone) ? htmlspecialchars($customer_phone) : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="service_id" class="form-label">Pilih Layanan *</label>
                                <select class="form-select" id="service_id" name="service_id" required>
                                    <option value="">-- Pilih Layanan --</option>
                                    <?php foreach ($services as $service): ?>
                                        <option value="<?php echo $service['id']; ?>" 
                                                data-price="<?php echo $service['price']; ?>"
                                                <?php echo $selected_service_id == $service['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($service['name']); ?> - 
                                            Rp <?php echo number_format($service['price'], 0, ',', '.'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Detail Pesanan -->
                            <div class="col-12 mt-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Detail Pesanan
                                </h5>
                            </div>
                            
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat Lengkap *</label>
                                <textarea class="form-control" id="address" name="address" rows="3" 
                                          placeholder="Masukkan alamat lengkap termasuk kode pos" required><?php echo isset($address) ? htmlspecialchars($address) : ''; ?></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="booking_date" class="form-label">Tanggal Layanan *</label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" 
                                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                                       value="<?php echo isset($booking_date) ? $booking_date : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="booking_time" class="form-label">Waktu Layanan *</label>
                                <select class="form-select" id="booking_time" name="booking_time" required>
                                    <option value="">-- Pilih Waktu --</option>
                                    <option value="08:00">08:00 - Pagi</option>
                                    <option value="10:00">10:00 - Pagi</option>
                                    <option value="13:00">13:00 - Siang</option>
                                    <option value="15:00">15:00 - Sore</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" 
                                          placeholder="Catatan khusus atau permintaan tambahan (opsional)"><?php echo isset($notes) ? htmlspecialchars($notes) : ''; ?></textarea>
                            </div>

                            <!-- Ringkasan Harga -->
                            <div class="col-12 mt-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Ringkasan Pesanan</h6>
                                        <div id="price-summary" style="display: none;">
                                            <div class="d-flex justify-content-between">
                                                <span>Harga Layanan:</span>
                                                <span id="service-price" class="fw-bold">Rp 0</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <strong>Total:</strong>
                                                <strong id="total-price" class="text-primary">Rp 0</strong>
                                            </div>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2">
                                            * Harga dapat berubah berdasarkan kondisi lokasi dan tingkat kesulitan
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane me-2"></i>Kirim Pesanan
                                </button>
                                <p class="text-muted small mt-2">
                                    Dengan mengirim pesanan, Anda menyetujui syarat dan ketentuan kami
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('service_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    
    if (price) {
        const formattedPrice = 'Rp ' + parseInt(price).toLocaleString('id-ID');
        document.getElementById('service-price').textContent = formattedPrice;
        document.getElementById('total-price').textContent = formattedPrice;
        document.getElementById('price-summary').style.display = 'block';
    } else {
        document.getElementById('price-summary').style.display = 'none';
    }
});

// Trigger change event if service is pre-selected
if (document.getElementById('service_id').value) {
    document.getElementById('service_id').dispatchEvent(new Event('change'));
}
</script>

<?php include 'includes/footer.php'; ?>