<?php
session_start();

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['user_login'])) {
    header("location: login.html");
    exit;
}

$user = $_SESSION['user_login'];

include_once("./function.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Approved List</title>
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
        .view-icon {
            color: #007bff;
            transition: color 0.3s ease;
        }
        .view-icon:hover {
            color: #0056b3;
        }
        .view-column {
            width: 150px; /* ขนาดที่พอดีกับคำว่า "ดูข้อมูลผู้ใช้" */
            text-align: center; /* ไอคอนอยู่ตรงกลาง */
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
            <div class="header-container text-center">
                <a><img src="image/logo.png" alt="Logo" class="mb-4"></a>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-primary btn-success">ไปยังหน้าเริ่มต้น</a>
                <a href="admin.php" class="btn btn-primary">User Not Approved List</a>
                <a href="logout_action.php" class="btn btn-primary btn-danger">ออกจากระบบ</a>
            </div>
        </div>
        <?php    
        $objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
        $strSQL = "SELECT * FROM user WHERE u_approved = 1";
        $result = mysqli_query($objCon, $strSQL); 
        ?>
        <div class="table-responsive mt-5">
            <div class="header-container">
                    <h2>Approved List</h2>
            </div><br><br>
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr align="center">
                        <th>ชื่อ</th>
                        <th>นามสกุล</th>
                        <th>Username</th>
                        <th class="view-column">ดูข้อมูลผู้ใช้</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row = mysqli_fetch_array($result)) { 
                        echo "<tr align='center'>";
                        echo "<td>" . $row["u_name_th"] . "</td>";
                        echo "<td>" . $row["u_surename_th"] . "</td>";
                        echo "<td>" . $row["u_username"] . "</td>";
                        echo "<td class='view-column'>";
                        echo "<a href='reviewuser.php?u_username=" . $row["u_username"] . "'><i class='fas fa-eye view-icon'></i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php
        mysqli_close($objCon); // ปิดการเชื่อมต่อฐานข้อมูล
        ?>
    </div>
</body>
</html>
