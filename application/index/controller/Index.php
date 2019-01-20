<?php
namespace app\index\controller;

use app\index\controller\MobileBase;

class Index extends MobileBase
{

    public function _initialize(){
        parent::_initialize();

        

    }


    public function index()
    {
       
        $user = session("user");
       
        $this->assign("mobile",$user['mobile']);
        $this->assign("nickname",$user['nickname']);
        $this->assign("head_pic",$user['head_pic']);


        $data = array();

        $this->assign('data',$data);

        return $this->fetch();
    }

   
   

    /**
     * 退出登录
     */
    public function logout(){
        session(null);
        session("user",null);
        session_destroy();
        $this->redirect('index');
    }

    
}
