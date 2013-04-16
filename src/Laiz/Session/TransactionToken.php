<?php

namespace Laiz\Session;

use Zend\Session\Container;
use Zend\Math\Rand;

class TransactionToken
{
    public $check; // receive from request
    public $token; // will check in next page
    public function __construct()
    {
        $this->token = Rand::getString(32);
        $container = self::getContainer();
        $container->prev = $container->check;
        $container->check = $this->token;
    }

    private static function getContainer()
    {
        return new Container('Laiz_Session_TransactionToken');
    }
    public static function getCheck()
    {
        return self::getContainer()->prev;
    }
}
