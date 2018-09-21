<?php
namespace wx\mini\user;

use wx\base\Exception;
use wx\base\WxObject;
use wx\mini\aes\WXBizDataCrypt;

/**
 * 获取小程序用户基本信息接口类
 * @Author Cheng
 * @Date   2018-09-15
 */
class User extends WxObject
{
    /**
     * 登录凭证校验请求地址
     */
    const API_JSCODE2SESSION_URL = 'https://api.weixin.qq.com/sns/jscode2session';

    /**
     * 获取用户基本信息
     * @Author Cheng
     * @return array
     */
    public function getUserInfo()
    {
        $login = $this->login();

        $data = $this->decryptData($login['session_key']);

        return array_merge($data, ['session_key' => $login['session_key']]);
    }

    /**
     * 解密数据
     * @Author Cheng
     * @param  string     $session_key 会话密钥
     * @return array
     */
    public function decryptData($session_key)
    {
        if (empty($this->extra['encryptedData'])) {
            throw new Exception("缺少加密数据，参数：\$extra['encryptedData']");
        }

        if (empty($this->extra['iv'])) {
            throw new Exception("缺少初始向量，参数：\$extra['iv']");
        }

        $crypt = new WXBizDataCrypt($this->config['appid'], $session_key);

        $code = $crypt->decryptData($this->extra['encryptedData'], $this->extra['iv'], $data);

        if ($code !== 0) {
            throw new Exception($code);
        }

        return $data;
    }

    /**
     * 登录凭证校验，换取会话密钥session_key、openid等
     * @Author Cheng
     * @return string
     */
    public function login()
    {
        if (empty($this->extra['jsCode'])) {
            throw new Exception("缺少临时登录凭证code，参数：\$extra['jsCode']");
        }

        $params = [
            'grant_type' => 'authorization_code',
            'appid'      => $this->config['appid'],
            'secret'     => $this->config['secret'],
            'js_code'    => $this->extra['jsCode'],
        ];
        $response = $this->httpClient->get(self::API_JSCODE2SESSION_URL, $params)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        
        $data = $response->getData();

        if (!isset($data['session_key'])) {
            throw new Exception($data['errmsg'], $data['errcode']);
        }

        return $data;
    }
}
