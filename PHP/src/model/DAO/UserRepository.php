<?php

namespace model;

require_once ('./src/model/DAO/Repository.php');

/**
 * Class UserRepository
 * @package model
 */
class UserRepository extends \model\Repository {
    private static $username = 'username';
    private  static $password = 'password';
    private $db;

    public function __construct(){
        $this->dbTable = 'user';
        $this->db = $this->connection();
    }

    public function add(User $user){
        try{
            $db = $this->connection();

            $sql = "INSERT INTO $this->dbTable (" . self::$username . ", " . self::$password . ") VALUES (?,?)";
            $params = array($user->getUsername(), $user->getPassword());

            $query = $this->db->prepare($sql);
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
        try {

            $sql = "SELECT * FROM $this->dbTable WHERE " . self::$username . " =?";
            $params = array($username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

            $result = $query->fetch();

            if ($result) {
                return new \model\User($result["username"], $result["password"]);
            }

            return null;

        } catch (\PDOException $e) {
            throw new \Exception("Ett oväntat fel inträffade");
        }
    }

    public function SetCookie($username, $expireTime){
        try{

            $sql = "UPDATE $this->dbTable SET acces =? WHERE ". self::$username ."=?";
            $params = array($expireTime, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

        }catch (\PDOException $e){
            die("Fel med cookies i db");
        }
    }
}