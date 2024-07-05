<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['u_img'])) {
    $uploadDir = 'temp/';
    $uploadFile = $uploadDir . basename($_FILES['u_img']['name']);

    if (move_uploaded_file($_FILES['u_img']['tmp_name'], $uploadFile)) {
        echo "File is valid, and was successfully uploaded.\n";
        include_once('./function.php');
        $objCon = connectDB();

        // เพิ่มการเช็คและ reconnect
        if (mysqli_ping($objCon) === false) {
            $objCon = connectDB();
        }

        $u_img = base64_encode(file_get_contents($uploadFile));
        $data = $_POST;
        $u_name_th = $data['u_name_th'];
        $u_surename_th = $data['u_surename_th'];
        $u_name_en = $data['u_name_en'];
        $u_surename_en = $data['u_surename_en'];
        $u_email = $data['u_email'];
        $u_rank = $data['u_rank'];
        $u_position = $data['u_position'];
        $u_username = $data['u_username'];
        $u_idcard = $data['u_idcard'];
        $u_password = base64_encode($data['u_password']);
        $u_level = $data['u_level'];
        $u_approved = 0;

        // ตรวจสอบข้อมูลซ้ำ
        $checkIdcardSQL = "SELECT * FROM user WHERE u_idcard = '$u_idcard'";
        $checkIdcardResult = mysqli_query($objCon, $checkIdcardSQL);
        $checkEmailSQL = "SELECT * FROM user WHERE u_email = '$u_email'";
        $checkEmailResult = mysqli_query($objCon, $checkEmailSQL);
        $checkUsernameSQL = "SELECT * FROM user WHERE u_username = '$u_username'";
        $checkUsernameResult = mysqli_query($objCon, $checkUsernameSQL);

        if (mysqli_num_rows($checkIdcardResult) > 0) {
            echo '<script>alert("เลขบัตรราชการนี้มีในระบบแล้ว");window.location="register.html";</script>';
            unlink($uploadFile);
        } elseif (mysqli_num_rows($checkEmailResult) > 0) {
            echo '<script>alert("อีเมลนี้มีในระบบแล้ว");window.location="register.html";</script>';
            unlink($uploadFile);
        } elseif (mysqli_num_rows($checkUsernameResult) > 0) {
            echo '<script>alert("ยูสเซอร์เนมนี้มีในระบบแล้ว");window.location="register.html";</script>';
            unlink($uploadFile);
        } else {
            // เพิ่มการเช็คและ reconnect ก่อน query
            if (mysqli_ping($objCon) === false) {
                $objCon = connectDB();
            }

            $strSQL = "INSERT INTO user (
                `u_idcard`, `u_name_en`, `u_surename_en`, `u_name_th`, `u_surename_th`, `u_email`,
                `u_rank`, `u_position`, `u_username`, `u_password`, `u_level`, `u_img`, `u_approved`
            ) VALUES (
                '$u_idcard', '$u_name_en', '$u_surename_en', '$u_name_th', '$u_surename_th', '$u_email',
                '$u_rank', '$u_position', '$u_username', '$u_password', '$u_level', '$u_img', '$u_approved'
            )";

            // เพิ่มการ log เพื่อ debug
            error_log("Before query: " . date('Y-m-d H:i:s'));
            $objQuery = mysqli_query($objCon, $strSQL) or die(mysqli_error($objCon));
            error_log("After query: " . date('Y-m-d H:i:s'));

            if ($objQuery) {
                echo '<script>alert("ลงทะเบียนเรียบร้อยแล้ว");window.location="login.html";</script>';
            } else {
                echo '<script>alert("พบข้อผิดพลาด");window.location="register.html";</script>';
            }
        }

        if (unlink($uploadFile)) {
            echo "File deleted successfully";
        } else {
            echo "Error deleting file";
        }
    } else {
        echo "Possible file upload attack!\n";
    }
} else {
    echo "No file uploaded or invalid request method.";
}
?>
