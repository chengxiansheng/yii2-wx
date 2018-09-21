<?php
namespace wx\common\customerMsg;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use Yii;
use yii\httpclient\Client;

/**
 * 发送客服消息与客服状态
 * @Author Cheng
 * @Date   2018-09-17
 */
class Send extends WxAccessTokenObject
{
    const API_STATUS_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/typing';

    const API_MSG_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send';

    /**
     * 下发客服当前输入状态给用户
     * @Author Cheng
     * @param  boolean    $cancel 默认下发`正在输入`
     * @return array
     */
    public function status($cancel = false)
    {
        if (empty($this->extra['touser'])) {
            throw new Exception("缺少用户的openid，参数：\$extra['touser']");
        }

        $url     = $this->spliceLink(self::API_STATUS_PRE_URL, ['access_token' => $this->accessToken]);
        $command = ($cancel === true) ? 'CancelTyping' : 'Typing';

        $params = array_merge($this->extra, ['command' => $command]);

        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

    /**
     * 下发客服消息给用户
     * @Author Cheng
     * @param  string     $type [description]
     * @return array
     */
    public function message($type = 'text')
    {
        if (empty($this->extra['touser'])) {
            throw new Exception("缺少用户的openid，参数：\$extra['touser']");
        }

        $url    = $this->spliceLink(self::API_MSG_PRE_URL, ['access_token' => $this->accessToken]);
        $config = [
            'class'      => sprintf('%s\%s', __NAMESPACE__, ucfirst($type)),
            'url'        => $url,
            'touser'     => $this->extra['touser'],
            'content'    => $this->extra['content'] ?? null,
            'httpClient' => $this->httpClient,
        ];
        $object = Yii::createObject($config);

        return $object->send();
    }
}
