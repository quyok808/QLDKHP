<?php
require_once 'app/configs/db_connect.php';
require_once 'app/models/Student.php';
session_start();
class StudentController
{
    private $student;

    public function __construct()
    {
        global $conn;
        $this->student = new Student($conn);
    }

    public function index()
    {
        $perPage = 4;
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;

        $totalRecords = $this->student->countTotal();
        $totalPages = ceil($totalRecords / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $students = $this->student->getAll($perPage, $offset);
        include 'app/views/students/index.php';
    }

    public function create()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->student->create($_POST, $_FILES);
                header("Location: ../Student");
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                $majors = $this->student->getMajors();
                include 'app/views/students/create.php';
            }
        } else {
            $majors = $this->student->getMajors();
            include 'app/views/students/create.php';
        }
    }

    public function detail($id)
    {
        $student = $this->student->getById($id);
        if (!$student) {
            $error = "Không tìm thấy sinh viên với mã $id.";
        }
        include 'app/views/students/detail.php';
    }

    public function delete($id)
    {
        $student = $this->student->getById($id);
        if (!$student) {
            header('Location: ../../Student');
            exit();
        }

        $result = $this->student->delete($id);
        if ($result) {
            $_SESSION['success'] = "Xóa sản phẩm thành công";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa sản phẩm";
        }

        header('Location: ../../Student');
        exit();
    }

    public function edit($id)
    {
        $student = $this->student->getById($id);
        if (!$student) {
            header('Location: ../Student');
            exit();
        }

        $majors = $this->student->getMajors();
        $page_title = "Chỉnh sửa sinh viên";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                $this->student->update($id, $_POST, $_FILES);
                header("Location: ../../Student");
                exit();
            } catch (Exception $e) {
                $error = $e->getMessage();
                // Giữ lại $student và $majors để hiển thị lại form với dữ liệu cũ
            }
        }

        ob_start();
        require_once 'app/views/students/edit.php';
        $content = ob_get_clean();

        include 'app/views/layout.php';
    }
}
