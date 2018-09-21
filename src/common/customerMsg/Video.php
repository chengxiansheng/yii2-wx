<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送视频消息
 * @Author Cheng
 * @Date   2018-09-20
 */
class Video extends MsgTemplate
{
    public $type = 'video';

    public function init()
    {
        parent::init();
        if (!is_array($this->content)) {
            throw new Exception("缺少视频消息数组，参数：\$extra['content']");
        }
        if (empty($this->content['media_id'])) {
            throw new Exception("缺少视频媒体id，参数：\$extra['content']['media_id']");
        }
        if (empty($this->content['thumb_media_id'])) {
            throw new Exception("缺少视频缩略图媒体id，参数：\$extra['content']['thumb_media_id']");
        }
        $this->content = [$this->type => $this->content];
    }
}
