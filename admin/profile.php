<?php
session_start();
require_once '../config/database.php';

$page_title = 'Profil Perusahaan';
include 'includes/header.php';

$db = new Database();

$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $company_name = trim($_POST['company_name']);
                $description = trim($_POST['description']);
                $address = trim($_POST['address']);
                $phone = trim($_POST['phone']);
                $email = trim($_POST['email']);
                $website = trim($_POST['website']);
                $established_year = intval($_POST['established_year']);
                $employees_count = intval($_POST['employees_count']);
                $mission = trim($_POST['mission']);
                $vision = trim($_POST['vision']);
                $values = trim($_POST['values']);
                $services_offered = trim($_POST['services_offered']);
                $certifications = trim($_POST['certifications']);
                $awards = trim($_POST['awards']);
                
                if (!empty($company_name) && !empty($description) && !empty($address) && !empty($phone) && !empty($email)) {
                    // Set all existing profiles to inactive first
                    $db->query('UPDATE company_profile SET status = "inactive"');
                    $db->execute();
                    
                    $db->query('INSERT INTO company_profile (company_name, description, address, phone, email, website, established_year, employees_count, mission, vision, values, services_offered, certifications, awards, status) 
                               VALUES (:company_name, :description, :address, :phone, :email, :website, :established_year, :employees_count, :mission, :vision, :values, :services_offered, :certifications, :awards, "active")');
                    $db->bind(':company_name', $company_name);
                    $db->bind(':description', $description);
                    $db->bind(':address', $address);
                    $db->bind(':phone', $phone);
                    $db->bind(':email', $email);
                    $db->bind(':website', $website);
                    $db->bind(':established_year', $established_year);
                    $db->bind(':employees_count', $employees_count);
                    $db->bind(':mission', $mission);
                    $db->bind(':vision', $vision);
                    $db->bind(':values', $values);
                    $db->bind(':services_offered', $services_offered);
                    $db->bind(':certifications', $certifications);
                    $db->bind(':awards', $awards);
                    
                    if ($db->execute()) {
                        $success_message = 'Profil perusahaan berhasil ditambahkan dan diaktifkan!';
                    } else {
                        $error_message = 'Gagal menambahkan profil perusahaan!';
                    }
                } else {
                    $error_message = 'Field wajib harus diisi!';
                }
                break;
                
            case 'edit':
                $id = intval($_POST['id']);
                $company_name = trim($_POST['company_name']);
                $description = trim($_POST['description']);
                $address = trim($_POST['address']);
                $phone = trim($_POST['phone']);
                $email = trim($_POST['email']);
                $website = trim($_POST['website']);
                $established_year = intval($_POST['established_year']);
                $employees_count = intval($_POST['employees_count']);
                $mission = trim($_POST['mission']);
                $vision = trim($_POST['vision']);
                $values = trim($_POST['values']);
                $services_offered = trim($_POST['services_offered']);
                $certifications = trim($_POST['certifications']);
                $awards = trim($_POST['awards']);
                $status = $_POST['status'];
                
                if (!empty($company_name) && !empty($description) && !empty($address) && !empty($phone) && !empty($email)) {
                    // If setting this profile to active, set all others to inactive
                    if ($status === 'active') {
                        $db->query('UPDATE company_profile SET status = "inactive" WHERE id != :id');
                        $db->bind(':id', $id);
                        $db->execute();
                    }
                    
                    $db->query('UPDATE company_profile SET company_name = :company_name, description = :description, address = :address, phone = :phone, email = :email, website = :website, established_year = :established_year, employees_count = :employees_count, mission = :mission, vision = :vision, values = :values, services_offered = :services_offered, certifications = :certifications, awards = :awards, status = :status WHERE id = :id');
                    $db->bind(':id', $id);
                    $db->bind(':company_name', $company_name);
                    $db->bind(':description', $description);
                    $db->bind(':address', $address);
                    $db->bind(':phone', $phone);
                    $db->bind(':email', $email);
                    $db->bind(':website', $website);
                    $db->bind(':established_year', $established_year);
                    $db->bind(':employees_count', $employees_count);
                    $db->bind(':mission', $mission);
                    $db->bind(':vision', $vision);
                    $db->bind(':values', $values);
                    $db->bind(':services_offered', $services_offered);
                    $db->bind(':certifications', $certifications);
                    $db->bind(':awards', $awards);
                    $db->bind(':status', $status);
                    
                    if ($db->execute()) {
                        $success_message = 'Profil perusahaan berhasil diperbarui!';
                    } else {
                        $error_message = 'Gagal memperbarui profil perusahaan!';
                    }
                } else {
                    $error_message = 'Field wajib harus diisi!';
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id']);
                $db->query('DELETE FROM company_profile WHERE id = :id');
                $db->bind(':id', $id);
                
                if ($db->execute()) {
                    $success_message = 'Profil perusahaan berhasil dihapus!';
                } else {
                    $error_message = 'Gagal menghapus profil perusahaan!';
                }
                break;
                
            case 'activate':
                $id = intval($_POST['id']);
                
                // Set all profiles to inactive first
                $db->query('UPDATE company_profile SET status = "inactive"');
                $db->execute();
                
                // Set selected profile to active
                $db->query('UPDATE company_profile SET status = "active" WHERE id = :id');
                $db->bind(':id', $id);
                
                if ($db->execute()) {
                    $success_message = 'Profil perusahaan berhasil diaktifkan!';
                } else {
                    $error_message = 'Gagal mengaktifkan profil perusahaan!';
                }
                break;
        }
    }
}

