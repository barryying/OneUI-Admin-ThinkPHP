<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2018/3/29
 * Time: 20:42
 */

namespace app\admin\controller;


class System extends Base
{

    public function index()
    {
        $this->assign('_website_',config('website'));
        return $this->fetch();
    }

    public function edit()
    {
        $row = $this->request->post();
        halt($row);
    }
}