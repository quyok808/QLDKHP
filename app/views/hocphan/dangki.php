<?php
$page_title = "Danh sách sinh viên";
ob_start();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<div class="container mt-4">
    <h2 class="text-center mb-4">Đăng kí học phần</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Mã học phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo isset($course['MaHP']) ? $course['MaHP'] : ""; ?></td>
                        <td><?php echo isset($course['TenHP']) ? $course['TenHP'] : ""; ?></td>
                        <td><?php echo isset($course['SoTinChi']) ? $course['SoTinChi'] : ""; ?></td>
                        <td>
                            <form action="/QLDKHP/Subject/unregister" method="POST" class="d-inline">
                                <input type="hidden" name="maDK" value="<?php echo $course['MaDK']; ?>">
                                <input type="hidden" name="maHP" value="<?php echo $course['MaHP']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Xoá</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <p><strong>Số học phần:</strong> <?php echo isset($courses[0]['TotalRows']) ? $courses[0]['TotalRows'] : "0"; ?></p>
        <p><strong>Tổng số tín chỉ:</strong> <?php echo isset($courses[0]['TongSoTinChi']) ? $courses[0]['TongSoTinChi'] : "0"; ?></p>
    </div>

    <form method="POST" action="/QLDKHP/Subject/save">
        <input type="hidden" name="maDK" value="<?php echo isset($courses[0]['MaDK']) ? $courses[0]['MaDK'] : ''; ?>">
        <button type="submit" class="btn btn-success">Lưu đăng kí</button>
        <a href="/QLDKHP/Subject/unregisterAll/<?php echo $courses[0]['MaDK']; ?>" class="btn btn-warning">Xoá tất cả</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>