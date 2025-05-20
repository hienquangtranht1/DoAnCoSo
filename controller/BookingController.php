<?php
// booking/controllers/BookingController.php
class BookingController {
    public function showForm() {
        // Hiển thị giao diện đặt lịch (view index)
        include __DIR__ . '/../view/index.php';
    }
    
    public function processBooking() {
        // Tạm thời không xử lý dữ liệu, chỉ chuyển sang trang cảm ơn
        include __DIR__ . '/../view/thankyou.php';
    }
}
?>
