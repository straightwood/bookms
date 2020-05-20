<?php

    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $manager_id = $data['manager_id'];
    $name = $data["name"];
    $telephone = $data["telephone"];

    // $manager_id = "123123";
    // $name = "iii";
    // $telephone = "1530000000";

    // if (!isset($_SESSION['username'])) {
    //     if (isset($_COOKIE['username'])) {
    //       $_SESSION['user_id'] = $_COOKIE['user_id'];
    //       $_SESSION['username'] = $_COOKIE['username'];
    //     }
    // }
    
    if($manager_id != '' && $name != '' && $telephone != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "UPDATE manager SET name = '$name', telephone = '$telephone'
            WHERE manager_id = '$manager_id'";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql  
            
            $check = "SELECT * FROM manager WHERE manager_id = '$manager_id'";
            $check_result = mysqli_query($conn, $check);
            $resArray = mysqli_fetch_array($check_result);//从$result中取一行

            if($resArray['name']==$name && $resArray['telephone']==$telephone){
                $result_array[0] = ['code'=>'1','message'=>'修改成功！'];
                echo json_encode($result_array);
            }else{
                $result_array[0] = ['code'=>'0','message'=>'修改失败！'];
                echo json_encode($result_array);
            }
            mysqli_close($conn);
        }
    }

?>