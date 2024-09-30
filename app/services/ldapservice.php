<?php


namespace App\Services;

use LdapRecord\ConnectionException;
use LdapRecord\Models\User;

class LdapService
{
    protected $connection;

    public function __construct()
    {
        $this->connection = app('ldap');
    }
    public function checkConnection()
    {
        try {
            // LDAP'a basit bir bağlanma testi yapıyoruz
            $this->connection->connect();
            return true;
        } catch (ConnectionException $e) {
            return false;
        }
    }
    public function createUser($data)
    {
        $user = new User();
        $user->fill($data);
        return $user->save();
    }

    public function deleteUser($username)
    {
        $user = User::findBy('samaccountname', $username);
        return $user ? $user->delete() : false;
    }

    public function searchUser($username)
    {
        return User::findBy('samaccountname', $username);
    }

    public function recentlyCreatedUsers($limit = 10)
    {
        return User::query()->orderBy('whenCreated', 'DESC')->limit($limit)->get();
    }
}
