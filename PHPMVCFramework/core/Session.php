<?php

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        \session_start();
        $getMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($getMessages as $key => &$message) {
            $message['remove'] = \true;
        }
        $_SESSION[self::FLASH_KEY] = $getMessages;
    }


    public function setFlash($key, $value)
    {
        $_SESSION[self::FLASH_KEY][$key]['remove'] = \false;
        $_SESSION[self::FLASH_KEY][$key]['value'] = $value;
    }


    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'];
    }


    public function __destruct()
    {
        $getMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($getMessages as $key => &$message) {
            if ($message['remove']) {
                unset($getMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $getMessages;
    }
}
