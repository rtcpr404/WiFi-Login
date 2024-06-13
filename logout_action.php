<?php
session_start();
session_destroy(); // ลบ session ทั้งหมด
header("location: login.html"); // redirect ไปยังหน้า login.html