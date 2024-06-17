<?php
session_start(); // เปิดใช้งาน session
if (isset($_SESSION['user_login'])) { // ถ้าเข้าระบบอยู่
    header("location: index.php"); // redirect ไปยังหน้า index.php
    exit;
}

include_once("./function.php");
$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$username = mysqli_real_escape_string($objCon, $_POST['username']); // รับค่า username
$password = mysqli_real_escape_string($objCon, $_POST['password']); // รับค่า password
$passwd = base64_encode($password);

// ตรวจสอบ username และ password ก่อน
$checkCredSQL = "SELECT * FROM user WHERE u_username = '$username' AND u_password = '$passwd'";
$checkCredQuery = mysqli_query($objCon, $checkCredSQL);
$checkCredRow = mysqli_num_rows($checkCredQuery);

if ($checkCredRow) {
    // ถ้า username และ password ถูกต้อง ตรวจสอบการอนุมัติ
    $strSQL = "SELECT * FROM user WHERE u_username = '$username' AND u_password = '$passwd' AND u_approved = 1";
    $objQuery = mysqli_query($objCon, $strSQL);
    $row = mysqli_num_rows($objQuery);
    if ($row) {
        $res = mysqli_fetch_assoc($objQuery);
        $_SESSION['user_login'] = array(
            'id' => $res['u_id'],
            'fullname' => $res['u_name_en'],
            'level' => $res['u_level']
        );
        
        // ตรวจสอบระดับสิทธิ์และเปลี่ยนหน้าที่จะแสดงไปเเต่ละหน้า
        if ($res['u_level'] == 'administrator') {
            echo '<script>alert("ยินดีต้อนรับคุณ ', $res['u_name_th'],'");window.location="admin.php";</script>';
        } else {
            echo '<script>alert("ยินดีต้อนรับคุณ ', $res['u_name_th'],'");window.location="index.php";</script>';
        }
    } else {
        echo '<script>alert("คุณยังไม่ได้รับการอนุมัติจากผู้ดูแลระบบ");window.location="login.html";</script>';
    }
} else {
    echo '<script>alert("ยูสเซอร์เนมหรือรหัสผ่านไม่ถูกต้อง");window.location="login.html";</script>';
}