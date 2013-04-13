<?php

namespace Laiz\Session\Exception;

use Laiz\Request\Exception\RedirectException;
use Laiz\Session\Message;
use Zend\Session\Container;

class RedirectMessageException extends RedirectException
{
    public function __construct($target, $message, $level = null)
    {
        parent::__construct($target);
        Message::add($message, $level);
    }
}
