<?php
namespace wx\base;

use Yii;

/**
 * 获取微信AccessToken接口类
 * @Author Cheng
 * @Date   2018-09-14
 */
class AccessToken extends WxObject
{
    /**
     * access_token请求地址
     */
    const API_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * 缓存key
     * @var string
     */
    protected $cacheKey;
    /**
     * 缓存过期的安全值
     * @var integer
     */
    protected $safeValue = 1000;

    public function init()
    {
        parent::init();
        $this->cacheKey = "wx-access-token-{$this->config['appid']}-&&-{$this->config['secret']}";
    }

    /**
     * 获取Token
     * @Author Cheng
     * @param  boolean    $refresh 是否刷新缓存
     * @return string
     */
    public function getToken($refresh = false)
    {
        $cache = Yii::$app->cache;
        if ($refresh === true) {
            $cache->delete($this->cacheKey);
        }
        $token = $cache->get($this->cacheKey);

        if ($token === false) {
            $data  = $this->getTokenFromWx();
            $token = $data['access_token'];
            $cache->set($this->cacheKey, $token, $data['expires_in'] - $this->safeValue);
        }

        return $token;
    }

    /**
     * 向微信服务器请求token
     * @Author Cheng
     * @return array
     */
    protected function getTokenFromWx()
    {
        $params = [
            'grant_type' => 'client_credential',
            'appid'      => $this->config['appid'],
            'secret'     => $this->config['secret'],
        ];
        $response = $this->httpClient->get(self::API_TOKEN_URL, $params)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        
        $data = $response->getData();

        if (!isset($data['access_token'])) {
            throw new Exception($data['errmsg'], $data['errcode']);
        }

        return $data;
    }
}
