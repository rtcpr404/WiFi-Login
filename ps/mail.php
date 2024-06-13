<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: http://192.168.1.100/login.php"); // redirect ไปยังหน้า login.php
    exit;
}

$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="./index.php";</script>';
    exit;
}
if($_POST["u_email"]==''){ 
	echo "<script type='text/javascript'>"; 
	echo "alert('Error Contact Admin !!');"; 
	echo "window.location = '../admin.php'; "; 
	echo "</script>"; 
	}
include_once("../function.php");
$email = $_POST["u_email"];
$name = $_POST["u_username"];



$to = $email;
$subject = 'Hello from IMM!';
$message = 'การลงทะเบียนขอใช้งาน Wi-Fi ของท่านได้รับการอนุมัติเรียบร้อยแล้ว
ท่านสามารถเข้าใช้งาน Wi-Fi ชื่อ IMM_WiFi ';
$headers = "From: bunma@primes.co.th\r\n";
if (mail($to, $subject, $message, $headers)) {
   echo "SUCCESS";
} else {
   echo "ERROR";
}

// Simulate an HTTP redirect:
header("Location: http://192.168.1.100/admin.php");
exit();

?>