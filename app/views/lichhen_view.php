<?php
// File: app/views/lichhen_view.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Include header file
include __DIR__ . '/../../header.php';
?>

<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
  }
  
  .container {
    padding: 20px;
    max-width: 1000px;
    margin: 60px auto 0;
    background-color: #ffffff;
    margin-left: 300px;
  }
  h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #343a40;
  }
  form {
    text-align: center;
    margin-bottom: 20px;
  }
  label {
    font-weight: bold;
    margin-right: 10px;
  }
  input[type="date"],
  input[type="text"] {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
    min-width: 150px;
  }
  .table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
  }
  .table thead {
    background-color: #f2f2f2;
  }
  .table th,
  .table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
  }
  .submit-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 5px 12px;
    border-radius: 3px;
    cursor: pointer;
  }
  .submit-btn:hover {
    background-color: #0056b3;
  }
  form.inline-form {
    display: inline-block;
  }
  select {
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 3px;
  }
</style>

<div class="container">
  <h2>MANAGE PATIENT</h2>
  <form method="GET" action="">
    <label for="date">Choose date:</label>
    <input type="date" id="date" name="date" value="<?= htmlspecialchars($selectedDate); ?>">
    <input type="submit" value="Tìm" class="submit-btn">
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>STT</th>
        <th>Time</th>
        <th>Full Name</th>
        <th>Address</th>
        <th>Gender</th>
        <th>Symptoms</th>
        <th>Current Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($appointments)): ?>
        <?php $stt = 1; ?>
        <?php foreach ($appointments as $appointment): ?>
          <?php $currentStatus = isset($appointment["TrangThai"]) ? $appointment["TrangThai"] : "No Status"; ?>
          <tr>
            <td><?= $stt++; ?></td>
            <td><?= htmlspecialchars(date("g:i A", strtotime($appointment["NgayGio"]))); ?></td>
            <td><?= htmlspecialchars($appointment["HoTen"]); ?></td>
            <td><?= htmlspecialchars($appointment["DiaChi"]); ?></td>
            <td><?= htmlspecialchars($appointment["GioiTinh"]); ?></td>
            <td><?= htmlspecialchars($appointment["TrieuChung"] ?? 'Không có'); ?></td>
            <td><?= htmlspecialchars($currentStatus); ?></td>
            <td>
              <?php if ($currentStatus === 'Chờ xác nhận' || $currentStatus === 'Chưa xác nhận'): ?>
                <form method="POST" action="<?= BASE_URL ?>LichHenBs?action=update" class="inline-form">
                  <input type="hidden" name="MaLich" value="<?= htmlspecialchars($appointment['MaLich']); ?>">
                  <input type="hidden" name="selectedDate" value="<?= htmlspecialchars($selectedDate); ?>">
                  <button type="submit" name="new_status" value="Đã xác nhận" class="submit-btn">Xác nhận</button>
                  <button type="submit" name="new_status" value="Đã hủy" class="submit-btn" style="margin-left:5px;">Hủy</button>
                </form>
              <?php else: ?>
                <form method="POST" action="<?= BASE_URL ?>LichHenBs?action=update" class="inline-form">
                  <input type="hidden" name="MaLich" value="<?= htmlspecialchars($appointment['MaLich']); ?>">
                  <input type="hidden" name="selectedDate" value="<?= htmlspecialchars($selectedDate); ?>">
                  <select name="new_status">
                    <?php 
                      $statuses = ['Chờ xác nhận', 'Đã xác nhận', 'Đã hủy'];
                      foreach ($statuses as $status):
                    ?>
                      <option value="<?= htmlspecialchars($status); ?>" <?= ($currentStatus === $status) ? "selected" : ""; ?>>
                        <?= htmlspecialchars($status); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit" class="submit-btn">Cập nhật</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8">Không có lịch hẹn.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>