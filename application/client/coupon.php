
<?php
    // 쿠폰 id
    $currentUri = $_SERVER['REQUEST_URI'];
    $uriArr = explode('/', $currentUri);
    $couponId = end($uriArr);

    // DB 연결 및 데이터 조회
    $host = 'localhost';
    $username = 'root';
    $password='';
    $dbname='picks';
    $con = mysqli_connect($host, $username, $password, $dbname);
    if (!($con)) {
        echo "db 연결 실패: " . mysqli_connect_error();
    } else {
        mysqli_set_charset($con, "utf8"); // 인코딩

        $sql = "select * from coupon where id = {$couponId};";
        $result = mysqli_query($con, $sql);

        $info = mysqli_fetch_array($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, p {
            margin: 0;
            padding: 0;
        }
        #coupon-container {
            max-width: 500px;
            margin: 30px auto 0;
            padding: 15px;
            border: 0.5px solid black;
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        #coupon-container p{
            margin-bottom: 20px;
        }
        #coupon-container button{
            background-color: white;
            cursor: pointer;
        }
    </style>
    <title>픽스 쿠폰</title>
    <script src="${pageContext.request.contextPath }/resources/js/jquery-3.3.1.js"></script>
    <script src="../api/api.js"></script>
</head>
<body>
    <div id="coupon-container">
        <?php 
        ?>
        <p id="coupon-name">
            <?php echo "카페이용 쿠폰({$info['type']})"; ?>
        </p>
        <p id="coupon-date">
            <?php echo "{$info['valid_start_date']} ~ {$info['valid_end_date']}"; ?>
        </p>
        <p id="coupon-info">
            <?php echo $info['info']; ?>
        </p>
        <div id="coupon-btn_container">
            <button onclick="useCoupon()">사용하기</button>
        </div>
    </div>
    <script>
        function useCoupon() {
            if (window.confirm('쿠폰을 사용하시겠습니까?')) {
                alert('사용되었습니다.');
                // try {
                //     const response = await api.
                //     if (response === 401) {
                //         alert(ALERT_MESSAGE.TOKEN_OVER);
                //         logout();
                //         return;
                //     }
                // location.reload();
                // } catch (error) {
                //     navigate('/error');
                // }
            }
        }
    </script>
</body>
</html>