<?php require_once __DIR__ . '/../../../auth.php'; ?>
<?php include __DIR__ . '/../../../header.php'; ?>

<div id="content" class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
        </ol>
    </nav>

    <h2 class="mb-4">Thống kê tổng quan</h2>
    <div class="row">
        <!-- Thống kê Bác sĩ -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <div class="card-content">
                        <p class="card-text display-4"><?= $stats['totalDoctors'] ?? 0 ?></p>
                        <h5 class="card-title">Bác sĩ</h5>
                    </div>
                    <i class="fa-solid fa-user-doctor card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Thống kê Bệnh nhân -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body">
                    <div class="card-content">
                        <p class="card-text display-4"><?= $stats['totalPatients'] ?? 0 ?></p>
                        <h5 class="card-title">Bệnh nhân</h5>
                    </div>
                    <i class="fa-solid fa-hospital-user card-icon"></i>
                </div>
            </div>
        </div>

        <!-- Thống kê Lịch hẹn -->
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body">
                    <div class="card-content">
                        <p class="card-text display-4"><?= $stats['totalSchedules'] ?? 0 ?></p>
                        <h5 class="card-title">Lịch hẹn</h5>
                    </div>
                    <i class="fa-solid fa-calendar-days card-icon"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .breadcrumb {
        background-color: #ffffff;
        border-radius: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 0.5rem 1rem;
        margin-bottom: 20px;
    }

    .breadcrumb a {
        text-decoration: none;
        color: #4e73df;
    }

    .card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-content {
        flex: 1;
    }

    .card-icon {
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.3);
        margin-left: 10px;
    }

    .card-text.display-4 {
        font-size: 2.8rem;
        font-weight: bold;
        margin-bottom: 0;
    }

    .card-title {
        font-size: 1.2rem;
        margin-top: 5px;
        color: rgba(255, 255, 255, 0.85);
    }

    .bg-primary {
        background-color: #4e73df !important;
    }

    .bg-success {
        background-color: #1cc88a !important;
    }

    .bg-info {
        background-color: #36b9cc !important;
    }

    /* Đảm bảo responsive */
    @media (max-width: 768px) {
        .card-text.display-4 {
            font-size: 2rem;
        }
        
        .card-icon {
            font-size: 3rem;
        }
    }
</style>