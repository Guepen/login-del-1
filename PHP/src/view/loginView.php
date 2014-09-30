<?php

namespace view;
use model\loginModel;
use view\CookieStorage;

require_once("./src/view/CookieStorage.php");

class loginView{

    private $loginModel;
    private $submitLogin = "submitLogin";
    private $KeepMe = "KeepMe";
    private $username = "username";
    private $password = "password";
    private $newUserFormLocation="register";
    private $ret;
    private $cookieStorage;

    public function __construct(loginModel $loginModel){
        $this->loginModel = $loginModel;
        $this->cookieStorage = new CookieStorage();
    }

    public function submitLogin(){
        if(isset($_POST[$this->submitLogin])){
            return  true;
        }
        return false;
    }

    public function getUserName(){
        if (isset($_POST[$this->username]) == true) {
            return htmlentities($_POST[$this->username]);
        }
        return null;
    }

    public function getPassword(){
        if (isset($_POST[$this->password]) == true) {
            return htmlentities($_POST[$this->password]);
        }
        return null;
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

    public function setWrongInformationInCookieMessage(){
        $this->ret = "Fel information i cookies";
    }

    public function setMissingUsernameMessage(){
        $this->ret = "Användarnamn saknas";
    }

    public function setMissingPasswordMessage(){
        $this->ret = "Lösenord saknas";
    }

    public function setWrongUserinformationMessage(){
        $this->ret = "Felaktigt användarnamn och/eller lösenord";
    }

    public function showLoginView (){

        $htmlBody = "<h1>Laborationskod th222fa</h1>
                     <a href='?register' name='newUser'>Registrera ny användare</a>
                     <h3>Ej inloggad</h3>
		             <form action='?login' method='POST' >
					 <fieldset>
					 <legend>Login - Skriv in användarnamn och lösenord</legend>
 					 <p>$this->ret</p>
 					 <label>Användarnamn : </label> <input type='text' name='".$this->username."' maxlength='30' value='".$this -> getUserName()."'/>
					 <label>Lösenord : </label><input type='password' name='".$this->password."' maxlength='30'/>
					 <label>Håll mig inloggad : </label><input type='checkbox' name='".$this->KeepMe."'/>
					 <input type='submit' name='".$this->submitLogin."' value='Logga in'/>
					 </fieldset>
					 </form>" ;

        return $htmlBody;
    }

    public function setCookie(){
        if (isset($_POST[$this->KeepMe]) == true) {
            $this->cookieStorage->save('loginView::pass', $this->loginModel->getCryptPassword(), $this->loginModel->getCookieExpireTime());
            $this->cookieStorage->save('loginView::user', $this->getUserName(), $this->loginModel->getCookieExpireTime());

        }
    }

    public function loadPasswordCookie(){
       return $this->cookieStorage->load('loginView::pass');
    }

    public function loadUsernameCookie(){
        return $this->cookieStorage->load('loginView::user');
    }

}
