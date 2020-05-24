<?php
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式
    $reader_number = $data['reader_number'];

    $token = $data['Authorization'];
    require_once('../decodeToken.php');//解码token验证

    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($reader_number != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "SELECT * FROM borrow_book WHERE reader_number = '$reader_number'";
                $result = mysqli_query($conn, $sql);
                $resNum =  mysqli_num_rows($result);//检查借书记录
                if($resNum==0){
                    $sql = "delete FROM reader where reader_number = '$reader_number'";
                    $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql
                    $result_num = mysqli_affected_rows($conn);
                    if($result_num>=1){
                        $result_array[0] = ['code'=>'1','message'=>'删除成功！'];
                    }else{
                        $result_array[0] = ['code'=>'0','message'=>'删除失败！'];
                    }
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'有借书记录，暂不可删！'];
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