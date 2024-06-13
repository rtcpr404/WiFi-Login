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
$username = $_GET["u_username"];

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
    $strSQL = "SELECT * FROM user where u_username = '$username' ";
    $result = mysqli_query($objCon, $strSQL); 
    //echo "<table border='1' align='center' width='800'>";
    //หัวข้อตาราง
    //echo "<tr align='center' bgcolor='#CCCCCC'><td>ชื่อ</td><td>นามสกุล</td><td>username</td><td>Password</td><td>Viewuser</td></tr>";
    while($row = mysqli_fetch_array($result)) { 
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
     
     // echo "<tr>";
     // echo "<td align='center'>" .$name_th."</td> "; 
     // echo "<td align='center'>" .$row["u_surename_th"] .  "</td> ";  
     // echo "<td align='center'>" .$row["u_username"] .  "</td> ";
     // echo "<td align='center'>" .$passwd."</td> "; 
      //แก้ไขข้อมูล
     // echo "<td align='center'><a href='./ps/approve.php?u_username=$row[u_username]'>approve</a></td> ";
      
      //ลบข้อมูล
     // echo "<td><a href='UserDelete.php?ID=$row[0]' onclick=\"return confirm('Do you want to delete this record? !!!')\">del</a></td> ";
     // echo "</tr>";
    }
   // echo "</table>";
    //5. close connection
    mysqli_close($objCon);
?>

<div class="container">
        <div class="bg-light p-5 rounded mt-3">
            <h1>รายละเอียดผู้ลงทะเบียน</h1>
            <form id="forme" method="post" action="/ps/approve.php">
            <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="u_name_th" class="form-label">ชื่อ</label>
                    <input type="text" readonly class="form-control" id="u_name_th" name="u_name_th" value="<?php echo $name_th; ?>" required>
                 </div>
                <div class="col-sm-6 mb-3">
                    <label for="u_surename_th" class="form-label">สกุล</label>
                    <input type="text" readonly class="form-control" id="u_surename_th" name="u_surename_th" value="<?php echo $surename_th; ?>" required>
                </div>
                <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="u_name_en" class="form-label">ชื่อ ภาษาอังกฤษ</label>
                    <input type="text" readonly class="form-control" id="u_name_en" name="u_name_en" value="<?php echo $name_en; ?>" required>
                 </div>
                <div class="col-sm-6 mb-3">
                    <label for="u_surename_en" class="form-label">สกุล ภาษาอังกฤษ</label>
                    <input type="text" readonly class="form-control" id="u_surename_en" name="u_surename_en" value="<?php echo $surename_en; ?>" required>
                </div>
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <label for="u_rank" class="form-label">ยศ</label>
                    <input type="text" readonly class="form-control" id="u_rank" name="u_rank" value="<?php echo $rank; ?>" required>
                 </div>
                <div class="col-sm-6 mb-3">
                    <label for="u_position" class="form-label">ตำแหน่ง</label>
                    <input type="text" readonly class="form-control" id="u_position" name="u_position" value="<?php echo $position; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" readonly class="form-control" id="u_email" name="u_email" value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_username" class="form-label">Username</label>
                    <input type="text" readonly class="form-control" id="u_username" name="u_username" value="<?php echo $username; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_idcard" class="form-label">เลขบัตรราชการ</label>
                    <input type="text" readonly class="form-control" id="u_idcard" name="u_idcard" value="<?php echo $idcard; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_img" class="form-label">รูปบัตรราชการ</label>
                    <input type="text" readonly class="form-control" id="u_img" name="u_img" value="<?php echo $idcard; ?>" required>
                </div>
                <div><?php echo '<img src="data:image/gif;base64,' . $img . '" class="img-fluid" alt="Responsive image"/>';?></div>
                <div class="mb-3">
                    <input type="hidden" class="form-control" id="u_password" name="u_password" value="<?php echo $passwd; ?>" required>
                </div>
                <!--<div class="mb-3">
                    <label for="uu_img" class="form-label">Upload รูปบัตรราชการ</label>
                    <input type="file" class="form-control" id="u_img" name="u_img" placeholder="Upload รูปบัตรราชการ" required>
                </div> -->
                <!-- <div class="mb-3">
                    <label for="u_level" class="form-label">Level</label>
                    <select id="u_level" name="u_level" class="form-select">
                        <option value="user">ผู้ใช้ทั่วไป</option>
                        <option value="administrator">ผู้ดูแลระบบ</option>
                    </select>
                </div> -->
                <button class="w-100 btn btn-lg btn-primary" type="submit">ลงทะเบียน</button>
                <a href="admin.php" class="w-100 btn btn-lg btn-danger mt-3">ยกเลิก</a>
            </form>
        </div> 
    </div>

</body>

</html>
