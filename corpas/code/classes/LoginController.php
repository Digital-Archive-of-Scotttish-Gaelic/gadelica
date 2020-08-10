<?php


class LoginController
{
  private $_user;

  public function __construct() {

    if (!isset($_REQUEST["loginAction"])) {
      $_REQUEST["loginAction"] = "";
    }

    switch ($_REQUEST["loginAction"]) {
      case "login":
        if (!$this->_authenticateUser($_POST)) {
          echo "<h3>Email/password combination not recognised</h3>";
        }
        break;
      case "logout":
        $this->_logout();
        break;
      default:
        break;
    }
  }

  /**
   * @param $params : the POST array
   * @return bool : true on authentic user, otherwise false
   */
  private function _authenticateUser($params) {
    $user = Users::getUser($params["email"]);
    if (empty($user) || !$user->checkPassword($params["password"])) {
      return false;
    }
    $this->_user = $user;
    $_SESSION["user"] = $user->getEmail();
    Users::saveUser($user);   //update last login in DB
    return true;
  }

  public function getUser() {
    return $this->_user;
  }

  public function isLoggedIn() {
    return isset($_SESSION["user"]);
  }

  private function _logout() {
    unset($this->_user);
    unset($_SESSION["user"]);
  }

  public function writeFormModal() {
    $view = new LoginView();
    $view->writeLoginModal();
  }
}