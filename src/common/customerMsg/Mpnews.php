<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送图文消息（点击跳转到图文消息页面） 图文消息条数限制在8条以内，注意，如果图文数超过8，则将会无响应。
 * @Author Cheng
 * @Date   2018-09-20
 */
class Mpnews extends MsgTemplate
{
    public $type = 'mpnews';

    public function init()
    {
        parent::init();
        if (empty($this->content)) {
            throw new Exception("缺少图文消息内容，参数：\$extra['content']");
        }
        if (!is_string($this->content)) {
            throw new Exception("图文消息内容是字符串，参数：\$extra['content']");
        }
        $this->content = [$this->type => ['media_id' => $this->content]];
    }
}
