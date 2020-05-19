<?php

    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $username = $data['username'];
    $password = $data["password"];

    // if (!isset($_SESSION['username'])) {
    //     if (isset($_COOKIE['username'])) {
    //       $_SESSION['user_id'] = $_COOKIE['user_id'];
    //       $_SESSION['username'] = $_COOKIE['username'];
    //     }
    // }
    
    if($username != '' && $password != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "select * FROM manager where manager_id = '$username'";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql   
            $resArray = mysqli_fetch_array($result);//从$result中取一行
            	
	  		if($resArray["password"] == $password){
				//$_SESSION['chenzhe_user_id'] = $resArray['user_id'];//超全局变量
				$result_array[0] = ['code'=>'1','message'=>'登陆成功!'];
				echo json_encode($result_array);
	  		}else{
				$result_array[0] = ['code'=>'0','message'=>'用户名或密码输入错误'];
				echo json_encode($result_array);
	  		}
            mysqli_close($conn);
        }
    }

?>