<?php


class LoginController
{
  public function __construct() {

    if (!isset($_REQUEST["loginAction"])) {
      $_REQUEST["loginAction"] = "";
    }

    switch ($_REQUEST["loginAction"]) {
      case "login":
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
    $_SESSION["user"] = $user->getEmail();
    Users::saveUser($user);   //update last login in DB
    return true;
  }

  public function isLoggedIn() {
    return isset($_SESSION["email"]);
  }

  public function writeFormModal() {
    $view = new LoginView();
    $view->writeLoginModal();
  }
}