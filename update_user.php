<?php
session_start();
if (!isset($_SESSION['user_login'])) {
    header("location: login.html");
    exit;
}

$user = $_SESSION['user_login'];

include_once("./function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form input
    $u_id = $_POST['u_id'];
    $username = $_POST['u_username'];
    $name_th = $_POST['u_name_th'];
    $surename_th = $_POST['u_surename_th'];
    $name_en = $_POST['u_name_en'];
    $surename_en = $_POST['u_surename_en'];
    $rank = $_POST['u_rank'];
    $position = $_POST['u_position'];
    $email = $_POST['u_email'];
    $idcard = $_POST['u_idcard'];
    $password = $_POST['u_password'];
    $password_confirm = $_POST['u_password_confirm'];
    $u_approved = isset($_POST['u_approved']) ? 1 : 0;

    // Check if new password matches confirmation
    if ($password !== $password_confirm) {
        echo "<script>alert('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน'); window.history.back();</script>";
        exit;
    }

    // Encode password only if it's provided
    if (!empty($password)) {
        $password = base64_encode($password);
        $password_sql = ", u_password = '$password'";
    } else {
        $password_sql = ''; // no password update
    }

    // Update user information in database
    $objCon = connectDB();
    if (!$objCon) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare image update if new image uploaded
    if ($_FILES['new_image']['name'] !== '') {
        $img_data = file_get_contents($_FILES['new_image']['tmp_name']);
        $img_base64 = base64_encode($img_data);
        $img_sql = ", u_img = '$img_base64'";
    } else {
        $img_sql = ''; // no image update
    }

    // Generate new username
    function generateUsername($name_en, $surename_en) {
        // Convert name and surname to lowercase
        $name_lower = strtolower($name_en);
        $surename_lower = strtolower($surename_en);
        
        // Get first two characters of surname (or full surname if less than two characters)
        $surename_part = substr($surename_lower, 0, 2);

        // Concatenate name and first two characters of surname
        $new_username = $name_lower . '.' . $surename_part;

        return $new_username;
    }

    // Generate new username based on updated name and surname (English)
    $new_username = generateUsername($name_en, $surename_en);

    // Prepare SQL update statement
    $strSQL = "UPDATE user SET 
               u_name_th = '$name_th', 
               u_surename_th = '$surename_th', 
               u_name_en = '$name_en', 
               u_surename_en = '$surename_en', 
               u_rank = '$rank', 
               u_position = '$position', 
               u_email = '$email', 
               u_idcard = '$idcard',
               u_approved = '$u_approved'
               $password_sql
               $img_sql,
               u_username = '$new_username'
               WHERE u_id = '$u_id'";

    $result = mysqli_query($objCon, $strSQL);
    if ($result) {
        if (mysqli_affected_rows($objCon) > 0) {
            echo "<script>alert('บันทึกการเปลี่ยนแปลงเรียบร้อยแล้ว'); window.location = 'index.php';</script>";
        } else {
            echo "<script>alert('ไม่มีการเปลี่ยนแปลงข้อมูล'); window.history.back();</script>";
        }
    } else {
        echo "Error updating record: " . mysqli_error($objCon);
    }

    // if ($result) {
    //     echo "<script>alert('บันทึกการเปลี่ยนแปลงเรียบร้อยแล้ว'); window.location = 'index.php';</script>";
    // } else {
    //     echo "Error updating record: " . mysqli_error($objCon);
    // }

    mysqli_close($objCon);
}
?>
