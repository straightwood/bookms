<?php
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');
    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式
    $token = $data['Authorization'];
    require_once('../decodeToken.php');

    if($tokenNum=='200'){
        $result_array[2] = ['code'=>'1','message'=>'登录成功！'];
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "select * FROM book";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 
            $resArray = mysqli_fetch_all($result,MYSQL_ASSOC);
            $resNum = mysqli_num_rows($result);

            $result_array[0] = $resArray;
            $result_array[1] = $resNum;
            mysqli_close($conn);
        }
    }else if($tokenNum=='401'){
        $result_array[2] = ['code'=>'0','message'=>'登录已过期，请重新登录！'];
    }else{
        $result_array[2] = ['code'=>'0','message'=>'请进行登录后操作！'];      
    }
    echo json_encode($result_array);
?>