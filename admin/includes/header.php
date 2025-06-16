<?php
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin Panel'; ?> - CleanPro Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
        }
        .stats-card {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border-radius: 15px;
        }
        .stats-card-2 {
            background: linear-gradient(45deg, #17a2b8, #6f42c1);
            color: white;
            border-radius: 15px;
        }
        .stats-card-3 {
            background: linear-gradient(45deg, #ffc107, #fd7e14);
            color: white;
            border-radius: 15px;
        }
        .stats-card-4 {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-4">
                        <h4 class="text-white fw-bold">
                            <i class="fas fa-broom me-2"></i>CleanPro
                        </h4>
                        <small class="text-white-50">Admin Panel</small>
                    </div>
                    
                    <nav class="nav flex-column px-3">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" 
                           href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>" 
                           href="services.php">
                            <i class="fas fa-concierge-bell me-2"></i>Kelola Layanan
                        </a>
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>" 
                           href="bookings.php">
                            <i class="fas fa-calendar-check me-2"></i>Kelola Pesanan
                        </a>
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" 
                           href="profile.php">
                            <i class="fas fa-building me-2"></i>Profil Perusahaan
                        </a>
                        <hr class="text-white-50">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </nav>
                    
                    <div class="p-3 mt-auto">
                        <div class="text-white-50 small">
                            <i class="fas fa-user me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">