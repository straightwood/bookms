<?php

    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $reader_number = $data['reader_number'];
    $name = $data["name"];
    $department = $data["department"];
    $gender = $data["gender"];
    $telephone = $data["telephone"];

    // $reader_number = "123123";
    // $name = "ggg";
    // $password = "123123";
    // $telephone = "1530000000";

    // if (!isset($_SESSION['username'])) {
    //     if (isset($_COOKIE['username'])) {
    //       $_SESSION['user_id'] = $_COOKIE['user_id'];
    //       $_SESSION['username'] = $_COOKIE['username'];
    //     }
    // }
    
    if($reader_number != '' && $name != '' && $gender != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "select * FROM reader where reader_number = '$reader_number'";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql   
            $resNum = mysqli_num_rows($result);//从$result中取一行

            if($resNum==0){
                $sql = "INSERT INTO reader (reader_number,name,department,gender,telephone) VALUES ('$reader_number','$name','$department','$gender','$telephone')";
                $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 

                $check = "select * FROM reader where reader_number = '$reader_number'";
                $check_result = mysqli_query($conn, $check);//$conn->query($sql);//执行$sql   
                $resNum = mysqli_num_rows($check_result);//从$result中取一行
                if($resNum!=0){
                    $result_array[0] = ['code'=>'1','message'=>'添加成功！'];
				    echo json_encode($result_array);
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'添加失败！'];
				    echo json_encode($result_array);
                }
            }else{
                $result_array[0] = ['code'=>'0','message'=>'读者编号已存在!'];
                echo json_encode($result_array);
            }
            mysqli_close($conn);
        }
    }

?>