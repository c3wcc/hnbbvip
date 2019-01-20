<?php
namespace app\common\controller;
use think\Controller;

class Login extends Controller
{
    public function check_login()
    {

        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';  

        $redirect_uri = $http_type.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; 
        //判断是否合法的uri
        if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$redirect_uri)){
            $redirect_uri = SITE_URL;
        }
       
        $user = session("user");
        if(!$user){
           $url = "/index.php/user/login/index?redirect_uri=".$redirect_uri;
           header("Location:".$url);
           exit;
        }

        //验证手机号码
        // if($user['mobile_validated'] == 0){
        //     $url = "/index.php/user/login/bind?redirect_uri=".$redirect_uri;
        //     header("Location:".$url);
        //     exit;
        // }

        return true;
    }

    /**
     * 检查
     */
    public function check_school()
    {
        $school = session("user.school");
        //增加学校字段
            
        $url = CONFIG('api_url')."/config/get_school_name?id=".$school;
        $res = httpRequest($url,"GET");
        $res = json_decode($res,true);

        // 去选择学校
        if(!isset($res['data']['name'])){
            $this->redirect("index/index/school");
        }

        session('user.school_name',$res['data']['name']);
    }
}
