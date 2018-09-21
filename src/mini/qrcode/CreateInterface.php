<?php
namespace wx\mini\qrcode;

interface CreateInterface
{
    public function create($saveFile = false);

    public function saveQrcode($file);
}
