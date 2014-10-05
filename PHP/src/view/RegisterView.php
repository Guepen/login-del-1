<?php

namespace view;

/**
 * Class RegisterView
 * @package view
 * The view for the register page
 */
class RegisterView{
    private $username;
    private $password;
    private $password2;
    private $message;
    private $loginFormLocation = "login";

    /**
     * @return string with HTML
     */
    public function showNewUserForm(){

        $html = "
                     <a href='?login'>Tillbaka</a>
                     <H3>Ej inloggad, registrear användare</H3>
                     <form method='post' class='form-horizontal'>
                     <fieldset>
					 <legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
					  $this->message

 					 <div class='form-group'>
 					 <label class='col-sm-2 control-label' for='username'>Användarnamn : </label>
 					 <div class='col-sm-10'>
 					 <input placeholder='Skriv in önskat användarnamn' class='form-control' type='text'
 					 name='username' value='$this->username' maxlength='30'/>
 					 </div>
 					 </div>

 					 <div class='form-group'>
					 <label class='col-sm-2 control-label' for='password' >Lösenord : </label>
					 <div class='col-sm-10'>
					 <input placeholder='Skriv in önskat lösenord' class='form-control' type='password'
					 name='password' maxlength='30'/>
					 </div>
					 </div>

					 <div class='form-group'>
					 <label class='col-sm-2 control-label' for='password'  >Repetera Lösenord : </label>
					 <div class='col-sm-10'>
					 <input placeholder='Repetera lösenordet' class='form-control' type='password'
					 name='password2' maxlength='30'/>
					 </div>
					 </div>

					 <div class='form-group'>
					 <div class='col-sm-offset-2 col-sm-10'>
					 <input class='btn btn-default' type='submit' name='submit' value='Registrera'/>
					 </div>
					 </div>

					 </fieldset>
					 </form>" ;

        return $html;

    }

    public function setToShortUsernameMessage(){
        $this->message = "Användarnamnet har för få tecken. Minst 3 tecken";
    }

    public function setUsernameAndPasswordToShortMessage(){
        $this->message = " Användarnamnet har för få tecken. Minst 3 tecken.
                          <p>Lösenorden har för få tecken. Minst 6 tecken</p>";
    }

    public function setToShortPasswordMessage(){
        $this->message = "Lösenorden har för få tecken. Minst 6 tecken";
    }

    public function setPasswordsDontMatchMessage(){
        $this->message = "Lösenorden matchar inte";
    }

    public function usrHasPressedBackToLogin(){
        if(isset($_GET[$this->loginFormLocation])){
            return true;
        }
        return false;
    }

    public function setUserExistsMessage(){
        $this->message = "Användarnamnet är upptaget";
    }

    /**
     * @param $username string filtered username
     */
    public function setProhibitedCharacterMessage($username){
        $this->username = $username;
        $this->message = "Användarnamnet innehåller ogiltliga tecken";
    }

    public function usrHasPressedRegister(){
        if(isset($_POST['submit'])){
            return true;
        }
        return false;
    }

    public function getUserName(){
        if(isset($_POST['username'])){
            return $this->username = $_POST['username'];
        }
        return false;
    }

    public function getPassword(){
        if(isset($_POST['password'])){
           return $this->password = $_POST['password'];
        }
        return false;
    }

    public function getPassword2(){
        if(isset($_POST['password2'])){
            return $this->password2 = $_POST['password2'];
        }
        return false;
    }
}