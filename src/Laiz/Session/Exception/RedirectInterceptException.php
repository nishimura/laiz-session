<?php

namespace Laiz\Session\Exception;

use Laiz\Request\Exception\RedirectException;
use Zend\Session\Container;

class RedirectInterceptException extends RedirectException
{
    private static function getContainer()
    {
        return new Container('Laiz_Session_Auth_Exception');
    }

    public function __construct($target, $current)
    {
        parent::__construct($target);

        $container = self::getContainer();
        if ($current instanceof \Zend\Uri\Uri){
            $current = $current->toString(); // for save session
        }
        $container->interceptUri = $current;
    }

    public static function resumeUri($defaultUri, $msg, $level)
    {
        $container = self::getContainer();
        if ($container->interceptUri){
            $uri = $container->interceptUri;
            unset($container->interceptUri);
            throw new RedirectMessageException($uri, $msg, $level);
        }else{
            throw new RedirectMessageException($defaultUri, $msg, $level);
        }
    }
}
