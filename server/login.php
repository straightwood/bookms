<?php

    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $Authorization = $data['Authorization'];
    $username = $data['username'];
    $password = $data["password"];

    // $Authorization = '1';
    // $username = 'admin';
    // $password = '123123';

    require_once('encodeToken.php');//生成token
    
    // if (!isset($_SESSION['$username'])) {
    //     if (isset($_COOKIE['$username'])) {
    //       $_SESSION['$username'] = $_COOKIE['$username'];
    //     }
    // }
    
    if($username != '' && $password != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $username = mysqli_real_escape_string($conn, trim($username));
            $password = mysqli_real_escape_string($conn, trim($password));

            $sql = "select * FROM manager 
                    where manager_id = '$username' and password = password($password)";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 
            $row = mysqli_fetch_array($result);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_array($result);
                
                // $token = $username.'['.$username.']'.$username;//随便设一个 test
                // $_SESSION['Authorization'] = $token;
                // setcookie('Authorization', $token, time() + (60 * 60 * 24 * 30));
                
                $result_array[0] = ['token'=>$token,'code'=>'1','message'=>'登陆成功!'];
			 	echo json_encode($result_array);
            }else{
				$result_array[0] = ['code'=>'0','message'=>'用户名或密码输入错误'];
				echo json_encode($result_array);                
            }
            mysqli_close($conn);



            // $resArray = mysqli_fetch_array($result);//从$result中取一行
            	
	  		// if($resArray["password"] == SHA('$password')){
			// 	//$_SESSION['chenzhe_user_id'] = $resArray['user_id'];//超全局变量
			// 	$result_array[0] = ['code'=>'1','message'=>'登陆成功!'];
			// 	echo json_encode($result_array);
	  		// }else{
			// 	$result_array[0] = ['code'=>'0','message'=>'用户名或密码输入错误'];
			// 	echo json_encode($result_array);
	  		// }
            // mysqli_close($conn);
        }
    }

?>