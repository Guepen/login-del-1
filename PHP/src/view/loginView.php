<?php

namespace view;

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

	public function __construct(\model\loginModel $loginModel){
		$this->loginModel = $loginModel;

	} 



	private function pressLogIn(){
		if (isset($_POST[$this->submitLogin]) == true) {
			return true;	
		}
			return false;
	}



	public function submitLogin(){
        if(isset($_POST[$this->submitLogin])){
            //var_dump("pressed login");
		return  true;
        }
        return false;
	}



	private function  userName(){
		if (isset($_POST[$this->username]) == true) {
			return true;
		}
			return false;
	}



	private function password(){
		if (isset($_POST[$this->password]) == true) {
			return true;
		}
			return false;
	}



	public function getUserName(){
		if ($this->userName() == true) {
			return htmlentities($_POST[$this->username]);
		}
	}


 	public function getPassword(){
		if ($this->password() == true) {
			return htmlentities($_POST[$this->password]);
		}
	}


  	private function didUsrCheKeepMe(){
		if (isset($_POST[$this->KeepMe])) {
			return true; 
		}
			return false;
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
	     	if ($this->userName() == empty($_POST[$this->username]) ){
				$this->ret .= "Användarnamn måste anges!";
			}

			if ($this->password() == empty($_POST[$this->password])) {	
				$this->ret .= "Lösenordet måste anges!";
			}
		}

		if (isset($_POST[$this->submitLogout]) == true) {
			$this->ret .="Du har nu loggat ut";
		}
		else{
				if ($this->IsSetCookies() == true && $loggedIn == false) {
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


	public function setCookie(){
		$currentTime = time();
		$userCookie = $this->getUserName();
		$passCookie = $this->getCryptPassword();
	    if (isset($_POST[$this->KeepMe]) == true) {		
								
			 	setcookie('loginView::user', $userCookie,$this->getCookieTime());
		 	 	setcookie('loginView::pass', $passCookie ,$this->getCookieTime());
		 	 	$this->loginModel->OpenTextFileToWrite($passCookie, $this->getCookieTime());
		  	    return true;
		}
				return false;
	}




	public function ifUsrDontWantKeepAnyMore(){
		if ($this->issetCookieUsername() == true && $this->issetCookiePassword() == true) {
			if (isset($_POST[$this->submitLogout]) == true) {
				setcookie('loginView::user', "" , time() -1);
				setcookie('loginView::pass' , "" , time() -1);
				unset($_SESSION[$this->session]);
				return true;
				}
				return false;
			}
		}

		public function getCryptPassword(){
 		return	crypt(md5($this->getPassword()));	
 	}


 	public function getCookieTime(){

 		return time()+60;		

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


	public function getCookieUsername(){
		if ($this->IsSetCookies() == true) {
			
			return $_COOKIE['loginView::user'];
		}
	}

	public function getCookiePassword(){
		if ($this->issetCookiePassword() == true) {
		
			return $_COOKIE['loginView::pass'];
		}
	}
}

?>