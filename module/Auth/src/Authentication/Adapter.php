<?php


namespace Auth\Authentication;

use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver;
use Laminas\Db\Adapter\Platform;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Storage\SessionStorage;
use Laminas\Session\Storage\StorageInterface;
use User\Model\User;
use User\Model\UserTable;

class Adapter implements AdapterInterface
{
    protected $email;
    protected $password;
    private $userTable;

    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        /**
         *@var $user User
         */

        if ($user = $this->userTable->getUserByEmail($this->email)) {
            if ($user->email_confirmed != 1) {

                return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [
                    'Você ainda não validou seu cadastro, verifique seu e-mail para validar.'
                ]);
            }
            if ($user->token != null) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [
                'Parece que alguem tentou recuperar a sua senha.
            Para a sua seguraça, verifique seu email e crie uma nova senha.'
            ]);
            }

            if ((new Bcrypt())->verify($this->password, $user->password)) {
                return new Result(Result::SUCCESS, $user);
            } else {
                return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [
                    'A senha informada não confere, favor reiniciar processo de autenticação.']);
            }
        } else {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, null, [
                    'E-mail informado não está armazenado na base.']);
        }
    }

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }
    public function getStorage()
    {
        if (null === $this->storage) {
            $this->setStorage(new SessionArrayStorage());
        }

        return $this->storage;
    }

    public function clearIdentity()
    {
        $this->getStorage()->clear();
    }

    public function getDriver()
    {
        // TODO: Implement getDriver() method.
    }

    public function getPlatform()
    {
        // TODO: Implement getPlatform() method.
    }
}