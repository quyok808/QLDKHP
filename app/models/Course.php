<?php
class Course
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAvailableCourses()
    {
        $stmt = $this->conn->query("SELECT * FROM hocphan ORDER BY MaHP");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseByMaSV($maSV)
    {
        $stmt = $this->conn->prepare("SELECT d.MaDK, d.NgayDK, ct.MaHP, h.TenHP, h.SoTinChi, COUNT(ct.MaHP) OVER () AS TotalRows, SUM(h.SoTinChi) OVER () AS TongSoTinChi  
             FROM DangKy d
             JOIN ChiTietDangKy ct ON d.MaDK = ct.MaDK
             JOIN HocPhan h ON ct.MaHP = h.MaHP
             WHERE d.MaSV = :masv AND ct.hasSave = 0
             ORDER BY d.NgayDK DESC, d.MaDK DESC, h.TenHP ASC");
        $stmt->bindParam(':masv', $maSV);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($maDK)
    {
        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $this->conn->beginTransaction();

            // Cập nhật bảng chitietdangky
            $stmt1 = $this->conn->prepare("UPDATE chitietdangky SET hasSave = 1 WHERE MaDK = ?");
            $stmt1->execute([$maDK]);

            // Cập nhật bảng dangky
            $stmt2 = $this->conn->prepare("UPDATE dangky SET hasSave = 1 WHERE MaDK = ?");
            $stmt2->execute([$maDK]);

            // Cập nhật SLDK trong bảng hocphan dựa trên MaHP từ chitietdangky
            $stmt3 = $this->conn->prepare("
            UPDATE hocphan hp
            JOIN chitietdangky ctdk ON hp.MaHP = ctdk.MaHP
            SET hp.SLDK = hp.SLDK - 1
            WHERE ctdk.MaDK = ?
        ");
            $stmt3->execute([$maDK]);

            // Commit transaction
            $this->conn->commit();

            return true; // Trả về true vì đây là lệnh UPDATE
        } catch (Exception $e) {
            // Nếu có lỗi, rollback transaction
            $this->conn->rollBack();
            throw new Exception("Lỗi khi lưu: " . $e->getMessage());
        }
    }


    public function registerCourse($maSV, $maHP)
    {
        try {
            // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
            $this->conn->beginTransaction();

            // Kiểm tra xem sinh viên đã có mã đăng ký trong bảng DangKy chưa
            $stmt = $this->conn->prepare("SELECT MaDK FROM dangky WHERE MaSV = ? AND hasSave = 0");
            $stmt->execute([$maSV]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                // Nếu chưa có, tạo mới một bản ghi trong DangKy
                $stmt = $this->conn->prepare("INSERT INTO dangky (MaSV, NgayDK) VALUES (?, NOW())");
                $stmt->execute([$maSV]);

                // Lấy MaDK mới vừa tạo
                $maDK = $this->conn->lastInsertId();
            } else {
                $maDK = $row['MaDK'];
            }

            // Kiểm tra xem sinh viên đã đăng ký môn học này chưa
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM chitietdangky WHERE MaDK = ? AND MaHP = ?");
            $stmt->execute([$maDK, $maHP]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("Sinh viên đã đăng ký môn học này!");
            }

            // Thêm bản ghi vào ChiTietDangKy
            $stmt = $this->conn->prepare("INSERT INTO chitietdangky (MaDK, MaHP) VALUES (?, ?)");
            $stmt->execute([$maDK, $maHP]);

            // Commit transaction
            $this->conn->commit();

            return "Đăng ký học phần thành công!";
        } catch (Exception $e) {
            // Nếu có lỗi, rollback transaction
            $this->conn->rollBack();
            return "Lỗi đăng ký: " . $e->getMessage();
        }
    }

    public function unregisterCourse($maDK, $maHP)
    {
        try {
            $this->conn->beginTransaction();

            // Kiểm tra xem môn học có tồn tại trong danh sách đăng ký không
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM chitietdangky WHERE MaDK = ? AND MaHP = ?");
            $stmt->execute([$maDK, $maHP]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception("Không tìm thấy môn học này trong danh sách đăng ký!");
            }

            // Xóa môn học khỏi ChiTietDangKy
            $stmt = $this->conn->prepare("DELETE FROM chitietdangky WHERE MaDK = ? AND MaHP = ?");
            $stmt->execute([$maDK, $maHP]);

            // Kiểm tra xem sinh viên còn học phần nào không
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM chitietdangky WHERE MaDK = ?");
            $stmt->execute([$maDK]);
            if ($stmt->fetchColumn() == 0) {
                // Nếu không còn môn học nào, xóa luôn đăng ký trong DangKy
                $stmt = $this->conn->prepare("DELETE FROM dangky WHERE MaDK = ?");
                $stmt->execute([$maDK]);
            }

            $this->conn->commit();
            return "Hủy đăng ký môn học thành công!";
        } catch (Exception $e) {
            $this->conn->rollBack();
            return "Lỗi khi hủy đăng ký: " . $e->getMessage();
        }
    }

    public function unregisterAll($maDK)
    {
        try {
            $this->conn->beginTransaction();

            // Xóa tất cả môn học trong ChiTietDangKy
            $stmt = $this->conn->prepare("DELETE FROM chitietdangky WHERE MaDK = ?");
            $stmt->execute([$maDK]);

            // Xóa luôn đăng ký trong DangKy
            $stmt = $this->conn->prepare("DELETE FROM dangky WHERE MaDK = ?");
            $stmt->execute([$maDK]);

            $this->conn->commit();
            return "Hủy đăng ký tất cả môn học!";
        } catch (Exception $e) {
            $this->conn->rollBack();
            return "Lỗi khi hủy đăng ký: " . $e->getMessage();
        }
    }

    public function InfoSave($maDK)
    {
        $stmt = $this->conn->prepare("SELECT d.MaDK, d.NgayDK, ct.MaHP, h.TenHP, h.SoTinChi, COUNT(ct.MaHP) OVER () AS TotalRows, SUM(h.SoTinChi) OVER () AS TongSoTinChi, sv.*, nh.*
         FROM dangky d
         JOIN chitietdangky ct ON d.MaDK = ct.MaDK
         JOIN hocphan h ON ct.MaHP = h.MaHP
         JOIN sinhvien sv ON d.MaSV = sv.MaSV
         JOIN nganhhoc nh ON sv.MaNganh = nh.MaNganh
         WHERE d.MaDK = :madk AND d.hasSave = 1
         ORDER BY d.NgayDK DESC, d.MaDK DESC, h.TenHP ASC");
        $stmt->bindParam(':madk', $maDK);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
