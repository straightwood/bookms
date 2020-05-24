<?php
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');
    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $reader_number = $data['reader_number'];
    $book_number = $data["book_number"];
    $date = $data["date"];

    $token = $data['Authorization'];
    require_once('../decodeToken.php');
    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($reader_number != '' && $book_number != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "SELECT inventory FROM book WHERE book_number = '$book_number'";
                $result = mysqli_query($conn, $sql);
                $resArray = mysqli_fetch_array($result);
                $pre_inventory = $resArray['inventory'];//检查库存

                $sql = "SELECT * FROM borrow_book WHERE reader_number = '$reader_number'";
                $result = mysqli_query($conn, $sql);
                $resNum =  mysqli_num_rows($result);//检查借书数量

                if($pre_inventory>0 && $resNum<=10){ 
                    $sql = "INSERT INTO borrow_book (reader_number,book_number,borrow_time) 
                            VALUES ('$reader_number','$book_number','$date')";
                    $result = mysqli_query($conn, $sql);
                    $result_num = mysqli_affected_rows($conn);
                    // echo ($result_num);

                    if($result_num>=1){//借书表更新成功
                        $now_inventory = $pre_inventory-1;
                        $sql = "UPDATE book SET inventory='$now_inventory' WHERE book_number = '$book_number'";
                        $result = mysqli_query($conn, $sql);
                        $result_array[0] = ['code'=>'1','message'=>'成功借书！'];
                    }else{
                        $result_array[0] = ['code'=>'0','message'=>'信息错误或重复，借书失败！'];
                    }
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'库存已空或借书超额，借书失败！'];
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