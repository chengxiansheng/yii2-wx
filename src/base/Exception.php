<?php

namespace wx\base;

use yii\base\Exception as baseException;

class Exception extends baseException
{
    public function getName()
    {
        return '微信SDK Exception';
    }
}
