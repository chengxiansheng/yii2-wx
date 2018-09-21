<?php
namespace wx\mp\user;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 用户管理
 * @Author Cheng
 * @Date   2018-09-19
 */
class User extends WxAccessTokenObject
{
    // 设置用户备注名
    const API_REMARK_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark';

    // 设获取用户基本信息
    const API_INFO_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/user/info';

    // 批量获取用户基本信息
    const API_BATCH_INFO_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget';

    // 获取用户列表
    const API_LIST_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/user/get';

    public function remark()
    {
        if (empty($this->extra['user'])) {
            throw new Exception("缺少用户openid，参数：\$extra['user']");
        }
        if (empty($this->extra['remark'])) {
            throw new Exception("缺少用户备注名，参数：\$extra['remark']");
        }
        $url      = $this->spliceLink(self::API_REMARK_PRE_URL, ['access_token' => $this->accessToken]);
        $params   = ['openid' => $this->extra['user'], 'remark' => $this->extra['remark']];
        $response = $this->httpClient->post($url, $params)->setFormat($this->setFormatterUnicodeJson())->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function info()
    {
        if (empty($this->extra['user'])) {
            throw new Exception("缺少用户openid，参数：\$extra['user']");
        }

        $url      = $this->spliceLink(self::API_INFO_PRE_URL, ['access_token' => $this->accessToken, 'openid' => $this->extra['user'], 'lang' => 'zh_CN']);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function batchInfo()
    {
        if (empty($this->extra['users'])) {
            throw new Exception("缺少用户openid数组，参数：\$extra['users']");
        }
        array_walk($this->extra['users'], function (&$item, $key) {
            if (is_array($item) && isset($item['openid'])) {
                $item = isset($item['lang']) ? ['openid' => $item['openid'], 'lang' => $item['lang']] : ['openid' => $item['openid']];
            } elseif (is_string($item)) {
                $item = ['openid' => $item];
            } else {
                throw new Exception("无效用户openid数组，参数：\$extra['users']");
            }
        });
        $url      = $this->spliceLink(self::API_BATCH_INFO_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['user_list' => $this->extra['users']])->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function search()
    {
        $params = ['access_token' => $this->accessToken];
        if (!empty($this->extra['user'])) {
            $params['next_openid'] = $this->extra['user'];
        }
        $url      = $this->spliceLink(self::API_LIST_PRE_URL, $params);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }
}
