<?php
namespace wx\mp\qrcode;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 长链接转短链接
 * @Author Cheng
 * @Date   2018-09-19
 */
class ShortUrl extends WxAccessTokenObject
{

    const API_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/shorturl';

    public function create()
    {
        if (empty($this->extra['url'])) {
            throw new Exception("缺少长链接，参数：\$extra['url']");
        }
        $params   = ['action' => 'long2short', 'long_url' => $this->extra['url']];
        $url      = $this->spliceLink(self::API_CREATE_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }
}
