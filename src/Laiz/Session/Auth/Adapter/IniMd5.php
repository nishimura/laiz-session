<?php

namespace Laiz\Session\Auth\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

/**
 * Simple ini file authentication.
 *
 * make md5: php -r 'echo md5("my-password") . "\n";'
 */
class IniMd5 extends AbstractAdapter
{
    private $configFile = 'config/auth_ini.ini';
    public function setConfigFile($configFile)
    {
        $this->configFile = $configFile;
    }

    public function authenticate()
    {
        // prepare result
        $code = Result::FAILURE_UNCATEGORIZED;
        $identity = $this->identity;
        $messages = array();

        // authenticate used ini file
        $ids = parse_ini_file($this->configFile);

        if (!array_key_exists($this->identity, $ids)){
            $code = Result::FAILURE_IDENTITY_NOT_FOUND;
            if ($this->identity === null ||
                strlen(trim($this->identity)) === 0)
                $messages[] = 'Id is empty.';
            else
                $messages[] = 'Id is not found.';
        }else if (md5($this->credential) !== $ids[$this->identity]){
            $code = Result::FAILURE_CREDENTIAL_INVALID;
            $messages[] = 'Supplied credential is invalid';
        }else{
            $code = Result::SUCCESS;
            $messages[] = 'Authentication successfull';
        }
        return new Result($code, $this->identity, $messages);
    }
}
