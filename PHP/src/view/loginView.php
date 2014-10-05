<?php

namespace view;
use model\loginModel;
use view\CookieStorage;

/**
 * Class loginView
 * @package view
 */
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

    public function setLoggedOutMessage(){
        $this->ret = "Du är nu utloggad";
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

    /**
     * @return string with HTML
     */
    public function showLoginView (){

        $htmlBody = "
                     <a href='?register'>Registrera ny användare</a>
                     <h3>Ej inloggad</h3>
		             <form action='?login' class='form-horizontal' method='POST' >
					 <fieldset>
					 <legend>Login - Skriv in användarnamn och lösenord</legend>
 					 <p>$this->ret</p>
 					 <div class='form-group'>
 					 <label class='col-sm-2 control-label'>Användarnamn : </label>
 					 <div class='col-sm-10'>
 					 <input placeholder='Skriv in ditt användarnamn' class='form-control' type='text'
 					 name='".$this->username."' maxlength='30' value='".$this -> getUserName()."'/>
 					 </div>
 					 </div>
 					 <div class='form-group'>
					 <label class='col-sm-2 control-label'>Lösenord : </label>
					 <div class='col-sm-10'>
					 <input placeholder='Skriv in ditt lösenord' class='form-control' type='password'
					 name='".$this->password."' maxlength='30'/>
					 </div>
					 </div>
					  <div class='form-group'>
				      <div class='col-sm-offset-2 col-sm-10'>
				      <div class='checkbox'>
					 <label>
					    <input class='checkbox' type='checkbox'  name='".$this->KeepMe."'/> Håll mig inloggad
					 </label>
					  </div>
					  </div>
					  </div>
					  <div class='form-group'>
				        <div class='col-sm-offset-2 col-sm-10'>
					 <input type='submit' class='btn btn-default' name='".$this->submitLogin."' value='Logga in'/>
					 </div>
					 </div>
					 </fieldset>
					 </form>" ;

        return $htmlBody;
    }

    public function setCookie(){
        if (isset($_POST[$this->KeepMe]) == true) {
            $this->cookieStorage->save('loginView::pass', $this->loginModel->getCryptPassword(),
            $this->loginModel->getCookieExpireTime());

            $this->cookieStorage->save('loginView::user', $this->getUserName(),
            $this->loginModel->getCookieExpireTime());
        }
    }

    public function loadPasswordCookie(){
       return $this->cookieStorage->load('loginView::pass');
    }

    public function loadUsernameCookie(){
        return $this->cookieStorage->load('loginView::user');
    }

}
