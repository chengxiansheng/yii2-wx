<?php
namespace wx\common\customerMsg;

use wx\base\Exception;
use wx\base\WxClientObject;

/**
 * 下发客服消息模板
 * @Author Cheng
 * @Date   2018-09-17
 */
class MsgTemplate extends WxClientObject
{
    public $type;

    public $url;

    public $touser;

    public $content;

    public $httpClient;

    /**
     * 下发客服消息
     * @Author Cheng
     * @return array
     */
    public function send()
    {
        $params = array_merge(['touser' => $this->touser, 'msgtype' => $this->type], $this->content);

        $response = $this->httpClient->post($this->url, $params)->setFormat($this->setFormatterUnicodeJson())->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }
}
