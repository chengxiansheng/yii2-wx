<?php
namespace wx\mp\user;

use wx\base\Exception;
use wx\base\WxAccessTokenObject;
use yii\httpclient\Client;

/**
 * 用户标签管理
 * @Author Cheng
 * @Date   2018-09-18
 */
class Tag extends WxAccessTokenObject
{
    // 创建标签
    const API_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/create';

    // 获取公众号已创建的标签
    const API_SEARCH_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/get';

    // 修改标签
    const API_UPDATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/update';

    // 删除标签
    const API_DELETE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/delete';

    // 获取标签下粉丝列表
    const API_SEARCH_USER_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/user/tag/get';

    // 批量为用户打标签
    const API_BATCH_CREATE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging';

    // 批量为用户取消标签
    const API_BATCH_DELETE_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging';

    // 获取用户身上的标签列表
    const API_BATCH_SEARCH_PRE_URL = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist';

    public function create()
    {
        if (empty($this->extra['tag'])) {
            throw new Exception("缺少标签名称，参数：\$extra['tag']");
        }
        $url      = $this->spliceLink(self::API_CREATE_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->post($url, ['tag' => ['name' => $this->extra['tag']]])->setFormat($this->setFormatterUnicodeJson())->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function search()
    {
        $url      = $this->spliceLink(self::API_SEARCH_PRE_URL, ['access_token' => $this->accessToken]);
        $response = $this->httpClient->get($url)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function update()
    {
        if (empty($this->extra['tagId'])) {
            throw new Exception("缺少标签编号，参数：\$extra['tagId']");
        }
        if (empty($this->extra['tag'])) {
            throw new Exception("缺少标签名称，参数：\$extra['tag']");
        }
        $url      = $this->spliceLink(self::API_UPDATE_PRE_URL, ['access_token' => $this->accessToken]);
        $params   = ['tag' => ['id' => $this->extra['tagId'], 'name' => $this->extra['tag']]];
        $response = $this->httpClient->post($url, $params)->setFormat($this->setFormatterUnicodeJson())->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function delete()
    {
        if (empty($this->extra['tagId'])) {
            throw new Exception("缺少标签编号，参数：\$extra['tagId']");
        }
        $url      = $this->spliceLink(self::API_DELETE_PRE_URL, ['access_token' => $this->accessToken]);
        $params   = ['tag' => ['id' => $this->extra['tagId']]];
        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function searchUser()
    {
        if (empty($this->extra['tagId'])) {
            throw new Exception("缺少标签编号，参数：\$extra['tagId']");
        }
        $url    = $this->spliceLink(self::API_SEARCH_USER_PRE_URL, ['access_token' => $this->accessToken]);
        $params = ['tagid' => $this->extra['tagId']];
        if (!empty($this->extra['nextUser'])) {
            $params = array_merge($params, ['next_openid' => $this->extra['nextUser']]);
        }
        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    public function batchCreate()
    {
        return $this->__batchSite(self::API_BATCH_CREATE_PRE_URL);
    }

    public function batchDelete()
    {
        return $this->__batchSite(self::API_BATCH_DELETE_PRE_URL);
    }

    public function batchSearch()
    {
        if (empty($this->extra['user'])) {
            throw new Exception("缺少用户openid，参数：\$extra['user']");
        }
        $url      = $this->spliceLink(self::API_BATCH_SEARCH_PRE_URL, ['access_token' => $this->accessToken]);
        $params   = ['openid' => $this->extra['user']];
        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();
    }

    private function __batchSite($url)
    {
        if (empty($this->extra['tagId'])) {
            throw new Exception("缺少标签编号，参数：\$extra['tagId']");
        }
        if (empty($this->extra['users'])) {
            throw new Exception("缺少用户openid数组，参数：\$extra['users']");
        }
        $url      = $this->spliceLink($url, ['access_token' => $this->accessToken]);
        $params   = ['tagid' => $this->extra['tagId'], 'openid_list' => $this->extra['users']];
        $response = $this->httpClient->post($url, $params)->setFormat(Client::FORMAT_JSON)->send();
        if ($response->isOk !== true) {
            throw new Exception(self::REQUEST_NO_RESPONSE);
        }
        return $response->getData();

    }
}
