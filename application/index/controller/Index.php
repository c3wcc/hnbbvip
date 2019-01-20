<?php
namespace app\index\controller;
use think\Controller;


class Index extends Controller
{

    public function _initialize() {

        //检查是否登录
        if($this->request->action() != 'logout'){
            //排除 logout
            $login = new \app\common\controller\Login();
            $login->check_login();
        }

       //不检查学校
       //记录日志
       add_log(session('user.user_id'));
    }


    public function index()
    {
        $user = session("user");
       
        $this->assign("mobile",$user['mobile']);
        $this->assign("nickname",$user['nickname']);
        $this->assign("head_pic",$user['head_pic']);
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

    /**
     * 设置
     */
    public function set(){
        return $this->fetch();
    }
}
