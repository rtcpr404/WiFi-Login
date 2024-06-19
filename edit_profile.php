<?php
session_start();
if (!isset($_SESSION['user_login'])) {
    header("location: login.html");
    exit;
}

include_once("./function.php");

// ตรวจสอบว่ามีการส่ง u_id มาหรือไม่
if (!isset($_GET['u_id']) || $_GET['u_id'] == '') {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้งาน'); window.location = 'index.php';</script>";
    exit;
}

$userid = $_GET['u_id'];

// ดึงข้อมูลผู้ใช้งานจากฐานข้อมูล
$objCon = connectDB();
$strSQL = "SELECT * FROM user WHERE u_id = '$userid'";
$result = mysqli_query($objCon, $strSQL);

if (!$result) {
    echo "Error: " . mysqli_error($objCon);
    exit;
}

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('ไม่พบข้อมูลผู้ใช้งาน'); window.location = 'index.php';</script>";
    exit;
}

$row = mysqli_fetch_array($result);
$name_th = $row["u_name_th"];
$surename_th = $row["u_surename_th"];
$name_en = $row["u_name_en"];
$surename_en = $row["u_surename_en"];
$idcard = $row["u_idcard"];
$email = $row["u_email"];
$rank = $row["u_rank"];
$position = $row["u_position"];
$img = $row["u_img"];
$username = $row["u_username"]; // Assuming there is a column u_username

mysqli_close($objCon);

function generateUsername($name_en, $surename_en) {
    // Convert name and surname to lowercase
    $name_lower = strtolower($name_en);
    $surename_lower = strtolower($surename_en);
    
    // Get first two characters of surname (or full surname if less than two characters)
    $surename_part = substr($surename_lower, 0, 2);

    // Concatenate name and first two characters of surname
    $new_username = $name_lower . '.' . $surename_part;

    return $new_username;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
        img {
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
    <script>
        function confirmSave() {
            return confirm('คุณต้องการบันทึกการเปลี่ยนแปลงจริงหรือไม่?');
        }

        function confirmBack() {
            return confirm('คุณต้องการย้อนกลับไปยังหน้าก่อนหน้าจริงหรือไม่?');
        }

        function previewImage(input) {
            const imagePreview = document.getElementById('image_preview');
            imagePreview.innerHTML = ''; // เคลียร์ค่าพรีวิวเก่า (ถ้ามี)

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgElement = document.createElement('img');
                    imgElement.src = e.target.result;
                    imgElement.classList.add('img-fluid');
                    imgElement.alt = 'Preview Image';

                    imagePreview.appendChild(imgElement);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // รับ reference ของ input ของชื่อและสกุล (อังกฤษ) และ Username
            const nameInput = document.getElementById('u_name_en');
            const surenameInput = document.getElementById('u_surename_en');
            const usernameInput = document.getElementById('u_username');

            // กำหนดค่าเริ่มต้นของ input ชื่อและสกุล (อังกฤษ)
            nameInput.value = "<?php echo $name_en; ?>";
            surenameInput.value = "<?php echo $surename_en; ?>";

            // สร้าง Username ใหม่ตั้งแต่เริ่มต้น
            updateUsername();

            // เพิ่ม event listener เมื่อมีการพิมพ์ใน input ชื่อและสกุล (อังกฤษ)
            nameInput.addEventListener('input', updateUsername);
            surenameInput.addEventListener('input', updateUsername);

            // function สำหรับสร้าง Username ใหม่
            function updateUsername() {
                const name = nameInput.value.trim().toLowerCase();
                const surename = surenameInput.value.trim().toLowerCase();

                // หาชื่อที่ตัดเฉพาะ 2 ตัวแรกของนามสกุล
                const surenamePart = surename.substring(0, 2);

                // สร้าง Username ใหม่
                const newUsername = name + '.' + surenamePart;

                // set ค่าของ input ช่อง Username
                usernameInput.value = newUsername;
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
            <div class="header-container text-center">
                <a href="#"><img src="image/logo.png" alt="Logo" class="mb-4"></a>
            </div>
        </div>
        <div class="bg-light p-5 rounded mt-3 shadow-lg">
          <h3>EDITING USER : <?php echo $username; ?></h3>
        </div>
    </div>
    <div class="container mt-3">
        <div class="bg-light p-5 rounded shadow-lg">
            <form method="post" action="update_user.php" enctype="multipart/form-data" onsubmit="return confirmSave();">
                <div class="mb-3">
                    <label for="u_name_th" class="form-label">ชื่อ (ไทย)</label>
                    <input type="text" class="form-control" id="u_name_th" name="u_name_th" pattern="[ก-๙]+" value="<?php echo $name_th; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_surename_th" class="form-label">สกุล (ไทย)</label>
                    <input type="text" class="form-control" id="u_surename_th" name="u_surename_th" pattern="[ก-๙]+" value="<?php echo $surename_th; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_name_en" class="form-label">ชื่อ (อังกฤษ)</label>
                    <input type="text" class="form-control" id="u_name_en" name="u_name_en" pattern="[a-zA-Z ]+" value="<?php echo $name_en; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_surename_en" class="form-label">สกุล (อังกฤษ)</label>
                    <input type="text" class="form-control" id="u_surename_en" name="u_surename_en" pattern="[a-zA-Z ]+" value="<?php echo $surename_en; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_rank" class="form-label">ยศ</label>
                    <input type="text" class="form-control" id="u_rank" name="u_rank" value="<?php echo $rank; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_position" class="form-label">ตำแหน่ง</label>
                    <input type="text" class="form-control" id="u_position" name="u_position" value="<?php echo $position; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_email" class="form-label">อีเมล</label>
                    <input type="email" class="form-control" id="u_email" name="u_email" value="<?php echo $email; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_idcard" class="form-label">เลขบัตรราชการ</label>
                    <input type="text" class="form-control" id="u_idcard" name="u_idcard" value="<?php echo $idcard; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="u_img" class="form-label">รูปภาพปัจจุบัน</label><br>
                    <img id="current_image" src="data:image/gif;base64,<?php echo $img; ?>" class="img-fluid" alt="User Image"><br><br>
                    <label for="new_image" class="form-label">อัปโหลดรูปภาพใหม่ (กรณีต้องการเปลี่ยน)</label>
                    <input type="file" class="form-control" id="new_image" name="new_image" onchange="previewImage(this)">
                </div>
                <div id="image_preview" class="mb-3"></div>
                <div class="mb-3">
                    <label for="u_username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="u_username" name="u_username" value="<?php echo $username; ?>" readonly required>
                </div>
                <div class="mb-3">
                    <label for="u_password" class="form-label">Password (กรอกเฉพาะหากต้องการเปลี่ยน)</label>
                    <input type="password" class="form-control" id="u_password" name="u_password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$">
                    <small id="passwordHelp" class="form-text text-muted">ต้องมีความยาวอย่างน้อย 6 ตัวอักษร ประกอบด้วยตัวเลขอย่างน้อย 1 ตัวและตัวอักษรใหญ่อย่างน้อย 1 ตัว</small>
                </div>
                <div class="mb-3">
                    <label for="u_password_confirm" class="form-label">ยืนยัน Password</label>
                    <input type="password" class="form-control" id="u_password_confirm" name="u_password_confirm">
                </div>
                <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
                <a href="index.php" class="btn btn-danger" id="goBackBtn" onclick="return confirmBack();">ย้อนกลับ</a>
            </form>
        </div>
    </div>
</body>

</html>
