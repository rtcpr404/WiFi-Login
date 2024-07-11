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

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-success,
        .btn-danger {
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

        .header-container h2 {
            text-align: center;
            margin-bottom: 1rem;
        }

        .header-container .d-flex {
            flex-wrap: wrap;
        }

        #lastUpdate {
            margin-left: 1rem;
        }

        @media (max-width: 576px) {
            .header-container .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            #manualUpdate,
            #lastUpdate {
                margin-bottom: 0.5rem;
            }

            #lastUpdate {
                margin-left: 0;
            }
        }

        @media (max-width: 576px) {
            .header-container a {
                margin-bottom: 10px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg header-container text-center">
            <a href="#"><img src="image/logo.png" alt="Logo"></a>
        </div>

        <div class="bg-light p-5 rounded mt-3 shadow-lg text-center">
            <a href="index.php" class="btn btn-primary btn-success">ไปยังหน้าเริ่มต้น</a>
            <a href="approved_list.php" class="btn btn-primary">User Approved List</a>
            <a href="logout_action.php" class="btn btn-primary btn-danger">ออกจากระบบ</a>
        </div>

        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container">
                <h2 class="mb-4">NOT Approved List</h2>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <button id="manualUpdate" class="btn btn-primary">อัพเดทข้อมูล</button>
                <span id="lastUpdate" class="ml-2">อัพเดทล่าสุด: -</span>
            </div>
            <div id="loadingIndicator" class="text-center mt-3" style="display:none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">กำลังโหลด...</span>
                </div>
            </div>
            <div class="table-responsive mt-5">
                <table id="userTable" class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr align="center">
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>Username</th>
                            <th class="user-info-col">ดูข้อมูลผู้ใช้</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- ข้อมูลจะถูกเพิ่มที่นี่ -->
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="footer mt-5 bg-white text-dark py-3 rounded shadow">
            <div class="container text-center">
                <span>© Immigration Bureau: สำนักงานตรวจคนเข้าเมือง</span>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateTable() {
            $('#loadingIndicator').show();
            $.ajax({
                url: 'fetch_unapproved_users.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var tableBody = $('#userTable tbody');
                    tableBody.empty();
                    $.each(data, function(i, user) {
                        var row = '<tr align="center">' +
                            '<td>' + user.u_name_th + '</td>' +
                            '<td>' + user.u_surename_th + '</td>' +
                            '<td>' + user.u_username + '</td>' +
                            '<td class="user-info-col"><a class="view-btn" href="reviewuser.php?u_username=' + user.u_username + '">ดูข้อมูล</a></td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                    updateLastUpdateTime();
                },
                error: function() {
                    console.log('Error fetching data');
                },
                complete: function() {
                    $('#loadingIndicator').hide();
                }
            });
        }

        function updateLastUpdateTime() {
            var now = new Date();
            var formattedTime = now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0') + ':' +
                now.getSeconds().toString().padStart(2, '0');
            $('#lastUpdate').text('อัพเดทล่าสุด: ' + formattedTime);
        }

        $(document).ready(function() {
            updateTable();
            setInterval(updateTable, 15000); // อัพเดทไทม์

            $('#manualUpdate').click(function() {
                updateTable();
            });
        });
    </script>
</body>

</html>