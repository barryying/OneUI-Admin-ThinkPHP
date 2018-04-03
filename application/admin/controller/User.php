<?php
/**
 * Author: 勇敢的小笨羊
 * Github: https://github.com/SingleSheep
 */

namespace app\admin\controller;


use app\admin\model\AuthGroupAccess;
use app\admin\model\AuthUser;
use think\Db;

class User extends Base
{
    /**
     * 管理员列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        if ($this->request->isPost()){
            $data = $this->request->post();
            $userdata=[
                'username'  =>  $data['username'],
                'nickname'  =>  $data['nickname'],
                'phone'     =>  $data['phone'],
                'password'  =>  xmd5($data['password']),
                'email'     =>  $data['email'],
                'status'    =>  $data['status'],
            ];
            $model  = new AuthUser();
            $result = $model->insert($userdata);
            $datagroup = $model->where(['username'=>$data['username'],'email'=>$data['email']])->find();
            if($result){
                if (!empty($data['group_ids'])) {
                    foreach ($data['group_ids'] as $k => $v) {
                        $group=array(
                            'uid'=>$datagroup['id'],
                            'group_id'=>$v
                        );
                        AuthGroupAccess::insert($group);
                    }
                }
                // 操作成功
                $this->success('添加成功','Admin/User/index');
            }else{
                $this->error('修改失败');
            }
        }

        $data= Db::name('admin_auth_group_access')
            ->alias('aga')
            ->field('u.*,aga.group_id,ag.title')
            ->join('__ADMIN_USER__ u' , 'aga.uid=u.id','RIGHT')
            ->join('__ADMIN_AUTH_GROUP__ ag' , 'aga.group_id=ag.id','LEFT')
            ->select();

        $first=$data[0];
        $first['title']=array();
        $user_data[$first['id']]=$first;
        // 组合数组
        foreach ($data as $k => $v) {
            foreach ($user_data as $m => $n) {
                $uids=array_map(function($a){return $a['id'];}, $user_data);
                if (!in_array($v['id'], $uids)) {
                    $v['title']=array();
                    $user_data[$v['id']]=$v;
                }
            }
        }
        // 组合管理员title数组
        foreach ($user_data as $k => $v) {
            foreach ($data as $m => $n) {
                if ($n['id']==$k) {
                    $user_data[$k]['title'][]=$n['title'];
                }
            }
            $user_data[$k]['title']=implode('、', $user_data[$k]['title']);
        }
        $data = Db::name('admin_auth_group')->select();
        $assign = [
            'data'=>$user_data,
            'auth'=>$data
            ];
        $this->assign($assign);
        return $this->fetch();

    }
}