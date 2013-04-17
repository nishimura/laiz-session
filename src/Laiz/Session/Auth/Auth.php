<?php

namespace Laiz\Session\Auth;

use Zend\Authentication\AuthenticationService;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Session\Container;
use Laiz\Session\Exception\RedirectInterceptException;
use Laiz\Session\Exception\InitializationException;
use Laiz\Session\Message;

class Auth
{
    private $loginUri;
    private $service;

    private $filterMessage = 'Required login.';

    public function __construct(AuthenticationService $zendAuth)
    {
        $this->service = $zendAuth;
    }
    private function getContainer()
    {
        return new Container('Laiz_Session_Auth');
    }
    public function setLoginUri($uri)
    {
        $this->loginUri = $uri;
    }
    public function setFilterMessage($message)
    {
        $this->filterMessage = $message;
    }

    public function filter(Request $request)
    {
        if (!$this->loginUri)
            throw new InitializationException('Un initialized loginUri');
        if (!$this->service->hasIdentity()){
            Message::add($this->filterMessage);
            throw new RedirectInterceptException($this->loginUri,
                                                 $request->getUri());
        }
    }

    public function login($id, $password)
    {
        $adapter = $this->service->getAdapter();
        $adapter->setIdentity($id);
        $adapter->setCredential($password);

        return $this->service->authenticate();
    }

    public function logout()
    {
        $this->service->clearIdentity();
    }

    public function resumeUri($defaultUri, $msg = null, $level = null)
    {
        RedirectInterceptException::resumeUri($defaultUri, $msg, $level);
    }
}
