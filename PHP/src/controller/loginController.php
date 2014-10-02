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
use view\RegisterView;
use view\UserAgent;

class LoginControl{

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
        $this->newUserView = new RegisterView();
        $this->userRepository = new UserRepository();
        $this->registerControl = new RegisterController();
        $this->cookieStorage = new CookieStorage();
    }

    public function doControl(){
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

    public function createSessionUserAgent(){
        $userAgent = new UserAgent();
        $this->loginModel->setUserAgent($userAgent->getUserAgent());
    }

    public function render(){
        if ( $this->isUsrLoggedOut()) {
            $this->loginView->setLoggedOutMessage();
            $this->logOutView->deleteCookies();
            return $this->loginView->showLoginView($this->loggedIn);
        }

        if ($this->loginModel->isUserLoggedin() == true) {
            $userAgent = new UserAgent();
            if ($this->loginModel->checkUserAgent($userAgent->getUserAgent())) {
                return $this->logOutView->showlogOutView($this->loggedIn);
            }
        }

        //TODO break out this in a new function
        if ($this->cookieStorage->cookiesIsSet()) {
            try {
                $cookie = $this->userRepository->getCookie($this->loginView->loadUsernameCookie());
                if ($this->loginModel->checkIfCookieExpireTimeIsValid($cookie['acces'])) {
                    if ($this->loginModel->doLogInCookie($cookie['cookieUser'], $cookie['cookiePass'],
                        $this->loginView->loadUsernameCookie(), $this->loginView->loadPasswordCookie())) {
                        $this->createSessionUserAgent();
                        $this->logOutView->setLoggedInWithCookiesMessage();
                        return $this->logOutView->showlogOutView($this->loggedIn);

                    } else{
                        $this->logOutView->deleteCookies();
                        $this->loginView->setWrongInformationInCookieMessage();
                    }
                }
                else{
                    $this->logOutView->deleteCookies();
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
                            $dbUser->getPassword())) {
                            $this->userDidPressLogin();
                            $this->createSessionUserAgent();
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
        }

        if($this->loginView->usrPressedAddNewUser()){
            return $this->registerControl->registerControl();
        }

        else
        {
            return $this->loginView->showLoginView();
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

