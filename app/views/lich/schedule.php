<?php require_once __DIR__ . '/../../../auth.php'; ?>
<?php include __DIR__ . '/../../../header.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý lịch làm việc</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/asset/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .section {
            padding: 15px;
            border-radius: 5px;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            border-top: 2px solid #000000;
        }

        .breadcrumb {
            margin: 0;
            padding: 0.5rem 1rem;
            background-color: #ffffff;
            font-size: 0.9rem;
            border-radius: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            gap: 10px;
            padding-top: 10px;
        }

        .page-title {
            font-weight: bold;
            color: #333;
            font-size: 1.5rem;
            margin: 0;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .search-form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .search-input-group {
            display: flex;
            flex-grow: 1;
        }

        .week-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .week-navigation .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .week-navigation h5 {
            font-size: 1rem;
            margin: 0;
            text-align: center;
            flex-grow: 1;
        }

        /* Calendar styles for mobile */
        .calendar-container {
            overflow-x: auto;
        }

        .calendar-grid {
            min-width: 700px; /* For horizontal scrolling on small screens */
            display: flex;
        }

        .day-column {
            flex: 1;
            min-width: 100px;
            border: 1px solid #dee2e6;
            padding: 8px;
            background-color: #f8f9fa;
            margin: 0 2px;
        }

        .day-header {
            text-align: center;
            font-weight: bold;
            padding: 5px;
            background-color: #e9ecef;
            border-bottom: 1px solid #dee2e6;
            margin: -8px -8px 8px -8px;
            font-size: 0.9rem;
        }

        /* Mobile view - single day at a time */
        .single-day-view {
            display: none;
            margin-top: 15px;
        }

        .day-selector {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            background-color: #e9ecef;
            padding: 8px;
            border-radius: 4px;
        }

        .single-day-content {
            border: 1px solid #dee2e6;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
        }

        .schedule-item {
            background-color: #e3f2fd;
            border-left: 4px solid #4e73df;
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .schedule-time {
            font-weight: bold;
            color: #495057;
        }

        .schedule-doctor {
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .schedule-status {
            margin-top: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .schedule-actions {
            margin-top: 5px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .today {
            background-color: #fff3cd;
        }

        .weekend {
            background-color: #f5f5f5;
        }

        /* Modal styles */
        .modal-header {
            background-color: #4e73df;
            color: white;
        }

        .time-inputs {
            display: flex;
            gap: 10px;
        }

        .time-inputs .form-control {
            width: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .week-navigation {
                justify-content: center;
            }

            .week-navigation .btn {
                flex: 0 0 auto;
            }

            .week-navigation h5 {
                flex: 0 0 100%;
                order: -1;
                margin-bottom: 10px;
            }

            .page-header {
                justify-content: center;
                text-align: center;
            }

            .page-title {
                flex: 0 0 100%;
                text-align: center;
                margin-bottom: 10px;
                font-size: 1.3rem;
            }

            /* Show single day view and hide week calendar on mobile */
            .calendar-container {
                display: none;
            }

            .single-day-view {
                display: block;
            }

            .search-form {
                flex-direction: column;
            }

            .search-input-group {
                width: 100%;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <?php
    if (!isset($lichs)) {
        $lichs = [];
    }
    if (!isset($bacSis)) {
        $bacSis = [];
    }

    // Lấy ngày bắt đầu và kết thúc của tuần hiện tại
    $today = new DateTime();
    $currentWeek = isset($_GET['week']) ? intval($_GET['week']) : 0;
    $today->modify($currentWeek . ' week');

    // Lấy ngày đầu tuần (thứ 2)
    $startOfWeek = clone $today;
    $startOfWeek->modify('monday this week');
    $endOfWeek = clone $startOfWeek;
    $endOfWeek->modify('+6 days');

    // Format cho hiển thị
    $weekLabel = $startOfWeek->format('d/m/Y') . ' - ' . $endOfWeek->format('d/m/Y');

    // Tạo mảng các ngày trong tuần
    $weekDays = [];
    $currentDay = clone $startOfWeek;
    for ($i = 0; $i < 7; $i++) {
        $weekDays[] = clone $currentDay;
        $currentDay->modify('+1 day');
    }

    // Hàm để lọc lịch làm việc theo ngày
    function filterSchedulesByDay($lichs, $date)
    {
        $filteredSchedules = [];
        $dateStr = $date->format('Y-m-d');

        foreach ($lichs as $lich) {
            if ($lich['NgayLamViec'] == $dateStr) {
                $filteredSchedules[] = $lich;
            }
        }

        return $filteredSchedules;
    }
    ?>
    <?php require_once __DIR__ . '/../../../auth.php'; ?>
    <?php include __DIR__ . '/../../../header.php'; ?>

    <div id="content" class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/dashboard">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lịch khám</li>
            </ol>
        </nav>

        <div class="page-header">
            <h1 class="page-title"><i class="fa-solid fa-calendar-days"></i> Lịch làm việc</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Thêm lịch mới
            </button>
        </div>

        <!-- Section -->
        <section class="section">
            <!-- Search Form -->
            <div class="search-form">
                <div class="search-input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Tìm theo tên bác sĩ..." value="<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>">
                    <button type="button" class="btn btn-primary" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Week navigation -->
            <div class="week-navigation">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=index&week=<?= $currentWeek - 1 ?>" class="btn btn-outline-primary">
                    <i class="fas fa-chevron-left"></i> Trước
                </a>
                <h5>Tuần: <?= htmlspecialchars($weekLabel) ?></h5>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=index&week=<?= $currentWeek + 1 ?>" class="btn btn-outline-primary">
                    Sau <i class="fas fa-chevron-right"></i>
                </a>
            </div>

            <!-- Weekly calendar (for desktop) -->
            <div class="calendar-container">
                <div class="calendar-grid">
                    <?php foreach ($weekDays as $day): ?>
                        <?php
                        $isToday = $day->format('Y-m-d') === (new DateTime())->format('Y-m-d');
                        $isWeekend = $day->format('N') >= 6; // 6 = Thứ 7, 7 = Chủ nhật
                        $daySchedules = filterSchedulesByDay($lichs, $day);
                        ?>
                        <div class="day-column <?= $isToday ? 'today' : '' ?> <?= $isWeekend ? 'weekend' : '' ?>" data-date="<?= $day->format('Y-m-d') ?>">
                            <div class="day-header">
                                <?= $day->format('d/m') ?>
                                <small>(<?= ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][$day->format('w')] ?>)</small>
                            </div>

                            <?php if (empty($daySchedules)): ?>
                                <div class="text-center text-muted py-3">
                                    <i class="far fa-calendar-times"></i><br>
                                    Không có lịch
                                </div>
                            <?php else: ?>
                                <?php foreach ($daySchedules as $lich): ?>
                                    <div class="schedule-item">
                                        <div class="schedule-time">
                                            <?= substr($lich['GioBatDau'], 0, 5) ?> - <?= substr($lich['GioKetThuc'], 0, 5) ?>
                                        </div>
                                        <div class="schedule-doctor" title="<?= htmlspecialchars($lich['TenBacSi']) ?>">
                                            <i class="fas fa-user-md"></i> <?= htmlspecialchars($lich['TenBacSi']) ?>
                                        </div>
                                        <div class="schedule-status">
                                            <span class="badge <?= trim($lich['TrangThai']) === 'Đã xác nhận' ? 'bg-success' : 'bg-secondary' ?>">
                                                <?= trim($lich['TrangThai']) ?>
                                            </span>
                                            
                                        </div>
                                        <div class="schedule-actions">
                                            <button class="btn btn-sm btn-warning"
                                                onclick="editSchedule(<?= htmlspecialchars(json_encode($lich)) ?>)"
                                                title="Sửa lịch">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="deleteSchedule(<?= $lich['MaLich'] ?>)"
                                                title="Xóa lịch">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Single day view (for mobile) -->
            <div class="single-day-view">
                <div class="day-selector">
                    <button id="prevDayBtn" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div id="currentDayDisplay" class="fw-bold"></div>
                    <button id="nextDayBtn" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div id="singleDayContent" class="single-day-content">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </section>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm Lịch Làm Việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=store">
                        <div class="mb-3">
                            <label class="form-label">Bác Sĩ</label>
                            <select name="MaBacSi" class="form-select" required>
                                <option value="">-- Chọn bác sĩ --</option>
                                <?php foreach ($bacSis as $bacSi): ?>
                                    <option value="<?= htmlspecialchars($bacSi['MaBacSi']) ?>"><?= htmlspecialchars($bacSi['HoTen']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày Làm Việc</label>
                            <input type="date" name="NgayLamViec" class="form-control" required min="<?= (new DateTime())->format('Y-m-d') ?>">
                        </div>

                        <div class="mb-3 time-inputs">
                            <div>
                                <label class="form-label">Giờ Bắt Đầu</label>
                                <input type="time" name="GioBatDau" class="form-control" required>
                            </div>
                            <div>
                                <label class="form-label">Giờ Kết Thúc</label>
                                <input type="time" name="GioKetThuc" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select name="TrangThai" class="form-select">
                                <option value="Chưa xác nhận ">Chưa xác nhận</option>
                                <option value="Đã xác nhận">Đã xác nhận</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Thêm Lịch</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Sửa Lịch Làm Việc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=update">
                        <input type="hidden" id="editMaLich" name="MaLich">

                        <div class="mb-3">
                            <label class="form-label">Bác Sĩ</label>
                            <select id="editMaBacSi" name="MaBacSi" class="form-select" required>
                                <?php foreach ($bacSis as $bacSi): ?>
                                    <option value="<?= htmlspecialchars($bacSi['MaBacSi']) ?>"><?= htmlspecialchars($bacSi['HoTen']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày Làm Việc</label>
                            <input type="date" id="editNgayLamViec" name="NgayLamViec" class="form-control" required>
                        </div>

                        <div class="mb-3 time-inputs">
                            <div>
                                <label class="form-label">Giờ Bắt Đầu</label>
                                <input type="time" id="editGioBatDau" name="GioBatDau" class="form-control" required>
                            </div>
                            <div>
                                <label class="form-label">Giờ Kết Thúc</label>
                                <input type="time" id="editGioKetThuc" name="GioKetThuc" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng Thái</label>
                            <select id="editTrangThai" name="TrangThai" class="form-select">
                                <option value="Chưa xác nhận ">Chưa xác nhận</option>
                                <option value="Đã xác nhận">Đã xác nhận</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Lưu Thay Đổi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa lịch này không?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" action="<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=destroy">
                        <input type="hidden" id="deleteMaLich" name="id">
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            document.getElementById('searchButton').addEventListener('click', function() {
                let keyword = document.getElementById('searchInput').value;
                window.location.href = '<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=index&week=<?= $currentWeek ?>&keyword=' + encodeURIComponent(keyword);
            });

            document.getElementById('searchInput').addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('searchButton').click();
                }
            });

            // Mobile single day view handling
            const weekDays = <?= json_encode(array_map(function($day) { return $day->format('Y-m-d'); }, $weekDays)) ?>;
            let currentDayIndex = 0;

            // Find today's index or default to the first day
            const today = new Date().toISOString().split('T')[0];
            const todayIndex = weekDays.indexOf(today);
            if (todayIndex !== -1) {
                currentDayIndex = todayIndex;
            }

            function updateSingleDayView() {
                const selectedDate = weekDays[currentDayIndex];
                const dayColumn = document.querySelector(`.day-column[data-date="${selectedDate}"]`);

                if (dayColumn) {
                    // Update date display
                    const dateObj = new Date(selectedDate);
                    const dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                    const dayName = dayNames[dateObj.getDay()];
                    const formattedDate = dateObj.getDate() + '/' + (dateObj.getMonth() + 1) + '/' + dateObj.getFullYear();

                    document.getElementById('currentDayDisplay').textContent = `${dayName} - ${formattedDate}`;

                    // Clone day content
                    const contentClone = dayColumn.innerHTML;
                    document.getElementById('singleDayContent').innerHTML = contentClone.replace('day-header', 'day-header d-none');
                } else {
                    document.getElementById('singleDayContent').innerHTML = '<div class="text-center text-muted py-3">Không có dữ liệu</div>';
                }
            }

            // Navigation buttons for single day view
            document.getElementById('prevDayBtn').addEventListener('click', function() {
                if (currentDayIndex > 0) {
                    currentDayIndex--;
                    updateSingleDayView();
                }
            });

            document.getElementById('nextDayBtn').addEventListener('click', function() {
                if (currentDayIndex < weekDays.length - 1) {
                    currentDayIndex++;
                    updateSingleDayView();
                }
            });

            // Initialize single day view
            updateSingleDayView();
        });

        // Edit schedule
        function editSchedule(lich) {
            document.getElementById('editMaLich').value = lich.MaLich;
            document.getElementById('editMaBacSi').value = lich.MaBacSi;
            document.getElementById('editNgayLamViec').value = lich.NgayLamViec;
            document.getElementById('editGioBatDau').value = lich.GioBatDau;
            document.getElementById('editGioKetThuc').value = lich.GioKetThuc;
            document.getElementById('editTrangThai').value = lich.TrangThai;

            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }

        // Delete schedule
        function deleteSchedule(maLich) {
            document.getElementById('deleteMaLich').value = maLich;
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Update status
        function updateStatus(maLich, status) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?= $_SERVER['SCRIPT_NAME'] ?>?controller=lichkham&action=updateStatus", true);
            xhr.setRequestHeader("Content-Type", "application/json");

            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE) {
                    if (this.status === 200) {
                        try {
                            var data = JSON.parse(this.responseText);
                            if (data.success) {
                                alert("Cập nhật trạng thái thành công!");
                                location.reload();
                            } else {
                                alert('Cập nhật trạng thái thất bại: ' + (data.message || 'Lỗi không xác định'));
                            }
                        } catch (e) {
                            console.error("Lỗi khi parse JSON:", e);
                            console.log("Response text:", this.responseText);
                            alert("Có lỗi xảy ra khi xử lý phản hồi từ máy chủ.");
                        }
                    } else {
                        alert("Lỗi kết nối: " + this.status);
                    }
                }
            };

            var data = JSON.stringify({
                MaLich: maLich,
                TrangThai: status
            });

            xhr.send(data);
        }
    </script>

    <script src="<?= BASE_URL ?>/asset/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>