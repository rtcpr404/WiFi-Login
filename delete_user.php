<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.html"); // redirect ไปยังหน้า login.php
    exit;
}

$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}

if($_GET["u_username"]==''){ 
    echo "<script type='text/javascript'>"; 
    echo "alert('Error Contact Admin !!');"; 
    echo "window.location = './admin.php'; "; 
    echo "</script>"; 
}

include_once("./function.php");

// เชื่อมต่อฐานข้อมูล
$objCon = connectDB();

// รับค่า u_username ที่ต้องการลบ
$username = $_GET["u_username"];

// ลบผู้ใช้งานออกจากฐานข้อมูล
$strSQL = "DELETE FROM user WHERE u_username = '$username'";
$result = mysqli_query($objCon, $strSQL);

// ตรวจสอบว่าลบสำเร็จหรือไม่
if ($result) {
    echo "<script>alert('ลบผู้ใช้งานเรียบร้อยแล้ว');window.location='admin.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการลบผู้ใช้งาน');window.location='admin.php';</script>";
}

mysqli_close($objCon);
?>
