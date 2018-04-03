<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2018/3/18
 * Time: 11:17
 */

namespace app\common\service;

use think\Cache;
use think\Exception;
use think\Log;

/**
 * 验证码处理类库
 * VerificationCode
 * @package app\common\service
 */
class VerificationCode
{
    /**
     * 静态变量保存全局的实例
     * @var null
     */
    private static $_instance = null;

    /**
     * 发送驱动
     * @var string
     */
    private static $drive = 'Mail';
    /**
     * 私有的构造方法
     */
    private function __construct() {

    }

    /**
     * 静态方法 单例模式统一入口
     * @param string $drive  驱动
     * @return VerificationCode|null
     */
    public static function getInstance($drive = null) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        if(!is_null($drive)) {
            self::$drive = $drive;
        }
        return self::$_instance;
    }

    /**
     * 发送验证码
     * @param $towhom
     * @return bool
     */
    public function send($towhom){
        $code = getRandChar('6','NUMBER');
        try{
            if (self::$drive == 'SMS'){
                $result = AliSms::getInstance(config('alisms'))->send($towhom, $code);
            }else{
                $result = Mail::getInstance(config('mail'))->send($towhom, $towhom, $subject = '您本次操作验证码', self::getMailCentent($towhom,$code), $attachment = null);
            }
            //储存验证码 300秒
            if($result){
                Cache::set($towhom , $code ,300);
            }
            return true;
        }catch (Exception $ex){
            Log::record('VeriCode:' . json_encode($ex->getMessage()));
            return false;
        }
    }

    /**
     * 验证码验证
     * @param $Towhom
     * @param $code
     * @return bool
     */
    public function valid($Towhom, $code){
        if (is_null($Towhom)){
            return false;
        }
        return Cache::get($Towhom) == $code?true:false;
    }

    /**
     * 获取发送邮件内容
     * @param $Towhom
     * @param $code
     * @return string
     */
    private function getMailCentent($Towhom,$code){
        $centent = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tbody><tr bgcolor=\"#e9e9e9\">
                        <td align=\"center\" valign=\"middle\"> 
                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"600\" bgcolor=\"#FFFFFF\" style=\"\">
                                <tbody>
                                    <tr bgcolor=\"#e9e9e9\">
                                        <td colspan=\"3\" height=\"60\"></td>
                                    </tr>
                                    <tr bgcolor=\"#1bb7f0\" style=\"height:100px;\">
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"middle\">
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" align=\"left\" style=\"\">
                                                <tbody>
                                                    <tr>
                                                        <td height=\"32\"></td>
                                                    </tr>
                                                    <tr>
                                                        <td width=\"148\"><img src=\"https://www.98imo.com/static/logo.png\"></td>
                                                    </tr>
                                                    <tr>
                                                        <td height=\"32\"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td valign=\"middle\" width=\"30\"></td>
                                    </tr>
                                    <tr>
                                        <td colspan=\"3\" height=\"49\"></td>
                                    </tr>
                                    <tr>
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"top\" style=\"color:#000000;font-size:15.6px;\"><p style=\"padding:0;margin:0;\">尊敬的【{$Towhom}】：</p></td>
                                        <td valign=\"middle\" width=\"30\"></td>
                                    </tr>
                
                                    
                                    <tr style=\"font-size:15px;color:#35bd62!important;\">
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"middle\" style=\"color:#3ed086;font-size:15.6px;\"><p style=\"padding-top:17px;margin:0;\">您正在进行敏感操作，请注意！</p></td>
                                        <td valign=\"middle\" width=\"30\"></td>
                                    </tr>
                                    <tr>
                                        <td valign=\"middle\" colspan=\"3\" height=\"6\"></td>
                                    </tr>
                                    <tr style=\"font-size:15px;\">
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"middle\" height=\"58\" style=\"color:#000000;font-size:15.6px;\"><p style=\"padding:0;margin:0;\">以下是您本次的验证码：</p></td>
                                        <td valign=\"middle\" style=\"word-break:break-all;padding-right:20px;\" width=\"30\"></td>
                                    </tr>
                                    <tr style=\"font-size:15px;\">
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"middle\" height=\"58\" style=\"color:#000000;font-size:15.6px;\"><p style=\"padding:0;margin:0;\">{$code}（验证码5分钟内有效）</p></td>
                                        <td valign=\"middle\" width=\"30\"></td>
					                </tr>
                                    <tr>
                                        <td valign=\"middle\" colspan=\"3\" height=\"10\"></td>
                                    </tr>
                                    <tr>
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td style=\"text-align:right;color:#1bb7f0;font-size:15.6px;\"><p style=\"padding:0;margin:0;\"><a style=\"color:#1bb7f0;text-decoration:none;\" href=\"https://www.98imo.com/\" target=\"_blank\">前往商户平台查看</a></p></td>
                                        <td width=\"30\"></td>
                                    </tr>
                                    <tr>
                                        <td valign=\"middle\" colspan=\"3\" height=\"36\"></td>
                                    </tr>
                            
                                    <tr bgcolor=\"#f1f1f1\">
                                        <td valign=\"middle\" width=\"30\"></td>
                                        <td valign=\"middle\">
                                            <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" align=\"left\" style=\"\">
                                                <tbody>
                                                    <tr>
                                                        <td height=\"49\" colspan=\"2\"></td>
                                                    </tr>
                                                    <tr>
                                                        <td valign=\"middle\" rowspan=\"2\" width=\"142\"><img height=\"120px;\" src=\"https://www.98imo.com/static/qrcode.jpg\"></td>
                                                        <td valign=\"middle\" height=\"60\" style=\"font-size:20px;\"><p style=\"padding:0;margin:0;\">加入Mofee聚合支付结算群</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td valign=\"top\" height=\"50\" style=\"font-size:14px;color:#b2b2b2;line-height:20px;\">
                                                            <p style=\"padding:0;margin:0;\">为您提供Mofee聚合支付接入相关资讯</p>
                                                            <p style=\"padding:0;margin:0;\">扫描左侧二维码加入群聊</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan=\"2\" height=\"30\" style=\"border-bottom:1px solid #d6d6d6\"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan=\"2\" height=\"73\"><p style=\"color:#b2b2b2;font-size:14px;text-align:center\">注意：该电子邮件地址不接受回复邮件，如果您要解决问题，请联系商户平台客服。</p></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan=\"2\" height=\"20\"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td valign=\"middle\" width=\"30\"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr bgcolor=\"#e9e9e9\">
                        <td height=\"60\"></td>
                    </tr>
                </tbody></table>";
        return $centent;

    }


}