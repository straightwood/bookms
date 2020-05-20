<?php
    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    require_once('../connectvars.php');
    
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = "select * FROM reader";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 
            $resArray = mysqli_fetch_all($result,MYSQL_ASSOC);
            $resNum = mysqli_num_rows($result);

            $result_array[0] = $resArray;
            $result_array[1] = $resNum;
            echo json_encode($result_array);

            mysqli_close($conn);
        }

?>