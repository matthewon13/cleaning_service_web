<?php
require_once 'config/database.php';

$page_title = 'Tentang Kami - CleanPro Service';
include 'includes/header.php';

$db = new Database();

// Get active company profile
$db->query('SELECT * FROM company_profile WHERE status = "active" ORDER BY created_at DESC LIMIT 1');
$company = $db->single();

// If no company profile found, use default data
if (!$company) {
    $company = [
        'company_name' => 'CleanPro Service',
        'description' => 'Kami adalah perusahaan layanan pembersihan profesional yang berkomitmen memberikan layanan terbaik untuk rumah dan kantor Anda.',
        'address' => 'Jakarta, Indonesia',
        'phone' => '+62 812-3456-7890',
        'email' => 'info@cleanpro.com',
        'website' => '',
        'established_year' => 2020,
        'employees_count' => 50,
        'mission' => 'Memberikan layanan pembersihan berkualitas tinggi dengan standar profesional dan kepuasan pelanggan sebagai prioritas utama.',
        'vision' => 'Menjadi perusahaan layanan pembersihan terdepan di Indonesia yang dipercaya dan dikenal karena kualitas dan profesionalitasnya.',
        'values' => 'Profesionalisme, Kualitas, Kepercayaan, dan Kepuasan Pelanggan',
        'services_offered' => 'Pembersihan Rumah, Pembersihan Kantor, Deep Cleaning, Maintenance',
        'certifications' => '',
        'awards' => ''
    ];
}
?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold text-primary mb-4">Tentang <?php echo htmlspecialchars($company['company_name']); ?></h1>
            <p class="lead text-muted mb-4"><?php echo htmlspecialchars($company['description']); ?></p>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Berdiri Sejak</h6>
                            <p class="mb-0 text-muted"><?php echo $company['established_year']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">Tim Profesional</h6>
                            <p class="mb-0 text-muted"><?php echo $company['employees_count']; ?>+ Karyawan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <img src="https://images.pexels.com/photos/4239013/pexels-photo-4239013.jpeg?auto=compress&cs=tinysrgb&w=600" 
                 alt="About Us" class="img-fluid rounded-3 shadow-lg">
        </div>
    </div>

    <!-- Mission, Vision, Values -->
    <div class="row g-4 mb-5">
        <?php if (!empty($company['mission'])): ?>
        <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-bullseye text-white fa-2x"></i>
                    </div>
                    <h4 class="text-primary mb-3">Misi Kami</h4>
                    <p class="text-muted"><?php echo htmlspecialchars($company['mission']); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($company['vision'])): ?>
        <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-eye text-white fa-2x"></i>
                    </div>
                    <h4 class="text-success mb-3">Visi Kami</h4>
                    <p class="text-muted"><?php echo htmlspecialchars($company['vision']); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($company['values'])): ?>
        <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="bg-info rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 70px; height: 70px;">
                        <i class="fas fa-heart text-white fa-2x"></i>
                    </div>
                    <h4 class="text-info mb-3">Nilai-Nilai</h4>
                    <p class="text-muted"><?php echo htmlspecialchars($company['values']); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Services Offered -->
    <?php if (!empty($company['services_offered'])): ?>
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-center text-primary mb-4">Layanan yang Kami Tawarkan</h3>
                    <div class="row g-3">
                        <?php 
                        $services = explode(',', $company['services_offered']);
                        foreach ($services as $service): 
                            $service = trim($service);
                            if (!empty($service)):
                        ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="text-center p-3">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-check text-primary fa-lg"></i>
                                </div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($service); ?></h6>
                            </div>
                        </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Achievements -->
    <?php if (!empty($company['certifications']) || !empty($company['awards'])): ?>
    <div class="row g-4 mb-5">
        <?php if (!empty($company['certifications'])): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-certificate me-2"></i>Sertifikasi
                    </h4>
                    <div class="list-group list-group-flush">
                        <?php 
                        $certifications = explode("\n", $company['certifications']);
                        foreach ($certifications as $cert): 
                            $cert = trim($cert);
                            if (!empty($cert)):
                        ?>
                        <div class="list-group-item border-0 px-0">
                            <i class="fas fa-award text-warning me-2"></i><?php echo htmlspecialchars($cert); ?>
                        </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($company['awards'])): ?>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h4 class="text-success mb-3">
                        <i class="fas fa-trophy me-2"></i>Penghargaan
                    </h4>
                    <div class="list-group list-group-flush">
                        <?php 
                        $awards = explode("\n", $company['awards']);
                        foreach ($awards as $award): 
                            $award = trim($award);
                            if (!empty($award)):
                        ?>
                        <div class="list-group-item border-0 px-0">
                            <i class="fas fa-medal text-success me-2"></i><?php echo htmlspecialchars($award); ?>
                        </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 class="mb-3">Hubungi Kami</h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt fa-lg me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Alamat</h6>
                                            <p class="mb-0 opacity-75"><?php echo htmlspecialchars($company['address']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone fa-lg me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Telepon</h6>
                                            <p class="mb-0 opacity-75"><?php echo htmlspecialchars($company['phone']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope fa-lg me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Email</h6>
                                            <p class="mb-0 opacity-75"><?php echo htmlspecialchars($company['email']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php if (!empty($company['website'])): ?>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-globe fa-lg me-3"></i>
                                        <div>
                                            <h6 class="mb-0">Website</h6>
                                            <p class="mb-0 opacity-75">
                                                <a href="<?php echo htmlspecialchars($company['website']); ?>" 
                                                   target="_blank" class="text-white text-decoration-none">
                                                    <?php echo htmlspecialchars($company['website']); ?>
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-4 text-lg-end">
                            <a href="booking.php" class="btn btn-light btn-lg">
                                <i class="fas fa-calendar-check me-2"></i>Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>