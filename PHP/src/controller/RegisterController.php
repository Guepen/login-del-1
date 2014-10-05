<?php

namespace controller;

use model\loginModel;
use model\PasswordsDontMatchException;
use model\PasswordToShortException;
use model\ProhibitedCharacterInUsernameException;
use model\RegisterModel;
use model\User;
use model\UserExistsException;
use model\usernameAndPasswordToShortException;
use model\UsernameToShortException;
use model\UserRepository;
use view\loginView;
use view\RegisterView;

/**
 * Class RegisterController
 * @package controller
 */
class RegisterController{

    private $newUserView;
    private $loggedIn = false;
    private $loginView;
    private $registerModel;
    private $loginModel;
    private $userRepository;

    function __construct(){
        $this->newUserView = new RegisterView();
        $this->registerModel = new RegisterModel();
        $this->loginModel = new loginModel();
        $this->loginView = new loginView($this->loginModel);
        $this->userRepository = new UserRepository();
    }

    /**
     * TODO break out this function to different functions
     */
    function registerControl() {
        if ($this->newUserView->usrHasPressedBackToLogin()) {
            return $this->loginView->showLoginView($this->loggedIn);
        }

        if ($this->newUserView->usrHasPressedRegister()) {
            try {
                if ($this->registerModel->validateNewUser($this->newUserView->getUserName(), $this->newUserView->getPassword(),
                    $this->newUserView->getPassword2())){
                    $user = new User($this->newUserView->getUserName(), $this->newUserView->getPassword());

                    $this->userRepository->add($user);
                    $this->loginView->setRegistrationSuccesMessae();
                    return $this->loginView->showLoginView($this->loggedIn);
                }

            } catch (usernameAndPasswordToShortException $e) {
                $this->newUserView->setUsernameAndPasswordToShortMessage();
            } catch (PasswordToShortException $e) {
                $this->newUserView->setToShortPasswordMessage();
            } catch (UsernameToShortException $e) {
                $this->newUserView->setToShortUsernameMessage();
            } catch (PasswordsDontMatchException $e) {
                $this->newUserView->setPasswordsDontMatchMessage();
            } catch (UserExistsException $e) {
                $this->newUserView->setUserExistsMessage();
            } catch (ProhibitedCharacterInUsernameException $e) {
                $this->newUserView->setProhibitedCharacterMessage($e->getMessage());
            }
        }

        return $this->newUserView->showNewUserForm();
    }
}