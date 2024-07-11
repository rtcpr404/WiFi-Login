<?php
session_start();
include_once("./function.php");

// ตรวจสอบการเข้าสู่ระบบและสิทธิ์ผู้ดูแลระบบ
if (!isset($_SESSION['user_login']) || $_SESSION['user_login']['level'] != 'administrator') {
    exit(json_encode(['error' => 'Unauthorized access']));
}

$objCon = connectDB();
$strSQL = "SELECT u_name_th, u_surename_th, u_username FROM user WHERE u_approved = 0";
$result = mysqli_query($objCon, $strSQL);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

mysqli_close($objCon);

header('Content-Type: application/json');
echo json_encode($users);
?>