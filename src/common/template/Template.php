<?php
namespace wx\common\template;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 下发小程序和公众号统一的服务消息
 * @Author Cheng
 * @Date   2018-09-17
 */
class Template extends WxAccessTokenObject
{
    const API_SEND_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/uniform_send';

    private $apiUrl;

    public function init()
    {
        parent::init();
        $this->apiUrl = $this->spliceLink(self::API_SEND_PRE_URL, ['access_token' => $this->accessToken]);
    }

    /**
     * 发送模板消息
     * @Author Cheng
     * @return [type]     [description]
     */
    public function send()
    {
        if (empty($this->extra['touser'])) {
            throw new Exception("缺少用户的openid，参数：\$extra['touser']");
        }

        if (empty($this->extra['weapp_template_msg']) && empty($this->extra['mp_template_msg'])) {
            throw new Exception("缺少模板消息，小程序模板参数：\$extra['weapp_template_msg']或公众号模板参数：\$extra['mp_template_msg']");
        }

        $response = $this->httpClient->post($this->apiUrl, $this->extra)->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }
}
