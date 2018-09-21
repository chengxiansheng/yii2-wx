<?php
namespace wx\base;

/**
 * 微信基础凭证初始化类
 * @Author Cheng
 * @Date   2018-09-19
 */
class WxAccessTokenObject extends WxObject
{

    protected $accessToken;

    public function init()
    {
        $this->accessToken = (new AccessToken(['config' => $this->config, 'httpClient' => $this->httpClient]))->getToken();
    }
}
