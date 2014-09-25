<?php

namespace controller;

require_once("./src/view/loginView.php");
require_once("./src/view/logOutView.php");
require_once("./src/view/NewUserView.php");
require_once("./src/model/loginModel.php");
require_once("./src/controller/RegisterController.php");
require_once("./src/view/CookieStorage.php");
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
    private $userRepository;
    private $registerControl;
    private $cookieStorage;
    private $loggedIn = false;
    private $password;
    private $username;

    public function __construct(){
        $this->loginModel = new \model\loginModel();
        $this->loginView = new \view\loginView($this->loginModel);
        $this->logOutView = new \view\logOutView($this->loginModel);
        $this->newUserView = new \view\NewUserView();
        $this->userRepository = new \model\UserRepository();
        $this->registerControl = new \controller\RegisterController();
        $this->cookieStorage = new \view\CookieStorage();
    }

    public function doControl(){
        $this->render();
    }

    private function getUsrAndPass(){
        $this->password = $this->loginView->getPassword();
        $this->username = $this->loginView->getUserName();
    }


    public function isUsrLoggedOut(){
        if ($this->logOutView->SubmitLogout() == true) {
            $this->loginModel->logout();
            //return $this->loginView->showLoginView($this->loggedIn);
        }
    }

    public function render(){
        $this->isUsrLoggedOut();
        if ($this->loginView->ifUsrDontWantKeepAnyMore() == true) {
            return $this->loginView->showLoginView($this->loggedIn);
        }

        if ($this->loginView->submitLogin() == true) {
            $this->getUsrAndPass();
            $dbUser = $this->userRepository->getUser($this->username);

            if ($dbUser !== NULL) {
                if ($this->loginModel->checkInput($this->username, $this->password, $dbUser->getUsername(),
                    $dbUser->getPassword())) {
                    $this->userDidPressLogin();
                    return $this->logOutView->showlogOutView($this->loggedIn);
                }
            }
            $this->loggedIn;
        }

        if($this->loginView->usrPressedAddNewUser()){
            return $this->registerControl->registerControl();

        }

        if ($this->loginModel->isUserLoggedin() == true) {
            return  $this->logOutView->showlogOutView($this->loggedIn);
        }
        else
        {
            return $this->loginView->showLoginView($this->loggedIn);
        }

    }

    public function userDidPressLogin(){
        if ($this->loginView->usrCheckedKeepMe() == true) {
            $this->loginView->setcookie();
            $this->userRepository->SetCookie($this->username, $this->loginModel->getCookieExpireTime());
        }
    }

}

