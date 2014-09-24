<?php

namespace model;
	
class loginModel {

	private $username;
	private $password;
	private $PasswordCookieFromFile;
	private $DateCookieFromFile;
	private $lines = "config.txt";
	private $linesWrite = "Cookie.txt";
	private $lineWriteCookieTime = "CookieTime.txt";
	private $session = "session";


	public function __construct(){
		@session_start();
		$this->OpenTextFile();
		$this->OpenTextFileToRead();
		$this->OpenTextFileToReadDate();
	}




	public function OpenTextFile(){
		
		$fp = fopen($this->lines, "r");
		$fr = fread($fp, 13);
		$this->username = substr($fr, 0,5);
		$this->password = substr($fr, 5);
	}




	public function OpenTextFileToWrite($cryptPass , $CookieTimeNow){
		
		$fo = fopen($this->linesWrite, "w");
		$fw = fwrite($fo, $cryptPass);
		fclose($fo);

		
		$fopen = fopen($this->lineWriteCookieTime, "w");
		$fwt = fwrite($fopen, $CookieTimeNow);
		fclose($fopen);
	}




	public function OpenTextFileToRead(){
		if ($this->IfEmptyPasswordFile() > 0) {
			
			$fo = fopen($this->linesWrite, "r");
			$fr = fread($fo, filesize($this->linesWrite));
			fclose($fo);
			$this->PasswordCookieFromFile = $fr;
		}
		return;
	}




	public function OpenTextFileToReadDate(){
		if ($this->IfEmptyDateFile() > 0) {

			$fp = fopen($this->lineWriteCookieTime, "r");
			$fr = fread($fp, filesize($this->lineWriteCookieTime));
			fclose($fp);
			$this->DateCookieFromFile = $fr;
		}
		return;
	}




	public function IfEmptyPasswordFile(){
		$checkPasswordFile = @file($this->linesWrite);
		if ($checkPasswordFile === false) {
			return 0;
		}
		return count($checkPasswordFile);
	}




	public function IfEmptyDateFile(){
		$checkDateFile = @file($this->lineWriteCookieTime);
		if ($checkDateFile == false) {
			return 0;
		}
		return count($checkDateFile);
	}




	public function isUserLoggedin(){
		if (isset($_SESSION[$this->session]) == true) {
			return true;
		}
		return false;
 	}


	public function checkInput($user , $pass , $userCookie, $passCookie , $CookieTimeNow){

		if (($user == $this->username && $pass == $this->password) == true
						|| $passCookie == $this->PasswordCookieFromFile && 
										 $userCookie == $this->username &&
							$CookieTimeNow < (int)$this->DateCookieFromFile ){

				 $_SESSION[$this->session] = true;
				 return true;
		}
		return false;
	}


    public function validateNewUser($username, $password, $password2){
        $regex = '/^[a-z0-9]+$/i';
        if(mb_strlen($username) < 3 && mb_strlen($password) < 6 && mb_strlen($password2) < 6){
            throw new \model\UsernameAndPasswordToShortException();
        }

        else if(mb_strlen($password) < 6 && mb_strlen($password2) < 6){
            throw new \model\PasswordToShortException();
        }

        else if(mb_strlen($username) < 3 && mb_strlen($password) > 5 && mb_strlen($password2) > 5){
            throw new \model\UsernameToShortException();
        }

        else if($password !== $password2){
            //echo"dont mtach";
            throw new \model\PasswordsDontMatchException();
        }

        else if(!preg_match('/^[a-z0-9]+$/i', $username)){
            throw new \model\ProhibitedCharacterInUsernameException();
        }

        else if(mb_strlen($username) > 2 && mb_strlen($password) > 5 && mb_strlen($password2) >5 && $password === $password2){
            return true;
        }
    }


	public function logout(){
		unset($_SESSION[$this->session]);	
	}

}


?>