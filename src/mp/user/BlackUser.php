<?php
namespace wx\mp\user;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 黑名单用户管理
 * @Author Cheng
 * @Date   2018-09-19
 */
class BlackUser extends WxAccessTokenObject
{
    // 获取公众号的黑名单列表
    const API_LIST_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist';

    // 拉黑用户
    const API_BATCH_BLACK_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist';

    // 取消拉黑用户
    const API_BATCH_UNBLACK_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist';

    public function search()
    {
        $params['begin_openid'] = empty($this->extra['beginUser']) ? null : $this->extra['beginUser'];
        $url                    = $this->spliceLink(self::API_LIST_PRE_URL, ['access_token' => $this->accessToken]);
        $response               = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function batchBlack()
    {
        return $this->__batchSite(self::API_BATCH_BLACK_PRE_URL);
    }

    public function batchUnBlack()
    {
        return $this->__batchSite(self::API_BATCH_UNBLACK_PRE_URL);
    }

    private function __batchSite($url)
    {
        if (empty($this->extra['users'])) {
            throw new Exception("缺少用户openid数组，参数：\$extra['users']");
        }
        $url      = $this->spliceLink($url, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['openid_list' => $this->extra['users']])->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();

    }
}
