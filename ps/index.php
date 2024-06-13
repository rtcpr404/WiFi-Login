<?php
session_start();
if (!isset($_SESSION['user_login'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.php"); // redirect ไปยังหน้า login.php
    exit;
}

$user = $_SESSION['user_login'];
if ($user['level'] != 'administrator') {
    echo '<script>alert("สำหรับผู้ดูแลระบบเท่านั้น");window.location="index.php";</script>';
    exit;
}
?>
<!doctype html>
<html lang=en>
	<head>
		<meta charset=utf-8>
		<title>PowerShell with PHP demo</title>
		<style>
			body {
				font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
				text-align: center;	
			}
			 
			div {
				margin: auto;
				width: 50%;
				border: 3px solid coral;				
				padding: 10px;
				border-radius: 0.3cm;
			}
			table {
				margin: 0 auto; /* or margin: 0 auto 0 auto */
				color: #606060;
			}
			.toolheader {
				font-size: x-large;
				font-weight: 600;
			}
			
			input[type=text] {
				width: 100%;
				padding: 12px 20px;
				margin: 8px 0;
				box-sizing: border-box;
				border: 1px solid gray;
				outline: none;
				color: #606060;
			}

			input[type=text]:focus {
				background-color: coral;
				color: white;
				border: 1px solid gray;
			}

			.button {
				background-color: coral; /* Green */
				border: none;
				color: white;
				padding: 15px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				margin: 4px 2px;
				cursor: pointer;
				-webkit-transition-duration: 0.4s; /* Safari */
				transition-duration: 0.4s;
			}

			.button:hover {
				box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24),0 17px 50px 0 rgba(0,0,0,0.19);
			}

			.loading {
				margin-left: auto;
				margin-right: auto;
				width: 128px;
				height: 128px;
				display: none;
			}
		</style>
		<script>
			function ClearFields() {
				// document.getElementById("searchBox").value = '';
				document.getElementById("phpBox").style.display = "none";
			}
		</script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body>
		<div>
			<table class="toolheader">
				<tr>
					<td style="text-align: center; vertical-align: middle;">Create User</td>
				<!--	<td><img src="reset.png" alt="reset.icon" width="48" height="48"></td> -->
					<td style="text-align: center; vertical-align: middle;">tool</td>
				</tr>
			</table>			
			<br>			
			<table>
				<tr>
					<th>UserID &nbsp &nbsp</th>
					<td><input id="searchBox" type="text" name="name" value="localhost"></td>
				</tr>
			</table>
			<button class="button searchButton">Create User</button> <!-- //instead of Class if id is used use #searchButton -->
			<input class="button clearButton" type="reset" name="Clear" value="Clear" onclick="ClearFields();"><br>
			<br>
			<br>			
			<img class="loading" id="loading" src="loading.gif" alt="Loading, Waiting, Spinner">
		</div>
		<p class="showData"></p>  <!--Show data from php here -->

<!--
		# Written By: http://vcloud-lab.com
		# Date: 19 January 2022
		# Env: Powershell 5.1, PHP (latest), JQuery (latest), HTML 5, CSS, XAMPP		
-->

		<script>
			$(document).ready(function(){
				$("button.searchButton").click(function(){	//instead of Class if id is used use #searchButton
					//document.getElementById("loading").style.display = 'block';
					$("#loading").css("display", "block")
					$("p.showData").html('')
					//document.getElementClassNyName("showData").innerHTML = "";
					$.ajax({
						method: "POST",
						url: "wrap.php",
						data: { 
							//text: $("p.unbroken").text(), //keep adding parameters here with comma
							name: $("input:text").val(), //keep adding parameters here with comma
						}
					})
					.done(function( response ) {
						$("p.showData").html(response); //class showData reference
						//document.getElementById("loading").style.display = "none";
						$("#loading").css("display", "none")
					});
				});
			});		  
		</script>
	</body>
</html>
 