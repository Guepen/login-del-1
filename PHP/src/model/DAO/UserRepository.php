<?php

namespace model;

require_once ('./src/model/DAO/Repository.php');

class UserRepository extends \model\Repository {
    private static $username = 'username';
    private  static $password = 'password';
    public function __construct(){
        $this->dbTable = 'user';
    }

    public function add(User $user){
        try{
            $db = $this->connection();

            $sql = "INSERT INTO $this->dbTable (" . self::$username . ", " . self::$password . ") VALUES (?,?)";
            $params = array($user->getUsername(), $user->getPassword());

            $query = $db->prepare($sql);
            $query->execute($params);

        }catch (\PDOException $e){
            /**
             * if a user already has wished username
             */
            if($e->getCode() === "23000"){
                throw new \model\UserExistsException();
            }
        }
    }

    public function getUser($username){
        $db = $this->connection();

        $sql = "SELECT * FROM $this->dbTable WHERE " . self::$username ." =?";
        $params = array($username);

        $query = $db->prepare($sql);
        $query->execute($params);

        $result = $query->fetch();

        if($result){
            return new \model\User($result["username"], $result["password"]);
        }

        return null;
    }
}