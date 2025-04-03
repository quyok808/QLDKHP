<h2 class="text-center mb-4">Chỉnh sửa sinh viên</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Mã SV</label>
        <input type="text" class="form-control" value="<?php echo $student['MaSV']; ?>" disabled>
    </div>
    <div class="mb-3">
        <label class="form-label">Họ Tên</label>
        <input type="text" name="hoTen" class="form-control" value="<?php echo $student['HoTen']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Giới Tính</label>
        <select name="gioiTinh" class="form-select" required>
            <option value="Nam" <?php echo $student['GioiTinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
            <option value="Nữ" <?php echo $student['GioiTinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Ngày Sinh</label>
        <input type="date" name="ngaySinh" class="form-control" value="<?php echo $student['NgaySinh']; ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Hình hiện tại</label>
        <?php if ($student['Hinh']): ?>
            <img src="/QLDKHP<?php echo $student['Hinh']; ?>" width="100" class="mb-2">
        <?php endif; ?>
        <input type="file" name="hinh" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Ngành Học</label>
        <select name="maNganh" class="form-select" required>
            <?php foreach ($majors as $major): ?>
                <option value="<?php echo $major['MaNganh']; ?>" <?php echo $student['MaNganh'] === $major['MaNganh'] ? 'selected' : ''; ?>>
                    <?php echo $major['TenNganh']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="../Student" class="btn btn-secondary">Hủy</a>
</form>