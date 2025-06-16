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
                    $db->query('INSERT INTO company_profile (company_name, description, address, phone, email, website, established_year, employees_count, mission, vision, values, services_offered, certifications, awards) 
                               VALUES (:company_name, :description, :address, :phone, :email, :website, :established_year, :employees_count, :mission, :vision, :values, :services_offered, :certifications, :awards)');
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
                        $success_message = 'Profil perusahaan berhasil ditambahkan!';
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
        }
    }
}

// Get all company profiles
$db->query('SELECT * FROM company_profile ORDER BY created_at DESC');
$profiles = $db->resultset();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Profil Perusahaan</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
        <i class="fas fa-plus me-2"></i>Tambah Profil
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
        <?php if (empty($profiles)): ?>
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h4>Belum ada profil perusahaan</h4>
                <p class="text-muted">Tambahkan profil perusahaan untuk ditampilkan di website</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                    <i class="fas fa-plus me-2"></i>Tambah Profil Pertama
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Perusahaan</th>
                            <th>Kontak</th>
                            <th>Tahun Berdiri</th>
                            <th>Karyawan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profiles as $profile): ?>
                        <tr>
                            <td><?php echo $profile['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($profile['company_name']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($profile['description'], 0, 50)) . '...'; ?></small>
                            </td>
                            <td>
                                <div class="small">
                                    <div><i class="fas fa-phone me-1"></i><?php echo htmlspecialchars($profile['phone']); ?></div>
                                    <div><i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($profile['email']); ?></div>
                                </div>
                            </td>
                            <td><?php echo $profile['established_year']; ?></td>
                            <td><?php echo $profile['employees_count']; ?> orang</td>
                            <td>
                                <span class="badge <?php echo $profile['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $profile['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-info me-1" onclick="viewProfile(<?php echo htmlspecialchars(json_encode($profile)); ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editProfile(<?php echo htmlspecialchars(json_encode($profile)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteProfile(<?php echo $profile['id']; ?>)">
                                    <i class="fas fa-trash"></i>
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

<!-- Add Profile Modal -->
<div class="modal fade" id="addProfileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Profil Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    
                    <!-- Basic Information -->
                    <h6 class="text-primary mb-3">Informasi Dasar</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Nama Perusahaan *</label>
                            <input type="text" class="form-control" name="company_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="established_year" class="form-label">Tahun Berdiri</label>
                            <input type="number" class="form-control" name="established_year" min="1900" max="2024">
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi Perusahaan *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h6 class="text-primary mb-3">Informasi Kontak</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="address" class="form-label">Alamat *</label>
                            <textarea class="form-control" name="address" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="phone" class="form-label">Telepon *</label>
                            <input type="text" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control" name="website">
                        </div>
                    </div>

                    <!-- Company Details -->
                    <h6 class="text-primary mb-3">Detail Perusahaan</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="employees_count" class="form-label">Jumlah Karyawan</label>
                            <input type="number" class="form-control" name="employees_count" min="1">
                        </div>
                        <div class="col-md-6">
                            <label for="services_offered" class="form-label">Layanan yang Ditawarkan</label>
                            <input type="text" class="form-control" name="services_offered" placeholder="Pisahkan dengan koma">
                        </div>
                        <div class="col-12">
                            <label for="mission" class="form-label">Misi</label>
                            <textarea class="form-control" name="mission" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="vision" class="form-label">Visi</label>
                            <textarea class="form-control" name="vision" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="values" class="form-label">Nilai-nilai Perusahaan</label>
                            <textarea class="form-control" name="values" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <h6 class="text-primary mb-3">Pencapaian</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="certifications" class="form-label">Sertifikasi</label>
                            <textarea class="form-control" name="certifications" rows="2" placeholder="Pisahkan dengan enter untuk setiap sertifikasi"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="awards" class="form-label">Penghargaan</label>
                            <textarea class="form-control" name="awards" rows="2" placeholder="Pisahkan dengan enter untuk setiap penghargaan"></textarea>
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

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil Perusahaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editProfileForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="edit_id">
                    
                    <!-- Basic Information -->
                    <h6 class="text-primary mb-3">Informasi Dasar</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="edit_company_name" class="form-label">Nama Perusahaan *</label>
                            <input type="text" class="form-control" name="company_name" id="edit_company_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_established_year" class="form-label">Tahun Berdiri</label>
                            <input type="number" class="form-control" name="established_year" id="edit_established_year" min="1900" max="2024">
                        </div>
                        <div class="col-12">
                            <label for="edit_description" class="form-label">Deskripsi Perusahaan *</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <h6 class="text-primary mb-3">Informasi Kontak</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="edit_address" class="form-label">Alamat *</label>
                            <textarea class="form-control" name="address" id="edit_address" rows="2" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_phone" class="form-label">Telepon *</label>
                            <input type="text" class="form-control" name="phone" id="edit_phone" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_website" class="form-label">Website</label>
                            <input type="url" class="form-control" name="website" id="edit_website">
                        </div>
                    </div>

                    <!-- Company Details -->
                    <h6 class="text-primary mb-3">Detail Perusahaan</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="edit_employees_count" class="form-label">Jumlah Karyawan</label>
                            <input type="number" class="form-control" name="employees_count" id="edit_employees_count" min="1">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_services_offered" class="form-label">Layanan yang Ditawarkan</label>
                            <input type="text" class="form-control" name="services_offered" id="edit_services_offered" placeholder="Pisahkan dengan koma">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="edit_mission" class="form-label">Misi</label>
                            <textarea class="form-control" name="mission" id="edit_mission" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="edit_vision" class="form-label">Visi</label>
                            <textarea class="form-control" name="vision" id="edit_vision" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="edit_values" class="form-label">Nilai-nilai Perusahaan</label>
                            <textarea class="form-control" name="values" id="edit_values" rows="2"></textarea>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <h6 class="text-primary mb-3">Pencapaian</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_certifications" class="form-label">Sertifikasi</label>
                            <textarea class="form-control" name="certifications" id="edit_certifications" rows="2" placeholder="Pisahkan dengan enter untuk setiap sertifikasi"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_awards" class="form-label">Penghargaan</label>
                            <textarea class="form-control" name="awards" id="edit_awards" rows="2" placeholder="Pisahkan dengan enter untuk setiap penghargaan"></textarea>
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

<!-- View Profile Modal -->
<div class="modal fade" id="viewProfileModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Profil Perusahaan</h5>
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
    const content = `
        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h4 class="text-primary">${profile.company_name}</h4>
                        <p class="mb-0">${profile.description}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-primary">Informasi Kontak</h6>
                <p><strong>Alamat:</strong><br>${profile.address}</p>
                <p><strong>Telepon:</strong> ${profile.phone}</p>
                <p><strong>Email:</strong> ${profile.email}</p>
                ${profile.website ? `<p><strong>Website:</strong> <a href="${profile.website}" target="_blank">${profile.website}</a></p>` : ''}
            </div>
            
            <div class="col-md-6">
                <h6 class="text-primary">Detail Perusahaan</h6>
                <p><strong>Tahun Berdiri:</strong> ${profile.established_year}</p>
                <p><strong>Jumlah Karyawan:</strong> ${profile.employees_count} orang</p>
                ${profile.services_offered ? `<p><strong>Layanan:</strong> ${profile.services_offered}</p>` : ''}
                <p><strong>Status:</strong> <span class="badge ${profile.status === 'active' ? 'bg-success' : 'bg-danger'}">${profile.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></p>
            </div>
            
            ${profile.mission ? `
            <div class="col-12">
                <h6 class="text-primary">Misi</h6>
                <p>${profile.mission}</p>
            </div>
            ` : ''}
            
            ${profile.vision ? `
            <div class="col-12">
                <h6 class="text-primary">Visi</h6>
                <p>${profile.vision}</p>
            </div>
            ` : ''}
            
            ${profile.values ? `
            <div class="col-12">
                <h6 class="text-primary">Nilai-nilai Perusahaan</h6>
                <p>${profile.values}</p>
            </div>
            ` : ''}
            
            ${profile.certifications || profile.awards ? `
            <div class="col-12">
                <div class="row">
                    ${profile.certifications ? `
                    <div class="col-md-6">
                        <h6 class="text-primary">Sertifikasi</h6>
                        <div>${profile.certifications.split('\n').map(cert => cert.trim()).filter(cert => cert).map(cert => `<div>• ${cert}</div>`).join('')}</div>
                    </div>
                    ` : ''}
                    ${profile.awards ? `
                    <div class="col-md-6">
                        <h6 class="text-primary">Penghargaan</h6>
                        <div>${profile.awards.split('\n').map(award => award.trim()).filter(award => award).map(award => `<div>• ${award}</div>`).join('')}</div>
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

function deleteProfile(id) {
    Swal.fire({
        title: 'Hapus Profil Perusahaan?',
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