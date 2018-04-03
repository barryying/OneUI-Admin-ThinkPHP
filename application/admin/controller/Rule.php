<?php
namespace app\admin\controller;
use app\common\controller\Adminbase;
use think\Db;
use think\Request;
/**
 * 
 * 后台权限管理
 */
class Rule extends AdminBase{

//******************权限***********************


    /*权限列表*/
    public function index(){
        $data=Db::name('auth_rule')
            ->getTreeData('tree', 'id', 'title')
        ;
        $this->assign([
            'data' => $data
        ]);
        return $this->fetch();
    }
    /*API Json权限列表*/
    public function rule_list(){
        $data=Db::name('auth_rule')
            ->getTreeData(
                'tree',
                'id',
                'title'
            );
        return show(
            config('app.success_code'),
            'OK', $data,
            200
        );
    }

    /**
     * 添加权限
     */
    public function add(){
        if(request()->isPost()){
            $data=input('post.');
            unset($data['id']);
            $result=Db::name('auth_rule')->insert($data);
            if ($result) {
                return show(config('app.success_code'), '添加成功', $data, 200);
            }else{
                return show(config('app.error_code'), '添加失败', $data, 200);
            }
        }
        return $this->fetch();
    }

    /**
     * 修改权限
     */
    public function edit(){
        if(request()->isPost()) {
            $data = input('post.');
            $info = ['title' => $data['title'], 'name' => $data['name']];
            $result = Db::name('auth_rule')->where(["id" => $data['id']])->update($info);

            if ($result) {
                return show(config('app.success_code'), '修改成功', $data, 200);
            } else {
                return show(config('app.error_code'), '修改失败', $data, 200);
            }
        }
        return $this->fetch();
    }

    /**
     * 删除权限
     */
    public function delete($id){
        $map=array(
            'id'=>$id
            );
        $result=Db::name('auth_rule')->delete($map);
        if($result){
            $this->success('删除成功','Admin/Rule/rule_list');
        }else{
            $this->error('请先删除子权限');
        }

    }

    /**
     * 角色列表
     */
    public function rule_group(){
        $data=Db::name('auth_group')->paginate(5);
        $assign=array(
            'data'=>$data
            );
        $this->assign($assign);
        return $this->fetch();
    }


     /**
     * 添加角色
     */
    public function add_group(){
        $data=input('post.');
        unset($data['id']);
        $result=Db::name('auth_group')->insert($data);
        if ($result) {
            return show(
                config('app.success_code'),
                '添加用户组成功', "",
                200
            );
        }else{
            return show(
                config('app.error_code'),
                '添加用户组失败', "",
                200
            );
        }
    }

    /**
     * 修改角色
     */
    public function edit_group(){
        $data=input('post.');
        $result=Db::name('auth_group')->where(["id"=>$data['id']])->update(['title'=>$data['title']]);
        // $result=Db::name('auth_group')->editData($map,$data);
        if ($result) {
            $this->success('修改成功','Admin/Rule/rule_group');
        }else{
            $this->error('您没有做任何修改');
        }
    }

    /**
     * 删除角色
     */
    public function delete_group($id){
        if(request()->isPost()){
            if ($id!=1) {
                $result=Db::name('auth_group')->delete($id);
                if ($result) {
                    $this->success('删除成功');
                }else{
                    $this->error('删除失败');
                }
            }
            $this->error('该用户组不能被删除');
        }
        $this->error("提交方式错误！");
    }


    /**
     * 分配权限
     */
    public function rule_distribution($id){
        if(Request::instance()->post()){
            $data=input('post.');
            $datas['rules']=implode(',', $data['rule_ids']);
            $result=Db::name('auth_group')->where(['id'=>$data['id']])->update($datas);
            // $result=Db::name('auth_group')->editData($map,$data);
            if ($result) {
                $this->success('操作成功','Admin/Rule/rule_group');
            }else{
                $this->error('操作失败');
            }
        }else{
            $group_data=Db::name('auth_group')->where(array('id'=>$id))->find();
            $group_data['rules']=explode(',', $group_data['rules']);
            // 获取规则数据
            $rule_data=Db::name('auth_rule')->getTreeData('level','id','title');
            $assign=array(
                'group_data'=>$group_data,
                'rule_data'=>$rule_data
                );
            // dump($group_data);
            $this->assign($assign);
            //return $this->fetch();
            return $this->fetch('distribution');
        }
    }

}
