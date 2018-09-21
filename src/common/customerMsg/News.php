<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送图文消息
 * @Author Cheng
 * @Date   2018-09-19
 */
class News extends MsgTemplate
{
    public $type = 'news';

    public function init()
    {
        parent::init();
        if (!is_array($this->content)) {
            throw new Exception("缺少图文消息数组，参数：\$extra['content']");
        }
        $this->content = [$this->type => ['articles' => $this->content]];
    }
}
