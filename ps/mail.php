<?php
session_start();
include_once("../function.php");

// ฟังก์ชันเดิมสำหรับการส่งอีเมลโดยผู้ดูแลระบบ
if (isset($_SESSION['user_login']) && isset($_POST["u_email"])) {
    $user = $_SESSION['user_login'];
    if ($user['level'] != 'administrator') {
        echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="./index.php";</script>';
        exit;
    }

    if ($_POST["u_email"] == '') {
        echo "<script type='text/javascript'>";
        echo "alert('Error Contact Admin !!');";
        echo "window.location = '../admin.php'; ";
        echo "</script>";
        exit;
    }

    $email = $_POST["u_email"];
    $username = $_POST["u_username"];
    $password = $_POST["u_password"];

    $to = $email;
    $subject = 'การลงทะเบียน Wi-Fi ได้รับการอนุมัติ';
    $message = "การลงทะเบียนขอใช้งาน Wi-Fi ของท่านได้รับการอนุมัติเรียบร้อยแล้ว\n\n";
    $message .= "ท่านสามารถเข้าใช้งาน Wi-Fi ชื่อ IMM_WiFi\n\n";
    $message .= "ข้อมูลการเข้าสู่ระบบของท่าน:\n";
    $message .= "Username: $username\n";
    $message .= "Password: $password\n\n";

    $headers = "From: bunma@primes.co.th\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "ส่งอีเมลสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการส่งอีเมล";
    }

    header("Location: http://10.10.22.11/admin.php");
    exit();
}

// ฟังก์ชันใหม่สำหรับการลืมรหัสผ่าน (ไม่จำกัดสิทธิ์)
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    
    // เชื่อมต่อกับฐานข้อมูล
    $conn = connectDB();
    
    // ตรวจสอบว่าอีเมลมีอยู่ในฐานข้อมูลหรือไม่
    $query = "SELECT * FROM user WHERE u_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // สร้างโทเค็นสำหรับรีเซ็ตรหัสผ่าน
        $reset_token = bin2hex(random_bytes(32));
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 day'));        
        
        // บันทึกโทเค็นและเวลาหมดอายุในฐานข้อมูล
        $update_query = "UPDATE user SET reset_token = ?, reset_expiry = ? WHERE u_email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $reset_token, $expiry_time, $email);
        $update_result = $update_stmt->execute();
        
        if ($update_result) {
            // สร้าง URL สำหรับรีเซ็ตรหัสผ่าน
            $reset_url = "http://10.10.22.11/reset_password.php?token=" . $reset_token;
            
            // ส่งอีเมลพร้อมลิงก์รีเซ็ตรหัสผ่าน
            $to = $email;
            $subject = 'รีเซ็ตรหัสผ่าน Wi-Fi';
            $message = "คุณได้ร้องขอการรีเซ็ตรหัสผ่านสำหรับการใช้งาน Wi-Fi\n\n";
            $message .= "โปรดคลิกลิงก์ด้านล่างเพื่อรีเซ็ตรหัสผ่านของคุณ:\n\n";
            $message .= $reset_url . "\n\n";
            $message .= "ลิงก์นี้จะหมดอายุภายใน 1 ชั่วโมง\n";
            $message .= "หากคุณไม่ได้ร้องขอการรีเซ็ตรหัสผ่าน กรุณาเพิกเฉยต่ออีเมลนี้\n";

            $headers = "From: bunma@primes.co.th\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            if (mail($to, $subject, $message, $headers)) {
                error_log("Reset password email sent to: " . $email . " with token: " . $reset_token);
                echo "<script type='text/javascript'>";
                echo "alert('ส่งลิงก์สำหรับรีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว');";
                echo "window.location = '../login.html';";
                echo "</script>";
            } else {
                error_log("Failed to send reset password email to: " . $email);
                echo "<script type='text/javascript'>";
                echo "alert('เกิดข้อผิดพลาดในการส่งอีเมล กรุณาติดต่อผู้ดูแลระบบ');";
                echo "window.location = '../forgot_password.php';";
                echo "</script>";
            }
        } else {
            error_log("Failed to update reset token for email: " . $email);
            echo "<script type='text/javascript'>";
            echo "alert('เกิดข้อผิดพลาดในการรีเซ็ตรหัสผ่าน กรุณาลองใหม่อีกครั้ง');";
            echo "window.location = '../forgot_password.php';";
            echo "</script>";
        }
    } else {
        error_log("Email not found: " . $email);
        echo "<script type='text/javascript'>";
        echo "alert('ไม่พบอีเมลนี้ในระบบ');";
        echo "window.location = '../forgot_password.php';";
        echo "</script>";
    }
    
    $stmt->close();
    $conn->close();
    exit();
}
?>