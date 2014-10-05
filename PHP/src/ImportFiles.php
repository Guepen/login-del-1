<?php

require_once("./src/model/RegisterModel.php");
require_once("src/controller/loginController.php");
require_once("./src/view/loginView.php");
require_once("./src/view/logOutView.php");
require_once("./src/view/RegisterView.php");
require_once("./src/model/loginModel.php");
require_once("./src/controller/RegisterController.php");
require_once("./src/model/User.php");
require_once ('./src/model/DAO/Repository.php');
require_once("./src/model/DAO/UserRepository.php");
require_once("./common/Exceptions/UsernameAndPasswordToShortException.php");
require_once("./common/Exceptions/PasswordToShortException.php");
require_once("./common/Exceptions/UsernameToShortException.php");
require_once("./common/Exceptions/PasswordsDontMatchException.php");
require_once("./common/Exceptions/UserExistsException.php");
require_once("./common/Exceptions/ProhibitedCharacterInUsernameException.php");
require_once("./common/CookieStorage.php");
require_once("./common/UserAgent.php");
require_once("./common/Exceptions/MissingPasswordException.php");
require_once("./common/Exceptions/MissingUsernameException.php");
require_once("./common/Exceptions/WrongUserinformationException.php");
require_once("./common/CookieStorage.php");
require_once("common/HTMLView.php");
