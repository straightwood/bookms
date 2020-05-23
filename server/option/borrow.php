<?php
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    // $reader_number = $data['reader_number'];
    // $book_number = $data["book_number"];
    // $date = $data["date"];
    $reader_number="1";
    $book_number= "1";
    $date= "2020-05-13 00:00:00";

    $token = $data['Authorization'];
    require_once('../decodeToken.php');

    // if($tokenNum=='200'){
    //     $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($reader_number != '' && $book_number != '' && $date != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            
            if($conn != null){
                echo('111');
                    $sql = "INSERT INTO borrow_book (reader_number,book_number,borrow_time) 
                            VALUES ('$reader_number','$book_number','$date')";
                    $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 

                    // $check = "select * FROM manager where manager_id = '$manager_id'";
                    // $check_result = mysqli_query($conn, $check);//$conn->query($sql);//执行$sql   
                    // $resNum = mysqli_num_rows($check_result);//从$result中取一行
                    // if($resNum!=0){
                    //     $result_array[0] = ['code'=>'1','message'=>'添加成功！'];
                    // }else{
                    //     $result_array[0] = ['code'=>'0','message'=>'添加失败！'];
                    // }
                mysqli_close($conn);
            }
        }
    // }else if($tokenNum=='401'){
    //     $result_array[1] = ['code'=>'0','message'=>'登录已过期，请重新登录！'];
    //     // echo json_encode($result_array);
    // }else{
    //     $result_array[1] = ['code'=>'0','message'=>'请进行登录后操作！'];      
    // }
    
    // echo json_encode($result_array);
?>