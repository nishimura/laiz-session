<?php


namespace Laiz\Session\Validator;

use Laiz\Session\TransactionToken as Token;
use Zend\Validator\AbstractValidator;
use Zend\Stdlib\RequestInterface;

class TransactionToken extends AbstractValidator
{
    const INVALID = 'transactionTokenInvalid';
    public function isValid($value)
    {
        $token = Token::getCheck();
        if ($value === null ||
            strlen(trim($value)) === 0)
            return false;

        if ($token !== $value){
            $this->error(self::INVALID);
            return false;
        }
        return true;
    }
}
