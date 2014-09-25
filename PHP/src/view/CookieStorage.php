<?php

namespace view;

class CookieStorage{
    public function save($name, $value, $expire){
        setcookie($name, $value, $expire);

    }

    public function load($name){
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }
    }

    public function delete($name){

    }

    public function getCookieUsername(){
        if (isset($_COOKIE['loginView::user'])) {
            var_dump("redffws");
            return $_COOKIE['loginView::user'];
        }
    }

    public function getCookiePassword(){
        if ($this->issetCookiePassword() == true) {
            return $_COOKIE['loginView::pass'];
        }
    }

    public function issetCookieUsername(){
        if (isset($_COOKIE['loginView::user'])) {
            return true;
        }
        return false;
    }

    public function issetCookiePassword(){
        if (isset($_COOKIE['loginView::pass'])) {
            return true;
        }
        return false;
    }

    public function IsSetCookies(){
        if ($this->issetCookiePassword() == true
            && $this->issetCookieUsername() == true) {
            return true;
        }
        return false;
    }
}