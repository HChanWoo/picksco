<?php
$host = 'localhost';
$username = 'root';
$password='';
$dbname='picks';
$con = mysqli_connect($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $couponId = $_POST['couponId'];

    $updateSql = "UPDATE coupon SET is_used = 1 WHERE id = '$couponId'";
    $result = mysqli_query($con, $updateSql);
}

?>