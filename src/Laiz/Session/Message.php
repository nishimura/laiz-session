<?php

namespace Laiz\Session;

use Zend\Session\Container;

class Message
{
    const INFO = 'info';
    const ERROR = 'error';
    const SUCCESS = 'success';

    public static function getContainer()
    {
        $container = new Container('Laiz_Session_Message');
        return $container;
    }
    public static function add($message, $level = null)
    {
        $container = self::getContainer();
        $messages = $container->messages;
        if ($messages === null)
            $messages = array();
        $messages[] = array('level' => $level,
                            'message' => $message);
        $container->messages = $messages;
    }
    public static function setMessages($messages)
    {
        $container = self::getContainer();
        $container->messages = $messages;
    }
    public static function removeMessages()
    {
        $container = self::getContainer();
        $ret = $container->messages;
        unset($container->messages);
        return (array)$ret;
    }
    private function __construct()
    {
    }
}
