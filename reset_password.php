<?php
session_start();
include_once("function.php");

// // เพิ่มฟังก์ชันสำหรับการล็อก
function logError($message)
{
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'reset_password_errors.log');
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $conn = connectDB();

    // ตรวจสอบความถูกต้องของโทเค็นและเวลาหมดอายุ
    $query = "SELECT * FROM user WHERE reset_token = ? AND reset_expiry > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // เพิ่มการล็อกข้อผิดพลาด
        logError("Invalid or expired token: " . $token);

        echo "<script type='text/javascript'>";
        echo "alert('ลิงก์รีเซ็ตรหัสผ่านไม่ถูกต้องหรือหมดอายุแล้ว');";
        echo "window.location = 'login.html';";
        echo "</script>";
        exit();
    } else {
        // เพิ่มการล็อกเมื่อโทเค็นถูกต้อง
        logError("Valid token used: " . $token);
    }
} else {
    // เพิ่มการล็อกเมื่อไม่มีโทเค็น
    logError("No token provided");

    echo "<script type='text/javascript'>";
    echo "alert('ไม่พบโทเค็นสำหรับรีเซ็ตรหัสผ่าน');";
    echo "window.location = 'login.html';";
    echo "</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $token = $_POST['token'];

    if ($new_password !== $confirm_password) {
        echo "<script type='text/javascript'>";
        echo "alert('รหัสผ่านไม่ตรงกัน กรุณาลองอีกครั้ง');";
        echo "</script>";
    } else {
        $conn = connectDB();
        $encoded_password = base64_encode($new_password);

        $update_query = "UPDATE user SET u_password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ? AND reset_expiry > NOW()";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $encoded_password, $token);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            // เพิ่มการล็อกเมื่อรีเซ็ตรหัสผ่านสำเร็จ
            logError("Password reset successful for token: " . $token);

            echo "<script type='text/javascript'>";
            echo "alert('รีเซ็ตรหัสผ่านสำเร็จ กรุณาเข้าสู่ระบบด้วยรหัสผ่านใหม่');";
            echo "window.location = 'login.html';";
            echo "</script>";
        } else {
            // เพิ่มการล็อกเมื่อรีเซ็ตรหัสผ่านไม่สำเร็จ
            logError("Password reset failed for token: " . $token);

            echo "<script type='text/javascript'>";
            echo "alert('เกิดข้อผิดพลาดในการรีเซ็ตรหัสผ่าน กรุณาลองใหม่อีกครั้ง');";
            echo "</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RESET NEW PASSWORD</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .logo {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signin">
        <form class="custom-form" method="post" action=""><br>
            <img src="image/logo.png" alt="Logo" class="mb-1 logo">
            <h1 class="h3 mb-3 fw-normal">RESET YOUR PASSWORD</h1>
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <input class="w-100 btn btn-lg btn-success btn-shadow" type="submit" value="รีเซ็ตรหัสผ่าน">
        </form>
    </main>
</body>

</html>