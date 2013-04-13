<?php

namespace Laiz\Session;

use Zend\Session\Container;
use Zend\Math\Rand;

class TransactionToken
{
    public $token; // receive from request
    private $next; // will check in next page
    public function __construct()
    {
        $this->next = Rand::getString(32);
        $container = self::getContainer();
        $container->prev = $container->token;
        $container->token = $this->next;
    }

    private static function getContainer()
    {
        return new Container('Laiz_Session_TransactionToken');
    }
    public static function getPrev()
    {
        return self::getContainer()->prev;
    }

    /**
     * Important Notice:
     * Don't use token property in value.
     * Must be use __toString to value attribute and
     * must be use token property to name tag in HTML.
     */
    public function __toString()
    {
        return $this->next;
    }
}
