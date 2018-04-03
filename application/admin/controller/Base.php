<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2018/3/28
 * Time: 18:01
 */

namespace app\admin\controller;
use app\admin\model\AuthRule;
use app\admin\service\Auth;
use think\Controller;

class Base extends Controller
{

    /**
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function _initialize()
    {

        parent::_initialize();
        $auth = new Auth();
        $m = $this->request->module();
        $c = $this->request->controller();
        $a = $this->request->action();
        $rule=$m.'/'.$c.'/'.$a;

        if($user = $auth::is_login()){
            $uid = $user['uid'];
            $this->assign('meuns',$this->getAllMenu());
            if(!$auth->check($rule,$uid)){
                $this->error("你没有权限访问！",url('admin/index/index'));
            }
        }else{
            $this->redirect(url("admin/login/index"));
        }
    }

    public function _empty($name)
    {
        $this->error("{$name}页面不存在");
    }

    /**
     * 获取所有菜单
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getAllMenu(){
        $model = new AuthRule();  //从数据库读取菜单
        $cate = $model->order('id asc')->select();     //读取用作菜单显示的
        $menu =$this->unlimitedForLayer($cate);
        return $menu;
    }


    /**
     * 返回所有菜单分类
     * @param $cate
     * @param string $name
     * @param int $pid
     * @return array
     */
    public function unlimitedForLayer ($cate, $name = 'child', $pid = 0) {
        $arr = array();
        foreach ($cate as $v) {
            if ($v['pid'] == $pid) {
                $v[$name] = $this->unlimitedForLayer($cate, $name, $v['id']);
                $arr[] = $v;
            }
        }
        return $arr;
    }

}