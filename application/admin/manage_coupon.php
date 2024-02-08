<?php
$host = 'localhost';
$username = 'root';
$password='';
$dbname='picks';
$con = mysqli_connect($host, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $id = $data['id'];
    $deleteSql = "DELETE FROM coupon WHERE id = '$id'";
    mysqli_query($con, $deleteSql);
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    mysqli_set_charset($con, "utf8"); // 인코딩
    $id = $_GET['id'];
    $getSql = "SELECT * FROM coupon WHERE id = '$id'";
    $result = mysqli_query($con, $getSql);
    $selectedRow = mysqli_fetch_assoc($result);

    echo json_encode($selectedRow);
}
?>