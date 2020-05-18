<?php
    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');
    
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "select * FROM manager";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 
            $resArray = mysqli_fetch_all($result,MYSQL_ASSOC);
            echo json_encode($resArray);

            mysqli_close($conn);
        }

?>