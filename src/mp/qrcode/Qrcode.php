<?php
namespace wx\mp\qrcode;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use Yii;
use yii\httpclient\Client;

/**
 * 二维码管理
 * @Author Cheng
 * @Date   2018-09-19
 */
class Qrcode extends WxAccessTokenObject
{
    const API_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';

    const API_SHOW_PRE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';

    /**
     * 临时二维码
     * @Author Cheng
     * @param  integer    $expire 有效时间
     * @return array
     */
    public function createTemp($expire = 60)
    {
        if ($this->isInt()) {
            return $this->tempScene2int($expire);
        }
        return $this->tempScene2str($expire);
    }

    /**
     * 永久二维码
     * @Author Cheng
     * @return array
     */
    public function createForever()
    {
        if ($this->isInt()) {
            return $this->foreverScene2int();
        }
        return $this->foreverScene2str();
    }

    /**
     * 通过ticket换取二维码
     * @Author Cheng
     * @param  string     $ticket
     * @param  boolean    $saveFile 是否保存图片，默认不保存
     * @return mix
     */
    public function showQrcode($ticket, $saveFile = false)
    {
        $url      = $this->spliceLink(self::API_SHOW_PRE_URL, ['ticket' => urlencode($ticket)]);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();
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

    private function isInt()
    {
        if (empty($this->extra['scene'])) {
            throw new Exception("缺少场景值，参数：\$extra['scene']");
        }
        if (is_int($this->extra['scene']) && $this->extra['scene'] > 0) {
            return true;
        }
        return false;
    }

    private function tempScene2int($expire)
    {
        return $this->create('QR_SCENE', ['scene' => ['scene_id' => $this->extra['scene']]], $expire);
    }

    private function tempScene2str($expire)
    {
        return $this->create('QR_STR_SCENE', ['scene' => ['scene_str' => $this->extra['scene']]], $expire);
    }

    private function foreverScene2int()
    {
        return $this->create('QR_LIMIT_SCENE', ['scene' => ['scene_id' => $this->extra['scene']]]);
    }

    private function foreverScene2str()
    {
        return $this->create('QR_LIMIT_STR_SCENE', ['scene' => ['scene_str' => $this->extra['scene']]]);
    }

    private function create($action, $scene, $expire = false)
    {
        $params = ['action_name' => $action, 'action_info' => $scene];

        if ($expire !== false) {
            $params = array_merge(['expire_seconds' => $expire], $params);
        }

        $url      = $this->spliceLink(self::API_CREATE_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, $params)->setFormat($this->setFormatterUnicodeJson())->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    private function saveQrcode($file)
    {
        $root = sprintf('wx/temp/files/mp/qrcode/%s/', date('Y-m-d'));
        $path = Yii::getAlias('@' . $root);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $imageName = Yii::$app->security->generateRandomString() . '.jpg';
        $path .= $imageName;
        if (file_put_contents($path, $file)) {
            return '@' . $root . $imageName;
        }
        return null;
    }

}
