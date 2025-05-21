<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include __DIR__ . '/../../header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hồ sơ bệnh án</title>
  <style>
      /* Reset chung */
      * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
      }
      
      body {
          font-family: Arial, sans-serif;
          background: #f2f2f2;
          
      }
      
      .container {
          position: relative;
          max-width: 1000px;
          margin: 60px auto 0;
          background: #ffffff;
          box-shadow: 0 2px 6px rgba(0,0,0,0.15);
          border-radius: 8px;
          overflow: hidden;
          margin-left: 300px;
      }
      
      .content {
          padding: 20px;
          margin-top: 60px;
      }
      
      h2 {
          text-align: center;
          margin-bottom: 20px;
          color: #333;
      }
      
      .search-form {
          max-width: 500px;
          margin: 0 auto 20px;
          display: flex;
          gap: 10px;
      }
      .search-form input[type="text"] {
          flex: 1;
          padding: 8px 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }
      .search-form select {
          padding: 8px 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }
      .search-form input[type="submit"] {
          background-color: #3498db;
          color: #fff;
          border: none;
          padding: 8px 15px;
          border-radius: 4px;
          cursor: pointer;
      }
      .search-form input[type="submit"]:hover {
          background-color: #2980b9;
      }
      
      table {
          width: 100%;
          border-collapse: collapse;
          margin-bottom: 30px;
      }
      table thead {
          background-color: #3498db;
          color: #fff;
      }
      table th, table td {
          padding: 12px 15px;
          border: 1px solid #ddd;
          text-align: left;
      }
      table tbody tr:hover {
          background-color: #f1f1f1;
      }
      form.update-form {
          display: flex;
          flex-wrap: wrap;
          gap: 10px;
          align-items: center;
      }
      form.update-form input[type="text"] {
          flex: 1;
          padding: 8px 10px;
          border: 1px solid #ccc;
          border-radius: 4px;
      }
      form.update-form input[type="submit"] {
          background-color: #3498db;
          color: #fff;
          border: none;
          padding: 8px 15px;
          border-radius: 4px;
          cursor: pointer;
      }
      form.update-form input[type="submit"]:hover {
          background-color: #2980b9;
      }
      .modal {
          display: none;
          position: fixed;
          z-index: 1000;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0,0,0,0.5);
          overflow: auto;
          justify-content: center;
          align-items: center;
      }
      .modal-content {
          background-color: #fff;
          margin: auto;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 4px 8px rgba(0,0,0,0.2);
          width: 500px;
          max-width: 90%;
          position: relative;
          animation: modalFadeIn 0.3s;
      }
      
      @keyframes modalFadeIn {
          from {opacity: 0; transform: translateY(-20px);}
          to {opacity: 1; transform: translateY(0);}
      }
      .close-modal {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
          cursor: pointer;
          margin-top: -10px;
      }
      
      .close-modal:hover,
      .close-modal:focus {
          color: #000;
          text-decoration: none;
      }
      form.add-form {
          display: flex;
          flex-direction: column;
          gap: 15px;
          padding: 10px;
      }
      
      form.add-form h3 {
          text-align: center;
          margin-bottom: 15px;
          color: #333;
      }
      
      form.add-form input[type="text"],
      form.add-form input[type="date"] {
          width: 100%;
          padding: 12px;
          border: 1px solid #ddd;
          border-radius: 4px;
          font-size: 14px;
      }
      
      form.add-form input[type="submit"] {
          background-color: #27ae60;
          color: #fff;
          border: none;
          padding: 12px;
          border-radius: 4px;
          cursor: pointer;
          font-weight: bold;
          margin-top: 10px;
      }
      
      form.add-form input[type="submit"]:hover {
          background-color: #219150;
      }
      .add-record-btn {
          display: block;
          width: 200px;
          margin: 0 auto 20px;
          background-color: #27ae60;
          color: #fff;
          border: none;
          padding: 10px 15px;
          border-radius: 4px;
          cursor: pointer;
          text-align: center;
          font-weight: bold;
      }
      .add-record-btn:hover {
          background-color: #219150;
      }
      
      .form-section {
          margin-top: 30px;
          text-align: center;
      }
  </style>
  <script>
      function openAddModal() {
          document.getElementById("addRecordModal").style.display = "flex";
      }
      function closeAddModal() {
          document.getElementById("addRecordModal").style.display = "none";
      }
      
      window.onclick = function(event) {
          var modal = document.getElementById("addRecordModal");
          if (event.target == modal) {
              closeAddModal();
          }
      }
  </script>
