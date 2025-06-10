<?php
session_start();
require_once '../config/database.php';

$page_title = 'Kelola Layanan';
include 'includes/header.php';

$db = new Database();

$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);
                $price = floatval($_POST['price']);
                $duration = intval($_POST['duration']);
                $category = $_POST['category'];
                
                if (!empty($name) && !empty($description) && $price > 0 && $duration > 0) {
                    $db->query('INSERT INTO services (name, description, price, duration, category) VALUES (:name, :description, :price, :duration, :category)');
                    $db->bind(':name', $name);
                    $db->bind(':description', $description);
                    $db->bind(':price', $price);
                    $db->bind(':duration', $duration);
                    $db->bind(':category', $category);
                    
                    if ($db->execute()) {
                        $success_message = 'Layanan berhasil ditambahkan!';
                    } else {
                        $error_message = 'Gagal menambahkan layanan!';
                    }
                } else {
                    $error_message = 'Semua field harus diisi dengan benar!';
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);
                $price = floatval($_POST['price']);
                $duration = intval($_POST['duration']);
                $category = $_POST['category'];
                $status = $_POST['status'];
                
                if (!empty($name) && !empty($description) && $price > 0 && $duration > 0) {
                    $db->query('UPDATE services SET name = :name, description = :description, price = :price, duration = :duration, category = :category, status = :status WHERE id = :id');
                    $db->bind(':id', $id);
                    $db->bind(':name', $name);
                    $db->bind(':description', $description);
                    $db->bind(':price', $price);
                    $db->bind(':duration', $duration);
                    $db->bind(':category', $category);
                    $db->bind(':status', $status);
                    
                    if ($db->execute()) {
                        $success_message = 'Layanan berhasil diperbarui!';
                    } else {
                        $error_message = 'Gagal memperbarui layanan!';
                    }
                } else {
                    $error_message = 'Semua field harus diisi dengan benar!';
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                $db->query('DELETE FROM services WHERE id = :id');
                $db->bind(':id', $id);
                
                if ($db->execute()) {
                    $success_message = 'Layanan berhasil dihapus!';
                } else {
                    $error_message = 'Gagal menghapus layanan!';
                }
                break;
        }
    }
}

// Get all services
$db->query('SELECT * FROM services ORDER BY created_at DESC');
$services = $db->resultset();

$categories = [
    'residential' => 'Rumah Tinggal',
    'commercial' => 'Komersial',
    'deep-cleaning' => 'Deep Cleaning',
    'maintenance' => 'Maintenance'
];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Kelola Layanan</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
        <i class="fas fa-plus me-2"></i>Tambah Layanan
    </button>
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
        <div class="table-responsive">
            <table class="table data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Layanan</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?php echo $service['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($service['name']); ?></strong>
                            <br><small class="text-muted"><?php echo htmlspecialchars(substr($service['description'], 0, 50)) . '...'; ?></small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?php echo $categories[$service['category']]; ?></span>
                        </td>
                        <td>Rp <?php echo number_format($service['price'], 0, ',', '.'); ?></td>
                        <td><?php echo $service['duration']; ?> jam</td>
                        <td>
                            <span class="badge <?php echo $service['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo $service['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1" onclick="editService(<?php echo htmlspecialchars(json_encode($service)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteService(<?php echo $service['id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Layanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori</label>
                            <select class="form-select" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" name="price" min="0" step="1000" required>
                        </div>
                        <div class="col-md-6">
                            <label for="duration" class="form-label">Durasi (Jam)</label>
                            <input type="number" class="form-control" name="duration" min="1" required>
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editServiceForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_category" class="form-label">Kategori</label>
                            <select class="form-select" name="category" id="edit_category" required>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_price" class="form-label">Harga (Rp)</label>
                            <input type="number" class="form-control" name="price" id="edit_price" min="0" step="1000" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_duration" class="form-label">Durasi (Jam)</label>
                            <input type="number" class="form-control" name="duration" id="edit_duration" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="4" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editService(service) {
    document.getElementById('edit_id').value = service.id;
    document.getElementById('edit_name').value = service.name;
    document.getElementById('edit_category').value = service.category;
    document.getElementById('edit_price').value = service.price;
    document.getElementById('edit_duration').value = service.duration;
    document.getElementById('edit_status').value = service.status;
    document.getElementById('edit_description').value = service.description;
    
    new bootstrap.Modal(document.getElementById('editServiceModal')).show();
}

function deleteService(id) {
    Swal.fire({
        title: 'Hapus Layanan?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php include 'includes/footer.php'; ?>