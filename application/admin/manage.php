<?php
    $selectedRow = null;
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $validStartDate = $_POST['valid_start_date'];
            $validEndDate = $_POST['valid_end_date'];
            $info = $_POST['info'];

            $insertSql = "INSERT INTO coupon (type, valid_start_date, valid_end_date, info) 
                          VALUES ('$type', '$validStartDate', '$validEndDate', '$info')";
            mysqli_query($con, $insertSql);

            header("Location: http://localhost/picksco/application/admin/manage.php");
        } else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $id = $selectedRow;

            $type = $_POST['type'];
            $validStartDate = $_POST['valid_start_date'];
            $validEndDate = $_POST['valid_end_date'];
            $info = $_POST['info'];

            $updateSql = "UPDATE coupon SET 
                  type = '$type', 
                  valid_start_date = '$validStartDate', 
                  valid_end_date = '$validEndDate', 
                  info = '$info' 
                  WHERE id = '$id'";
            mysqli_query($con, $updateSql);

            header("Location: http://localhost/picksco/application/admin/manage.php");
        }

        $sql = "select * from coupon";
        $result = mysqli_query($con, $sql);
        $count = mysqli_num_rows($result);

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>픽스 쿠폰 관리자 페이지</title>
    <link rel="stylesheet" href="manage.css"/>
</head>
<body>
    <div>
        <div id="overlay"></div>
        <div class="modal" id="postModal">
            <form method="POST">
                <label>쿠폰 종류</label>
                <select name="type" required>
                    <option value="카페이용">카페이용</option>
                    <option value="식사권">식사권</option>
                    <option value="체험권">체험권</option>
                </select>
                
                <label>유효 시작 날짜</label>
                <input type="datetime-local" name="valid_start_date" required>
                
                <label>유효 종료 날짜</label>
                <input type="datetime-local" name="valid_end_date" required>
                
                <label>사용 안내</label>
                <textarea id="info" name="info" required></textarea>
                
                <div>
                    <button type="submit">추가하기</button>
                    <button onclick="closeModal('postModal')">닫기</button>
                </div>

            </form>
        </div>

        <div class="modal" id="patchModal">
            <form method="PATCH">
                <label>쿠폰 종류</label>
                <input type="text" name="type"  required>
                
                <label>유효 시작 날짜</label>
                <input type="datetime-local" name="valid_start_date" required>
                
                <label>유효 종료 날짜</label>
                <input type="datetime-local" name="valid_end_date" required>
                
                <label>사용 안내</label>
                <textarea id="info" name="info" required></textarea>
                
                <div>
                    <button type="submit">수정완료</button>
                    <button onclick="closeModal('patchModal')">닫기</button>
                </div>
            </form>
        </div>
        
        <header>
            <img height="20px" width="20px" src="https://oopy.lazyrockets.com/api/rest/cdn/image/f7a1cc04-976a-48e1-b8d0-75b3b467b04c.png" />
            <p>쿠폰 관리자 페이지</p>
        </header>
        <nav>
            <div id="coupon">
                <p>COUPON</p>
            </div>
        </nav>
        <main>
            <button id="add" onclick="openModal('postModal')">+</button>
            <?php if($count !== 0) {?>
            <table>
                <colgroup>
                    <col style="width:10px">
                    <col style="width:200px">
                    <col style="width:200px">
                    <col style="width:200px">
                    <col style="width:200px">
                    <col style="width:200px">
                </colgroup>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>쿠폰 종류</th>
                        <th>유효기간</th>
                        <th>사용안내</th>
                        <th>사용여부</th>
                        <th>수정/삭제</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($result as $row) {?>
                        <tr>
                            <td onclick="copyToClipboard(<?php echo $row['id']; ?>)">
                                <?php echo $row['id']?>
                            </td>
                            <td>
                                <?php echo $row['type']?>
                            </td>
                            <td>
                                <?php echo "{$row['valid_start_date']} - {$row['valid_end_date']}"?>
                            </td>
                            <td>
                                <?php echo nl2br($row['info'])?>
                            </td>
                            <td>
                                <?php 
                                if($row['is_used'] === 1) {
                                    echo '사용완료';
                                } else {
                                    echo '미사용';
                                }
                                ?>
                            </td>
                            <td>
                                <button onclick="openModal('patchModal', <?php echo $row['id']; ?>)">수정</button>
                                <button onclick="deleteCoupon(<?php echo $row['id']; ?>)">삭제</button>
                            </td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php } else { echo '데이터가 없습니다';}?>
        </main>
    </div>
    <script>
        function openModal(name,id=0) {
            if(name==='patchModal') {
                getCoupon(name,id);
            } else {
                document.getElementById(name).style.display = 'block';
            }
            document.getElementById('overlay').style.display = 'block';

            document.getElementById('overlay').addEventListener('click', function () {
                closeModal(name);
            });
        }
        function closeModal(name) {
            document.getElementById(name).style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function getCoupon(name,id) {
            fetch('manage_coupon.php?id=' + encodeURIComponent(id))
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#' + name + ' [name="type"]').value = data.type;
                    document.querySelector('#' + name + ' [name="valid_start_date"]').value = data.valid_start_date;
                    document.querySelector('#' + name + ' [name="valid_end_date"]').value = data.valid_end_date;
                    document.querySelector('#' + name + ' [name="info"]').value = data.info;

                    document.getElementById(name).style.display = 'block';
                })
                .catch(error => console.error(error));
        }
        function deleteCoupon(couponId) {
            if (confirm("정말로 삭제하시겠습니까?")) {
                fetch('manage_coupon.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: 'id=' + encodeURIComponent(couponId),
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        console.error('쿠폰을 삭제하지 못했습니다.');
                    }
                })
                .catch(error => console.error(error));
            }
        }

        function copyToClipboard(couponId) {
            let currentUrl = window.location.href;
            let applicationIndex = currentUrl.indexOf('application');

            let baseUrl = currentUrl.substring(0, applicationIndex + 'application'.length);
            let url = baseUrl + '/client/coupon.php/' + couponId;
            navigator.clipboard.writeText(url)
            .then(() => {
                alert('클립보드에 저장되었습니다.');
            })
            .catch((error) => {
                console.error(error);
            });
        }
    </script>
</body>
</html>