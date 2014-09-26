<?php

namespace view;
require_once("./src/view/CookieStorage.php");

class loginView{

    private $loginModel;
    private $submitLogin = "submitLogin";
    private $submitLogout = "submitLogout";
    private $KeepMe = "KeepMe";
    private $username = "username";
    private $password = "password";
    private $session = "session";
    private $newUserFormLocation="register";
    private $ret;
    private $cookieStorage;

    public function __construct(\model\loginModel $loginModel){
        $this->loginModel = $loginModel;
        $this->cookieStorage = new \view\CookieStorage();

    }

    public function submitLogin(){
        if(isset($_POST[$this->submitLogin])){
            //var_dump("pressed login");
            return  true;
        }
        return false;
    }

    public function getUserName(){
        if (isset($_POST[$this->username]) == true) {
            return htmlentities($_POST[$this->username]);
        }
    }

    public function getPassword(){
        if ((isset($_POST[$this->password]) == true)) {
            return htmlentities($_POST[$this->password]);
        }
    }

    public function usrCheckedKeepMe(){
        return isset($_POST[$this->KeepMe]);
    }

    public function usrPressedAddNewUser(){
        if(isset($_GET[$this->newUserFormLocation])){
            return true;
        }
        return false;
    }

    public function setRegistrationSuccesMessae(){
        $this->ret = "Registrering av ny användare lyckades";
    }

    public function showLoginView ($loggedIn){
        if ($this->getUserName() == true && $this->getPassword() == true) {
            if ($this->loginModel->isUserLoggedin() == false) {
                $this->ret .= "Felaktigt användarnamn och/eller lösenord ";
            }
        }

        if ( $this->submitLogin() == true) {
            if (empty($_POST[$this->username]) ){
                $this->ret .= "Användarnamn måste anges!";
            }

            if (empty($_POST[$this->password])) {
                $this->ret .= "Lösenordet måste anges!";
            }
        }

        if (isset($_POST[$this->submitLogout]) == true) {
            $this->ret .="Du har nu loggat ut";
        }
        else{
            if ($this->cookieStorage->IsSetCookies() == true && $loggedIn == false) {
                $this->ret .= "Felaktig information i cookie";
                setcookie('loginView::user', "" , time() -1);
                setcookie('loginView::pass' , "" , time() -1);

            }
        }

        $htmlBody = "<h1>Laboration login del 1 - Ej Inloggad</h1>
		             <form action='' method='POST' >
		             <a href='?register' name='newUser'>Registrera ny användare</a>
					 <fieldset>
					 <legend>Login - Skriv in användarnamn och lösenord</legend>
 					 $this->ret
 					 <label>Användarnamn : </label> <input type='text' name='".$this->username."' maxlength='30' value='".$this -> getUserName()."'/>
					 <label>Lösenord : </label><input type='password' name='".$this->password."' maxlength='30'/>
					 <label>Håll mig inloggad : </label><input type='checkbox' name='".$this->KeepMe."'/>
					 <input type='submit' name='".$this->submitLogin."' value='Logga in'/>
					 </fieldset>
					 </form>" ;
        return $htmlBody;
    }


    /**
     * @return bool
     * TODO Check why we return
     */
    public function setCookie(){
        if (isset($_POST[$this->KeepMe]) == true) {
            $this->cookieStorage->save('loginView::user', $this->getUserName(),$this->loginModel->getCookieExpireTime());
            $this->cookieStorage->save('loginView::pass', $this->getCryptPassword() ,$this->loginModel->getCookieExpireTime());

            if(!isset($_COOKIE['loginView::pass'])){
                $_COOKIE['loginView::pass'] = $this->getCryptPassword();
            }

                return true;
        }
        return false;
    }

    public function loadCookie(){

        if(isset($_COOKIE['loginView::pass'])){
            $_COOKIE['loginView::pass'] = $this->getCryptPassword();

            return $_COOKIE['loginView::pass'];
        }
        return false;
    }


    /**
     * @return bool
     * TODO move the unset SESSION to model
     */
    public function ifUsrDontWantKeepAnyMore(){
        if ($this->cookieStorage->issetCookieUsername() == true && $this->cookieStorage->issetCookiePassword() == true) {
            if (isset($_POST[$this->submitLogout]) == true) {
                $this->cookieStorage->save('loginView::user', "" , time() -1);
                $this->cookieStorage->save('loginView::pass' , "" , time() -1);

                unset($_SESSION[$this->session]);
                return true;
            }
            return false;
        }
    }

    public function getCryptPassword(){
        return	crypt(md5($this->getPassword()));
    }


}
