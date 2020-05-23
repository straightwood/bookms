<?php
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $manager_id = $data['manager_id'];
    $name = $data["name"];
    $password = $data["password"];
    $telephone = $data["telephone"];

    $token = $data['Authorization'];
    require_once('../decodeToken.php');

    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($manager_id != '' && $name != '' && $password != '' && $telephone != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "select * FROM manager where manager_id = '$manager_id'";
                $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql   
                $resNum = mysqli_num_rows($result);//从$result中取一行

                if($resNum==0){
                    $sql = "INSERT INTO manager (manager_id,name,password,telephone) VALUES ('$manager_id','$name','$password','$telephone')";
                    $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 

                    $check = "select * FROM manager where manager_id = '$manager_id'";
                    $check_result = mysqli_query($conn, $check);//$conn->query($sql);//执行$sql   
                    $resNum = mysqli_num_rows($check_result);//从$result中取一行
                    if($resNum!=0){
                        $result_array[0] = ['code'=>'1','message'=>'添加成功！'];
                    }else{
                        $result_array[0] = ['code'=>'0','message'=>'添加失败！'];
                    }
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'管理员id已存在!'];
                }
                mysqli_close($conn);
            }
        }
    }else if($tokenNum=='401'){
        $result_array[1] = ['code'=>'0','message'=>'登录已过期，请重新登录！'];
        // echo json_encode($result_array);
    }else{
        $result_array[1] = ['code'=>'0','message'=>'请进行登录后操作！'];      
    }
    
    echo json_encode($result_array);
?>