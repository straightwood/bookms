<?php  
    $admin = $username; //获取前台传来的用户账号
    $time = time();
    $end_time = time()+86400;
    $info = $admin. '.' .$time.'.'.$end_time;//设置token过期时间为一天
    $signature = hash_hmac('md5',$info,'siasqr');//根据信息生成签名（密钥为 siasqr)
    $token = $info . '.' . $signature;//最后将这两部分拼接起来，得到最终的Token字符串
?>