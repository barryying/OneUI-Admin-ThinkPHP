<?php
/**
 * Author: 勇敢的小笨羊
 * Github: https://github.com/SingleSheep
 */

/**
 * 获取当前登录的管理用户
 * @return mixed
 */
function get_current_admin()
{
    return session('__admin__');
}