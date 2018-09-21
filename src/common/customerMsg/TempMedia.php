<?php
namespace wx\common\customerMsg;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 临时媒体文件的下载与上传
 * @Author Cheng
 * @Date   2018-09-17
 */
class TempMedia extends WxAccessTokenObject
{
    const API_GET_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/media/get';

    const API_UPLOAD_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/media/upload';

    /**
     * 下载媒体文件二进制内容
     * @Author Cheng
     * @return mix
     */
    public function get()
    {
        if (empty($this->extra['media_id'])) {
            throw new Exception("缺少媒体文件编号，参数：\$extra['media_id']");
        }
        $url = $this->spliceLink(self::API_GET_PRE_URL, ['access_token' => $this->accessToken, 'media_id' => $this->extra['media_id']]);

        $response = $this->httpClient->get($url)->send();

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

        return $response->getContent();
    }

    /**
     * 上传媒体文件到微信服务器
     * @Author Cheng
     * @return [type]     [description]
     */
    public function upload()
    {
        if (empty($this->extra['type'])) {
            throw new Exception("缺少文件类型，参数：\$extra['type']");
        }
        if (empty($this->extra['file'])) {
            throw new Exception("缺少文件，参数：\$extra['file']");
        }
        $url      = $this->spliceLink(self::API_UPLOAD_PRE_URL, ['access_token' => $this->accessToken, 'type' => $this->extra['type']]);
        $response = $this->httpClient->post($url)->addFile('media', $this->extra['file'])->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }
}
