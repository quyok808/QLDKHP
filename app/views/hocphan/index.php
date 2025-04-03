<!-- student_list.php -->
<?php
$page_title = "Danh sách học phần";
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        .table img {
            border-radius: 8px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">Danh sách học phần</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Mã học phần</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Số lượng dự kiến</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo $course['MaHP']; ?></td>
                            <td><?php echo $course['TenHP']; ?></td>
                            <td><?php echo $course['SoTinChi']; ?></td>
                            <td><?php echo $course['SLDK']; ?></td>
                            <td>
                                <a href="/QLDKHP/Subject/register/<?php echo $course['MaHP']; ?>" class="btn btn-success btn-sm">Đăng kí</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>