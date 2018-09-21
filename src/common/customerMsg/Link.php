<?php
namespace wx\common\customerMsg;

use wx\base\Exception;

/**
 * 发送图文链接
 * @Author Cheng
 * @Date   2018-09-17
 */
class Link extends MsgTemplate
{
    public $type = 'link';

    public function init()
    {
        parent::init();
        if (!is_array($this->content)) {
            throw new Exception("缺少图文链接消息数组，参数：\$extra['content']");
        }
        if (empty($this->content['title'])) {
            throw new Exception("缺少图文链接标题，参数：\$extra['content']['title']");
        }
        if (empty($this->content['description'])) {
            throw new Exception("缺少图文链接描述，参数：\$extra['content']['description']");
        }
        if (empty($this->content['url'])) {
            throw new Exception("缺少图文链接地址，参数：\$extra['content']['url']");
        }
        if (empty($this->content['thumb_url'])) {
            throw new Exception("缺少图文链接消息的图片链接，参数：\$extra['content']['thumb_url']");
        }
        $this->content = [$this->type => $this->content];
    }
}
