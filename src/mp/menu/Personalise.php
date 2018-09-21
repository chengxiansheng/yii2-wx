<?php
namespace wx\mp\menu;

use wx\base\Exception;
use yii\httpclient\Client;

/**
 * 个性化菜单设置
 * @Author Cheng
 * @Date   2018-09-18
 */
class Personalise extends Menu
{
    // 创建个性化菜单
    const API_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/addconditional';
    // 测试个性化菜单
    const API_TRY_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/trymatch';
    // 删除个性化菜单
    const API_DELETE_SELF_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/delconditional';

    public function create()
    {
        if (empty($this->extra['button'])) {
            throw new Exception("缺少按钮参数，参数：\$extra['button']");
        }
        if (empty($this->extra['matchrule'])) {
            throw new Exception("缺少菜单匹配规则，参数：\$extra['matchrule']");
        }
        $url    = $this->spliceLink(self::API_CREATE_PRE_URL, ['access_token' => $this->accessToken]);
        $params = [
            'button'    => $this->extra['button'],
            'matchrule' => $this->extra['matchrule'],
        ];
        $response = $this->httpClient->post($url, $params)->setFormat($this->setFormatterUnicodeJson())->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

    /**
     * 删除具体个性化菜单
     * @Author Cheng
     * @return array
     */
    public function deleteSelf()
    {
        if (empty($this->extra['menuid'])) {
            throw new Exception("缺少个性化菜单编号，参数：\$extra['menuid']");
        }

        $url      = $this->spliceLink(self::API_DELETE_SELF_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['menuid' => $this->extra['menuid']])->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }

    /**
     * 测试具体用户的个性化菜单
     * @Author Cheng
     * @return array
     */
    public function trySelf()
    {
        if (empty($this->extra['user_id'])) {
            throw new Exception("缺少用户openid或微信号，参数：\$extra['user_id']");
        }

        $url      = $this->spliceLink(self::API_TRY_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['user_id' => $this->extra['user_id']])->setFormat(Client::FORMAT_JSON)->send();

        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }

        return $response->getData();
    }
}
