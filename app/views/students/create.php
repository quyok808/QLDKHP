<!-- student_list.php -->
<?php
$page_title = "Thêm mới sinh viên";
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

        .table-hover tbody tr:hover {
            background-color: #e9ecef;
        }

        .preview-img {
            max-width: 100px;
            margin-top: 10px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <a onclick="history.back()" class="btn btn-back">⬅ Quay lại danh sách</a>
    <div class="card p-4 shadow-sm">

        <h4 class="text-center">Thêm Sinh Viên</h4>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label">Mã SV</label>
                <input type="text" name="maSV" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" name="hoTen" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select name="gioiTinh" class="form-select">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" name="ngaySinh" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngành Học</label>
                <select name="maNganh" class="form-select">
                    <?php foreach ($majors as $major): ?>
                        <option value="<?php echo $major['MaNganh']; ?>"><?php echo $major['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình</label>
                <input type="file" name="hinh" class="form-control" accept="image/*" onchange="previewImage(event)">
                <img id="preview" class="preview-img d-none">
            </div>
            <button type="submit" class="btn btn-success w-100">Thêm</button>

        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function validateForm() {
            const fileInput = document.querySelector('input[name="hinh"]');
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Vui lòng chọn một tệp ảnh hợp lệ.');
                    return false;
                }
            }
            return true;
        }
    </script>
</body>

</html>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>