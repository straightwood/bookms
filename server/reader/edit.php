<?php
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $reader_number = $data['reader_number'];
    $name = $data["name"];
    $department = $data["department"];
    $gender = $data["gender"];
    $telephone = $data["telephone"];

    $token = $data['Authorization'];
    require_once('../decodeToken.php');//解码token验证

    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($reader_number != '' && $name != '' && $gender != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "UPDATE reader SET name = '$name', department = '$department',gender = '$gender',telephone = '$telephone'
                WHERE reader_number = '$reader_number'";
                $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql  
                $result_num = mysqli_affected_rows($conn);
                $check = "SELECT * FROM reader WHERE reader_number = '$reader_number'";
                $check_result = mysqli_query($conn, $check);
                $resArray = mysqli_fetch_array($check_result);//从$result中取一行

                if($result_num>=1){
                    $result_array[0] = ['code'=>'1','message'=>'修改成功！'];
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'修改失败！'];
                }
                mysqli_close($conn);
            }
        }
    }else if($tokenNum=='401'){
        $result_array[1] = ['code'=>'0','message'=>'登录已过期，请重新登录！'];
    }else{
        $result_array[1] = ['code'=>'0','message'=>'请进行登录后操作！'];      
    }
    echo json_encode($result_array);
?>