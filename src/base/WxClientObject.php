<?php
namespace wx\base;

use yii\base\BaseObject;

/**
 * http客户端基础类
 * @Author Cheng
 * @Date   2018-09-14
 */
class WxClientObject extends BaseObject
{

    /**
     * http客户端
     * @var array
     */
    public $httpClient;

    /**
     * 设置编码，中文不转义 JSON_UNESCAPED_UNICODE
     * @Author Cheng
     * @Date   2018-09-18
     * @return string  中文编码名称
     */
    public function setFormatterUnicodeJson()
    {
        $this->httpClient->formatters = ['unicodeJson' => ['class' => 'yii\httpclient\JsonFormatter', 'encodeOptions' => 256]];

        return 'unicodeJson';
    }
}
