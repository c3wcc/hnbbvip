<?php
namespace app\user\controller;

use think\Controller;

class Login extends Controller
{


    public function _initialize() {
    
        //记录日志
        add_log(session('user.user_id'));
    }

    /**
     * 登录首页
     */
    public function index()
    {
        session('redirect_uri', input('redirect_uri'));
        //这是上一页

        //已经登录了
        $user = session("user");
        if ($user) {
            $this->redirect("index/index/index");
            exit;
        }

        if (is_weixin() == true) {
            
            $u = urlencode(SITE_URL."/user/login/check");
            $wx_login = "/?redirect_uri=".$u;
            //微信强制跳转
            $this->redirect($wx_login);
            exit;
            
            $this->assign("wx_login", $wx_login);
        } else {
            $this->assign("wx_login", "javascript:alert('请在微信中打开')");
        }

        if ($this->request->method() == 'POST') {
            $username = input('username');
            $password = input("password");

            $url = CONFIG('api_url')."/user/login?username=" . $username . "&password=" . $password;

            $res = httpRequest($url, "GET");
            $res = json_decode($res, true);

            if ($res['status'] == 1) {

                $user = $res['data'];
                // 用户信息存 session
                session("user", $user);

                //有没有上一步地址
                if (session('redirect_uri')) {
                    $this->redirect(session('redirect_uri'));
                    exit;
                } else {
                    $this->redirect("index/index/index");
                }

            } else {
                $this->error($res['msg']);
            }
            exit;
        }

        return $this->fetch();
    }

    /**
     * 登录后的
     */
    public function check()
    {

        $token = input("token");

        if (!$token) {
            $this->error("登录出错");
            exit;
        }

        //发起请求，获取信息
        $url = CONFIG('api_url')."/login/token?token=" . $token;
        $res = httpRequest($url, "GET");
        $res = json_decode($res, true);

        if ($res['status'] == 1) {
            //登录成功
            $user = $res['data'];
            session('user', $user);

            //有没有上一步地址
            if (session('redirect_uri')) {
                $this->redirect(session('redirect_uri'));
                exit;
            } else {
                $this->redirect("index/index/index");
            }

            //成功跳转

        } else {
            //登录失败哦
            $this->error("登录失败");
            exit;
        }

    }

    /**
     * 手机号码登录
     */
    public function phone()
    {

        if ($this->request->method() == 'POST') {
            $post = input('');
            $mobile = $post['mobile'];
            $mobile_code = $post['mobile_code'];

            $url = CONFIG('api_url')."/user/phonelogin?mobile=" . $mobile . "&code=" . $mobile_code;

            $res = httpRequest($url, "GET");
            $res = json_decode($res, true);

            if ($res['status'] == 1) {
                //绑定成功
                session("user", $res['data']);

                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }

        }

        return $this->fetch();
    }

   
    /*
     * 绑定手机号码
     */
    public function bind()
    {
        $user_id = session('user.user_id');
        if (!$user_id) {
            $this->redirect('index');
            exit;
        }
        if ($this->request->method() == 'POST') {
            $post = input('');
            $mobile = $post['mobile'];
            $mobile_code = $post['mobile_code'];
            $new_password = $post['new_password'];

            $url = CONFIG('api_url')."/api/user/bind?mobile=" . $mobile . "&code=" . $mobile_code . "&user_id=" . $user_id . "&new_password=" . $new_password;
            //链接后台接口，要传送的数据
            $res = httpRequest($url, "GET");
            $res = json_decode($res, true);
            //把得到的数据解析成JSON格式，并转换成PHP格式
            if ($res['status'] == 1) {
                //绑定成功
                session("user.mobile", $mobile);
                session("user.mobile_validated", 1);
                // 存储手机号码
                $this->success($res['msg'], 'index');
                exit;
            } else {
                $this->error($res['msg']);
                exit;
            }

        }

        return $this->fetch();
    }

}
