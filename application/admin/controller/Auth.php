<?php
/**
 * Author: 勇敢的小笨羊
 * Github: https://github.com/SingleSheep
 */

namespace app\admin\controller;
use app\admin\model\AuthGroup;
use app\admin\model\AuthRule;

class Auth extends Base
{


    /**
     * 权限列表
     */
    public function index()
    {
        if ($this->request->isPost()){
            $data=$this->request->post();
            $result=AuthRule::create($data);
            if ($result) {
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }
        $data= (new AuthRule())->getTreeData('tree','id','title');
        $assign=array(
            'data'=>$data
        );
        $this->assign($assign);
        return $this->fetch();
    }

    /**
     * 角色管理
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function group()
    {
        if ($this->request->isPost()){
            $data=$this->request->post();
            $result =  AuthGroup::create($data);
            if ($result) {
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }
        $data= AuthGroup::all();
        $assign=array(
            'data'=>$data
        );
        $this->assign($assign);
        return $this->fetch();
    }
}