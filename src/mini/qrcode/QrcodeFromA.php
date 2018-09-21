<?php
namespace wx\mini\qrcode;

use wx\base\Exception;
use Yii;
use yii\httpclient\Client;

/**
 * 获取小程序二维码接口A类
 * 适用于需要的码数量较少的业务场景
 * @Author Cheng
 * @Date   2018-09-15
 */
class QrcodeFromA extends BaseQrcode implements CreateInterface
{
    const API_LIMIT_PRE_URL = 'https://api.weixin.qq.com/wxa/getwxacode';

    private $apiUrl;

    public function init()
    {
        parent::init();
        
        $this->apiUrl = $this->spliceLink(self::API_LIMIT_PRE_URL,['access_token'=>$this->accessToken]);

        if (empty($this->extra['path'])) {
            throw new Exception("缺少页面路径，参数：\$extra['path']");
        }
    }

    /**
     * 生成小程序页面二维码
     * @Author Cheng
     * @param  boolean    $saveFile 是否保存图片，默认不保存
     * @return string
     */
    public function create($saveFile = false)
    {
        $response = $this->httpClient->post($this->apiUrl, $this->extra)->setFormat(Client::FORMAT_JSON)->send();
        
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        $contentType = $response->getHeaders()->get('content-type');
        if (strpos($contentType, 'json') != false) {
            $data = $response->getData();
            if (isset($data['errcode'])) {
                throw new Exception($data['errmsg'], $data['errcode']);
            }
        }
        if ($saveFile === false) {
            return $response->getContent();
        }
        return $this->saveQrcode($response->getContent());
    }

}
