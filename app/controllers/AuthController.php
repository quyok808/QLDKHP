<?php
require_once './app/configs/db_connect.php';
require_once './app/models/Student.php';

class AuthController
{

    private $student;

    public function __construct()
    {
        global $conn;
        $this->student = new Student($conn);
    }

    public function index()
    {
        include 'app/views/login/index.php';
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $Check = $this->student->login($_POST);
            if ($Check) {
                header("Location: ../Subject");
                exit;
            } else {
                header("Location: ../Auth");
                exit;
            }
        }
    }
    public function Logout()
    {
        session_start();
        session_unset();  // Xóa tất cả biến session
        session_destroy(); // Hủy session hoàn toàn
        header("Location: ../Student");
        exit;
    }
}
