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

    public function checkIfCookieExpireTimeIsValid($cookieExpireTime){
        if(time() < $cookieExpireTime){
            return true;
        }
        return false;
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

    public function checkUserAgent($userAgent){
        if(isset($_SESSION['userAgent'])){
            if($userAgent === $_SESSION['userAgent']){
                return true;
            }
        }
        return false;
    }


    public function setUserAgent($userAgent){
        if(isset($_SESSION['userAgent']) == false){
            $_SESSION['userAgent'] = $userAgent;
        }
    }

    public function doLogIn($user , $pass, $dbUser, $dbPassword){
        if ($user === $dbUser && $pass === $dbPassword) {
            $_SESSION['password'] = crypt(md5($pass));
            $_SESSION[$this->session] = $user;
            return true;

        } else {
            throw new WrongUserinformationException();
        }

    }

    public function doLogInCookie($dbUser, $dbPass, $cookieUser, $cookiePassword){
        if($dbUser === $cookieUser && $dbPass === $cookiePassword){
            $_SESSION['password'] = crypt(md5($dbPass));
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
        return $_SESSION['password'];
    }
}

