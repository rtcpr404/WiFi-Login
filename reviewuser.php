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

if ($_GET["u_username"] == '') {
    echo "<script type='text/javascript'>";
    echo "alert('Error Contact Admin !!');";
    echo "window.location = './admin.php'; ";
    echo "</script>";
}
$username = $_GET["u_username"];

include_once("./function.php");

$objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$strSQL = "SELECT * FROM user where u_username = '$username' ";
$result = mysqli_query($objCon, $strSQL);
if (!$result) {
    echo "Error: " . mysqli_error($objCon);
    exit;
}
$row = mysqli_fetch_array($result);

$name_th = $row["u_name_th"];
$surename_th = $row["u_surename_th"];
$name_en = $row["u_name_en"];
$surename_en = $row["u_surename_en"];
$username = $row["u_username"];
$idcard = $row["u_idcard"];
$email = $row["u_email"];
$rank = $row["u_rank"];
$position = $row["u_position"];
$passwd = base64_decode($row["u_password"]);
$img = $row["u_img"];
$u_approved = $row["u_approved"]; // เพิ่มการเก็บค่า u_approved

mysqli_close($objCon);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP login</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-container h2 {
            flex-grow: 1;
            text-align: center;
            margin: 0;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 10px 2px 10px rgba(0, 0, 0, 0.5);
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-success, .btn-danger {
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.25);
        }
        .img-container {
            text-align: center;
        }
        .img-container img {
            display: block;
            margin: 0 auto;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .img-container img.zoomable:hover {
            transform: scale(1.1);
        }
        .img-container img.zoomable.zoomed {
            transform: scale(2);
        }
        .page-title {
            text-align: center;
        }
        
        @media (max-width: 576px) {
            .btn-lg {
                padding: 10px 16px;
                font-size: 18px;
                line-height: 1.3333333;
                border-radius: 6px;
            }
        }
    </style>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".zoomable").click(function () {
                $(this).toggleClass("zoomed");
            });
        });
    </script>
    <script>
        function confirmDelete() {
            return confirm("คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้งานนี้?");
        }
    </script>
    <script>
        function confirmRegistration() {
            return confirm("คุณแน่ใจหรือไม่ว่าต้องการทำการลงทะเบียน?");
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <a href="admin.php" class="btn btn-lg btn-success btn-block">ไปยัง Approved</a>
                <h2>Administrator Page</h2>
                <a href="logout_action.php" class="btn btn-lg btn-danger btn-block">ออกจากระบบ</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <h1 class="page-title">รายละเอียดผู้ลงทะเบียน</h1><br>
            <form id="forme" method="post" action="/ps/approve.php">
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="u_name_th" class="form-label">ชื่อ</label>
                        <input type="text" readonly class="form-control" id="u_name_th" name="u_name_th"
                            value="<?php echo $name_th; ?>" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="u_surename_th" class="form-label">สกุล</label>
                        <input type="text" readonly class="form-control" id="u_surename_th" name="u_surename_th"
                            value="<?php echo $surename_th; ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="u_name_en" class="form-label">ชื่อ ภาษาอังกฤษ</label>
                        <input type="text" readonly class="form-control" id="u_name_en" name="u_name_en"
                            value="<?php echo $name_en; ?>" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="u_surename_en" class="form-label">สกุล ภาษาอังกฤษ</label>
                        <input type="text" readonly class="form-control" id="u_surename_en" name="u_surename_en"
                            value="<?php echo $surename_en; ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <label for="u_rank" class="form-label">ยศ</label>
                        <input type="text" readonly class="form-control" id="u_rank" name="u_rank"
                            value="<?php echo $rank; ?>" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="u_position" class="form-label">ตำแหน่ง</label>
                        <input type="text" readonly class="form-control" id="u_position" name="u_position"
                            value="<?php echo $position; ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" readonly class="form-control" id="u_email" name="u_email"
                        value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_username" class="form-label">Username</label>
                    <input type="text" readonly class="form-control" id="u_username" name="u_username"
                        value="<?php echo $username; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_idcard" class="form-label">เลขบัตรราชการ</label>
                    <input type="text" readonly class="form-control" id="u_idcard" name="u_idcard"
                        value="<?php echo $idcard; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_img" class="form-label">รูปบัตรราชการ</label>
                </div>
                <div class="mb-3 img-container ">
                    <?php echo '<img src="data:image/gif;base64,' . $img . '" class="img-fluid shadow-lg zoomable"
                        alt="Responsive image"/>'; ?>
                </div>
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="u_password" name="u_password"
                        value="<?php echo $passwd; ?>" required>
                </div>

                <?php if ($u_approved != 1) : ?>
                <!-- แสดงปุ่ม "ลงทะเบียน" เฉพาะเมื่อ u_approved เป็น 0 -->
                <button onclick="return confirmRegistration();" class="w-100 btn btn-lg btn-primary shadow-lg btn-block"
                    type="submit">ลงทะเบียน</button>
                <?php endif; ?>

                <a href="delete_user.php?u_username=<?php echo $username; ?>" onclick="return confirmDelete();"
                    class="w-100 btn btn-lg btn-danger mt-3 shadow-lg btn-block">ลบผู้ใช้งาน</a>
                <a href="edit_user.php?u_username=<?php echo $username; ?>"
                    class="w-100 btn btn-lg btn-warning mt-3 shadow-lg btn-block">แก้ไขข้อมูล</a>
                <a href="admin.php" class="w-100 btn btn-lg btn-success mt-3 shadow-lg btn-block">ย้อนกลับ</a>
            </form>
        </div>
    </div>

</body>

</html>
