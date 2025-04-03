<?php
$page_title = "Thông tin học phần đã đăng ký";
ob_start();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container mt-4">
    <h2 class="text-center mb-4">Thông Tin Học Phần Đã Đăng Ký</h2>
    <p class="text-center">Mã số sinh viên: <?php echo isset($courses[0]['MaSV']) ? $courses[0]['MaSV'] : ''; ?></p>
    <p class="text-center">Tên sinh viên: <?php echo isset($courses[0]['HoTen']) ? $courses[0]['HoTen'] : ''; ?></p>
    <p class="text-center">Ngày sinh: <?php echo isset($courses[0]['NgaySinh']) ? $courses[0]['NgaySinh'] : ''; ?></p>
    <p class="text-center">Ngành học: <?php echo isset($courses[0]['TenNganh']) ? $courses[0]['TenNganh'] : ''; ?></p>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Mã học phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($courses)): ?>
                    <tr>
                        <td colspan="3" class="text-center">Chưa có học phần nào được đăng ký</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course['MaHP']; ?></td>
                            <td><?php echo $course['TenHP']; ?></td>
                            <td><?php echo $course['SoTinChi']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <a href="/QLDKHP/Subject" class="btn btn-primary">Trở lại danh sách học phần</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>