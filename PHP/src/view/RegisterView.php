<?php

namespace view;

class RegisterView{
    private $username;
    private $password;
    private $password2;
    private $message;
    private $loginFormLocation = "login";

    public function __construct(){}

    public function showNewUserForm(){

        $html = "
                     <h1>Laborationskod th222fa</h1>
                     <a href='?login'>Tillbaka</a>
                     <H1>Ej inloggad, registrear användare</H1>
                     <form method='post'>
                     <fieldset>
                     $this->message
					 <legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>
 					 </br>
 					 <label>Användarnamn : </label> <input type='text' name='username' value='$this->username' maxlength='30'/>
 					 <p></p>
					 <label>Lösenord : </label><input type='password' name='password' maxlength='30'/>
					 <p></p>
					 <label>Repetera Lösenord : </label><input type='password' name='password2' maxlength='30'/>
					 <p></p>
					 <input type='submit' name='submit' value='Registrera'/>
					 </fieldset>
					 </br>
					 </form>" ;

        return $html;

    }

    public function setToShortUsernameMessage(){
        $this->message = "Användarnamnet har för få tecken. Minst 3 tecken";
    }

    public function setUsernameAndPasswordToShortMessage(){
        $this->message = " Användarnamnet har för få tecken. Minst 3 tecken. <p>Lösenorden har för få tecken. Minst 6 tecken</p>";
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
    }

    public function getPassword2(){
        if(isset($_POST['password2'])){
            return $this->password2 = $_POST['password2'];
        }
    }
}