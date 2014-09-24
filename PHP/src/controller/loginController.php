<?php
	
	namespace controller;

require_once("./src/view/loginView.php");
	require_once("./src/view/logOutView.php");
    require_once("./src/view/NewUserView.php");
	require_once("./src/model/loginModel.php");
    require_once("./src/model/DAO/User.php");
    require_once("./src/model/DAO/UserRepository.php");
    require_once("./src/model/Exceptions/UsernameAndPasswordToShortException.php");
    require_once("./src/model/Exceptions/PasswordToShortException.php");
    require_once("./src/model/Exceptions/UsernameToShortException.php");
    require_once("./src/model/Exceptions/PasswordsDontMatchException.php");
    require_once("./src/model/Exceptions/UserExistsException.php");
    require_once("./src/model/Exceptions/ProhibitedCharacterInUsernameException.php");


class loginControll{

		private $loginView;
		private $loginModel;
		private $logOutView;
        private $newUserView;
		private $loggedIn = false;




		public function __construct(){
			$this->loginModel = new \model\loginModel();
			$this->loginView = new \view\loginView($this->loginModel);
			$this->logOutView = new \view\logOutView($this->loginModel);
            $this->newUserView = new \view\NewUserView();
		}




		private function getUsrAndPass(){

			$pass = $this->loginView->getPassword();
			$user = $this->loginView->getUserName();
			$userCookie = $this->loginView->getCookieUsername();
 			$passCookie = $this->loginView->getCookiePassword();
 			$CookieTimeNow = time();
			return $this->loginModel-> checkInput($user , $pass, $userCookie, $passCookie , $CookieTimeNow);
		}




 		public function isUsrLoggedOut(){
 			if ($this->logOutView->SubmitLogout() == true) {
 				 $this->loginModel->logout();
 			}
 		}
 			



 		public function didLoginThisRequest(){
			if ($this->getUsrAndPass() == true) {
				if ($this->loginView->usrCheckedit() == true) {
						$this->loginView->ifUsrWantToKeepUsrAPass();
					}
				return true;
			} 	
			return false;
 		}




		public function displayLogin(){
			$this->isUsrLoggedOut();
			if ($this->loginView->ifUsrDontWantKeepAnyMore() == true) {
				   return $this->loginView->showLoginView($this->loggedIn);
			}

			if ($this->loginView->submitLogin() == true 
				&& $this->loginModel->isUserLoggedin() == true	
						|| $this->loginView->IsSetCookies() == true
						 && $this->loginModel->isUserLoggedin() == true) {

					$this->loggedIn;
						
			}
			else
			{
					$this->loggedIn = $this->didLoginThisRequest();
			}

            if($this->newUserView->usrHasPressedBackToLogin()){
                return $this->loginView->showLoginView($this->loggedIn);
            }

            if($this->newUserView->usrHasPressedRegister()){
                try {
                    if ( $this->loginModel->validateNewUser($this->newUserView->getUserName(), $this->newUserView->getPassword(),
                        $this->newUserView->getPassword2())) {
                        $user = new \model\User($this->newUserView->getUserName(), $this->newUserView->getPassword());
                        $userRepository = new \model\UserRepository();
                        $userRepository->add($user);
                        $this->loginView->setRegistrationSuccesMessae();
                        return $this->loginView->showLoginView($this->loggedIn);


                    }

                }catch (\model\usernameAndPasswordToShortException $e){
                    $this->newUserView->setUsernameAndPasswordToShortMessage();
                    return $this->newUserView->showNewUserForm();
                }catch(\model\PasswordToShortException $e){
                    $this->newUserView->setToShortPasswordMessage();
                    return $this->newUserView->showNewUserForm();
                }catch(\model\UsernameToShortException $e){
                    $this->newUserView->setToShortUsernameMessage();
                    return $this->newUserView->showNewUserForm();
                }catch(\model\PasswordsDontMatchException $e){
                    $this->newUserView->setPasswordsDontMatchMessage();
                    return $this->newUserView->showNewUserForm();
                }catch(\model\UserExistsException $e){
                    $this->newUserView->setUserExistsMessage();
                    return $this->newUserView->showNewUserForm();
                }catch(\model\ProhibitedCharacterInUsernameException $e){
                    $this->newUserView->setProhibitedCharacterMessage();
                    return $this->newUserView->showNewUserForm();
                }
            }

            if($this->loginView->usrPressedAddNewUser()){
                return $this->newUserView->showNewUserForm();

            }


			if ($this->loginModel->isUserLoggedin() == true) {
				return  $this->logOutView->showlogOutView($this->loggedIn);
			}
			else
			{
				return $this->loginView->showLoginView($this->loggedIn);
			}
		}

	}

?>
