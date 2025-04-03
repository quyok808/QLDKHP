<!-- student_list.php -->
<?php
$page_title = "Danh sách sinh viên";
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
    <div class="container-fluid">
        <h2 class="text-center mb-4">Danh sách sinh viên</h2>
        <div class="text-end mb-3">
            <a href="./Student/create" class="btn btn-primary">Thêm sinh viên mới</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ Tên</th>
                        <th>Giới Tính</th>
                        <th>Ngày Sinh</th>
                        <th>Hình</th>
                        <th>Ngành Học</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo $student['MaSV']; ?></td>
                            <td><?php echo $student['HoTen']; ?></td>
                            <td><?php echo $student['GioiTinh']; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($student['NgaySinh'])); ?></td>
                            <td>
                                <img src="../QLDKHP/<?php echo $student['Hinh']; ?>" width="80" height="80" alt="Hình SV">
                            </td>
                            <td><?php echo $student['TenNganh']; ?></td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-1">
                                    <a href="./Student/edit/<?php echo $student['MaSV']; ?>" class="btn btn-success btn-sm w-100 w-md-auto">Sửa</a>
                                    <a href="./Student/detail/<?php echo $student['MaSV']; ?>" class="btn btn-info btn-sm w-100 w-md-auto">Chi tiết</a>
                                    <a href="./Student/delete/<?php echo $student['MaSV']; ?>" class="btn btn-danger btn-sm w-100 w-md-auto" onclick="return confirm('Bạn có chắc muốn xoá sinh viên này?');">Xoá</a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                <nav>
                    <ul class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">Trước</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>