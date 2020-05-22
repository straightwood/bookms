<?php
    // require_once('/reader/add.php');
    $token='admin.1590142024.1590228424.7939dd87ae387c09b3201f4840abe237';
    $tokenNum = '';
    if(!isset($token) || empty($token)){
        $tokenNum = '400';
//      $msg['code']='400';
//      $msg['msg']='非法请求';
//      return json_encode($msg,JSON_UNESCAPED_UNICODE);
    }else{
        $explode = explode('.',$token);//以.分割token为数组
   
        if(!empty($explode[0]) && !empty($explode[1]) 
            && !empty($explode[2]) && !empty($explode[3]) ){
            $info = $explode[0].'.'.$explode[1].'.'.$explode[2];//信息部分
            $true_signature = hash_hmac('md5',$info,'siasqr');//正确的签名

            if(time() > $explode[2]){
                $tokenNum = '401';
                // $msg['code']='401';
                // $msg['msg']='Token已过期,请重新登录';
                // return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }else if ($true_signature == $explode[3]){
                $tokenNum = '200';
            //     $msg['code']='200';
            //     $msg['msg']='Token合法';
            //     return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }else{
                $tokenNum = '400';
        //         $msg['code']='400';
        //         $msg['msg']='Token不合法';
        //         return json_encode($msg,JSON_UNESCAPED_UNICODE);
            }
        }else{
            $tokenNum = '400';
        //     $msg['code']='400';
        //     $msg['msg']='Token不合法';
        //     return json_encode($msg,JSON_UNESCAPED_UNICODE);
        }
    }

    

?>