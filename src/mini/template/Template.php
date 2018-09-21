<?php
namespace wx\mini\template;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 小程序模版消息
 * @Author Cheng
 * @Date   2018-09-15
 */
class Template extends WxAccessTokenObject
{
    const API_SEND_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send';

    private $apiUrl;

    public function init()
    {
        parent::init();

        $this->apiUrl = $this->spliceLink(self::API_SEND_PRE_URL, ['access_token' => $this->accessToken]);
    }

    /**
     * 发送模板消息
     * @Author Cheng
     * @return array
     */
    public function send()
    {
        if (empty($this->extra['touser'])) {
            throw new Exception("缺少用户的openid，参数：\$extra['touser']");
        }
        if (empty($this->extra['template_id'])) {
            throw new Exception("缺少模板消息的id，参数：\$extra['template_id']");
        }
        if (empty($this->extra['form_id'])) {
            throw new Exception("缺少场景，参数：\$extra['form_id']");
        }
        if (empty($this->extra['data'])) {
            throw new Exception("缺少模板内容，参数：\$extra['data']");
        }

        $response = $this->httpClient->post($this->apiUrl, $this->extra)->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

}
