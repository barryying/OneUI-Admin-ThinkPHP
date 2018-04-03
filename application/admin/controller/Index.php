<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2018/3/28
 * Time: 18:01
 */

namespace app\admin\controller;

use app\admin\service\User as AuthService;
use app\admin\library\Tree;

class Index extends Base
{

    public function index()
    {

        return $this->fetch();
    }

    //global search
    public function search($q = '')
    {
        return $this->fetch('/search_result');
    }

//    private function menu(){
//        $menu       = AuthService::menuCheck();
//        foreach ($menu as $k => $v) {
//            $url    = $v['url_param']?'?'.$v['url_param']:'';
//            $url    = url("{$v['app']}/{$v['model']}/{$v['action']}").$url;
//            $menu[$k]['icon']    = !empty($v['icon'])?$v['icon']:'fa-list';
//            $menu[$k]['url']      = $url;
//        }
//
//        return($this->node_merge($menu));
//    }
//
//    function node_merge($node,$pid=0){
//        $arr = array();
//        foreach ($node as $v){
//            if($v['parent_id']==$pid){
//                $v['childs']=$this->node_merge($node,$v['id']);
//                $arr[]=$v;
//            }
//        }
//        return $arr;
//
//    }
}