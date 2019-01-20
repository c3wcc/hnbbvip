<?php
namespace app\user\controller;
use think\Controller;

class Index extends Controller
{

    public function _initialize() {

        //检查是否登录
        //if($this->request->action() != 'index'){
            //排除 index
            $login = new \app\common\controller\Login();
            $login->check_login();
        //}

        //记录日志
        add_log(session('user.user_id'));

    }

    /**
     * 跳转
     */
    public function index()
    {
        $user = session("user");
      
    }

   
}
