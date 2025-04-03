<?php
require_once 'app/configs/db_connect.php';
require_once 'app/models/Course.php';
require_once 'app/models/Student.php';
session_start();
class SubjectController
{
    private $courseModel;
    public function __construct()
    {
        global $conn;
        $this->courseModel = new Course($conn);
    }

    public function index()
    {
        $courses = $this->courseModel->getAvailableCourses();
        include 'app/views/hocphan/index.php';
    }

    public function register($id)
    {

        $maSV = $_SESSION['maSV'];
        if (!isset($_SESSION['maSV'])) {
            header('Location: /QLDKHP/Auth');
            exit;
        }
        $_SESSION['countHP'] = $_SESSION['countHP'] + 1;

        $this->courseModel->registerCourse($maSV, $id);
        header('Location: /QLDKHP/Subject/registered');
    }

    public function registered()
    {
        if (!isset($_SESSION['maSV'])) {
            header('Location: ../Auth');
            exit;
        }

        $maSV = $_SESSION['maSV'];
        $courses = $this->courseModel->getCourseByMaSV($maSV) ?? [];

        include 'app/views/hocphan/dangki.php';
    }


    public function save()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['maDK'])) {
                $maDK = $_POST['maDK'];

                $result = $this->courseModel->save($maDK);
                $_SESSION['countHP'] = 0;
                echo "<script>window.location.href='/QLDKHP/Subject/infosave/$maDK';</script>";
            } else echo "abcd";
        }
    }

    public function infosave($maDK)
    {
        $courses = $this->courseModel->infosave($maDK);

        include 'app/views/hocphan/infosave.php';
    }

    public function unregister()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['maDK']) && isset($_POST['maHP'])) {
                $maDK = $_POST['maDK'];
                $maHP = $_POST['maHP'];

                $result = $this->courseModel->unregisterCourse($maDK, $maHP);
                $_SESSION['countHP'] = $_SESSION['countHP'] - 1;
                echo "<script>window.location.href='/QLDKHP/Subject/registered';</script>";
            } else echo "abcd";
        }
    }

    public function unregisterAll($maDK)
    {
        $result = $this->courseModel->unregisterAll($maDK);
        $_SESSION['countHP'] = 0;
        echo "<script> window.location.href='/QLDKHP/Subject/registered';</script>";
    }
}
