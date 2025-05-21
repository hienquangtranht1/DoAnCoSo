<?php
if (!isset($benhNhans)) {
    $benhNhans = [];
}
if (!isset($keyword)) {
    $keyword = ''; // Gán giá trị mặc định là chuỗi rỗng
}
?>
<?php
require_once __DIR__ . '/../../../auth.php';
?>
<?php include __DIR__ . '/../../../header.php'; ?>

<div id="content" class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bệnh nhân</li>
        </ol>
    </nav>

    <!-- Title and Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="fa-solid fa-arrow-right"></i> Bệnh Nhân</h1>
    </div>

    <!-- Search Form -->
    <div class="search-form">
        <form method="GET" action="<?= $_SERVER['SCRIPT_NAME'] ?>" class="d-flex">
            <input type="hidden" name="controller" value="benhnhan">
            <input type="hidden" name="action" value="index">
            <input type="text" name="keyword" id="searchInput" class="form-control" placeholder="Nhập tên bệnh nhân..." value="<?= $keyword ?>">
        </form>
    </div>
    <section class="section mt-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã BN</th>
                        <th>Hình ảnh</th>
                        <th>Họ tên</th>
                        <th class="hide-on-mobile">Ngày sinh</th>
                        <th>Giới tính</th>
                        <th class="hide-on-mobile">SĐT</th>
                        <th class="hide-on-mobile">Địa chỉ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($benhNhans)): ?>
                        <?php foreach ($benhNhans as $benhNhan): ?>
                            <tr>
                                <td><?= htmlspecialchars($benhNhan['MaBenhNhan']); ?></td>
                                <td>
                                    <img src="<?= !empty($benhNhan['HinhAnhBenhNhan']) ? htmlspecialchars($benhNhan['HinhAnhBenhNhan']) : 'public/img/default-avatar.png'; ?>"
                                        alt="Ảnh bệnh nhân" class="img-fluid rounded" width="50">
                                </td>
                                <td><?= htmlspecialchars($benhNhan['HoTen']); ?></td>
                                <td class="hide-on-mobile"><?= htmlspecialchars($benhNhan['NgaySinh']); ?></td>
                                <td><?= htmlspecialchars($benhNhan['GioiTinh']); ?></td>
                                <td class="hide-on-mobile"><?= htmlspecialchars($benhNhan['SoDienThoai']); ?></td>
                                <td class="hide-on-mobile"><?= htmlspecialchars($benhNhan['DiaChi']); ?></td>
                                <td>
                                    <form method="POST" action="<?= BASE_URL ?>/benhnhan/destroy" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bệnh nhân này không?');" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $benhNhan['MaBenhNhan']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .breadcrumb {
        margin: 0;
        padding: 0.5rem 1rem;
        background-color: #ffffff;
        font-size: 1rem;
        border-radius: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section {
        padding-top: 10px;
        border-radius: 5px;
        width: 100%;
        height: calc(100vh - 56px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #ffffff;
        overflow-y: auto;
        border-top: 2px solid #000000;
    }

    .breadcrumb a {
        text-decoration: none;
        color: #4e73df;
    }

    .d-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        margin-bottom: 1.5rem;
    }

    .search-form {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .search-form label {
        font-weight: bold;
        margin-right: 10px;
    }

    .search-form .form-control {
        width: 250px;
    }

    .btn-primary {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2653d4;
    }

    .table-responsive {
        margin-top: 20px;
        margin-left: 10px;
        margin-right: 10px;
    }

    /* Responsive styles */
    @media screen and (max-width: 768px) {
        .hide-on-mobile {
            display: none;
        }
    }
</style>