<?php

namespace controller;

use controller\RegisterController;
use model\loginModel;
use model\MissingPasswordException;
use model\MissingUsernameException;
use model\UserRepository;
use model\WrongUserinformation;
use model\WrongUserinformationException;
use view\CookieStorage;
use view\loginView;
use view\logOutView;
use view\NewUserView;

require_once("./src/view/loginView.php");
require_once("./src/view/logOutView.php");
require_once("./src/view/NewUserView.php");
require_once("./src/model/loginModel.php");
require_once("./src/controller/RegisterController.php");
require_once("./src/view/CookieStorage.php");
require_once("./src/model/DAO/User.php");
require_once("./src/model/DAO/UserRepository.php");
require_once("./src/model/Exceptions/MissingPasswordException.php");
require_once("./src/model/Exceptions/MissingUsernameException.php");
require_once("./src/model/Exceptions/WrongUserinformationException.php");

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
        $this->loginModel = new loginModel();
        $this->loginView = new loginView($this->loginModel);
        $this->logOutView = new logOutView($this->loginModel);
        $this->newUserView = new NewUserView();
        $this->userRepository = new UserRepository();
        $this->registerControl = new RegisterController();
        $this->cookieStorage = new CookieStorage();
    }

    public function doControl(){
        // $this->doLoginCookie();
        $this->render();
    }

    private function getUsrAndPass(){
        $this->password = $this->loginView->getPassword();
        $this->username = $this->loginView->getUserName();
    }


    public function isUsrLoggedOut(){
        if ($this->logOutView->submitLogout() == true) {
            $this->loginModel->logout();
            return true;
        }
        return false;
    }


    public function render(){
        if ( $this->isUsrLoggedOut()) {
            return $this->loginView->showLoginView($this->loggedIn);
        }

        if($this->loginModel->isUserLoggedin()){
            return $this->logOutView->showlogOutView($this->loggedIn);
        }
        //TODO make this ceheck in a new function
        if ($this->cookieStorage->cookiesIsSet()) {
            try {
                $cookie = $this->userRepository->getCookie($this->loginView->loadUsernameCookie());
                if (time() < $cookie['acces']) {
                    if ($this->loginModel->doLogInCookie($cookie['cookieUser'], $cookie['cookiePass'],
                        $this->loginView->loadUsernameCookie(), $this->loginView->loadPasswordCookie())) {
                        $this->logOutView->setLoggedInWithCookiesMessage();
                        return $this->logOutView->showlogOutView($this->loggedIn);

                    } else{
                        $this->loginView->setWrongInformationInCookieMessage();
                    }
                }
                else{
                    $this->loginView->setWrongInformationInCookieMessage();
                }
            } catch (\Exception $e) {
            }

        }

        if ($this->loginView->submitLogin() == true) {
            $this->getUsrAndPass();

            try {
                if ($this->loginModel->validateInput($this->username, $this->password)) {
                    $dbUser = $this->userRepository->getUser($this->username);

                    if ($dbUser !== NULL) {
                        if ($this->loginModel->doLogIn($this->username, $this->password, $dbUser->getUsername(),
                            $dbUser->getPassword())
                        ) {
                            $this->userDidPressLogin();
                            return $this->logOutView->showlogOutView($this->loggedIn);
                        }
                    }
                }
            } catch (MissingUsernameException $e) {
                $this->loginView->setMissingUsernameMessage();
            } catch(MissingPasswordException $e){
                $this->loginView->setMissingPasswordMessage();
            } catch(WrongUserinformationException $e){
                $this->loginView->setWrongUserinformationMessage();
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
            $this->userRepository->SetCookie($this->username, $this->loginModel->getCookieExpireTime(),
                $this->loginView->loadPasswordCookie(), $this->loginView->getUserName());
        }
    }

}

