<?php
namespace wx\mini\check;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;

/**
 * 检查一段文本是否含有违法违规内容
 * @Author Cheng
 * @Date   2018-09-15
 */
class Msg extends WxAccessTokenObject
{

    const API_MSG_PRE_URL = 'https://api.weixin.qq.com/wxa/msg_sec_check';

    private $apiUrl;

    public function init()
    {
        parent::init();

        $this->apiUrl = $this->spliceLink(self::API_MSG_PRE_URL, ['access_token' => $this->accessToken]);
        if (empty($this->extra['content'])) {
            throw new Exception("缺少文本资源，参数：\$extra['content']");
        }
    }

    public function check()
    {
        $response = $this->httpClient->post($this->apiUrl, $this->extra)->setFormat($this->setFormatterUnicodeJson())->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();

    }
}
