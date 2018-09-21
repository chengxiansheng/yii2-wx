<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送音乐消息
 * @Author Cheng
 * @Date   2018-09-19
 */
class Music extends MsgTemplate
{
    public $type = 'music';

    public function init()
    {
        parent::init();
        if (!is_array($this->content)) {
            throw new Exception("缺少音乐消息数组，参数：\$extra['content']");
        }
        if (empty($this->content['musicurl'])) {
            throw new Exception("缺少音乐链接，参数：\$extra['content']['musicurl']");
        }
        if (empty($this->content['hqmusicurl'])) {
            throw new Exception("缺少高品质音乐链接，参数：\$extra['content']['hqmusicurl']");
        }
        if (empty($this->content['thumb_media_id'])) {
            throw new Exception("缺少音乐缩略图媒体id，参数：\$extra['content']['thumb_media_id']");
        }
        $this->content = [$this->type => $this->content];
    }
}