// Get all company profiles
$db->query('SELECT * FROM company_profile ORDER BY status DESC, created_at DESC');
$profiles = $db->resultset();

// Get active profile for quick stats
$db->query('SELECT * FROM company_profile WHERE status = "active" LIMIT 1');
$active_profile = $db->single();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Profil Perusahaan</h2>
        <p class="text-muted mb-0">Kelola informasi dan profil perusahaan Anda</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
        <i class="fas fa-plus me-2"></i>Tambah Profil Baru
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

<!-- Active Profile Summary -->
<?php if ($active_profile): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-building fa-2x me-3"></i>
                            <div>
                                <h4 class="mb-0"><?php echo htmlspecialchars($active_profile['company_name']); ?></h4>
                                <p class="mb-0 opacity-75">Profil Aktif Saat Ini</p>
                            </div>
                        </div>
                        <p class="mb-0 opacity-90"><?php echo htmlspecialchars(substr($active_profile['description'], 0, 120)) . '...'; ?></p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="mb-0"><?php echo $active_profile['established_year']; ?></h3>
                                <small class="opacity-75">Tahun Berdiri</small>
                            </div>
                            <div class="col-6">
                                <h3 class="mb-0"><?php echo $active_profile['employees_count']; ?>+</h3>
                                <small class="opacity-75">Karyawan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>Daftar Profil Perusahaan
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($profiles)): ?>
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4>Belum ada profil perusahaan</h4>
                <p class="text-muted mb-4">Tambahkan profil perusahaan untuk ditampilkan di website</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                    <i class="fas fa-plus me-2"></i>Tambah Profil Pertama
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Perusahaan</th>
                            <th>Kontak</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-building text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($profile['company_name']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($profile['description'], 0, 40)) . '...'; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="fas fa-phone me-1 text-muted"></i><?php echo htmlspecialchars($profile['phone']); ?></div>
                                    <div><i class="fas fa-envelope me-1 text-muted"></i><?php echo htmlspecialchars($profile['email']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <div><strong>Berdiri:</strong> <?php echo $profile['established_year']; ?></div>
                                    <div><strong>Karyawan:</strong> <?php echo $profile['employees_count']; ?> orang</div>
                                </div>
                            </td>
                            <td>
                                <?php if ($profile['status'] == 'active'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-info" onclick="viewProfile(<?php echo htmlspecialchars(json_encode($profile)); ?>)" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="editProfile(<?php echo htmlspecialchars(json_encode($profile)); ?>)" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <?php if ($profile['status'] != 'active'): ?>
                                    <button class="btn btn-sm btn-outline-success" onclick="activateProfile(<?php echo $profile['id']; ?>)" title="Aktifkan">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteProfile(<?php echo $profile['id']; ?>)" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Profile Modal -->
<div class="modal fade" id="addProfileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Tambah Profil Perusahaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                            </h6>
                        </div>
                        <div class="col-md-8">
                            <label for="company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="established_year" class="form-label">Tahun Berdiri</label>
                            <input type="number" class="form-control" name="established_year" min="1900" max="2024" value="2020">
                        </div>
                        <div class="col-12 mt-3">
                            <label for="description" class="form-label">Deskripsi Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" rows="3" required placeholder="Jelaskan tentang perusahaan Anda..."></textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-address-book me-2"></i>Informasi Kontak
                            </h6>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" rows="2" required placeholder="Alamat lengkap perusahaan..."></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" required placeholder="+62 xxx-xxxx-xxxx">
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required placeholder="info@perusahaan.com">
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" placeholder="https://www.perusahaan.com">
                        </div>
                    </div>

                    <!-- Company Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i>Detail Perusahaan
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="employees_count" class="form-label">Jumlah Karyawan</label>
                            <input type="number" class="form-control" name="employees_count" min="1" value="10">
                        </div>
                        <div class="col-md-6">
                            <label for="services_offered" class="form-label">Layanan yang Ditawarkan</label>
                            <input type="text" class="form-control" name="services_offered" placeholder="Layanan 1, Layanan 2, Layanan 3">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="mission" class="form-label">Misi Perusahaan</label>
                            <textarea class="form-control" name="mission" rows="2" placeholder="Misi perusahaan..."></textarea>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="vision" class="form-label">Visi Perusahaan</label>
                            <textarea class="form-control" name="vision" rows="2" placeholder="Visi perusahaan..."></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="values" class="form-label">Nilai-nilai Perusahaan</label>
                            <textarea class="form-control" name="values" rows="2" placeholder="Nilai-nilai yang dipegang perusahaan..."></textarea>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-trophy me-2"></i>Pencapaian (Opsional)
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="certifications" class="form-label">Sertifikasi</label>
                            <textarea class="form-control" name="certifications" rows="2" placeholder="Sertifikat ISO 9001&#10;Sertifikat Keamanan&#10;..."></textarea>
                            <small class="text-muted">Pisahkan setiap sertifikasi dengan baris baru</small>
                        </div>
                        <div class="col-md-6">
                            <label for="awards" class="form-label">Penghargaan</label>
                            <textarea class="form-control" name="awards" rows="2" placeholder="Penghargaan Terbaik 2023&#10;Sertifikat Kualitas&#10;..."></textarea>
                            <small class="text-muted">Pisahkan setiap penghargaan dengan baris baru</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Profil Perusahaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editProfileForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="company_name" id="edit_company_name" required>
                        </div>
                        <div class="col-md-3">
                            <label for="edit_established_year" class="form-label">Tahun Berdiri</label>
                            <input type="number" class="form-control" name="established_year" id="edit_established_year" min="1900" max="2024">
                        </div>
                        <div class="col-md-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="edit_description" class="form-label">Deskripsi Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-address-book me-2"></i>Informasi Kontak
                            </h6>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="edit_address" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="address" id="edit_address" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" id="edit_phone" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_website" class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" id="edit_website">
                        </div>
                    </div>

                    <!-- Company Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i>Detail Perusahaan
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_employees_count" class="form-label">Jumlah Karyawan</label>
                            <input type="number" class="form-control" name="employees_count" id="edit_employees_count" min="1">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_services_offered" class="form-label">Layanan yang Ditawarkan</label>
                            <input type="text" class="form-control" name="services_offered" id="edit_services_offered">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="edit_mission" class="form-label">Misi Perusahaan</label>
                            <textarea class="form-control" name="mission" id="edit_mission" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label for="edit_vision" class="form-label">Visi Perusahaan</label>
                            <textarea class="form-control" name="vision" id="edit_vision" rows="2"></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="edit_values" class="form-label">Nilai-nilai Perusahaan</label>
                            <textarea class="form-control" name="values" id="edit_values" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-trophy me-2"></i>Pencapaian
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_certifications" class="form-label">Sertifikasi</label>
                            <textarea class="form-control" name="certifications" id="edit_certifications" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_awards" class="form-label">Penghargaan</label>
                            <textarea class="form-control" name="awards" id="edit_awards" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Profile Modal -->
<div class="modal fade" id="viewProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>Detail Profil Perusahaan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="profileDetails">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewProfile(profile) {
    const statusLabels = {
        'active': '<span class="badge bg-success">Aktif</span>',
        'inactive': '<span class="badge bg-secondary">Nonaktif</span>'
    };
    
    const content = `
        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h4 class="text-primary mb-1">${profile.company_name}</h4>
                                <p class="text-muted mb-0">${profile.description}</p>
                            </div>
                            <div>${statusLabels[profile.status]}</div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <h5 class="text-primary mb-0">${profile.established_year}</h5>
                                <small class="text-muted">Tahun Berdiri</small>
                            </div>
                            <div class="col-6">
                                <h5 class="text-primary mb-0">${profile.employees_count}+</h5>
                                <small class="text-muted">Karyawan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-primary mb-3"><i class="fas fa-address-book me-2"></i>Informasi Kontak</h6>
                <div class="mb-2">
                    <strong>Alamat:</strong><br>
                    <span class="text-muted">${profile.address}</span>
                </div>
                <div class="mb-2">
                    <strong>Telepon:</strong> <span class="text-muted">${profile.phone}</span>
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> <span class="text-muted">${profile.email}</span>
                </div>
                ${profile.website ? `<div class="mb-2"><strong>Website:</strong> <a href="${profile.website}" target="_blank" class="text-decoration-none">${profile.website}</a></div>` : ''}
            </div>
            
            <div class="col-md-6">
                <h6 class="text-primary mb-3"><i class="fas fa-cogs me-2"></i>Detail Perusahaan</h6>
                ${profile.services_offered ? `<div class="mb-2"><strong>Layanan:</strong><br><span class="text-muted">${profile.services_offered}</span></div>` : ''}
                <div class="mb-2">
                    <strong>Jumlah Karyawan:</strong> <span class="text-muted">${profile.employees_count} orang</span>
                </div>
            </div>
            
            ${profile.mission ? `
            <div class="col-12">
                <h6 class="text-primary mb-2"><i class="fas fa-bullseye me-2"></i>Misi</h6>
                <p class="text-muted">${profile.mission}</p>
            </div>
            ` : ''}
            
            ${profile.vision ? `
            <div class="col-12">
                <h6 class="text-primary mb-2"><i class="fas fa-eye me-2"></i>Visi</h6>
                <p class="text-muted">${profile.vision}</p>
            </div>
            ` : ''}
            
            ${profile.values ? `
            <div class="col-12">
                <h6 class="text-primary mb-2"><i class="fas fa-heart me-2"></i>Nilai-nilai Perusahaan</h6>
                <p class="text-muted">${profile.values}</p>
            </div>
            ` : ''}
            
            ${profile.certifications || profile.awards ? `
            <div class="col-12">
                <div class="row">
                    ${profile.certifications ? `
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2"><i class="fas fa-certificate me-2"></i>Sertifikasi</h6>
                        <div class="text-muted">${profile.certifications.split('\n').map(cert => cert.trim()).filter(cert => cert).map(cert => `<div class="mb-1">• ${cert}</div>`).join('')}</div>
                    </div>
                    ` : ''}
                    ${profile.awards ? `
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2"><i class="fas fa-trophy me-2"></i>Penghargaan</h6>
                        <div class="text-muted">${profile.awards.split('\n').map(award => award.trim()).filter(award => award).map(award => `<div class="mb-1">• ${award}</div>`).join('')}</div>
                    </div>
                    ` : ''}
                </div>
            </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('profileDetails').innerHTML = content;
    new bootstrap.Modal(document.getElementById('viewProfileModal')).show();
}

function editProfile(profile) {
    document.getElementById('edit_id').value = profile.id;
    document.getElementById('edit_company_name').value = profile.company_name;
    document.getElementById('edit_description').value = profile.description;
    document.getElementById('edit_address').value = profile.address;
    document.getElementById('edit_phone').value = profile.phone;
    document.getElementById('edit_email').value = profile.email;
    document.getElementById('edit_website').value = profile.website || '';
    document.getElementById('edit_established_year').value = profile.established_year || '';
    document.getElementById('edit_employees_count').value = profile.employees_count || '';
    document.getElementById('edit_services_offered').value = profile.services_offered || '';
    document.getElementById('edit_mission').value = profile.mission || '';
    document.getElementById('edit_vision').value = profile.vision || '';
    document.getElementById('edit_values').value = profile.values || '';
    document.getElementById('edit_certifications').value = profile.certifications || '';
    document.getElementById('edit_awards').value = profile.awards || '';
    document.getElementById('edit_status').value = profile.status;
    
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
}

function activateProfile(id) {
    Swal.fire({
        title: 'Aktifkan Profil?',
        text: 'Profil ini akan menjadi profil aktif dan profil lain akan dinonaktifkan.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Aktifkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="activate">
                <input type="hidden" name="id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteProfile(id) {
    Swal.fire({
        title: 'Hapus Profil Perusahaan?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
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