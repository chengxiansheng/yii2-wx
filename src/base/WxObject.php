<?php
namespace wx\base;

/**
 * 基础类
 * @Author Cheng
 * @Date   2018-09-14
 */
class WxObject extends WxClientObject
{
    const REQUEST_NO_RESPONSE = '本次请求并没有得到响应，请检查网络是否畅通。';
    /**
     * 基本配置
     * @var array
     */
    public $config;

    /**
     * 额外参数
     * @var array
     */
    public $extra;

    public function init()
    {
        parent::init();
        if (empty($this->config['appid'])) {
            throw new Exception("缺少appid，参数：\$config['appid']");
        }

        if (empty($this->config['secret'])) {
            throw new Exception("缺少秘钥，参数：\$config['secret']");
        }
    }

    /**
     * 拼接请求地址
     * @Author Cheng
     * @Date   2018-09-18
     * @param  string     $pre_url 前部分
     * @param  array      $params  参数部分
     * @return string
     */
    public function spliceLink($pre_url, $params = [])
    {
        if (is_array($params) && empty($params)) {
            return $pre_url;
        }
        $pre_url .= '?' . http_build_query($params);

        return $pre_url;
    }
}
