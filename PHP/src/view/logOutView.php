<?php

namespace view;	

class logOutView{

 	private $loginModel;
 	private $loginView;
    private $cookieStorage;
 	private $submitLogout = "submitLogout";

 	public function ShowlogOutView($loggedIn){
        $username = $this->loginModel->getUsername();
 		$ret ="";
 		if ($this->loginView->usrCheckedKeepMe() == false
 			&& $this->loginView->getUserName() == true
 		 	&& $this->loginView->getPassword() == true) {
 			$ret .= "Inloggning lyckades!";	
 		}
 	

 		if ($this->loginView->usrCheckedKeepMe() == true
 			&& $this->loginView->submitLogin() == true 
 			&& $this->loginModel->isUserLoggedin() == true) {
 			$ret .= "Inloggning lyckades och vi kommer ihåg dig nästa gång";
	 	}

 		if ($this->cookieStorage->IsSetCookies() == true
 			 && $loggedIn == true) {
 			$ret .= "Inloggning lyckades via cookies";
 		}
 		
 		$LoggedInForm = "
 		<form action='' method='POST' >
 				 <h1>Laboration login del 1</h1>
 				<h3> $username</h3>
 				 $ret
 				 </br>
 				 </br>
 				 <input type='submit' name='".$this->submitLogout."' value='Logga ut'/>
 				 </br>
 				 </br>
 				 </form>";
 		return $LoggedInForm;	
	 }

 	public function __construct(\model\loginModel $loginModel){
 		$this->loginModel = $loginModel;	
 		$this->loginView = new \view\loginView($this->loginModel);
        $this->cookieStorage = new \view\CookieStorage();
 	}




 	public function SubmitLogout(){ 	
	 	if (isset($_POST[$this->submitLogout]) == true) {

 			return true;
 		}
 			return false;
 	}

}	