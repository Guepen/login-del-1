<?php

namespace model;

use model\Repository;
use model\User;

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
            else{
                die("Ett oväntat fel inträffade");
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
                return new User($result["username"], $result["password"]);
            }

            return null;

        } catch (\PDOException $e) {
            die("Ett oväntat fel inträffade");
        }
    }

    public function SetCookie($username, $expireTime, $cookiePassword, $cookieUsername){
        try{

            $sql = "UPDATE $this->dbTable SET acces =?, cookiePass =?, cookieUser=? WHERE ". self::$username ."=?";
            $params = array($expireTime, $cookiePassword, $cookieUsername, $username);

            $query = $this->db->prepare($sql);
            $query->execute($params);

        }catch (\PDOException $e){
            die("Fel med cookies i db");
        }
    }

   public function getCookie($name){
       try {

           $sql = "SELECT acces, cookiePass, cookieUser  FROM $this->dbTable WHERE " . self::$username . " =?";
           $params = array($name);

           $query = $this->db->prepare($sql);
           $query->execute($params);

           $result = $query->fetch();
           //var_dump($result);

           if ($result) {
               return $result;
           }

           return null;

       } catch (\PDOException $e) {
           die("Ett oväntat fel inträffade");
       }

   }
}