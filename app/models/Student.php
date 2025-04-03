<?php
require_once './app/configs/db_connect.php';

class Student
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($limit, $offset)
    {
        $stmt = $this->conn->prepare("SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh LIMIT $limit OFFSET $offset");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countTotal()
    {
        $sql = "SELECT COUNT(*) as total FROM sinhvien";
        $result = $this->conn->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function create($data, $file)
    {
        try {
            // Validation dữ liệu
            if (empty($data['maSV'])) {
                throw new Exception("Mã sinh viên không được để trống.");
            }
            if (empty($data['hoTen'])) {
                throw new Exception("Họ tên không được để trống.");
            }
            if (empty($data['gioiTinh']) || !in_array($data['gioiTinh'], ['Nam', 'Nữ'])) {
                throw new Exception("Giới tính không hợp lệ.");
            }
            if (empty($data['ngaySinh'])) {
                throw new Exception("Ngày sinh không được để trống.");
            }
            if (empty($data['maNganh'])) {
                throw new Exception("Ngành học không được để trống.");
            }

            // Kiểm tra trùng MaSV
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM SinhVien WHERE MaSV = ?");
            $stmt->execute([$data['maSV']]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Mã sinh viên đã tồn tại.");
            }

            // Xử lý upload file ảnh
            $target_dir = './public/images/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $hinh = null;
            if (isset($file['hinh']) && $file['hinh']['error'] != UPLOAD_ERR_NO_FILE) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024;
                $file_type = mime_content_type($file['hinh']['tmp_name']);
                $file_size = $file['hinh']['size'];

                if (!in_array($file_type, $allowed_types)) {
                    throw new Exception("Chỉ chấp nhận file ảnh JPEG, PNG hoặc GIF.");
                }
                if ($file_size > $max_size) {
                    throw new Exception("File ảnh không được vượt quá 5MB.");
                }

                $file_extension = pathinfo($file['hinh']['name'], PATHINFO_EXTENSION);
                $hinh = $target_dir . uniqid() . '.' . $file_extension;

                if (!move_uploaded_file($file['hinh']['tmp_name'], $hinh)) {
                    throw new Exception("Không thể upload file ảnh.");
                }
            }

            // Thêm vào database
            $stmt = $this->conn->prepare("INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) VALUES (?, ?, ?, ?, ?, ?)");
            $success = $stmt->execute([
                $data['maSV'],
                $data['hoTen'],
                $data['gioiTinh'],
                $data['ngaySinh'],
                $hinh,
                $data['maNganh']
            ]);

            if (!$success) {
                throw new Exception("Không thể thêm sinh viên vào cơ sở dữ liệu.");
            }

            return true;
        } catch (Exception $e) {
            if (isset($hinh) && file_exists($hinh)) {
                unlink($hinh);
            }
            throw $e;
        }
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh WHERE MaSV = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Phương thức mới để lấy danh sách ngành học
    public function getMajors()
    {
        $stmt = $this->conn->prepare("SELECT * FROM NganhHoc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        try {
            // Kiểm tra ID có hợp lệ không
            if (empty($id)) {
                throw new Exception("ID không được rỗng.");
            }

            // Chuẩn bị truy vấn SQL
            $stmt = $this->conn->prepare("DELETE FROM SinhVien WHERE MaSV = :MaSV");

            // Bind giá trị vào tham số
            $stmt->bindParam(':MaSV', $id, PDO::PARAM_STR);

            // Thực thi truy vấn
            if ($stmt->execute()) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            die("Lỗi SQL: " . $e->getMessage());
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    public function update($id, $data, $file = null)
    {
        try {
            // Validation dữ liệu
            if (empty($id)) {
                throw new Exception("Mã sinh viên không được để trống.");
            }
            if (empty($data['hoTen'])) {
                throw new Exception("Họ tên không được để trống.");
            }
            if (empty($data['gioiTinh']) || !in_array($data['gioiTinh'], ['Nam', 'Nữ'])) {
                throw new Exception("Giới tính không hợp lệ.");
            }
            if (empty($data['ngaySinh'])) {
                throw new Exception("Ngày sinh không được để trống.");
            }
            if (empty($data['maNganh'])) {
                throw new Exception("Ngành học không được để trống.");
            }

            // Lấy thông tin sinh viên hiện tại
            $current_student = $this->getById($id);
            if (!$current_student) {
                throw new Exception("Không tìm thấy sinh viên.");
            }

            // Xử lý ảnh
            $hinh = $current_student['Hinh']; // Giữ ảnh cũ làm mặc định
            $new_image_uploaded = false; // Biến để theo dõi việc upload ảnh mới

            if (isset($file['hinh']) && $file['hinh']['error'] != UPLOAD_ERR_NO_FILE) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 5 * 1024 * 1024;
                $file_type = mime_content_type($file['hinh']['tmp_name']);
                $file_size = $file['hinh']['size'];

                if (!in_array($file_type, $allowed_types)) {
                    throw new Exception("Chỉ chấp nhận file ảnh JPEG, PNG hoặc GIF.");
                }
                if ($file_size > $max_size) {
                    throw new Exception("File ảnh không được vượt quá 5MB.");
                }

                $target_dir = './public/images/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_extension = pathinfo($file['hinh']['name'], PATHINFO_EXTENSION);
                $hinh = $target_dir . uniqid() . '.' . $file_extension;

                if (move_uploaded_file($file['hinh']['tmp_name'], $hinh)) {
                    $new_image_uploaded = true; // Đánh dấu ảnh mới đã được upload thành công
                } else {
                    throw new Exception("Không thể upload file ảnh.");
                }
            }

            // Cập nhật database
            $stmt = $this->conn->prepare("UPDATE SinhVien SET HoTen = ?, GioiTinh = ?, NgaySinh = ?, Hinh = ?, MaNganh = ? WHERE MaSV = ?");
            $success = $stmt->execute([
                $data['hoTen'],
                $data['gioiTinh'],
                $data['ngaySinh'],
                $hinh,
                $data['maNganh'],
                $id
            ]);

            if (!$success) {
                throw new Exception("Không thể cập nhật thông tin sinh viên.");
            }

            // Xóa ảnh cũ nếu upload ảnh mới thành công
            if ($new_image_uploaded && !empty($current_student['HINH']) && file_exists($current_student['HINH'])) {
                unlink($current_student['HINH']);
            }

            return true;
        } catch (Exception $e) {
            // Nếu upload ảnh mới thất bại hoặc cập nhật thất bại, xóa ảnh mới (nếu có)
            if (isset($hinh) && $hinh != $current_student['HINH'] && file_exists($hinh)) {
                unlink($hinh);
            }
            throw $e;
        }
    }

    public function login($data)
    {
        try {
            session_start();
            // Kiểm tra dữ liệu đầu vào
            if (empty($data['username'])) {
                throw new Exception("Username không được để trống.");
            }

            $stmt = $this->conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ? AND Password = ?");
            $stmt->execute([$data['username'], $data['password']]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$student) {
                return false;
            }
            // Lưu mã sinh viên vào session
            $_SESSION['maSV'] = $student['MaSV'];

            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
