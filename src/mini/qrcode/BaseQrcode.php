<?php
namespace wx\mini\qrcode;

use wx\base\WxAccessTokenObject;
use Yii;

/**
 * 二维码基础类
 * @Author Cheng
 * @Date   2018-09-15
 */
class BaseQrcode extends WxAccessTokenObject
{

    /**
     * 保存图片文件
     * @Author Cheng
     * @param  string     $file 文件数据流
     * @return mix
     */
    public function saveQrcode($file)
    {
        $root = sprintf('wx/temp/files/mini/qrcode/%s/%s/', strtolower(substr($this->className(), -1, 1)), date('Y-m-d'));
        $path = Yii::getAlias('@' . $root);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        $imageName = Yii::$app->security->generateRandomString() . '.jpg';
        $path .= $imageName;
        if (file_put_contents($path, $file)) {
            return '@' . $root . $imageName;
        }
        return null;
    }
}
