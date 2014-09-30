<?php

namespace model;

use model\MissingPasswordException;
use model\MissingUsernameException;
use model\WrongUserinformationException;

class loginModel {

    private $session = "session";

    public function getCookieExpireTime(){
        return time()+200;
    }

    public function isUserLoggedin(){
        if (isset($_SESSION[$this->session]) == true) {
            return true;
        }
        return false;
    }

    public function validateInput($username, $password){
        if(empty($username) || empty($username) && empty($password)){
            throw new MissingUsernameException();
        }

        else if(empty($password)){
            throw new MissingPasswordException();
        }

        else{
            return true;
        }

    }

    public function doLogIn($user , $pass, $dbUser, $dbPassword){
        if (($user == $dbUser && $pass == $dbPassword) === true ){
            $_SESSION['password'] = $pass;
            $_SESSION[$this->session] = $user;
            return true;
        }

        else{
            throw new WrongUserinformationException();
        }

    }

    public function doLogInCookie($dbUser, $dbPass, $cookieUser, $cookiePassword){
        if($dbUser === $cookieUser && $dbPass === $cookiePassword){
            $_SESSION['password'] = $dbPass;
            $_SESSION[$this->session] = $dbUser;
            return true;
        }
        return false;
    }

    public function logout(){
        unset($_SESSION[$this->session]);
    }

    public function getUsername(){
        return $_SESSION[$this->session];
    }

    public function getCryptPassword(){
        return	crypt(md5($_SESSION['password']));
    }
}

