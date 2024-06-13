<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: http://192.168.1.36/login.php"); // redirect ไปยังหน้า login.php
    exit;
}

$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}
if($_GET["name"]==''){ 
	echo "<script type='text/javascript'>"; 
	echo "alert('Error Contact Admin !!');"; 
	echo "window.location = 'index.php'; "; 
	echo "</script>"; 
	}
	
$name = $_GET["name"];

#$pass = 'P@ssw0rd';
/*	
	$text = $_POST['text'];
	$output = wordwrap($text, 60, "<br>");
	echo $output; 
	*/
	$name = isset($_POST['name']) ? $_POST['name'] : ''; 
		

	
	    echo "<div id='phpBox' class='phpData' style='background-color: coral; color: white'>";
	    $userName = shell_exec('powershell -ExecutionPolicy Unrestricted -Command $env:USERNAME');
	    echo "<p class='maintext' style='display: block;'> '<strong>$userName</strong>' Create User for '<strong>$name</strong>' </p><br/>"; 
	
//    # Written By: http://vcloud-lab.com
//    # Date: 19 January 2022
//    # Env: Powershell 5.1, PHP (latest), JQuery (latest), HTML 5, CSS, XAMPP
	
		$psfileoutput = shell_exec("PowerShell -ExecutionPolicy Unrestricted -NonInteractive -File Reset-UserPassword.ps1 -UserAccount $name ");
#	    $psfileoutput = shell_exec("PowerShell -ExecutionPolicy Unrestricted -NonInteractive -File Create-UserPassword.ps1 -UserAccount $name -UserPassword $pass");
	    echo '<pre>' . $psfileoutput . '</pre>';
	echo "</div>";
	
?>