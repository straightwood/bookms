<?php

    // session_start();
    // header('Content-Type:application/json; charset=utf-8');
    session_start();
    require_once('../connectvars.php');

    $data = json_decode(file_get_contents('php://input'),true);//转换数据格式

    $book_number = $data['book_number'];
    $category = $data['category'];
    $book_name = $data["book_name"];
    $author = $data["author"];
    $publisher = $data["publisher"];
    $price = $data["price"];
    $book_total = $data["book_total"];
    $inventory = $data["inventory"];

    // if (!isset($_SESSION['username'])) {
    //     if (isset($_COOKIE['username'])) {
    //       $_SESSION['user_id'] = $_COOKIE['user_id'];
    //       $_SESSION['username'] = $_COOKIE['username'];
    //     }
    // }
    
    if($book_number != '' && $book_name != '' && $publisher != ''&& $book_total != ''&& $inventory != ''){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if($conn != null){
            $sql = 
            "UPDATE book SET book_name = '$book_name', 
                    category = '$category',
                    publisher = '$publisher',
                    author = '$author',
                    price = '$price',
                    book_total = '$book_total',
                    inventory = '$inventory'
                    WHERE book_number = '$book_number'";
            $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql  
            
            $check = "SELECT * FROM book WHERE book_number = '$book_number'";
            $check_result = mysqli_query($conn, $check);
            $resArray = mysqli_fetch_array($check_result);//从$result中取一行

            if($resArray['book_name']==$book_name  
                && $resArray['category']==$category
                && $resArray['publisher']==$publisher
                && $resArray['author']==$author
                && $resArray['price']==$price
                && $resArray['book_total']==$book_total
                && $resArray['inventory']==$inventory){
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