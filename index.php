<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.html"); // redirect ไปยังหน้า login.html
    exit;
}

$user = $_SESSION['user_login'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .header-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header-container h1 {
            text-align: center;
            margin: 0;
        }
        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .btn-container .btn {
            margin-top: 10px;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        }
        .btn-container .btn:last-child {
            margin-top: 10px;
        }
        .logo {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <img src="image/logo.png" alt="Logo" class="logo mb-4"><br>
                <h1>สวัสดีคุณ : <?php echo $user['fullname']; ?> <br> ระดับผู้ใช้ของคุณ : <?php echo $user['level']; ?> </h1>
            </div>
            <div class="btn-container mt-3">
                <?php if ($user['level'] == 'administrator') { // แสดงลิงค์ไปยังหน้าผู้ดูแลระบบเมื่อผู้ใช้เป็นแอดมิน ?>
                    <a href="admin.php" class="btn btn-lg btn-warning">หน้าสำหรับผู้ดูแลระบบ</a>
                <?php } ?>
                <a href="logout_action.php" class="btn btn-lg btn-danger">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</body>

</html>
