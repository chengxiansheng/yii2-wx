<?php
namespace wx\mp\menu;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 自定义菜单设置
 * @Author Cheng
 * @Date   2018-09-18
 */
class Menu extends WxAccessTokenObject
{
    // 创建自定义菜单
    const API_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    // 查询自定义菜单
    const API_SEARCH_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/get';
    // 删除自定义菜单
    const API_DELETE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/delete';

    /**
     * 创建
     * @Author Cheng
     * @return array
     */
    public function create()
    {
        if (empty($this->extra['button'])) {
            throw new Exception("缺少按钮参数，参数：\$extra['button']");
        }
        $url      = $this->spliceLink(self::API_CREATE_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['button' => $this->extra['button']])->setFormat($this->setFormatterUnicodeJson())->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

    /**
     * 查询
     * @Author Cheng
     * @return array
     */
    public function search()
    {
        $url      = $this->spliceLink(self::API_SEARCH_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

    /**
     * 删除
     * @Author Cheng
     * @return array
     */
    public function delete()
    {
        $url      = $this->spliceLink(self::API_DELETE_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }
}
