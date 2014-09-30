<?php

namespace view;	

use model\loginModel;
use view\CookieStorage;
use view\loginView;

class logOutView{

 	private $loginModel;
 	private $loginView;
    private $cookieStorage;
 	private $submitLogout = "submitLogout";
    private $ret = "";

    public function __construct(){
        $this->loginModel = new loginModel();
        $this->loginView = new loginView($this->loginModel);
        $this->cookieStorage = new CookieStorage();
    }

 	public function ShowlogOutView(){
        $username = $this->loginModel->getUsername();
 		if ($this->loginView->usrCheckedKeepMe() == false
 			&& $this->loginView->getUserName() == true
 		 	&& $this->loginView->getPassword() == true) {
 			$this->ret .= "Inloggning lyckades!";
 		}

 		if ($this->loginView->usrCheckedKeepMe() == true
 			&& $this->loginView->submitLogin() == true 
 			&& $this->loginModel->isUserLoggedin() == true) {
 			$this->ret .= "Inloggning lyckades och vi kommer ihåg dig nästa gång";
	 	}

 		$LoggedInForm = "
 		<form action='' method='POST' >
 				 <h1>Laboration login del 1</h1>
 				<h3> $username</h3>
 				 $this->ret
 				 </br>
 				 </br>
 				 <input type='submit' name='".$this->submitLogout."' value='Logga ut'/>
 				 </br>
 				 </br>
 				 </form>";
 		return $LoggedInForm;	
	 }

    public function setLoggedInWithCookiesMessage(){
        $this->ret = "inloggad med cookies";
    }

    public function deleteCookies(){
        $this->cookieStorage->save('loginView::pass' , "" , time() -1);
        $this->cookieStorage->save('loginView::user' , "" , time() -1);
    }

 	public function submitLogout(){
	 	if (isset($_POST[$this->submitLogout]) == true) {
            $this->deleteCookies();
 			return true;
 		}
 			return false;
 	}

}	