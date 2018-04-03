<?php
namespace app\admin\model;


class AuthGroupAccess extends BaseModel
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'admin_auth_group_access';

    //初始化属性
    protected function initialize()
    {

    }

    //关联一对一 角色
    public function authRule()
    {
        return $this->hasOne('AuthRule','menu_id','menu_id');
    }
}
?>