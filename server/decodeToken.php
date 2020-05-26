<?php
    $tokenNum = '';
    if(!isset($token) || empty($token)){
        $tokenNum = '400';
    }else{
        $explode = explode('.',$token);//以.分割token为数组
   
        if(!empty($explode[0]) && !empty($explode[1]) 
            && !empty($explode[2]) && !empty($explode[3]) ){
            $info = $explode[0].'.'.$explode[1].'.'.$explode[2];//信息部分
            $true_signature = hash_hmac('md5',$info,'siasqr');//正确的签名

            if(time() > $explode[2]){
                $tokenNum = '401';
            }else if ($true_signature == $explode[3]){
                $tokenNum = '200';
            }else{
                $tokenNum = '400';
            }
        }else{
            $tokenNum = '400';
        }
    }
?>