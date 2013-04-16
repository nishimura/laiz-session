<?php

namespace Laiz\Session\Auth\Adapter;

use Laiz\Db\Db as LaizDb;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;

/**
 * database authentication.
 */
class Db extends AbstractAdapter
{
    private $db;
    private $voName;
    private $idName;
    private $credentialName;

    public function __construct(LaizDb $db)
    {
        $this->db = $db;
    }

    public function setVoName($voName)
    {
        $this->voName = $voName;
    }
    public function setIdName($idName)
    {
        $this->idName = $idName;
    }
    public function setCredentialName($credentialName)
    {
        $this->credentialName = $credentialName;
    }

    public function authenticate()
    {
        // prepare result
        $code = Result::FAILURE_UNCATEGORIZED;
        $identity = $this->identity;
        $messages = array();

        // authenticate used ini file
        $vo = $this->db->from($this->voName)
            ->eq(array($this->idName => $this->identity))
            ->result();
        if (!$vo){
            $code = Result::FAILURE_IDENTITY_NOT_FOUND;
            if ($this->identity === null ||
                strlen(trim($this->identity)) === 0)
                $messages[] = 'Id is empty.';
            else
                $messages[] = 'Id is not found.';
        }else if ($this->credential !== $vo->{$this->credentialName}){
            $code = Result::FAILURE_CREDENTIAL_INVALID;
            $messages[] = 'Supplied credential is invalid';
        }else{
            $code = Result::SUCCESS;
            $messages[] = 'Authentication successfull';
        }
        return new Result($code, $this->identity, $messages);
    }
}
