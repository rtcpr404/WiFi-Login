<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน</title>
    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="./css/style.css" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="image/icon.png">
    <style>
        .logo {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body class="text-center">
    <main class="form-signin">
        <form class="custom-form" method="post" action="ps/mail.php">
            <img src="image/logo.png" alt="Logo" class="mb-1 logo">
            <h1 class="h3 mb-3 fw-normal">FORGOT PASSWORD</h1>
            <div class="form-floating mb-2">
                <input type="email" class="form-control" id="floatingEmail" name="email" placeholder="อีเมล" required>
                <label for="floatingEmail">E-mail</label>
            </div>
            <input type="hidden" name="forgot_password" value="1">
            <button class="w-100 btn btn-lg btn-success btn-shadow" type="submit">ส่งอีเมลกู้คืน</button>
            <a href="login.html" class="w-100 btn btn-lg btn-secondary btn-shadow mt-3">ย้อนกลับ</a>
        </form>
    </main>
</body>
</html>
