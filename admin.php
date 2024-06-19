<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_login'])) {
    header("location: login.html");
    exit;
}

$user = $_SESSION['user_login'];

// ตรวจสอบสิทธิ์ผู้ดูแลระบบ
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}

include_once("./function.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User NOT Approved List</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
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
        .view-icon {
            color: #007bff;
            transition: color 0.3s ease;
        }
        .view-icon:hover {
            color: #0056b3;
        }
        td a {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .user-info-col {
            width: 1%;
            white-space: nowrap;
        }
        .approve-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .header-container a img {
            max-width: 100%;
            height: auto;
        }
        @media (max-width: 576px) {
            .header-container a {
                margin-bottom: 10px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <div class="header-container text-center">
                    <a href="#"><img src="image/logo.png" alt="Logo" class="mb-4"></a>
                </div>
            </div>
        </div>
        
        <div class="bg-light p-5 rounded mt-3 shadow-lg text-center">
            <a href="index.php" class="btn btn-primary btn-success">ไปยังหน้าเริ่มต้น</a>
            <a href="approved_list.php" class="btn btn-primary">User Approved List</a>
            <a href="logout_action.php" class="btn btn-primary btn-danger">ออกจากระบบ</a>
        </div>
        <?php    
        $objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
        $strSQL = "SELECT * FROM user WHERE u_approved = 0";
        $result = mysqli_query($objCon, $strSQL); 
        ?>
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <h2>NOT Approved List</h2>
            </div>
            <div class="table-responsive mt-5">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr align="center">
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>Username</th>
                            <th class="user-info-col">ดูข้อมูลผู้ใช้</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_array($result)) { 
                            echo "<tr align='center'>";
                            echo "<td>" . $row["u_name_th"] . "</td>";
                            echo "<td>" . $row["u_surename_th"] . "</td>";
                            echo "<td>" . $row["u_username"] . "</td>";
                            echo "<td class='user-info-col'><a href='reviewuser.php?u_username=" . $row["u_username"] . "'><i class='fas fa-eye view-icon'></i></a></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
        mysqli_close($objCon); // ปิดการเชื่อมต่อฐานข้อมูล
        ?>
        <footer class="footer mt-5 bg-white text-dark py-3 rounded shadow">
            <div class="container text-center">
                <span>© Immigration Bureau: สำนักงานตรวจคนเข้าเมือง</span>
            </div>
        </footer>
    </div>
</body>
</html>
