<?php
/**
 * Created by 小羊.
 * Author: 勇敢的小笨羊
 * 微博: http://weibo.com/xuzuxing
 * Date: 2017/11/25
 * Time: 22:03
 */

namespace app\common\service;

use Aliyun\Sms\Api\SendSmsRequest;
use Aliyun\Sms\Core\Config;
use Aliyun\Sms\Core\DefaultAcsClient;
use Aliyun\Sms\Core\Profile\DefaultProfile;

// 加载区域结点配置
Config::load();

class AliSms
{

    /**
     * 静态变量保存全局的实例
     * @var null
     */
    private static $_instance = null;

    /**
     * Aliacs全局的实例
     * @var null
     */
    static $acsClient = null;

    /**
     * 参数配置
     * @var null
     */
    static $config = null;

    /**
     * 私有的构造方法
     */
    private function __construct() {

    }

    /**
     * 静态方法 单例模式统一入口
     */
    public static function getInstance($config) {
        if(is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        self::$config = $config;
        return self::$_instance;
    }

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    private static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";
        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";
        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = self::$config['AccessKeyId']; // AccessKeyId
        $accessKeySecret = self::$config['AccessKeySecret']; // AccessKeySecret
        // 暂时不支持多Region
        $region = "cn-hangzhou";
        // 服务结点
        $endPointName = "cn-hangzhou";
        if(static::$acsClient == null) {
            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);
            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @param $phoneNumbers
     * @param string $templateParam
     * @param null $outId
     * @param null $smsUpExtendCode
     * @return mixed|\SimpleXMLElement
     */
    public function send($phoneNumbers, $templateParam = '0000', $outId = null, $smsUpExtendCode = null) {

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);
        // 必填，设置签名名称
        $request->setSignName(self::$config['signName']);
        // 必填，设置模板CODE
        $request->setTemplateCode(self::$config['templateCode']);
        // 可选，设置模板参数
        if($templateParam) {
            //参数格式  按照Alisms后台
            $SmsParam = ['code'  => $templateParam];

            $request->setTemplateParam(json_encode($SmsParam));
        }
        // 可选，设置流水号
        if($outId) {
            $request->setOutId($outId);
        }
        // 选填，上行短信扩展码
        if($smsUpExtendCode) {
            $request->setSmsUpExtendCode($smsUpExtendCode);
        }
        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;

    }

}