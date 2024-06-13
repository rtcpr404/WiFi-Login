<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.html"); // redirect ไปยังหน้า login.html
    exit;
}

$user = $_SESSION['user_login'];
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
    <title>PHP login</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3">
            <h1>หน้าสำหรับผู้ดูแลระบบ</h1>
            <div class="mt-5">
                <a href="index.php" class="btn btn-lg btn-success">กลับหน้าหลัก</a>
                <a href="logout_action.php" class="btn btn-lg btn-danger">ออกจากระบบ</a>
            </div>
        </div>
    </div>
<?php    
    $objCon = connectDB(); // เชื่อมต่อฐานข้อมูล
$strSQL = "SELECT * FROM user where u_approved = 0 ";
$result = mysqli_query($objCon, $strSQL); 
echo "<table border='1' align='center' width='800'>";
//หัวข้อตาราง
echo "<tr align='center' bgcolor='#CCCCCC'><td>ชื่อ</td><td>นามสกุล</td><td>username</td><td>Viewuser</td></tr>";
while($row = mysqli_fetch_array($result)) { 
  echo "<tr>";
  echo "<td align='center'>" .$row["u_name_th"] .  "</td> "; 
  echo "<td align='center'>" .$row["u_surename_th"] .  "</td> ";  
  echo "<td align='center'>" .$row["u_username"] .  "</td> ";

  //แก้ไขข้อมูล
  echo "<td align='center'><a href='reviewuser.php?u_username=$row[u_username]'>Viewuser</a></td> ";
 
  //ลบข้อมูล
 // echo "<td><a href='UserDelete.php?ID=$row[0]' onclick=\"return confirm('Do you want to delete this record? !!!')\">del</a></td> ";
  echo "</tr>";
}
echo "</table>";
//5. close connection
mysqli_close($objCon);
?>
</body>

</html>