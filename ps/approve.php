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
if($_POST["u_username"]==''){ 
	echo "<script type='text/javascript'>"; 
	echo "alert('Error Contact Admin !!');"; 
	echo "window.location = '../admin.php'; "; 
	echo "</script>"; 
	}
include_once("../function.php");
$username = $_POST["u_username"];
$pass = $_POST["u_password"];
$name = $_POST["u_name_en"];
$surename = $_POST["u_surename_en"];
$email = $_POST["u_email"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP login</title>
    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-container h1 {
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
        @media (max-width: 576px) {
            .btn-lg {
                padding: 10px 16px;
                font-size: 18px;
                line-height: 1.3333333;
                border-radius: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <a href="../admin.php" class="btn btn-lg btn-success btn-block">กลับหน้าหลัก</a>
                <h2>Administrator Page</h2>
                <a href="logout_action.php" class="btn btn-lg btn-danger btn-block">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</html>


<?php

$objCon = connectDB();
$strSQL = "UPDATE user SET 
            u_approved= '1' 
			WHERE u_username='$username' ";
$objCon = mysqli_query($objCon, $strSQL) or die(mysqli_error($objCon));

//หัวข้อตาราง

//while($row = mysqli_fetch_array($result)) { 
 //$name = $row["u_username"];  
 //$pass = base64_decode($row["u_password"]);
//}
//5. close connection
//mysqli_close($objCon);


	/*
	$text = $_POST['text'];
	$output = wordwrap($text, 60, "<br>");
	echo $output; 
	*/
	  //  $name = isset($_POST['name']) ? $_POST['name'] : ''; 
		

	
	    echo "<div class='container'>";
		echo "<div class='bg-light p-5 rounded mt-3'>";
		//echo "<div id='phpBox' class='phpData' style='background-color: coral; color: white'>";
	    $adminuserName = shell_exec('powershell -ExecutionPolicy Unrestricted -Command $env:USERNAME');
	    echo "<p class='maintext' style='display: block;'> '<strong>$adminuserName</strong>' Create User for '<strong>$name $surename</strong>' </p><br/>"; 
		
//    # Written By: http://vcloud-lab.com
//    # Date: 19 January 2022
//    # Env: Powershell 5.1, PHP (latest), JQuery (latest), HTML 5, CSS, XAMPP
	
#		$psfileoutput = shell_exec("PowerShell -ExecutionPolicy Unrestricted -NonInteractive -File Create-UserPassword.ps1 -UserAccount $name ");
	    $psfileoutput = shell_exec("PowerShell -ExecutionPolicy Unrestricted -NonInteractive -File Create-UserPassword.ps1 -UserAccount $username -UserPassword $pass -GivenName $name -Surname $surename -EmailAddress $email ");
	    echo '<pre>' . $psfileoutput . '</pre>';
	echo "</div>";
	





    
?>
<!DOCTYPE html>
<html lang="en">


<main class="form-signin shadow-lg">
        <form method="post" action="mail.php">
            <h1 class="h3 mb-3 fw-normal">Sendmail</h1>
            <div class="form-floating">
            <input type="hidden" class="form-control" id="u_password" name="u_email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-floating">
            <input type="hidden" class="form-control" id="u_password" name="u_username" value="<?php echo $username; ?>" required>
                
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sendmail</button>
        </form>
    </main>

</html>

