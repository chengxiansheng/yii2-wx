<?php
namespace wx;

use wx\base\Exception;
use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client;

/**
 * 微信统一接口入口
 */
class Application extends BaseObject
{
    /**
     * wx基本配置
     * @var array
     */
    public $config;

    /**
     * http客户端
     * @var Client
     */
    protected $httpClient;

    /**
     * 基础类映射
     * @var array
     */
    protected $classMap = [];


    public function init()
    {
        parent::init();

        $this->classMap = require __DIR__ . '/config/map-class.php';
        $this->httpClient = new Client([
            // 'transport' => 'yii\httpclient\CurlTransport',
        ]);

    }

    /**
     * 构建具体实例
     * @Author Cheng
     * @Date   2018-09-14
     * @param  string     $api   类的映射名
     * @param  array      $extra 额外参数
     * @return object
     */
    public function run($api, $extra = [])
    {
        if (empty($api) || isset($this->classMap[$api]) == false) {
            throw new Exception('当前输入的API不合法，请仔细核对。');
        }
        $config = [
            'class'      => $this->classMap[$api],
            'config'     => $this->config,
            'httpClient' => $this->httpClient,
            'extra'      => $extra,
        ];
        return Yii::createObject($config);
    }
}
