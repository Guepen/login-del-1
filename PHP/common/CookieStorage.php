<?php

namespace view;

use model\loginModel;

class CookieStorage{
    private $name;
    private $loginModel;

    public function __construct(){
        $this->loginModel = new loginModel();
    }

    public function save($name, $value, $expire){
        $this->name = $name;
        setcookie($name, $value, $expire);

        /**
         * Beacuse the cookie isnt set before the page reloads
         * i do this workaround so i can get the cookie and save it to the database
         * when its created
         */
        if(!isset($_COOKIE[$name])){
            $_COOKIE[$name] = $value;
        }
    }

    public function load($name){
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }
        return false;
    }


    public function cookiesIsSet(){
        if (isset($_COOKIE['loginView::pass']) && isset($_COOKIE['loginView::user'])) {
            return true;
        }
        return false;
    }


}