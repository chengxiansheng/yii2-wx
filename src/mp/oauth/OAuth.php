<?php
namespace wx\mp\oauth;

use wx\base\Exception;
use wx\base\WxObject;

/**
 * 微信网页授权
 * @Author Cheng
 * @Date   2018-09-20
 */
class OAuth extends WxObject
{
    // 获取code
    const API_AUTH_PRE_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
    // 通过code换取网页授权access_token
    const API_TOKEN_PRE_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    // 刷新access_token
    const API_REFRESH_TOKEN_PRE_URL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    // 拉取用户信息(需scope为 snsapi_userinfo)
    const API_USER_INFO_PRE_URL = 'https://api.weixin.qq.com/sns/userinfo';
    // 检验授权凭证（access_token）是否有效
    const API_CHECK_PRE_URL = 'https://api.weixin.qq.com/sns/auth';

    public function send()
    {
        if (empty($this->extra['redirect_uri'])) {
            throw new Exception("缺少redirect_uri，参数：\$extra['redirect_uri']");
        }
        if (empty($this->extra['scope'])) {
            throw new Exception("缺少应用授权作用域，参数：\$extra['scope']");
        }
        $state  = $this->extra['state'] ?? 'STATE';
        $params = [
            'appid'         => $this->config['appid'],
            'redirect_uri'  => urlencode($this->extra['redirect_uri']),
            'response_type' => 'code',
            'scope'         => $this->extra['scope'],
            'state'         => $state . '#wechat_redirect',
        ];
        $url = $this->spliceLink(self::API_AUTH_PRE_URL, $params);
        header("location:{$url}");
    }

    public function info($code)
    {
        $tokenArr = $this->getToken($code);
        $scopeArr = explode(',', $tokenArr['scope']);
        if (in_array('snsapi_userinfo', $scopeArr)) {
            $params = [
                'access_token' => $tokenArr['access_token'],
                'openid'       => $tokenArr['openid'],
                'lang'         => 'zh_CN',
            ];
            $url = $this->spliceLink(self::API_USER_INFO_PRE_URL, $params);
            return $this->baseGet($url);
        }
        return $tokenArr;
    }

    private function getToken($code)
    {
        $params = [
            'appid'      => $this->config['appid'],
            'secret'     => $this->config['secret'],
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
        $url = $this->spliceLink(self::API_TOKEN_PRE_URL, $params);
        return $this->baseGet($url);
    }

    public function refreshToken($refreshToken)
    {
        $params = [
            'appid'         => $this->config['appid'],
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];
        $url = $this->spliceLink(self::API_REFRESH_TOKEN_PRE_URL, $params);
        return $this->baseGet($url);
    }

    public function checkToken($token, $openid)
    {
        $params = [
            'access_token' => $token,
            'openid'       => $openid,
        ];
        $url = $this->spliceLink(self::API_REFRESH_TOKEN_PRE_URL, $params);
        return $this->baseGet($url);

    }

    private function baseGet($url)
    {
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }
}
