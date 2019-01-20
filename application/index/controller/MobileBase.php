<?php
namespace app\index\controller;
use think\Controller;


class MobileBase extends Controller
{

    public $session_id;
    
    public function _initialize() {

        //检查是否登录
        if($this->request->action() != 'logout'){
            //排除 logout
            $login = new \app\common\controller\Login();
            $login->check_login();
        }

        $this->session_id = session_id(); // 当前的 session_id       
        define('SESSION_ID',$this->session_id); //将当前的session_id保存为常量，供其它方法调用
        // 判断当前用户是否手机                
        if(isMobile())
            cookie('is_mobile','1',3600); 
        else 
            cookie('is_mobile','0',3600);      

           
           
        $this->public_assign();
    }


      /**
     * 保存公告变量到 smarty中 比如 导航 
     */   
    public function public_assign()
    {
        
       
    }      


}
