<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送小程序卡片
 * @Author Cheng
 * @Date   2018-09-17
 */
class Miniprogrampage extends MsgTemplate
{
    public $type = 'miniprogrampage';

    public function init()
    {
        parent::init();
        if (!is_array($this->content)) {
            throw new Exception("缺少小程序卡片消息数组，参数：\$extra['content']");
        }
        if (empty($this->content['title'])) {
            throw new Exception("缺少小程序卡片标题，参数：\$extra['content']['title']");
        }
        if (empty($this->content['pagepath'])) {
            throw new Exception("缺少小程序pagepath，参数：\$extra['content']['pagepath']");
        }
        if (empty($this->content['thumb_media_id'])) {
            throw new Exception("缺少小程序卡片缩略图媒体id，参数：\$extra['content']['thumb_media_id']");
        }
        $this->content = [$this->type => $this->content];
    }
}
