<?php

namespace controller;


class RegisterController{

    private $newUserView;
    private $loggedIn = false;
    private $loginView;
    private $loginModel;
    private $userRepository;

    function __construct(){
        $this->newUserView = new \view\NewUserView();
        $this->loginModel = new \model\loginModel();
        $this->loginView = new \view\loginView($this->loginModel);
        $this->userRepository = new \model\UserRepository();
    }

    function registerControl() {
        if ($this->newUserView->usrHasPressedBackToLogin()) {
            return $this->loginView->showLoginView($this->loggedIn);
        }

        if ($this->newUserView->usrHasPressedRegister()) {
            try {
                if ($this->loginModel->validateNewUser($this->newUserView->getUserName(), $this->newUserView->getPassword(),
                    $this->newUserView->getPassword2())){
                    $user = new \model\User($this->newUserView->getUserName(), $this->newUserView->getPassword());

                    $this->userRepository->add($user);
                    $this->loginView->setRegistrationSuccesMessae();
                    return $this->loginView->showLoginView($this->loggedIn);


                }

            } catch (\model\usernameAndPasswordToShortException $e) {
                $this->newUserView->setUsernameAndPasswordToShortMessage();
                return $this->newUserView->showNewUserForm();
            } catch (\model\PasswordToShortException $e) {
                $this->newUserView->setToShortPasswordMessage();
                return $this->newUserView->showNewUserForm();
            } catch (\model\UsernameToShortException $e) {
                $this->newUserView->setToShortUsernameMessage();
                return $this->newUserView->showNewUserForm();
            } catch (\model\PasswordsDontMatchException $e) {
                $this->newUserView->setPasswordsDontMatchMessage();
                return $this->newUserView->showNewUserForm();
            } catch (\model\UserExistsException $e) {
                $this->newUserView->setUserExistsMessage();
                return $this->newUserView->showNewUserForm();
            } catch (\model\ProhibitedCharacterInUsernameException $e) {
                $this->newUserView->setProhibitedCharacterMessage();
                return $this->newUserView->showNewUserForm();
            }
        }

        return $this->newUserView->showNewUserForm();


    }
}