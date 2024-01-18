<?php

    $user = 'root';
    $pass = '';
    $inst = '/cloudsql/sonic-arcadia-397502:us-central1:php-service';
    $db = 'php-service-demo';
    $host = '34.69.4.207';
    $port = 3306;
    //tạo kết nối cơ sở dữ liệu
    $con =  mysqli_connect(null, $user, $pass, $db, null, $inst) or die("can't connect database");
    mysqli_set_charset($con,'utf8');

	$id = $_COOKIE['id'] ?? null;
	$name = $_COOKIE['name'] ?? null;
	// echo "<pre>"; print_r($_GET); echo "<br>"; print_r($_POST); echo "<br>"; print_r($_COOKIE); die;
    if(isset($_GET['action'])){
        switch ($_GET['action']) {
            case "getPrivateMsg": 
                $user2 = $_GET['id2'];
                $sql = "SELECT `online`.`username`,chat.* FROM `online`,chat WHERE online.id=chat.id AND (((chat.id=$id) AND (chat.user2=$user2)) OR ((chat.id = $user2) AND (chat.user2=$id))) ORDER BY idchat DESC LIMIT 20";
                $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                $data = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $data[] = $row;
                }
                $data = array_reverse($data);
                echo json_encode($data);
            break;
            case "active": // Giữ cho tôi online
                $sql = "UPDATE online SET online=UNIX_TIMESTAMP() WHERE id=$id";
                setcookie("id",$id,time()+7*86400);
                setcookie("name",$name,time()+7*86400);
                $query = mysqli_query($con,$sql);
                echo 1;
            break;
            case "olderMsg": // Lấy danh sách tin nhắn cũ hơn
                $firstID = $_GET['id'];
                $user2 = $_GET['id2'];
                if($user2 != "undefined")
                    $sql = "SELECT `online`.`username`,chat.* FROM `online`,chat WHERE `online`.id=chat.id AND (((chat.id=$id) AND (chat.user2=$user2)) OR ((chat.id = $user2) AND (chat.user2=$id))) AND chat.idchat<$firstID ORDER BY idchat DESC LIMIT 15";
                else
                    $sql = "SELECT `online`.`username`,chat.* FROM `online`,chat WHERE `online`.id=chat.id AND chat.idchat<$firstID ORDER BY idchat DESC LIMIT 15";
                $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                $data = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $data[] = $row;
                }
                $data = array_reverse($data);
                echo json_encode($data);
            break;
            case "getMsg": // Lấy danh sách tin nhắn mới
                $sql = "SELECT `online`.`username`,chat.* FROM `online`,chat WHERE `online`.id=chat.id AND user2 IS NULL ORDER BY idchat DESC LIMIT 20";
                $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                $data = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $data[] = $row;
                }
                $data = array_reverse($data);
                echo json_encode($data);
            break;
            case "getOnline": // Lấy danh sách người đang online
                $sql = "SELECT * FROM online WHERE username IS NOT NULL AND online+15>=UNIX_TIMESTAMP()";
                $query = mysqli_query($con,$sql);
                $data = [];
                while ($row = mysqli_fetch_assoc($query)) {
                    $data[] = $row;
                }
                echo json_encode($data);
            break;
            case "getID": // Lấy ID/name của tôi
                if (!$id) {
                    $sql = "INSERT INTO `online` (status,online) VALUES (0,UNIX_TIMESTAMP())";
                    $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                    $result = mysqli_insert_id($con);
                    $id = $result;
                    setcookie("id",$id,time()+86400);
                    $name = "";
                } else { // Đã có ID
                    
                }
                $data = [
                    'id'=>$id,
                    'name'=>$name
                ];
                echo json_encode($data);
            break;
        }
    }
	
    if(isset($_POST['action'])){
        switch ($_POST['action']) {
            case "send": // Gửi tin nhắn
                $user2 = $_POST['id2'];
                $msg = $_POST['msg'] or die("Vui lòng nhập nội dung");
                $msg = mysqli_real_escape_string($con,$msg);
                if ($user2 != "undefined")
                    $sql = "INSERT INTO chat (id,content,time,timestamp,user2) VALUES ('$id','$msg',CURTIME(),UNIX_TIMESTAMP(),'$user2')";
                else
                    $sql = "INSERT INTO chat (id,content,time,timestamp) VALUES ('$id','$msg',CURTIME(),UNIX_TIMESTAMP())";
                $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                $idchat = mysqli_insert_id($con);
                echo $idchat;
            break;
            case "leave": // Leave chat
                $sql = "UPDATE online SET status=0,online=NULL WHERE id=$id";
                $query = mysqli_query($con,$sql);
                echo 1;
            break;
            case "rename": // Đổi tên + join
                $name = $_POST['name'] or die("Vui lòng điền tên");
                $name = mysqli_real_escape_string($con,$name);
                $sql = "UPDATE online SET username='$name',online=UNIX_TIMESTAMP() WHERE id=$id";
                $query = mysqli_query($con,$sql) or die(mysqli_error($con));
                setcookie("name",$name,time()+86400);
                echo 1;
            break;
        }
    }
	

?>