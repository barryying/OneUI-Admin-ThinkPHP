<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2018/3/29
 * Time: 17:57
 */

namespace app\admin\controller;


use app\admin\model\AuthUser;
use app\admin\service\Auth;
use think\Controller;

class Login extends Controller
{

    /**
     * 登录
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if($this->request->isPost()){
            $data   = $this->request->post();
            $result =  AuthUser::get(['username'=>$data['username'], 'password'=>xmd5($data['password'])]);
            if($result){
                //流程处理
                Auth::login($result['id'],$result['username']);
                //登录成功 数据更新
                $info = [
                    'online'    => 1, //登录状态
                    'last_login_ip'   => request()->ip(), //登录IP
                    'last_login_time' => time()  //登录时间
                ];
                $result->save($info);
                $this->redirect(url('admin/index/index'));
            }else{
                $this->error('账户或密码错误');
            }
        }
        return $this->fetch('/login');
    }


    // 退出操作
    public function logout()
    {
        Auth::logout();
        $this->success('注销成功',url('admin/login/index'));
    }
}