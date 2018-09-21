<?php
namespace wx\common\customerMsg;

use wx\base\Exception;
/**
 * 发送文本消息
 * @Author Cheng
 * @Date   2018-09-17
 */
class Text extends MsgTemplate
{
    public $type = 'text';

    public function init()
    {
        parent::init();

        if (empty($this->content)) {
            throw new Exception("缺少发送文本内容，参数：\$extra['content']");
        }

        $this->content = [$this->type => ['content' => $this->content]];
    }
}
