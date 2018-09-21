<?php
namespace wx\mini\check;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 校验一张图片是否含有违法违规内容
 * @Author Cheng
 * @Date   2018-09-15
 */
class Image extends WxAccessTokenObject
{
    const API_IMG_PRE_URL = 'https://api.weixin.qq.com/wxa/img_sec_check';

    private $apiUrl;

    public function init()
    {
        parent::init();

        $this->apiUrl = $this->spliceLink(self::API_IMG_PRE_URL, ['access_token' => $this->accessToken]);
        if (empty($this->extra['image'])) {
            throw new Exception("缺少图片资源，参数：\$extra['image']");
        }
    }

    public function check()
    {
        $response = $this->httpClient->post($this->apiUrl)->addFile('media', $this->extra['image'])->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();

    }
}
