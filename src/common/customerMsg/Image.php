<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送图片消息
 * @Author Cheng
 * @Date   2018-09-17
 */
class Image extends MsgTemplate
{
    public $type = 'image';

    public function init()
    {
        parent::init();
        if (empty($this->content)) {
            throw new Exception("缺少图片消息内容，参数：\$extra['content']");
        }
        if (!is_string($this->content)) {
            throw new Exception("图片消息内容是字符串，参数：\$extra['content']");
        }
        $this->content = [$this->type => ['media_id' => $this->content]];
    }
}