</head>
<body>

  
<div class="container">
  <div class="content">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
          <!-- Sửa form: action sử dụng URL sạch -->
          <form class="search-form" method="GET" action="<?= BASE_URL ?>HoSoBenhAn">
              <input type="hidden" name="action" value="index">
              <input type="text" name="search" placeholder="Nhập từ khoá tìm kiếm..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
              <select name="mode">
                  <option value="patient" <?= (!isset($_GET['mode']) || $_GET['mode'] === 'patient') ? 'selected' : '' ?>>Tên bệnh nhân</option>
                  <option value="chanDoan" <?= (isset($_GET['mode']) && $_GET['mode'] === 'chanDoan') ? 'selected' : '' ?>>Chẩn đoán</option>
              </select>
              <input type="submit" value="Tìm kiếm">
          </form>
          <!-- Link mở modal thêm hồ sơ bệnh án -->
          <button type="button" id="openModalBtn" class="add-record-btn" onclick="openAddModal()" style="margin: 0;">Thêm hồ sơ bệnh án</button>
      </div>
      
      <!-- Danh sách hồ sơ bệnh án -->
      <h2>Danh sách bệnh án</h2>
      <table>
          <thead>
              <tr>
                  <th>Tên bệnh nhân</th>
                  <th>Tên bác sĩ</th>
                  <th>Ngày khám</th>
                  <th>Chẩn đoán</th>
                  <th>Phác đồ điều trị</th>
                  <th>Hành động</th>
              </tr>
          </thead>
          <tbody>
          <?php if (!empty($records)): ?>
              <?php foreach ($records as $record): ?>
              <tr>
                  <td><?= htmlspecialchars($record['TenBenhNhan']) ?></td>
                  <td><?= htmlspecialchars($record['TenBacSi']) ?></td>
                  <td><?= htmlspecialchars($record['NgayKham']) ?></td>
                  <td><?= htmlspecialchars($record['ChanDoan']) ?></td>
                  <td><?= htmlspecialchars($record['PhuongAnDieuTri']) ?></td>
                  <td>
                      <?php if ($record['MaBacSi'] == $currentDoctorId): ?>
                          <!-- Sửa form update: action sử dụng URL sạch -->
                          <form class="update-form" method="POST" action="<?= BASE_URL ?>HoSoBenhAn?action=edit">
                              <input type="hidden" name="MaHoSo" value="<?= htmlspecialchars($record['MaHoSo']) ?>">
                              <?php if (isset($_GET['search'])): ?>
                                  <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                              <?php endif; ?>
                              <?php if (isset($_GET['mode'])): ?>
                                  <input type="hidden" name="mode" value="<?= htmlspecialchars($_GET['mode']) ?>">
                              <?php endif; ?>
                              <input type="text" name="ChanDoan" value="<?= htmlspecialchars($record['ChanDoan']) ?>" placeholder="Chẩn đoán" required>
                              <input type="text" name="PhuongAnDieuTri" value="<?= htmlspecialchars($record['PhuongAnDieuTri']) ?>" placeholder="Phác đồ điều trị">
                              <input type="submit" value="Cập nhật">
                          </form>
                      <?php else: ?>
                          <em>Chỉ xem</em>
                      <?php endif; ?>
                  </td>
              </tr>
              <?php endforeach; ?>
          <?php else: ?>
              <tr>
                  <td colspan="6" style="text-align: center;">Không có hồ sơ bệnh án</td>
              </tr>
          <?php endif; ?>
          </tbody>
      </table>
      <!-- Modal thêm hồ sơ bệnh án -->
      <div id="addRecordModal" class="modal">
          <div class="modal-content">
              <span class="close-modal" onclick="closeAddModal()">&times;</span>
              <!-- Sửa form thêm: action sử dụng URL sạch -->
              <form id="addRecordForm" class="add-form" method="POST" action="<?= BASE_URL ?>HoSoBenhAn?action=add">
                  <h3>Nhập thông tin hồ sơ bệnh án mới</h3>
                  <input type="text" name="MaBenhNhan" placeholder="Mã bệnh nhân" required>
                  <input type="date" name="NgayKham" required>
                  <input type="text" name="ChanDoan" placeholder="Chẩn đoán" required>
                  <input type="text" name="PhuongAnDieuTri" placeholder="Phác đồ điều trị">
                  <input type="submit" value="Thêm hồ sơ">
              </form>
          </div>
      </div>
  </div>
</div>
</body>
</html>
