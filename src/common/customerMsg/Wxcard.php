<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送卡券
 * @Author Cheng
 * @Date   2018-09-20
 */
class Wxcard extends MsgTemplate
{
    public $type = 'wxcard';

    public function init()
    {
        parent::init();
        if (empty($this->content)) {
            throw new Exception("缺少卡券消息内容，参数：\$extra['content']");
        }
        if (!is_string($this->content)) {
            throw new Exception("卡券消息内容是字符串，参数：\$extra['content']");
        }
        $this->content = [$this->type => ['card_id' => $this->content]];
    }
}
