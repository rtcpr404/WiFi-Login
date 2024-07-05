<?php
function connectDB()
{
    $serverName = "127.0.0.1";
    $userName = "immdb";
    $userPassword = "KPOpv(zw3rnW1mUS";
    $dbName = "immdb";

    $objCon = mysqli_connect($serverName, $userName, $userPassword, $dbName);
    mysqli_set_charset($objCon, "utf8");
    return $objCon;
}