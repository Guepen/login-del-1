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
            if($e->getCode() === "23000"){
                throw new \model\UserExistsException();
            }
        }
    }
}