<!-- student_list.php -->
<?php
$page_title = "Đăng nhập";
ob_start();
?>
<div class="card p-4 shadow-sm">

    <h4 class="text-center">Đăng nhập</h4>
    <form method="post" action="./Auth/login" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Thêm</button>

    </form>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
$content = ob_get_clean();
include './app/views/Layout.php';
?>