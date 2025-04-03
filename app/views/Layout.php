<!-- layout.php -->
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($page_title) ? $page_title : 'Quản Lý Sinh Viên'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../public/images/67e3aa010ad13.png">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            margin-top: 30px;
        }

        .table img {
            border-radius: 8px;
            object-fit: cover;
        }

        .main-content {
            flex: 1 0 auto;
        }

        footer {
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="./Default">Quản Lý Sinh Viên</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/QLDKHP/Student">Sinh viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/QLDKHP/Subject">Học phần</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/QLDKHP/Subject/registered">Đăng kí(<?php echo isset($_SESSION['countHP']) ? $_SESSION['countHP'] : 0; ?>)</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['maSV'])): ?>
                            <p class="nav-link">Xin chào, <?php echo $_SESSION['maSV']; ?></p>
                        <?php endif; ?>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['maSV'])): ?>
                            <a class="nav-link" href="/QLDKHP/Auth/Logout">Đăng xuất</a>
                        <?php else: ?>
                            <a class="nav-link" href="/QLDKHP/Auth">Đăng nhập</a>
                        <?php endif; ?>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <?php
            // Nơi nội dung trang sẽ được chèn vào
            if (isset($content)) {
                echo $content;
            } else {
                include($content_file);
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p>© <?php echo date("Y"); ?> Quản Lý Sinh Viên. All rights reserved.</p>
            <p>Developed by [Nguyen Cong Quy]</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>