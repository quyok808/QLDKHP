<!-- student_list.php -->
<?php
$page_title = "Thông tin sinh viên";
ob_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thẻ Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f7;
            font-family: Arial, sans-serif;
        }

        .student-card {
            width: 350px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            text-align: center;
            padding: 20px;
            margin: 50px auto;
            border: 3px solid #007bff;
        }

        .student-card img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }

        .student-card h3 {
            margin-top: 10px;
            color: #007bff;
        }

        .student-info {
            text-align: left;
            padding: 10px 20px;
        }

        .student-info p {
            margin: 5px 0;
            font-size: 16px;
        }

        .student-info strong {
            color: #333;
        }

        .btn-back {
            display: block;
            margin: 20px auto;
            background-color: #6c757d;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            width: 80%;
            text-align: center;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    <a onclick="history.back()" role="button" class="btn-back pointer">⬅ Quay lại danh sách</a>
    <div class="student-card">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"> <?php echo htmlspecialchars($error); ?> </div>
        <?php else: ?>
            <?php if ($student): ?>
                <img src="/QLDKHP/<?php echo $student['Hinh']; ?>" alt="Ảnh sinh viên">
                <h3><?php echo htmlspecialchars($student['HoTen']); ?></h3>
                <div class="student-info">
                    <p><strong>Mã SV:</strong> <?php echo htmlspecialchars($student['MaSV']); ?></p>
                    <p><strong>Giới Tính:</strong> <?php echo htmlspecialchars($student['GioiTinh']); ?></p>
                    <p><strong>Ngày Sinh:</strong> <?php echo htmlspecialchars($student['NgaySinh']); ?></p>
                    <p><strong>Ngành Học:</strong> <?php echo htmlspecialchars($student['TenNganh']); ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Không tìm thấy sinh viên với mã <?php echo htmlspecialchars($id); ?>.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>