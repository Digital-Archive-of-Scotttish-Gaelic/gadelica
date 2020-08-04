<?php

class Users
{
  public static function getUser($email) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT `password`, `salt`, `firstname`, `lastname`, `slip_admin`, 
        `passwordAuth`, UNIX_TIMESTAMP(`last_logged_in`) AS last_logged_in, 
				UNIX_TIMESTAMP(`updated`) AS updated FROM user WHERE email = :email;");
      $sth->execute(array(":email"=>$email));
      while ($row = $sth->fetch()) {
        $user = new User($email);
        $user->setPassword($row['password']);
        $user->setSalt($row['salt']);
        $user->setFirstName($row['firstname']);
        $user->setLastName($row['lastname']);
        $user->setIsSlipAdmin($row['slip_admin']);
        $user->setPasswordAuth($row['passwordAuth']);
        $user->setUpdated($row["updated"]);
      }
      return $user;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function saveUser($user) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("REPLACE INTO user(email, password, salt, firstname, lastname, slip_admin, passwordAuth, last_logged_in) VALUES 
				(:email, :password, :salt, :firstname, :lastname, :slip_admin, :passwordAuth, now())");
      $sth->execute(array(":email"=>$user->getEmail(),
        ":password"=>$user->getPassword(), ":salt"=>$user->getSalt(), ":firstname"=>$user->getFirstName(),
        ":lastname"=>$user->getLastName(),
        ":slip_admin"=>$user->getIsSlipAdmin(), ":passwordAuth"=>$user->getPasswordAuth()));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function getAllUsers() {
    $users = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("SELECT email FROM user;");
      $sth->execute();
      while ($row = $sth->fetch()) {
        $users[] = self::getUser($row["email"]);
      }
      return $users;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}