<?php

    // header('Content-Type:application/json; charset=utf-8');
    require_once('connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式
    $username = $data['username'];
    $password = $data["password"];

    require_once('encodeToken.php');//生成token

    if($username != '' && $password != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $username = mysqli_real_escape_string($conn, trim($username));
            $password = mysqli_real_escape_string($conn, trim($password));

            $sql = "select * FROM manager 
                    where manager_id = '$username' and password = password($password)";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 
            if (mysqli_num_rows($result) == 1) {
                // $row = mysqli_fetch_array($result);
                $result_array[0] = ['token'=>$token,'code'=>'1','message'=>'登陆成功!'];
			 	
            }else{
				$result_array[0] = ['code'=>'0','message'=>'用户名或密码输入错误'];               
            }
            mysqli_close($conn);
        }else{
            $result_array[0] = ['code'=>'0','message'=>'数据库连接失败！'];
        }
        echo json_encode($result_array);
    }

?>