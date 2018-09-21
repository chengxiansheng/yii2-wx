<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送语音消息
 * @Author Cheng
 * @Date   2018-09-20
 */
class Voice extends MsgTemplate
{
    public $type = 'voice';

    public function init()
    {
        parent::init();
        if (empty($this->content)) {
            throw new Exception("缺少语音消息内容，参数：\$extra['content']");
        }
        if (!is_string($this->content)) {
            throw new Exception("语音消息内容是字符串，参数：\$extra['content']");
        }
        $this->content = [$this->type => ['media_id' => $this->content]];
    }
}
