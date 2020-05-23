<?php
    // header('Content-Type:application/json; charset=utf-8');
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

    $token = $data['Authorization'];
    require_once('../decodeToken.php');
    if($tokenNum=='200'){
        $result_array[1] = ['code'=>'1','message'=>'登录成功！'];
        if($book_number != '' && $book_name != '' && $publisher != ''&& $book_total!= ''&& $inventory != ''){
            $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if($conn != null){
                $sql = "select * FROM book where book_number = '$book_number'";
                $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql   
                $resNum = mysqli_num_rows($result);//从$result中取一行

                if($resNum==0){
                    if($price==''){
                        $sql = "INSERT INTO book (book_number,category,book_name,publisher,author,book_total,inventory) 
                            VALUES ('$book_number','$category','$book_name','$publisher','$author','$book_total','$inventory')";
                    }else{
                        $sql = "INSERT INTO book (book_number,category,book_name,publisher,author,price,book_total,inventory) 
                            VALUES ('$book_number','$category','$book_name','$publisher','$author','$price','$book_total','$inventory')";
                    }
                    $result = mysqli_query($conn, $sql);//$conn->query($sql);//执行$sql 

                    $check = "select * FROM book where book_number = '$book_number'";
                    $check_result = mysqli_query($conn, $check);//$conn->query($sql);//执行$sql   
                    $resNum = mysqli_num_rows($check_result);//从$result中取一行
                    if($resNum!=0){
                        $result_array[0] = ['code'=>'1','message'=>'添加成功！'];
                    }else{
                        $result_array[0] = ['code'=>'0','message'=>'添加失败！'];
                    }
                }else{
                    $result_array[0] = ['code'=>'0','message'=>'图书编号已存在!'];
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