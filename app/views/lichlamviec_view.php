<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Ho_Chi_Minh');

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$editMaLich = isset($_GET['MaLich']) ? $_GET['MaLich'] : '';

// Sửa cách lấy ngày đầu tuần
$weekStart = isset($_GET['weekStart']) ? $_GET['weekStart'] : date('Y-m-d', strtotime('monday this week'));
$monday = new DateTime($weekStart);

// Đảm bảo luôn bắt đầu từ thứ 2
if ($monday->format('N') !== '1') {
    $monday->modify('last monday');
}

$weekDays = [];
for ($i = 0; $i < 7; $i++) {
    $day = clone $monday;
    $day->modify("+{$i} day");
    $weekDays[] = $day;
}
$sunday = clone $monday;
$sunday->modify("+6 days");

$prevWeek = (clone $monday)->modify("-7 days")->format("Y-m-d");
$nextWeek = (clone $monday)->modify("+7 days")->format("Y-m-d");

// Nhóm các lịch theo NgayLamViec, do Controller truyền vào qua biến $schedules
$schedulesByDay = [];
foreach ($schedules as $sch) {
    $date = $sch['NgayLamViec'];
    if (!isset($schedulesByDay[$date])) {
        $schedulesByDay[$date] = [];
    }
    $schedulesByDay[$date][] = $sch;
}
?>
<?php include __DIR__ . '/../../header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Lịch Làm Việc</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
     /* Global Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }
    a {
      text-decoration: none;
      transition: all 0.3s ease;
    }
    /* Header (không thay đổi) */
    
    /* Container */
    .container {
      background-color: #fff;
      padding: 30px;
      max-width: 1000px;
      margin: 80px auto;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-left: 300px;
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #343a40;
      font-size: 1.5rem;
    }
    /* Navigation Week */
    .nav-week {
      text-align: center;
      margin-bottom: 25px;
    }
    .nav-week a {
      margin: 0 12px;
      background-color: #007bff;
      color: #fff;
      padding: 8px 16px;
      border-radius: 4px;
      font-size: 0.95rem;
    }
    .nav-week a:hover {
      background-color: #0056b3;
    }
    /* Table Styles */
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      margin-bottom: 30px;
    }
    th, td {
      padding: 12px 15px;
      border: 1px solid #ddd;
      text-align: center;
      font-size: 0.9rem;
    }
    th {
      background-color: #f2f2f2;
    }
    /* Working Hours Highlight */
    .working-hours {
        font-size: 20px;
        font-weight: bold;
        color: #333;
        background-color: #FFFF99; /* nền vàng nhạt */
        padding: 6px 10px;
        border-radius: 5px;
        display: inline-block;
        margin-bottom: 5px;
    }
    /* Action Section */
    .action-section {
      background-color: #eef;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 30px;
    }
    .action-section h3 {
      margin-top: 0;
      text-align: center;
      font-size: 1.3rem;
      color: #333;
    }
    .action-section ul {
      list-style: none;
      padding: 0;
    }
    .action-section li {
      margin-bottom: 15px;
      text-align: center;
    }
    /* Inline Form Styles */
    .inline-form {
      display: inline-block;
      margin-top: 10px;
    }
    .inline-form label {
      font-size: 0.85rem;
      margin-right: 5px;
    }
    .inline-form input[type="time"],
    .inline-form input[type="submit"] {
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 3px;
      font-size: 0.9rem;
      margin-right: 5px;
    }
    .inline-form input[type="submit"] {
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .inline-form input[type="submit"]:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div class="container" style="margin-left:260px; padding:20px;">
    <h2>Lịch Làm Việc Tuần (<?= $monday->format("d/m/Y") ?> - <?= $sunday->format("d/m/Y") ?>)</h2>
    <div class="nav-week" style="text-align:center; margin-bottom:20px;">
      <a href="<?= BASE_URL ?>LichLamViec?weekStart=<?= $prevWeek ?>">&laquo; Tuần trước</a>
      <a href="<?= BASE_URL ?>LichLamViec?weekStart=<?= $nextWeek ?>" style="margin-left:20px;">Tuần sau &raquo;</a>
    </div>
    
    <!-- Bảng hiển thị lịch làm việc tổng quan -->
    <table style="width:100%; border-collapse:collapse; background-color:#fff; margin:0 auto;">
      <thead style="background-color:#f2f2f2;">
        <tr>
          <th style="border:1px solid #ddd; padding:10px; text-align:center;">Ngày</th>
          <th style="border:1px solid #ddd; padding:10px; text-align:center;">Lịch Làm Việc</th>
          <th style="border:1px solid #ddd; padding:10px; text-align:center;">Trạng thái</th> <!-- Thêm cột trạng thái -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($weekDays as $day): 
              $dateStr = $day->format("Y-m-d");
              $weekdayNumber = date("N", $day->getTimestamp());
              if ($weekdayNumber == 1) { 
                  $weekdayName = "Thứ 2"; 
              } elseif ($weekdayNumber == 2) {
                  $weekdayName = "Thứ 3";
              } elseif ($weekdayNumber == 3) {
                  $weekdayName = "Thứ 4";
              } elseif ($weekdayNumber == 4) {
                  $weekdayName = "Thứ 5";
              } elseif ($weekdayNumber == 5) {
                  $weekdayName = "Thứ 6";
              } elseif ($weekdayNumber == 6) {
                  $weekdayName = "Thứ 7";
              } else {
                  $weekdayName = "Chủ nhật";
              }
              $daySchedules = isset($schedulesByDay[$dateStr]) ? $schedulesByDay[$dateStr] : [];
        ?>
          <tr>
            <td style="border:1px solid #ddd; padding:10px; text-align:center;">
              <?= $weekdayName . "<br/>" . $day->format("d/m/Y") ?>
            </td>
            <td style="border:1px solid #ddd; padding:10px; text-align:center;">
              <?php if (!empty($daySchedules)): ?>
                <?php foreach ($daySchedules as $sch): ?>
                  <div class="working-hours">
                    <?= substr($sch['GioBatDau'], 0, 8) ?> - <?= substr($sch['GioKetThuc'], 0, 8) ?>
                  </div>
                  <br/>
                <?php endforeach; ?>
              <?php else: ?>
                  Không có lịch làm việc
              <?php endif; ?>
            </td>
            <td style="border:1px solid #ddd; padding:10px; text-align:center;">
              <?php if (!empty($daySchedules)): ?>
                <?php foreach ($daySchedules as $sch): ?>
                  <?= htmlspecialchars($sch['TrangThai']) ?><br/>
                <?php endforeach; ?>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    
    <!-- Phần Hành động: form inline cho từng ngày (trừ Chủ nhật) -->
    <div class="action-section" style="margin-top:30px;">
      <h3>Chỉnh Sửa / Thêm / Xóa Lịch Làm Việc</h3>
      <ul style="list-style: none; padding: 0;">
        <?php foreach ($weekDays as $day): 
                $dateStr = $day->format("Y-m-d");
                $weekdayNumber = date("N", $day->getTimestamp());
                if ($weekdayNumber == 1) {
                    $weekdayName = "Thứ 2";
                } elseif ($weekdayNumber == 2) {
                    $weekdayName = "Thứ 3";
                } elseif ($weekdayNumber == 3) {
                    $weekdayName = "Thứ 4";
                } elseif ($weekdayNumber == 4) {
                    $weekdayName = "Thứ 5";
                } elseif ($weekdayNumber == 5) {
                    $weekdayName = "Thứ 6";
                } elseif ($weekdayNumber == 6) {
                    $weekdayName = "Thứ 7";
                } else {
                    $weekdayName = "Chủ nhật";
                }
                $daySchedules = isset($schedulesByDay[$dateStr]) ? $schedulesByDay[$dateStr] : [];
        ?>
          <li style="margin-bottom:15px;">
            <strong><?= $weekdayName . " - " . $day->format("d/m/Y") ?>:</strong>
            <?php if ($weekdayNumber == 7): ?>
              (Không cho phép thêm lịch vào Chủ nhật)
            <?php else: ?>
              <?php if (empty($daySchedules)): // Nếu chưa có ca, hiển thị form thêm mới ?>
                  <form class="inline-form" method="POST" action="<?= BASE_URL ?>LichLamViec?action=add" style="display:inline-block;">
                    <input type="hidden" name="NgayLamViec" value="<?= $dateStr ?>">
                    <label>Giờ bắt đầu:</label>
                    <input type="time" name="GioBatDau" min="00:00" max="23:59" step="60" required>
                    <label>Giờ kết thúc:</label>
                    <input type="time" name="GioKetThuc" min="00:00" max="23:59" step="60" required>
                    <input type="submit" value="Thêm mới lịch">
                  </form>
              <?php else: 
                      // Nếu đã có ca, lấy ca đầu tiên để cập nhật
                      $sch = $daySchedules[0];
              ?>
                  <form class="inline-form" method="POST" action="<?= BASE_URL ?>LichLamViec?action=edit" style="display:inline-block;">
                    <input type="hidden" name="MaLich" value="<?= $sch['MaLich'] ?>">
                    <input type="hidden" name="NgayLamViec" value="<?= $dateStr ?>">
                    <label>Giờ bắt đầu:</label>
                    <input type="time" name="GioBatDau" value="<?= substr($sch['GioBatDau'], 0, 5) ?>" min="00:00" max="23:59" step="60" required>
                    <label>Giờ kết thúc:</label>
                    <input type="time" name="GioKetThuc" value="<?= substr($sch['GioKetThuc'], 0, 5) ?>" min="00:00" max="23:59" step="60" required>
                    <input type="submit" value="Cập nhật lịch">
                  </form>
                  <!-- Nút Xóa lịch: đặt ngay sau form cập nhật -->
                  <form class="inline-form" method="POST" action="<?= BASE_URL ?>LichLamViec?action=delete" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa lịch này không?');">
                    <input type="hidden" name="MaLich" value="<?= $sch['MaLich'] ?>">
                    <input type="hidden" name="NgayLamViec" value="<?= $dateStr ?>">
                    <input type="submit" value="Xóa lịch">
                  </form>
              <?php endif; ?>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</body>
</html>
