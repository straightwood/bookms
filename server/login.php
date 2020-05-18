<?php

    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    require_once('connectvars.php');

    // $username = $_POST["username"];
    // $password = $_POST["password"];

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $username = $data['username'];
    $password = $data["password"];
    // echo("hello");
    
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