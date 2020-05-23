<?php
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式
    $book_number = $data['book_number'];

    $token = $data['Authorization'];
    require_once('../decodeToken.php');

    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($book_number != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "delete FROM book where book_number = '$book_number'";
                $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql

                $check = "select * FROM book where book_number = '$book_number'";
                $check_result = mysqli_query($conn, $check);//执行
                $resArray =mysqli_fetch_array($check_result);//从$result中取一行
                
                if($resArray["num_rows"] == null ){
                    $result_array[0] = ['code'=>'1','message'=>'删除成功！'];
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'删除失败！'];
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